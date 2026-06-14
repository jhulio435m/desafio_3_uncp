import { Client, Message } from '@open-wa/wa-automate';
import { LANGUAGE_PROMPT, BOT_COPY } from './constants';
import { normalize, loadTexts, languageFromInput, formatWelcome, replyAndStore, setting } from './utils';
import { isInformalMessage, isOffTopic } from './gemini';
import { appendConversationMessage, getRecentConversation, getUserState, saveUserState } from './state';
import {
  replyProcessServices,
  replyInfoMenu,
  replyHoursAndCost,
  replyNeedOrientation,
  replyOfficialChannels,
  replyTrackingScope,
  replyContacts,
  replyFullGuide,
  replyKnowledgeSearchOrFallback,
  handleHumanContactFlow,
  handleRegistrationFlow,
  handleTrackingFlow,
} from './handlers';

function isMenuSelection(normalized: string): boolean {
  return /^[0-9]$/.test(normalized);
}

export async function onMessage(client: Client, message: Message) {
  const from = message.from;
  const text = message.body;

  // Guard: ignore empty messages (stickers, images, audio without caption, etc.)
  if (!text || text.trim().length === 0) {
    console.log(`[MSG] Ignored empty message from=${from}`);
    return;
  }

  const normalized = normalize(text);
  const state = await getUserState(from);

  // --- Malla de Seguridad Global ---
  if (state.step === 'IDLE') {
    if (isOffTopic(text)) {
      await replyAndStore(
        client,
        from,
        await setting(
          'off_topic_message',
          `Este canal está dedicado exclusivamente a la orientación sobre proyección social de la UNCP.\n\n_Describa la necesidad de su comunidad o escriba *menu* para ver las opciones disponibles._`,
        ),
        message.id,
      );
      return;
    }

    if (isInformalMessage(text)) {
      await replyAndStore(
        client,
        from,
        await setting(
          'informal_message',
          '_Cuando guste, describa la necesidad de su comunidad o escriba *menu* para ver las opciones disponibles._',
        ),
        message.id,
      );
      return;
    }
  }
  // ---------------------------------

  const history = await getRecentConversation(from, 6);
  await appendConversationMessage(from, 'user', text);

  console.log(`[MSG] from=${from} step=${state.step} body=${JSON.stringify(text)}`);

  try {
    // Global triggers — always available
    if (['idioma', 'lengua', '/reset_lang'].includes(normalized)) {
      state.step = 'LANG_SELECTION';
      state.lang = undefined;
      state.data = {};
      state.lastIntent = 'general';
      await replyAndStore(client, from, LANGUAGE_PROMPT, message.id);
      return;
    }

    if (['hola', 'inicio'].includes(normalized)) {
      if (!state.lang) {
        state.step = 'LANG_SELECTION';
        state.data = {};
        await replyAndStore(client, from, LANGUAGE_PROMPT, message.id);
        return;
      }

      const texts = await loadTexts(state.lang);
      state.step = 'IDLE';
      state.data = {};
      await replyAndStore(
        client,
        from,
        `Seguimos con su consulta.\n\n${texts.menu}`,
        message.id,
      );
      return;
    }

    // Language selection step
    if (state.step === 'LANG_SELECTION') {
      const lang = languageFromInput(normalized);
      if (!lang) {
        await replyAndStore(client, from, LANGUAGE_PROMPT, message.id);
        return;
      }
      state.lang = lang;
      state.step = 'IDLE';
      state.data = {};
      state.lastIntent = 'general';
      const texts = await loadTexts(lang);
      await replyAndStore(client, from, formatWelcome(texts, lang), message.id);
      return;
    }

    const texts = await loadTexts(state.lang || 'es');

    // Menu reset
    if (['menu', 'menú', '0'].includes(normalized)) {
      state.step = 'IDLE';
      state.data = {};
      state.lastIntent = 'general';
      await replyAndStore(client, from, texts.menu, message.id);
      return;
    }

    // Active flow routing
    if (state.step === 'ORIENTATION_NEED') {
      await replyNeedOrientation(client, message, text, state.lang || 'es', history);
      state.step = 'IDLE';
      state.data = {};
      state.lastIntent = 'orientation';
      return;
    }

    if (state.step.startsWith('HUMAN_')) {
      await handleHumanContactFlow(client, message, state, texts);
      state.lastIntent = 'human';
      return;
    }

    if (state.step.startsWith('REQ_')) {
      await handleRegistrationFlow(client, message, state, texts);
      state.lastIntent = 'request';
      return;
    }

    if (state.step.startsWith('TRACK_')) {
      await handleTrackingFlow(client, message, state, texts);
      state.lastIntent = 'tracking';
      return;
    }

    if (state.step === 'INFO_MENU') {
      switch (normalized) {
        case '1':
          await replyProcessServices(client, message);
          break;
        case '2':
          await replyHoursAndCost(client, message, texts);
          break;
        case '3':
          await replyOfficialChannels(client, message);
          break;
        case '4':
          await replyContacts(client, message);
          break;
        case '5':
          await replyTrackingScope(client, message, texts);
          break;
        case '6':
          state.step = 'IDLE';
          await replyAndStore(client, from, texts.menu, message.id);
          break;
        default:
          await replyAndStore(client, from, texts.infoMenu, message.id);
          break;
      }
      state.lastIntent = 'general';
      return;
    }

    // Main menu dispatch
    switch (normalized) {
      case '1':
        state.step = 'ORIENTATION_NEED';
        state.lastIntent = 'orientation';
        await replyAndStore(client, from, texts.needPrompt, message.id);
        break;
      case '2':
        state.step = 'REQ_REP_NAME';
        state.data = {};
        state.lastIntent = 'request';
        await replyAndStore(client, from, texts.reqRepName, message.id);
        break;
      case '3':
        state.step = 'INFO_MENU';
        state.lastIntent = 'general';
        await replyAndStore(client, from, texts.infoMenu, message.id);
        break;
      case '4':
        state.step = 'TRACK_TICKET';
        state.data = {};
        state.lastIntent = 'tracking';
        await replyAndStore(client, from, texts.trackPrompt, message.id);
        break;
      case '5':
        state.step = 'HUMAN_NAME';
        state.data = {};
        state.lastIntent = 'human';
        await replyAndStore(client, from, texts.humanName, message.id);
        break;
      default:
        await replyKnowledgeSearchOrFallback(client, message, text, texts, history);
        state.lastIntent = 'orientation';
        break;
    }
  } catch (err) {
    console.error('[MSG] Unhandled error:', err);
    const fallbackLang = state.lang || 'es';
    await replyAndStore(client, from, BOT_COPY[fallbackLang].error, message.id);
  } finally {
    await saveUserState(from, state);
  }
}
