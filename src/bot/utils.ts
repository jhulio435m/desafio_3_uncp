import { execSync } from 'child_process';
import * as fs from 'fs';
import { Client } from '@open-wa/wa-automate';
import { query } from '../database/db';
import { LanguageCode, BotTexts } from './types';
import { BOT_COPY } from './constants';
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
  const copy = BOT_COPY[lang];

  return {
    ...copy,
    welcome: await setting(
      'welcome_message',
      'Hola, soy el orientador virtual para solicitudes de proyección social. Puedo ayudarte a entender qué apoyo podrías solicitar, qué datos preparar, qué canal oficial usar y cuándo pedir contacto con una persona.',
    ),
    fallback: await setting(
      'fallback_message',
      'No tengo una respuesta segura para esa consulta. Puedo registrar tu pedido para que una persona te oriente sobre el proceso de proyección social.',
    ),
    scope: await setting(
      'scope_message',
      'Este canal orienta sobre el proceso de proyección social. No reemplaza ADESA, mesa de partes ni los procedimientos oficiales de aprobación y ejecución.',
    ),
    officeHours: await setting(
      'office_hours',
      'Lunes a Viernes de 8:00 AM a 1:00 PM y de 2:00 PM a 5:00 PM.',
    ),
  } as BotTexts;
}

export function languageFromInput(input: string): LanguageCode | null {
  const n = input.toLowerCase().trim();
  if (['1', 'espanol', 'español', 'castellano'].includes(n)) return 'es';
  if (['2', 'quechua', 'runasimi', 'wanka'].includes(n)) return 'qu';
  if (['3', 'ashaninka', 'asháninka'].includes(n)) return 'ash';
  return null;
}

export function formatWelcome(texts: BotTexts, lang: LanguageCode): string {
  let welcome = `*Orientador de Proyección Social UNCP*\n\n${texts.welcome}\n\n${texts.menu}`;
  if (lang === 'qu') {
    welcome = `*Proyección Social UNCP Yanapakuq*\n\n${texts.welcome}\n\n${texts.menu}`;
  }
  return welcome;
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
  try {
    await (client as any).simulateTyping(to, true);
    const delay = Math.min(Math.max(body.length * 15, 500), 2500);
    await new Promise((resolve) => setTimeout(resolve, delay));
  } catch (err) {
    console.warn('[BOT] simulateTyping failed:', err);
  } finally {
    try {
      await (client as any).simulateTyping(to, false);
    } catch (_) {}
  }
  await (client as any).reply(to, body, id);
  await appendConversationMessage(to, 'assistant', body);
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
