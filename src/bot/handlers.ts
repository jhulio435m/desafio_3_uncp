import { Client, Message } from '@open-wa/wa-automate';
import * as path from 'path';
import * as fs from 'fs';
import { query } from '../database/db';
import { BotTexts, ConversationTurn, LanguageCode, UserState } from './types';
import { meaningfulTokens, normalize, tokenize, isAnyHumanAvailableNow, replyAndStore, setting, loadTexts } from './utils';
import { askAssistant, formatAiReply } from './gemini';
import { BOT_COPY } from './constants';

type FaqRow = {
  question: string;
  answer: string;
  keywords?: string | null;
};

function scoreFaqMatch(faq: FaqRow, terms: string[]): number {
  const questionTokens = new Set(tokenize(faq.question || ''));
  const answerTokens = new Set(tokenize(faq.answer || ''));
  const keywordTokens = new Set(tokenize(faq.keywords || ''));
  const normalizedQuestion = normalize(faq.question || '');
  const normalizedKeywords = normalize(faq.keywords || '');

  let score = 0;
  let matchedTerms = 0;

  for (const term of terms) {
    const keywordHit = keywordTokens.has(term);
    const questionHit = questionTokens.has(term);
    const answerHit = answerTokens.has(term);

    if (keywordHit || questionHit || answerHit) {
      matchedTerms += 1;
    }
    if (keywordHit) score += 4;
    if (questionHit) score += 2;
    if (answerHit) score += 1;
  }

  if (matchedTerms >= 2) score += 3;
  if (terms.length > 0 && normalizedQuestion.includes(terms.join(' '))) score += 2;
  if (terms.length > 0 && normalizedKeywords.includes(terms.join(' '))) score += 2;

  return score;
}

export async function findFaq(text: string): Promise<FaqRow | null> {
  const terms = meaningfulTokens(text);
  if (terms.length === 0) return null;

  const faqs = await query(
    `SELECT f.question, f.answer, f.keywords
     FROM faqs f
     JOIN knowledge_categories kc ON kc.id = f.knowledge_category_id
     WHERE f.is_active = true
       AND kc.slug != 'contacto-humano'`,
  );

  const ranked = (faqs.rows as FaqRow[])
    .map((faq) => ({ faq, score: scoreFaqMatch(faq, terms) }))
    .sort((a, b) => b.score - a.score);

  const best = ranked[0];
  if (!best || best.score < 4) return null;
  return best.faq;
}

export async function replyProcessServices(client: Client, message: Message, texts: BotTexts, lang: LanguageCode) {
  if (lang !== 'es') {
    await replyAndStore(client, message.from, texts.processServices, message.id);
    return;
  }

  const result = await query(`
    SELECT answer FROM faqs
    WHERE is_active = true
      AND (keywords ILIKE '%apoyo%' OR keywords ILIKE '%comunidad%' OR keywords ILIKE '%solicitar%')
    ORDER BY id
    LIMIT 1
  `);

  if (result.rows.length > 0) {
    const response = `*Tipos de apoyo disponibles*\n\n${result.rows[0].answer}\n\n_Escriba *2* para orientar su necesidad específica._`;
    await replyAndStore(client, message.from, response, message.id);
  } else {
    await replyAndStore(client, message.from, texts.processServices, message.id);
  }
}

export async function replyNeedOrientation(
  client: Client,
  message: Message,
  text: string,
  lang: LanguageCode,
  history: ConversationTurn[] = [],
) {
  // Try Grok/Gemini first for intelligent contextual routing
  const aiResponse = await askAssistant(text, history, lang);
  if (aiResponse) {
    console.log('[AI] Responded to orientation query');
    const texts = await loadTexts(lang);
    await replyAndStore(client, message.from, formatAiReply(aiResponse, texts.aiFooter), message.id);
    return;
  }

  const faq = await findFaq(text);
  if (!faq) {
    const terms = meaningfulTokens(text);
    if (terms.length === 0) {
      await replyAndStore(
        client,
        message.from,
        BOT_COPY[lang].needMoreDetail,
        message.id,
      );
      return;
    }

    console.log('[AI] No response for orientation query; sending deterministic fallback');
    await replyAndStore(
      client,
      message.from,
      BOT_COPY[lang].noSpecificOrientation,
      message.id,
    );
    return;
  }

  if (lang !== 'es') {
    await replyAndStore(client, message.from, BOT_COPY[lang].noSpecificOrientation, message.id);
    return;
  }

  await replyAndStore(
    client,
    message.from,
    `*Orientación*\n\n${faq.answer}\n\n_¿No es lo que buscaba? Escriba *5* para hablar con una persona._`,
    message.id,
  );
}

export async function replyOfficialChannels(client: Client, message: Message, texts: BotTexts) {
  const result = await query('SELECT title, url FROM official_links WHERE is_active = true ORDER BY id');
  let response = `${texts.officialChannelsTitle}\n`;

  result.rows.forEach((link: any) => {
    response += `\n- *${link.title}*\n  ${link.url}`;
  });

  response += `\n\n${texts.backToMenu}`;
  await replyAndStore(client, message.from, response, message.id);
}

export async function replyInfoMenu(client: Client, message: Message, texts: BotTexts) {
  await replyAndStore(client, message.from, texts.infoMenu, message.id);
}

export async function replyHoursAndCost(client: Client, message: Message, texts: BotTexts) {
  await replyAndStore(client, message.from, texts.officeHours, message.id);
}

export async function replyTrackingScope(client: Client, message: Message, texts: BotTexts) {
  await replyAndStore(client, message.from, texts.scope, message.id);
}

export async function replyContacts(client: Client, message: Message, texts: BotTexts) {
  const result = await query('SELECT name, phone FROM contacts WHERE is_active = true ORDER BY office');
  let response = `${texts.contactsTitle}\n`;

  result.rows.forEach((c: any) => {
    response += `\n- ${c.name}: *${c.phone || '-'}*`;
  });

  response += `\n\n${texts.humanContactHint}`;
  await replyAndStore(client, message.from, response, message.id);
}

export async function replyFullGuide(client: Client, message: Message) {
  const pdfPath = path.resolve(__dirname, '../../documentos/reglamento_proyeccion_social.pdf');
  let sent = false;

  if (typeof (client as any).sendFile === 'function') {
    try {
      let fileData: string = pdfPath;
      if (fs.existsSync(pdfPath)) {
        const fileContent = fs.readFileSync(pdfPath).toString('base64');
        fileData = `data:application/pdf;base64,${fileContent}`;
      }
      const result = await (client as any).sendFile(
        message.from,
        fileData,
        'reglamento_proyeccion_social.pdf',
        'PDF de referencia del proceso de Proyección Social UNCP.',
        message.id,
      );
      sent = result !== false;
      console.log(`[PDF] sendFile result for ${message.from}:`, result);
    } catch (err) {
      console.error('[PDF] Error sending reference PDF:', err);
    }
  }

  if (sent) {
    await replyAndStore(
      client,
      message.from,
      '*Material de referencia*\n\nLe envié un PDF de referencia del proceso de Proyección Social UNCP. Úselo como apoyo informativo; la orientación inicial y los canales oficiales siguen siendo lo principal.\n\nEscriba *menu* para volver.',
      message.id,
    );
    return;
  }

  await replyAndStore(
    client,
    message.from,
    '*Material de referencia*\n\nNo pude enviar el PDF en este momento. Puede continuar con los enlaces oficiales y contactos del menú de información útil, o escribir *menu* para volver.',
    message.id,
  );
}

export async function replyKnowledgeSearchOrFallback(
  client: Client,
  message: Message,
  text: string,
  texts: BotTexts,
  lang: LanguageCode,
  history: ConversationTurn[] = [],
) {
  // Ignoramos findFaq a pedido del usuario para usar directamente la IA
  // y tener mejor contexto conversacional.

  const aiResponse = await askAssistant(text, history, lang);
  if (aiResponse) {
    console.log('[AI] Responded to free-text query');
    await replyAndStore(client, message.from, formatAiReply(aiResponse, texts.aiFooter), message.id);
    return;
  }

  // Fallback determinista si la IA falla o no hay API key
  const faq = await findFaq(text);
  if (faq && lang === 'es') {
    await replyAndStore(client, message.from, faq.answer, message.id);
    return;
  }

  console.log('[AI] No response for free-text query; sending deterministic fallback');
  await replyAndStore(
    client,
    message.from,
    texts.noInformation,
    message.id,
  );
}

function hasEnoughLetters(text: string, minLetters = 3): boolean {
  const letters = text.match(/[a-zA-ZáéíóúÁÉÍÓÚñÑ]/g);
  return (letters?.length || 0) >= minLetters;
}

export async function handleHumanContactFlow(client: Client, message: Message, state: UserState, texts: BotTexts) {
  const text = message.body.trim();
  const normalized = normalize(text);
  const from = message.from;

  switch (state.step) {
    case 'HUMAN_NAME':
      if (text.toLowerCase() !== 'sin nombre' && !hasEnoughLetters(text, 4)) {
        await replyAndStore(client, from, texts.invalidName, message.id);
        break;
      }
      state.data.citizen_name = text.toLowerCase() === 'sin nombre' ? '' : text;
      state.step = 'HUMAN_PHONE';
      await replyAndStore(client, from, `*Contacto Humano - Paso 2/4*\n\n${texts.humanPhone}`, message.id);
      break;
    case 'HUMAN_PHONE':
      if (!/^\+?\d[\d\s-]{6,}$/.test(text)) {
        await replyAndStore(client, from, texts.invalidPhone, message.id);
        break;
      }
      state.data.phone = text;
      state.step = 'HUMAN_TOPIC';
      await replyAndStore(client, from, `*Contacto Humano - Paso 3/4*\n\n${texts.humanTopic}`, message.id);
      break;
    case 'HUMAN_TOPIC':
      if (!hasEnoughLetters(text, 4)) {
        await replyAndStore(client, from, texts.invalidTopic, message.id);
        break;
      }
      state.data.topic = text;
      state.step = 'HUMAN_MESSAGE';
      await replyAndStore(client, from, `*Contacto Humano - Paso 4/4*\n\n${texts.humanMessage}`, message.id);
      break;
    case 'HUMAN_MESSAGE':
      if (tokenize(text).length < 2) {
        await replyAndStore(client, from, texts.invalidMessage, message.id);
        break;
      }
      state.data.message = text;
      state.step = 'HUMAN_CONFIRM';
      const summary = `${texts.confirmSummary}\n\n• *Nombre:* ${state.data.citizen_name || 'Sin nombre'}\n• *Teléfono:* ${state.data.phone}\n• *Tema:* ${state.data.topic}\n• *Mensaje:* ${state.data.message}\n\n${texts.confirmPrompt}`;
      await replyAndStore(client, from, summary, message.id);
      break;
    case 'HUMAN_CONFIRM':
      if (normalized === 'si' || normalized === 'sí' || normalized === 'yes') {
        try {
          const response = await fetch('http://admin:8080/api/human-contacts', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              citizen_name: state.data.citizen_name || null,
              phone: state.data.phone,
              topic: state.data.topic || null,
              message: state.data.message,
              preferred_channel: 'WhatsApp',
            }),
          });
          const result = (await response.json()) as any;
          if (!result.success) {
            console.error('[HUMAN] Failed to save human contact via API:', result);
          }
        } catch (e) {
          console.error('[HUMAN] Error calling Laravel API for human contact:', e);
        }
        state.step = 'IDLE';
        state.data = {};

        const isAvailable = await isAnyHumanAvailableNow();
        const availabilityNote = isAvailable
          ? await setting('human_available_message', '_Un orientador está de turno. Le contactaremos pronto._')
          : await setting('human_unavailable_message', '_Fuera de horario. Le contactaremos al próximo turno._');

        await replyAndStore(
          client,
          from,
          `${texts.humanSaved}\n\n${availabilityNote}`,
          message.id,
        );
        await replyAndStore(client, from, texts.menu, message.id);
      } else if (normalized === 'no') {
        state.step = 'IDLE';
        state.data = {};
        await replyAndStore(client, from, texts.cancelHint, message.id);
        await replyAndStore(client, from, texts.menu, message.id);
      } else {
        await replyAndStore(client, from, texts.confirmPrompt, message.id);
      }
      break;
  }
}

export async function handleRegistrationFlow(client: Client, message: Message, state: UserState, texts: BotTexts) {
  const text = message.body.trim();
  const normalized = normalize(text);
  const from = message.from;

  switch (state.step) {
    case 'REQ_CONSENT':
      if (['aceptar', 'si', 'acepto'].includes(normalized)) {
        state.step = 'REQ_REP_NAME';
        await replyAndStore(client, from, `*Registro - Paso 1/6*\n\n${texts.reqRepName}`, message.id);
      } else {
        state.step = 'IDLE';
        state.data = {};
        await replyAndStore(client, from, texts.cancelHint, message.id);
        await replyAndStore(client, from, texts.menu, message.id);
      }
      break;
    case 'REQ_REP_NAME':
      state.data.representative_name = text;
      state.step = 'REQ_REP_DNI';
      await replyAndStore(client, from, `*Registro - Paso 2/6*\n\n${texts.reqRepDni}`, message.id);
      break;
    case 'REQ_REP_DNI':
      state.data.representative_dni = text;
      state.step = 'REQ_INST_NAME';
      await replyAndStore(client, from, `*Registro - Paso 3/6*\n\n${texts.reqInstName}`, message.id);
      break;
    case 'REQ_INST_NAME':
      state.data.institution_name = text;
      state.step = 'REQ_INST_TYPE';
      await replyAndStore(client, from, `*Registro - Paso 4/6*\n\n${texts.reqInstType}`, message.id);
      break;
    case 'REQ_INST_TYPE':
      state.data.institution_type = text;
      state.step = 'REQ_LOCATION';
      await replyAndStore(client, from, `*Registro - Paso 5/6*\n\n${texts.reqLocation}`, message.id);
      break;
    case 'REQ_LOCATION':
      state.data.location = text;
      state.step = 'REQ_DESC';
      await replyAndStore(client, from, `*Registro - Paso 6/6*\n\n${texts.reqDesc}`, message.id);
      break;
    case 'REQ_DESC':
      state.data.description = text;
      state.step = 'REQ_CONFIRM';
      const summary = `${texts.confirmSummary}\n\n• *Representante:* ${state.data.representative_name}\n• *DNI:* ${state.data.representative_dni}\n• *Institución:* ${state.data.institution_name}\n• *Tipo:* ${state.data.institution_type}\n• *Ubicación:* ${state.data.location}\n• *Problema:* ${state.data.description}\n\n${texts.confirmPrompt}`;
      await replyAndStore(client, from, summary, message.id);
      break;
    case 'REQ_CONFIRM':
      if (normalized === 'si' || normalized === 'sí' || normalized === 'yes') {
        try {
          const response = await fetch('http://admin:8080/api/requests', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              representative_name: state.data.representative_name,
              representative_dni: state.data.representative_dni,
              institution_name: state.data.institution_name,
              institution_type: state.data.institution_type,
              location: state.data.location,
              description: state.data.description,
            }),
          });
          const result = (await response.json()) as any;
          if (result.success) {
            await replyAndStore(client, from, texts.reqSaved.replace('{ticket}', result.ticket_id), message.id);
          } else {
            await replyAndStore(client, from, texts.error, message.id);
          }
        } catch (e) {
          console.error('[REG] Error calling Laravel API:', e);
          await replyAndStore(client, from, texts.error, message.id);
        }
        state.step = 'IDLE';
        state.data = {};
        await replyAndStore(client, from, texts.menu, message.id);
      } else if (normalized === 'no') {
        state.step = 'IDLE';
        state.data = {};
        await replyAndStore(client, from, texts.cancelHint, message.id);
        await replyAndStore(client, from, texts.menu, message.id);
      } else {
        await replyAndStore(client, from, texts.confirmPrompt, message.id);
      }
      break;
  }
}

export async function handleTrackingFlow(client: Client, message: Message, state: UserState, texts: BotTexts) {
  const text = message.body.trim();
  const from = message.from;

  if (state.step === 'TRACK_TICKET') {
    try {
      const response = await fetch(`http://admin:8080/api/requests/${text}`);
      const result = (await response.json()) as any;
      if (result.success) {
        const data = result.data;
        const msg = `${texts.trackingStatusTitle}\n\nTicket: \`\`\`${data.ticket_id}\`\`\`\n${texts.trackingInstitution}: ${data.institution_name}\n${texts.trackingStatus}: *${data.status}*\n${texts.trackingDate}: ${new Date(data.created_at).toLocaleDateString('es-PE')}`;
        await replyAndStore(client, from, msg, message.id);
        await replyAndStore(client, from, texts.menu, message.id);
      } else {
        await replyAndStore(client, from, texts.trackNotFound, message.id);
        await replyAndStore(client, from, texts.menu, message.id);
      }
    } catch (e) {
      console.error('[TRACK] Error calling Laravel API:', e);
      await replyAndStore(client, from, texts.error, message.id);
      await replyAndStore(client, from, texts.menu, message.id);
    }
    state.step = 'IDLE';
    state.data = {};
  }
}
