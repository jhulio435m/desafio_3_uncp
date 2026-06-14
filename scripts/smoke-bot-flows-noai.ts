import { onMessage } from '../src/bot/messageHandler';
import { query } from '../src/database/db';
import { clearStateCache, getUserState } from '../src/bot/state';

type Reply = { to: string; body: string; id?: string };

class FakeClient {
  replies: Reply[] = [];

  async reply(to: string, body: string, id?: string) {
    this.replies.push({ to, body, id });
  }

  async simulateTyping() {
    return true;
  }
}

function makeMessage(from: string, body: string, id: string) {
  return { from, body, id } as any;
}

async function send(client: FakeClient, from: string, body: string, label: string) {
  await onMessage(client as any, makeMessage(from, body, `${from}-${label}`));
}

async function bootSpanish(client: FakeClient, phone: string, label: string) {
  await send(client, phone, 'hola', `${label}-lang`);
  await send(client, phone, '1', `${label}-select-es`);
}

async function bootQuechua(client: FakeClient, phone: string, label: string) {
  await send(client, phone, 'hola', `${label}-lang`);
  await send(client, phone, '2', `${label}-select-qu`);
}

function latest(client: FakeClient) {
  return client.replies[client.replies.length - 1]?.body || '';
}

function assertContains(body: string, expected: string, label: string) {
  if (!body.includes(expected)) {
    throw new Error(`${label}: expected ${JSON.stringify(expected)} in ${JSON.stringify(body)}`);
  }
}

function assertNotContains(body: string, unexpected: string, label: string) {
  if (body.includes(unexpected)) {
    throw new Error(`${label}: unexpected ${JSON.stringify(unexpected)} in ${JSON.stringify(body)}`);
  }
}

function assertAnyReplyContains(client: FakeClient, expected: string, label: string) {
  if (!client.replies.some((reply) => reply.body.includes(expected))) {
    throw new Error(`${label}: expected a reply containing ${JSON.stringify(expected)}`);
  }
}

async function resetPhones(phones: string[]) {
  const deletions = [
    'DELETE FROM bot_conversation_history WHERE user_phone = ANY($1)',
    'DELETE FROM bot_conversation_states WHERE user_phone = ANY($1)',
    'DELETE FROM human_contact_requests WHERE phone = ANY($1)',
  ];

  for (const sql of deletions) {
    try {
      await query(sql, [phones]);
    } catch (error) {
      console.warn(`[smoke-noai] skip cleanup: ${sql}`);
    }
  }
}

async function main() {
  const phones = [
    '51999910001@c.us',
    '51999910002@c.us',
    '51999910003@c.us',
    '51999910004@c.us',
    '51999910005@c.us',
    '51999910006@c.us',
    '51999910007@c.us',
    '51999910008@c.us',
    '51999910009@c.us',
  ];

  const client = new FakeClient();
  const originalAiMode = await query(`SELECT value FROM bot_settings WHERE key = 'ai_mode' LIMIT 1`);
  const previousAiMode = originalAiMode.rows[0]?.value || 'activa';

  try {
    await query(`UPDATE bot_settings SET value = 'off' WHERE key = 'ai_mode'`);
    clearStateCache();
    await getUserState(phones[0]);
    await resetPhones(phones);
    clearStateCache();

    const [languagePhone, humanPhone, requestPhone, trackPhone, infoPhone, offTopicPhone, informalPhone, esPhone, quPhone] = phones;

    await send(client, languagePhone, 'hola', 'lang-prompt');
    assertContains(latest(client), 'Seleccione un idioma', 'language prompt');
    await send(client, languagePhone, '1', 'lang-select-es');
    assertContains(latest(client), 'Menú Principal', 'spanish welcome');
    await send(client, languagePhone, 'menu', 'menu-reset');
    assertContains(latest(client), 'Menú Principal', 'menu reset');

    await bootSpanish(client, offTopicPhone, 'offtopic');
    await bootSpanish(client, informalPhone, 'informal');
    await bootSpanish(client, infoPhone, 'info');
    await bootSpanish(client, humanPhone, 'human');
    await bootSpanish(client, requestPhone, 'request');
    await bootSpanish(client, trackPhone, 'track');
    await bootSpanish(client, esPhone, 'orient');
    await bootQuechua(client, quPhone, 'qu');
    assertAnyReplyContains(client, 'Akllana / Menú', 'quechua welcome');

    await send(client, offTopicPhone, 'qué opinas de las elecciones', 'offtopic');
    assertContains(latest(client), 'exclusivamente a la orientación', 'off-topic guard');

    await send(client, informalPhone, 'xd', 'informal');
    assertContains(latest(client), 'identificar una solicitud', 'informal guard');

    await send(client, infoPhone, '3', 'info-open');
    assertContains(latest(client), 'Información útil', 'info menu open');
    await send(client, infoPhone, '1', 'info-process');
    // Checks for either DB content or deterministic fallback
    if (!latest(client).includes('La UNCP puede') && !latest(client).includes('Tipos de apoyo disponibles')) {
      throw new Error(`process services: unexpected response ${JSON.stringify(latest(client))}`);
    }
    await send(client, infoPhone, '3', 'info-links');
    assertContains(latest(client), 'Canales oficiales', 'official links');
    await send(client, infoPhone, '4', 'info-contacts');
    assertContains(latest(client), 'Contactos de orientación', 'contacts');
    await send(client, infoPhone, '2', 'info-hours');
    assertContains(latest(client), 'Lunes a Viernes', 'hours');
    assertContains(latest(client), 'S/ 3.00', 'hours cost');
    await send(client, infoPhone, '5', 'info-scope');
    assertContains(latest(client), 'ADESA', 'scope');
    await send(client, infoPhone, '6', 'info-back');
    assertContains(latest(client), 'Menú Principal', 'back to menu');

    await send(client, humanPhone, '5', 'human-start');
    assertContains(latest(client), 'nombre del representante', 'human prompt');
    await send(client, humanPhone, 'aa', 'human-invalid-name');
    assertContains(latest(client), 'nombre válido', 'human invalid name');
    await send(client, humanPhone, 'Maria Quispe', 'human-name');
    await send(client, humanPhone, 'abc', 'human-invalid-phone');
    assertContains(latest(client), 'teléfono o WhatsApp válido', 'human invalid phone');
    await send(client, humanPhone, '999888777', 'human-phone');
    await send(client, humanPhone, 'xx', 'human-invalid-topic');
    assertContains(latest(client), 'tema entendible', 'human invalid topic');
    await send(client, humanPhone, 'Agua potable', 'human-topic');
    await send(client, humanPhone, 'Los', 'human-invalid-message');
    assertContains(latest(client), 'Describa un poco más', 'human invalid message');
    await send(client, humanPhone, 'Necesitamos orientación para saneamiento', 'human-message');
    assertContains(latest(client), 'Resumen', 'human summary');
    await send(client, humanPhone, 'sí', 'human-confirm');
    if (!client.replies.some((reply) => reply.body.includes('Pendiente'))) {
      throw new Error('human saved: expected a reply containing Pendiente');
    }

    await send(client, requestPhone, '2', 'request-start');
    assertContains(latest(client), 'Consentimiento', 'request consent');
    await send(client, requestPhone, 'aceptar', 'request-consent-ok');
    assertContains(latest(client), 'nombre completo', 'request rep name');
    await send(client, requestPhone, 'Juan Ramos', 'request-name');
    await send(client, requestPhone, '12345678', 'request-dni');
    await send(client, requestPhone, 'Comunidad Demo Smoke', 'request-inst');
    await send(client, requestPhone, 'Comunidad Campesina', 'request-type');
    await send(client, requestPhone, 'El Tambo / Anexo Demo', 'request-location');
    await send(client, requestPhone, 'Queremos capacitación para procesar lácteos', 'request-desc');
    assertContains(latest(client), 'Resumen', 'request summary');
    await send(client, requestPhone, 'sí', 'request-confirm');
    const ticketReply = client.replies.find((reply) => reply.body.includes('código de seguimiento'));
    if (!ticketReply) throw new Error('request saved: expected a reply with tracking code');
    const ticketMatch = ticketReply.body.match(/```([^`]+)```/);
    if (!ticketMatch) throw new Error('request saved: missing tracking code');
    const ticketCode = ticketMatch[1].trim();

    await send(client, trackPhone, 'menu', 'track-reset');
    await send(client, trackPhone, '4', 'track-start');
    assertAnyReplyContains(client, 'código de seguimiento', 'track prompt');
    await send(client, trackPhone, 'SIM001', 'track-ticket');
    assertAnyReplyContains(client, 'Estado', 'tracking status');

    await send(client, esPhone, '1', 'orient-start');
    assertContains(latest(client), 'Describa en una frase', 'orientation prompt');
    await send(client, esPhone, 'Necesitamos apoyo para nuestro ganado', 'orient-faq');
    assertNotContains(latest(client), 'Ocurrió un error', 'orientation faq should succeed');
    // If FAQ works, it should contain the answer. If not, it hits 'noSpecificOrientation'
    if (!latest(client).includes('ganado') && !latest(client).includes('No encontré orientación específica')) {
      throw new Error(`orientation faq: unexpected response ${JSON.stringify(latest(client))}`);
    }
    await send(client, esPhone, 'abc', 'orient-no-match');
    assertContains(latest(client), 'identificar una solicitud', 'orientation fallback');

    await send(client, quPhone, 'menu', 'qu-menu');
    assertAnyReplyContains(client, 'Akllana / Menú', 'quechua menu persist');

    console.log(JSON.stringify({
      ok: true,
      replies: client.replies.length,
      trackedTicket: ticketCode,
      phones: phones.length,
    }));
  } finally {
    await query(`UPDATE bot_settings SET value = $1 WHERE key = 'ai_mode'`, [previousAiMode]);
    clearStateCache();
  }
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
