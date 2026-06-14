import { query } from '../database/db';
import { ConversationTurn, LanguageCode, UserState } from './types';

const DEFAULT_STATE: UserState = {
  step: 'LANG_SELECTION',
  data: {},
};

const userStates = new Map<string, UserState>();
let storageReady: Promise<void> | null = null;

async function ensureStorage(): Promise<void> {
  if (!storageReady) {
    storageReady = (async () => {
      await query(`
        CREATE TABLE IF NOT EXISTS bot_conversation_states (
          user_phone VARCHAR(80) PRIMARY KEY,
          step VARCHAR(50) NOT NULL,
          lang VARCHAR(10),
          data JSONB NOT NULL DEFAULT '{}'::jsonb,
          last_intent VARCHAR(20),
          updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        )
      `);

      await query(`
        CREATE TABLE IF NOT EXISTS bot_conversation_history (
          id BIGSERIAL PRIMARY KEY,
          user_phone VARCHAR(80) NOT NULL,
          role VARCHAR(20) NOT NULL,
          body TEXT NOT NULL,
          created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        )
      `);
    })().catch((err) => {
      storageReady = null;
      throw err;
    });
  }

  await storageReady;
}

function normalizeState(state?: Partial<UserState> | null): UserState {
  return {
    step: state?.step || DEFAULT_STATE.step,
    lang: state?.lang as LanguageCode | undefined,
    data: state?.data || {},
    lastIntent: state?.lastIntent,
  };
}

export async function getUserState(userPhone: string): Promise<UserState> {
  await ensureStorage();

  const cached = userStates.get(userPhone);
  if (cached) return cached;

  const result = await query(
    `SELECT step, lang, data, last_intent
     FROM bot_conversation_states
     WHERE user_phone = $1
     LIMIT 1`,
    [userPhone],
  );

  const row = result.rows[0];
  const state = normalizeState(row ? {
    step: row.step,
    lang: row.lang || undefined,
    data: row.data || {},
    lastIntent: row.last_intent || undefined,
  } : null);

  userStates.set(userPhone, state);
  return state;
}

export async function saveUserState(userPhone: string, state: UserState): Promise<void> {
  await ensureStorage();
  userStates.set(userPhone, state);

  await query(
    `INSERT INTO bot_conversation_states (user_phone, step, lang, data, last_intent, updated_at)
     VALUES ($1, $2, $3, $4::jsonb, $5, CURRENT_TIMESTAMP)
     ON CONFLICT (user_phone)
     DO UPDATE SET
       step = EXCLUDED.step,
       lang = EXCLUDED.lang,
       data = EXCLUDED.data,
       last_intent = EXCLUDED.last_intent,
       updated_at = CURRENT_TIMESTAMP`,
    [userPhone, state.step, state.lang || null, JSON.stringify(state.data || {}), state.lastIntent || null],
  );
}

export async function appendConversationMessage(
  userPhone: string,
  role: ConversationTurn['role'],
  body: string,
): Promise<void> {
  await ensureStorage();

  await query(
    `INSERT INTO bot_conversation_history (user_phone, role, body)
     VALUES ($1, $2, $3)`,
    [userPhone, role, body],
  );
}

export async function getRecentConversation(userPhone: string, limit = 6): Promise<ConversationTurn[]> {
  await ensureStorage();

  const result = await query(
    `SELECT role, body
     FROM (
       SELECT role, body, created_at, id
       FROM bot_conversation_history
       WHERE user_phone = $1
       ORDER BY created_at DESC, id DESC
       LIMIT $2
     ) recent
     ORDER BY created_at ASC, id ASC`,
    [userPhone, limit],
  );

  return result.rows.map((row) => ({
    role: row.role === 'assistant' ? 'assistant' : 'user',
    body: row.body,
  }));
}

export function clearStateCache(userPhone?: string): void {
  if (userPhone) {
    userStates.delete(userPhone);
    return;
  }

  userStates.clear();
}
