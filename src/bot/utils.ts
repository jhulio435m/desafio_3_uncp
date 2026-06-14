import { execSync } from 'child_process';
import * as fs from 'fs';
import { Client } from '@open-wa/wa-automate';
import { query } from '../database/db';
import { LanguageCode, BotTexts } from './types';
import { appendConversationMessage } from './state';

const STOPWORDS = new Set([
  'que', 'como', 'cual', 'cuales', 'donde', 'cuando', 'porque', 'por', 'para',
  'con', 'sin', 'sobre', 'desde', 'hasta', 'entre', 'hacia', 'segun', 'según',
  'esta', 'este', 'estos', 'estas', 'eso', 'esa', 'esos', 'esas', 'hola',
  'buenas', 'buenos', 'dias', 'días', 'tardes', 'noches', 'quiero', 'queremos',
  'necesito', 'necesitamos', 'puedo', 'pueden', 'hagan', 'hacer', 'ayuda',
  'apoyo', 'tema', 'cosa', 'algo', 'pasa', 'pues',
]);

export function cleanUpChromium() {
  try {
    const commands = [
      'pkill -9 -f "chrome" || true',
      'pkill -9 -f "chromium" || true',
      'pkill -9 -f "google-chrome" || true'
    ];
    
    commands.forEach(cmd => {
      try {
        execSync(cmd, { stdio: 'ignore' });
      } catch (e) {}
    });

    const sessionPaths = ['/sessions', '/tmp'];
    sessionPaths.forEach(p => {
      if (fs.existsSync(p)) {
        try {
          execSync(`find ${p} -name "SingletonLock" -delete || true`, { stdio: 'ignore' });
          execSync(`find ${p} -name "SingletonSocket" -delete || true`, { stdio: 'ignore' });
        } catch (e) {}
      }
    });
  } catch (e) {
  }
}

export async function setting(key: string, fallback: string): Promise<string> {
  const result = await query('SELECT value FROM bot_settings WHERE key = $1 LIMIT 1', [key]);
  return result.rows[0]?.value || fallback;
}

export async function loadTexts(lang: LanguageCode): Promise<BotTexts> {
  try {
    const [fallbackResult, langResult] = await Promise.all([
      query(
        `SELECT bk.key, bt.value 
         FROM bot_translations bt
         JOIN bot_translation_keys bk ON bk.id = bt.bot_translation_key_id
         WHERE bt.lang = 'es'`,
      ),
      lang === 'es'
        ? Promise.resolve(null)
        : query(
            `SELECT bk.key, bt.value 
             FROM bot_translations bt
             JOIN bot_translation_keys bk ON bk.id = bt.bot_translation_key_id
             WHERE bt.lang = $1`,
            [lang]
          ),
    ]);

    const copy: Record<string, string> = {};
    for (const row of fallbackResult.rows) {
      copy[row.key] = row.value;
    }

    if (langResult) {
      for (const row of langResult.rows) {
        copy[row.key] = row.value;
      }
    }

    return copy as unknown as BotTexts;
  } catch (error) {
    console.error(`[BOT] Error loading translations for lang ${lang} from DB:`, error);
    return {} as unknown as BotTexts;
  }
}

export async function loadLanguagePrompt(): Promise<string> {
  return setting('language_prompt', '');
}

export function languageFromInput(input: string): LanguageCode | null {
  const n = input.toLowerCase().trim();
  if (['1', 'espanol', 'español', 'castellano'].includes(n)) return 'es';
  if (['2', 'quechua', 'runasimi', 'wanka'].includes(n)) return 'qu';
  if (['3', 'ashaninka', 'asháninka'].includes(n)) return 'ash';
  return null;
}

export function formatWelcome(texts: BotTexts): string {
  return `${texts.welcome}\n\n${texts.menu}`;
}

export function normalize(text: string): string {
  return text
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .trim();
}

export function tokenize(text: string): string[] {
  return normalize(text)
    .split(/\W+/)
    .filter((t) => t.length > 2);
}

export function meaningfulTokens(text: string): string[] {
  return tokenize(text).filter((token) => !STOPWORDS.has(token));
}

export async function replyAndStore(client: Client, to: any, body: string, id?: string): Promise<void> {
  const messageBody = body || 'Lo siento, no pude procesar su mensaje en este momento. Escriba *menu* para reiniciar.';
  try {
    await (client as any).simulateTyping(to, true);
    const delay = Math.min(Math.max(messageBody.length * 25, 1000), 4000);
    await new Promise((resolve) => setTimeout(resolve, delay));
  } catch (err) {
    console.warn('[BOT] simulateTyping failed:', err);
  } finally {
    try {
      await (client as any).simulateTyping(to, false);
    } catch (_) {}
  }
  await (client as any).reply(to, messageBody, id);
  await appendConversationMessage(to, 'assistant', messageBody);
}

export async function isAnyHumanAvailableNow(): Promise<boolean> {
  const now = new Date();
  const day = now.getDay() === 0 ? 7 : now.getDay();
  const time = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

  const result = await query(
    `SELECT cs.id 
     FROM contact_schedules cs
     JOIN contacts c ON c.id = cs.contact_id
     WHERE c.is_active = true 
       AND cs.day_of_week = $1 
       AND $2 BETWEEN cs.start_time AND cs.end_time
     LIMIT 1`,
    [day, time],
  );

  return result.rows.length > 0;
}
