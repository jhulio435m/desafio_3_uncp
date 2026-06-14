import { GoogleGenerativeAI } from '@google/generative-ai';
import { ConversationTurn, LanguageCode } from './types';
import { setting } from './utils';

const GEMINI_API_KEY = process.env.GEMINI_API_KEY || '';
const XAI_API_KEY = process.env.XAI_API_KEY || '';
const XAI_BASE_URL = (process.env.XAI_BASE_URL || 'https://api.x.ai/v1').replace(/\/+$/, '');
const XAI_MODEL = process.env.XAI_MODEL || 'grok-4-latest';
const GROQ_API_KEY = process.env.GROQ_API_KEY || '';
const GROQ_BASE_URL = (process.env.GROQ_BASE_URL || 'https://api.groq.com/openai/v1').replace(/\/+$/, '');
const GROQ_MODEL = process.env.GROQ_MODEL || 'llama-3.3-70b-versatile';
const NVIDIA_API_KEY = process.env.NVIDIA_API_KEY || '';
const NVIDIA_BASE_URL = (process.env.NVIDIA_BASE_URL || 'https://integrate.api.nvidia.com/v1').replace(/\/+$/, '');
const NVIDIA_MODEL = process.env.NVIDIA_MODEL || 'moonshotai/kimi-k2.6';
const NVIDIA_FALLBACK_MODEL = process.env.NVIDIA_FALLBACK_MODEL || 'minimaxai/minimax-m3';

const DEFAULT_DETAIL_FIELDS = [
  'Comunidad o institución que reporta',
  'Distrito o centro poblado',
  'Nombre del representante y teléfono',
  'Descripción breve de la necesidad',
] as const;

let genAI: GoogleGenerativeAI | null = null;

function getClient(): GoogleGenerativeAI | null {
  if (!GEMINI_API_KEY) return null;
  if (!genAI) {
    genAI = new GoogleGenerativeAI(GEMINI_API_KEY);
  }
  return genAI;
}

function cleanHistoryTurn(body: string): string {
  return body.replace(/>\s*Contenido generado con IA\.?/gi, '').trim();
}

function cleanAiBody(body: string): string {
  const stripped = body
    .replace(/>\s*(Contenido generado con IA|Esta orientación es referencial.*)\.?/gi, '')
    .replace(/\r/g, '');

  return stripped
    .split('\n')
    .map((line) => line.replace(/[ \t]+$/g, '').replace(/\s{2,}/g, ' ').trim())
    .join('\n')
    .replace(/\n{3,}/g, '\n\n')
    .trim();
}

function splitSentences(text: string): string[] {
  return text
    .split(/(?<=[.!?¿?])\s+|\n+/)
    .map((part) => part.trim())
    .filter(Boolean);
}

function extractBulletLines(text: string): string[] {
  const bullets: string[] = [];
  for (const rawLine of text.split('\n')) {
    const line = rawLine.trim();
    const match = line.match(/^(?:[-*•]|\d+[.)])\s+(.*)$/);
    if (match?.[1]) {
      bullets.push(match[1].trim());
    }
  }
  return bullets.filter(Boolean);
}

function truncateLine(text: string, maxLength = 160): string {
  const compact = text.replace(/\s+/g, ' ').trim();
  if (compact.length <= maxLength) return compact;
  return `${compact.slice(0, maxLength - 1).trim()}…`;
}

function pickFallbackBullets(text: string): string[] {
  const sentences = splitSentences(text).slice(2, 6);
  const fallback = [...DEFAULT_DETAIL_FIELDS];
  const bullets = sentences.length > 0 ? sentences : fallback;
  return bullets.slice(0, 4).map((item) => truncateLine(item));
}

function extractSection(text: string, startLabel: RegExp, endLabel?: RegExp): string {
  const startMatch = text.match(startLabel);
  if (!startMatch || startMatch.index === undefined) return '';

  const startIndex = startMatch.index + startMatch[0].length;
  const remaining = text.slice(startIndex);
  if (!endLabel) return remaining.trim();

  const endMatch = remaining.match(endLabel);
  return (endMatch?.index !== undefined ? remaining.slice(0, endMatch.index) : remaining).trim();
}

function splitLooseBullets(text: string): string[] {
  return text
    .split(/\n+|(?:\s+[-•*]\s+)|;\s+|,\s+/)
    .map((part) => part.replace(/^(?:[-*•]|\d+[.)])\s+/, '').replace(/[;.,\s]+$/g, '').trim())
    .filter(Boolean);
}

function normalizeListItem(text: string): string {
  return text
    .replace(/^(?:prepara|datos a preparar|dato a preparar|siguiente paso)\s*:\s*/i, '')
    .replace(/^(?:consejo|recomendaci[oó]n)\s*:\s*/i, '')
    .replace(/^[-*•]\s*/, '')
    .replace(/\*/g, '')
    .replace(/\s+/g, ' ')
    .trim();
}

function isInformativeDetail(text: string): boolean {
  const compact = text.replace(/\s+/g, ' ').trim();
  if (compact.length < 18) return false;
  if (/^(comunidad|tipo de siembra|lugar)$/i.test(compact)) return false;
  return true;
}

function buildConversationPrompt(userMessage: string, history: ConversationTurn[]): string {
  if (history.length === 0) return userMessage;

  const transcript = history
    .map((turn) => {
      const content = turn.role === 'assistant' ? cleanHistoryTurn(turn.body) : turn.body;
      return `${turn.role === 'assistant' ? 'Asistente' : 'Usuario'}: ${content}`;
    })
    .join('\n');

  return `Historial reciente:\n${transcript}\n\nMensaje actual del usuario: ${userMessage}`;
}

export async function askGemini(userMessage: string, history: ConversationTurn[] = [], systemPrompt: string = ''): Promise<string | null> {
  const client = getClient();
  if (!client) return null;

  try {
    const model = client.getGenerativeModel({
      model: 'gemini-flash-latest',
      systemInstruction: systemPrompt,
    });

    const timeoutMs = 8000;
    const result = await Promise.race([
      model.generateContent(buildConversationPrompt(userMessage, history)),
      new Promise<null>((_, reject) =>
        setTimeout(() => reject(new Error('Gemini timeout')), timeoutMs),
      ),
    ]);

    if (!result) return null;

    const text = (result as any).response?.text?.();
    if (!text || text.trim().length === 0) return null;

    return text.trim();
  } catch (err: any) {
    const msg = err?.message || String(err);
    if (msg.includes('API_KEY_INVALID') || msg.includes('API key expired') || msg.includes('quota')) {
      console.warn('[GEMINI] Key issue (skipping AI):', msg.split('\n')[0]);
    } else if (msg.includes('timeout')) {
      console.warn('[GEMINI] Timeout - skipping AI response');
    } else {
      console.error('[GEMINI] Unexpected error:', msg.split('\n')[0]);
    }
    return null;
  }
}

type ChatCompletionResponse = {
  choices?: Array<{
    message?: {
      content?: string;
    };
  }>;
  error?: {
    message?: string;
  };
};

async function askOpenAiCompatible(
  provider: 'GROK' | 'GROQ' | 'NVIDIA',
  baseUrl: string,
  apiKey: string,
  model: string,
  userMessage: string,
  history: ConversationTurn[],
  systemPrompt: string,
): Promise<string | null> {
  if (!apiKey) return null;
  try {
    const timeoutMs = provider === 'NVIDIA' ? 15000 : 4000;
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), timeoutMs);

    const tokenLimit = provider === 'GROQ'
      ? { max_completion_tokens: 220 }
      : provider === 'NVIDIA'
        ? { max_tokens: 1024 }
        : { max_tokens: 220 };

    const response = await fetch(`${baseUrl}/chat/completions`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${apiKey}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        model,
        temperature: 0.2,
        ...tokenLimit,
        messages: [
          { role: 'system', content: systemPrompt },
          ...history.map((turn) => ({
            role: turn.role,
            content: turn.role === 'assistant' ? cleanHistoryTurn(turn.body) : turn.body,
          })),
          { role: 'user', content: userMessage },
        ],
      }),
      signal: controller.signal,
    });

    clearTimeout(timeout);

    const payload = (await response.json().catch(() => ({}))) as ChatCompletionResponse;
    if (!response.ok) {
      const detail = payload.error?.message || `${response.status} ${response.statusText}`;
      console.warn(`[${provider}] Request failed:`, detail);
      return null;
    }

    const text = payload.choices?.[0]?.message?.content;
    if (!text || text.trim().length === 0) return null;

    return text.trim();
  } catch (err: any) {
    if (err?.name === 'AbortError') {
      console.warn(`[${provider}] Timeout - skipping AI response`);
    } else {
      console.error(`[${provider}] Unexpected error:`, (err?.message || String(err)).split('\n')[0]);
    }
    return null;
  }
}

async function askGrok(userMessage: string, history: ConversationTurn[], systemPrompt: string): Promise<string | null> {
  return askOpenAiCompatible('GROK', XAI_BASE_URL, XAI_API_KEY, XAI_MODEL, userMessage, history, systemPrompt);
}

async function askGroqCloud(userMessage: string, history: ConversationTurn[], systemPrompt: string): Promise<string | null> {
  return askOpenAiCompatible('GROQ', GROQ_BASE_URL, GROQ_API_KEY, GROQ_MODEL, userMessage, history, systemPrompt);
}

async function askKimi(userMessage: string, history: ConversationTurn[], systemPrompt: string): Promise<string | null> {
  return askOpenAiCompatible('NVIDIA', NVIDIA_BASE_URL, NVIDIA_API_KEY, NVIDIA_MODEL, userMessage, history, systemPrompt);
}

async function askMiniMax(userMessage: string, history: ConversationTurn[], systemPrompt: string): Promise<string | null> {
  return askOpenAiCompatible('NVIDIA', NVIDIA_BASE_URL, NVIDIA_API_KEY, NVIDIA_FALLBACK_MODEL, userMessage, history, systemPrompt);
}

export async function askAssistant(
  userMessage: string,
  history: ConversationTurn[] = [],
  lang: LanguageCode = 'es',
): Promise<string | null> {
  const aiMode = (await setting('ai_mode', 'activa')).toLowerCase().trim();
  if (['off', 'false', '0', 'no', 'desactivada', 'desactivado'].includes(aiMode)) {
    console.log('[AI] Disabled by bot_settings.ai_mode');
    return null;
  }

  const systemPrompt = (await setting('system_prompt', '')).trim();
  if (!systemPrompt) {
    console.warn('[AI] bot_settings.system_prompt is empty; skipping AI');
    return null;
  }

  const trimmedHistory = history.slice(-6);

  const groqResponse = await askGroqCloud(userMessage, trimmedHistory, systemPrompt);
  if (groqResponse) {
    console.log('[GROQ] Responded to AI fallback query');
    return groqResponse;
  }

  const kimiResponse = await askKimi(userMessage, trimmedHistory, systemPrompt);
  if (kimiResponse) {
    console.log('[KIMI] Responded to AI fallback query');
    return kimiResponse;
  }

  const minimaxResponse = await askMiniMax(userMessage, trimmedHistory, systemPrompt);
  if (minimaxResponse) {
    console.log('[MINIMAX] Responded to AI fallback query');
    return minimaxResponse;
  }

  const grokResponse = await askGrok(userMessage, trimmedHistory, systemPrompt);
  if (grokResponse) {
    console.log('[GROK] Responded to AI fallback query');
    return grokResponse;
  }

  const geminiResponse = await askGemini(userMessage, trimmedHistory, systemPrompt);
  if (geminiResponse) {
    console.log('[GEMINI] Responded to AI fallback query');
    return geminiResponse;
  }

  return null;
}

export function formatAiReply(text: string, aiFooter: string = 'Esta orientación es referencial y no reemplaza la evaluación oficial de la UNCP.'): string {
  const normalized = cleanAiBody(text);
  const supportSection = extractSection(normalized, /apoyo probable\s*:/i, /datos a preparar\s*:/i);
  const dataSection = extractSection(normalized, /datos a preparar\s*:/i, /siguiente paso\s*:/i);
  const nextSection = extractSection(normalized, /siguiente paso\s*:/i);
  const introSection = normalized
    .split(/apoyo probable\s*:/i)[0]
    .split(/datos a preparar\s*:/i)[0]
    .split(/siguiente paso\s*:/i)[0]
    .trim();

  const paragraphs = introSection
    .split(/\n{2,}/)
    .map((part) => part.trim())
    .filter(Boolean);

  const explicitBullets = extractBulletLines(text).map((line) => truncateLine(line));
  const sentencePool = splitSentences(introSection || normalized).map((line) => truncateLine(line));

  const intro = truncateLine(sentencePool[0] || paragraphs[0] || normalized || 'Entiendo su necesidad.');
  const support = truncateLine(
    supportSection
      || sentencePool[1]
      || paragraphs[1]
      || sentencePool.slice(2).find((line) => /apoyo|asesor|capacit|orient|área|area/i.test(line))
      || 'Orientación general sobre proyección social.',
  );

  const structuredDetails = splitLooseBullets(dataSection)
    .map(normalizeListItem)
    .filter((line) => line.length > 0 && !/siguiente paso|menu|escriba\s*2|escriba\s*5/i.test(line));

  const fallbackDetails = [
    ...explicitBullets,
    ...sentencePool.slice(2),
    ...paragraphs.slice(2).map((part) => truncateLine(part)),
  ]
    .map(normalizeListItem)
    .filter((line) => line.length > 0 && !/siguiente paso|menu|escriba\s*2|escriba\s*5/i.test(line));

  const detailCandidates = structuredDetails.length > 0 ? structuredDetails : fallbackDetails;

  const detailPool = detailCandidates
    .filter(isInformativeDetail)
    .map((item) => truncateLine(item));

  const detailBullets = Array.from(new Set([...DEFAULT_DETAIL_FIELDS, ...detailPool]))
    .map((item) => truncateLine(item))
    .filter(Boolean)
    .slice(0, 4);

  const nextStep =
    truncateLine(
      splitSentences(nextSection || normalized).find((line) => /menu|opción\s*2|opción\s*5|registr/i.test(line))
      || nextSection
      || sentencePool.find((line) => /menu|opción\s*2|opción\s*5|registr/i.test(line))
      || '',
    )
    || 'Escriba *2* para registrar o *5* para hablar con una persona.';

  return [
    intro,
    `*Apoyo probable:* ${support}`,
    '',
    '*Datos a preparar:*',
    ...detailBullets.map((item) => `• ${item}`),
    '',
    `*Siguiente paso:* ${nextStep.replace(/^\s*\*\s*/,'')}`,
    '',
    `> ${aiFooter}`,
  ].join('\n');
}

// ─── Local guards (no API needed) ────────────────────────────────────────────

const OFF_TOPIC_PATTERNS = [
  // Política y elecciones
  /elecciones?|candidato|vot(ar|o|ación)|partido\s+polit|presidente|congres[oa]|alcalde|gobierno\s+central/i,
  // Entretenimiento, cultura pop y ocio
  /tung\s*tung|brainrot|sigma|skibidi|meme|anime|serie|pelicula|película|netflix|spotify|youtube|tiktok|instagram|videojuego|playstation|xbox|nintendo|gamer/i,
  // Deportes
  /f[uú]tbol|partido|estadio|jugador|apuesta|entrenamiento|liga|copa|gol|balon|pelota|mundial|messi|ronaldo/i,
  // Identidad e IA (preguntas sobre el bot)
  /quien\s+eres|eres\s+(ia|robot|bot|persona|humano|real)|tu\s+nombre|como\s+te\s+llamas|quien\s+te\s+creo|openai|gemini|chatgpt|asistente\s+virtual/i,
  // Sentimental, privado y sexual
  /enamorado|novi[oa]|pareja|amor|te\s+quiero|te\s+amo|solter[oa]|casad[oa]|sexo|xxx|porn|pack|gemir|beso|besame/i,
  // Matemática o consultas generales fuera de dominio
  /cu[aá]nto\s+es\s+\d+|\b\d+\s*[\+\-*x\/]\s*\d+\b|raiz\s+cuadrada|logaritmo/i,
  // Logística general no relacionada a proyección social
  /tom(o|ar)\s+carro|llegar\s+a\s+la\s+universidad|pasaje|ruta|bus|micro|combi|uber|taxi/i,
  // Insultos o agresión verbal
  /chucha|mrd|mierda|carajo|ptm|joder|imbecil|idiota|cojud|huevon|estupido|pendejo|baboso|basura|porqueria/i,
  // Opiniones personales y consejos de vida
  /qu[eé]\s+(opinas|piensas|crees|sientes)|tu\s+opini[oó]n|qu[eé]\s+har[ií]as|consejo|ayudame\s+con\s+mi\s+vida/i,
  // Academico fuera de alcance
  /carrera\s+de\s+(medicina|ingenieria\s+aeroesp|arquitectura|odontologia|psicolog)|examen\s+de\s+admision|cepre|matricularme|notas|mi\s+horario/i,
  // Preguntas filosóficas/existenciales y religión
  /sentido\s+de\s+la\s+vida|dios\s+existe|inteligencia\s+artificial\s+(vs|contra)|religion|biblia|jesus|iglesia/i,
  // Tecnología general
  /hackear|virus|computadora|celular|windows|linux|iphone|android|wifi|contraseña|password/i,
];

export function isOffTopic(text: string): boolean {
  return OFF_TOPIC_PATTERNS.some(pattern => pattern.test(text));
}

export function isInformalMessage(text: string): boolean {
  if (!text || text.trim().length === 0) return true;
  const trimmed = text.trim();
  // Ignorar mensajes muy cortos que no sean números
  if (trimmed.length <= 3 && !/^\d$/.test(trimmed)) return true;

  // Expresiones informales ampliadas
  const informalPatterns = /^(xd+|jaja+|lol|ok|oka+y|piola|chevere|chev[eé]re|hehe|uwu|owo|:v|bruh|lmao|genial|cool|nice|parcero|causa|pana|bro|men|we+y|wey|crack|gg|ntp|np|dale|ya|sip|nop|neg|po+s|ps|oye|oiga|hey|manito|mano|habla|hablame|alo|oe|oee+|oye|asumi|ya|listo|entendido)$/i;
  return informalPatterns.test(trimmed.toLowerCase());
}
