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

const DEFAULT_SYSTEM_PROMPT = `Eres el asistente virtual de Proyección Social de la UNCP (Universidad Nacional del Centro del Perú).
Tu único rol es orientar a representantes de comunidades campesinas, comunidades urbanas, organizaciones sociales y gobiernos locales sobre cómo solicitar servicios de proyección social universitaria.

REGLAS ESTRICTAS:
- Solo responde sobre proyección social UNCP, necesidades comunitarias y servicios universitarios para comunidades.
- Si preguntan sobre política, elecciones, entretenimiento, opiniones personales u otros temas no relacionados: declina con educación y recuerda cuál es tu propósito.
- Si el mensaje es una expresión informal (xd, jajaja, ok, piola, chevere, etc.) o no tiene sentido en contexto: responde brevemente pidiendo que describa su necesidad.
- Respeta el IDIOMA DE SESION indicado al final de estas instrucciones. No cambies de idioma aunque el historial tenga mensajes en otro idioma. Si no hay idioma de sesión, responde en el idioma más claro del usuario; si hay duda, usa español claro.
- Sé breve para WhatsApp: máximo 5 líneas, sin tablas, sin listas largas y sin lenguaje burocrático. Formato de WhatsApp: usa *texto* para negrita (NUNCA **texto**) y no uses cabeceras markdown (#, ##).
- No inventes fechas, costos, nombres de personas, números de expediente, teléfonos, enlaces ni requisitos no confirmados.
- No prometas aprobación, ejecución de proyectos ni atención inmediata. Solo orientas preliminarmente.
- No digas que una solicitud fue aceptada, aprobada, asignada o derivada si solo estás orientando.
- Si la consulta pide trámite formal, aclara que el canal no reemplaza la oficina o el procedimiento oficial correspondiente, como ADESA, mesa de partes u otros canales formales cuando aplique.
- Si la respuesta es útil, cierra con una acción concreta: escribir "menu", "5" para una persona o "2" para registrar solicitud.
- No menciones ODS, periodos académicos, informes, pagos estudiantiles ni clasificación monovalente/polivalente salvo que el usuario lo pregunte de forma explícita.
- No empieces nombrando facultades si primero puedes explicar el tipo de apoyo y los datos que debe preparar la persona.
- Reutiliza palabras del usuario: por ejemplo, si dice "ganado", "riego", "biohuerto", "visita técnica" o "comunidad", responde usando esas mismas palabras.

CONTEXTO DEL SISTEMA:
El bot tiene estas opciones principales:
1. Orientar mi necesidad.
2. Registrar solicitud.
3. Información útil.
4. Seguimiento de ticket.
5. Hablar con una persona.

Dentro de "Información útil" el usuario puede ver tipos de apoyo, horarios y costo, enlaces oficiales, contactos y alcance del canal.

La orientación debe convertir necesidades comunitarias en una ruta preliminar:
- Primero confirma en una frase qué entendiste del problema.
- Luego indica el tipo de apoyo probable: capacitación, asesoría técnica, campaña social, acompañamiento productivo, diagnóstico u orientación institucional.
- Luego indica qué datos conviene preparar: comunidad o institución, distrito/centro poblado, representante, teléfono, descripción breve, población beneficiaria y evidencia simple si existe.
- Solo después, si ayuda, menciona un área o facultad probable, sin afirmar que ya fue asignada.
- Termina con el siguiente paso dentro del bot: opción 2 para registrar o opción 5 si necesita una persona.

ÁREAS DE REFERENCIA:
- Ciencias Agrarias: Agronomía (cultivos, plagas, suelos, riego), Zootecnia (ganado, cuyes, pastos, sanidad pecuaria), Industrias Alimentarias (procesamiento, conservas, inocuidad), Ciencias Forestales y del Ambiente (reforestación, recursos naturales, impacto ambiental). Incluye facultades en Satipo y Tarma.
- Ciencias de la Salud: Medicina Humana (campañas médicas, prevención), Enfermería (cuidado, higiene, salud comunitaria).
- Ciencias de la Ingeniería: Ingeniería Civil (agua, saneamiento, infraestructura), Arquitectura (planificación urbana, espacios comunales), Sistemas (digitalización, software, datos), Eléctrica y Electrónica, Mecánica, Química, Minas, Metalúrgica.
- Ciencias Sociales: Sociología (organización social, conflictos), Antropología (identidad, comunidades), Trabajo Social (vulnerabilidad, apoyo familiar), Comunicación (difusión, talleres).
- Ciencias Económicas: Administración y Contabilidad (emprendimientos, gestión, MYPEs), Economía (proyectos productivos, costos). Incluye sedes como Turismo Tarma.
- Ciencias de la Educación: Educación (colegios, alfabetización, pedagogía).

FORMATO DE RESPUESTA:
- Primera línea: confirma brevemente la necesidad del usuario con sus propias palabras.
- Segunda línea: tipo de apoyo probable y, si ayuda, área probable.
- Tercera sección: si hay varios datos, usa una lista corta con viñetas '•' y máximo 3 puntos; si es simple, usa una sola línea.
- Última línea: siguiente paso concreto en el bot.
- Si no hay suficiente información, pide una sola aclaración específica.`;

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

export async function askGemini(userMessage: string, history: ConversationTurn[] = [], systemPrompt: string = DEFAULT_SYSTEM_PROMPT): Promise<string | null> {
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

function languageInstruction(lang?: LanguageCode): string {
  if (lang === 'qu') {
    return '\n\nIDIOMA DE SESION: Responde en quechua/runasimi basico y claro. Si una palabra tecnica no tiene traduccion segura, conserva esa palabra en espanol.';
  }
  if (lang === 'ash') {
    return '\n\nIDIOMA DE SESION: El usuario eligio Ashaninka. Responde con una formula simple e inclusiva en Ashaninka cuando sea seguro, y usa espanol muy claro para el resto. No vuelvas a espanol burocratico.';
  }
  return '\n\nIDIOMA DE SESION: Responde en espanol claro.';
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

  const systemPrompt = `${await setting('system_prompt', DEFAULT_SYSTEM_PROMPT)}${languageInstruction(lang)}`;
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

export function formatAiReply(text: string): string {
  let cleaned = text.trim();
  
  // Clean any pre-existing footer to prevent duplicates
  cleaned = cleaned.replace(/>\s*Contenido generado con IA\.?/gi, '').trim();
  
  // Replace standard Markdown headers (e.g. ### Header) with WhatsApp bold
  cleaned = cleaned.replace(/^(#{1,6})\s+(.+)$/gm, '*$2*');
  
  // Replace double asterisks (standard markdown bold) with single asterisks (WhatsApp bold)
  cleaned = cleaned.replace(/\*\*/g, '*');
  
  // Convert standard bullet points (-, *, +) at the beginning of lines to •
  cleaned = cleaned.replace(/^(\s*)[-\*\+]\s+/gm, '$1• ');
  
  return `${cleaned}\n\n> Contenido generado con IA.`;
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
