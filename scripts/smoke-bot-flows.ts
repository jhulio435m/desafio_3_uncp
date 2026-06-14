import { onMessage } from '../src/bot/messageHandler';
import { query } from '../src/database/db';
import { clearStateCache, getUserState } from '../src/bot/state';

type Reply = {
  to: string;
  body: string;
  id?: string;
};

type SentFile = {
  to: string;
  file: string;
  filename: string;
  caption: string;
  id?: string;
};

class FakeClient {
  replies: Reply[] = [];
  files: SentFile[] = [];

  async reply(to: string, body: string, id?: string) {
    this.replies.push({ to, body, id });
  }

  async sendFile(to: string, file: string, filename: string, caption: string, id?: string) {
    this.files.push({ to, file, filename, caption, id });
    return true;
  }
}

function makeMessage(from: string, body: string, id: string) {
  return {
    from,
    body,
    id,
  } as any;
}

function assertReplyContains(client: FakeClient, expected: string, label: string) {
  const last = client.replies[client.replies.length - 1];
  if (!last || !last.body.includes(expected)) {
    throw new Error(`${label}: expected latest reply to contain ${JSON.stringify(expected)}, got ${JSON.stringify(last?.body)}`);
  }
}

function assertAnyReplyContains(client: FakeClient, expected: string, label: string) {
  if (!client.replies.some((reply) => reply.body.includes(expected))) {
    throw new Error(`${label}: expected any reply to contain ${JSON.stringify(expected)}`);
  }
}

function assertNoReplyContains(client: FakeClient, unexpected: string, label: string) {
  const last = client.replies[client.replies.length - 1];
  if (last?.body.includes(unexpected)) {
    throw new Error(`${label}: expected latest reply not to contain ${JSON.stringify(unexpected)}, got ${JSON.stringify(last.body)}`);
  }
}

async function send(client: FakeClient, from: string, body: string, label: string) {
  await onMessage(client as any, makeMessage(from, body, `${from}-${label}`));
}

function replyCount(client: FakeClient) {
  return client.replies.length;
}

async function scenarioCommonVillager(client: FakeClient, phone: string) {
  await send(client, phone, 'hola', 'lang-prompt');
  assertReplyContains(client, 'Seleccione un idioma', 'villager language prompt');

  await send(client, phone, '1', 'select-es');
  assertReplyContains(client, 'Menú Principal', 'villager spanish welcome');

  await send(client, phone, 'hola', 'contextual-greeting');
  assertReplyContains(client, 'Seguimos con su consulta', 'villager contextual greeting');

  await send(client, phone, '1', 'orientation-start');
  assertReplyContains(client, 'Describa en una frase', 'villager option 1 prompt');

  await send(client, phone, 'En mi comunidad tenemos problemas con riego y plagas en papa', 'orientation-answer');
  // AI response is dynamic, just check it didn't fail
  assertNoReplyContains(client, 'Industrias Alimentarias', 'villager should not drift to food engineering');
  assertNoReplyContains(client, 'ODS', 'villager answer should not mention ODS');

  await send(client, phone, 'y también queremos mejorar nuestro biohuerto comunal', 'orientation-followup');
  const last = client.replies[client.replies.length - 1];
  if (!last?.body || last.body.includes('Ocurrió un error')) {
    throw new Error(`villager followup failed: ${JSON.stringify(last?.body)}`);
  }
  assertNoReplyContains(client, 'Industrias Alimentarias', 'villager followup should not drift to food engineering');
  assertNoReplyContains(client, 'ODS', 'villager followup should not mention ODS');

  await send(client, phone, 'necesitan que la atención en un centro médico sea rápida', 'orientation-health-followup');
  assertAnyReplyContains(client, 'salud', 'villager health followup should route to health guidance');
  assertNoReplyContains(client, '*6*', 'villager health followup should not reference stale human option');

  await send(client, phone, '3', 'info-menu');
  assertReplyContains(client, 'Información útil', 'villager info menu');

  await send(client, phone, '3', 'official-links');
  assertReplyContains(client, 'Canales oficiales', 'villager official links');

  await send(client, phone, '4', 'contacts');
  assertReplyContains(client, 'Contactos de orientación', 'villager contacts');

  await send(client, phone, '6', 'back-main');
  assertReplyContains(client, 'Menú Principal', 'villager back to main');
}

async function scenarioStudentAndProjectIntent(client: FakeClient, phone: string) {
  await send(client, phone, 'hola', 'student-lang');
  assertReplyContains(client, 'Seleccione un idioma', 'student language prompt');

  await send(client, phone, '1', 'student-select-es');
  assertReplyContains(client, 'Menú Principal', 'student welcome');

  await send(client, phone, 'Quiero hacer mi proyección social en la universidad, soy estudiante de educación inicial. En donde puedo realizar mi proyección', 'student-question');
  assertNoReplyContains(client, 'a cuántas personas afecta', 'student query should not receive community-generic FAQ');

  await send(client, phone, '¿Cómo podríamos ayudar a los representantes de comunidades campesinas, urbanas y gobiernos locales de Huancayo a comprender qué apoyo pueden solicitar a la UNCP, con quién comunicarse y cómo iniciar una solicitud de proyección social, sin tener que acudir presencialmente para orientarse?', 'project-purpose');
  assertNoReplyContains(client, 'formalización se realiza', 'project purpose should not fall into start-request generic FAQ');
}

async function scenarioTroublemaker(client: FakeClient, phone: string) {
  await send(client, phone, 'hola', 'troll-lang');
  assertReplyContains(client, 'Seleccione un idioma', 'troll language prompt');

  await send(client, phone, '1', 'troll-select-es');
  assertReplyContains(client, 'Menú Principal', 'troll welcome');

  await send(client, phone, 'xd', 'troll-informal');
  assertReplyContains(client, 'Cuando guste', 'troll informal guard');

  await send(client, phone, 'que opinas de las elecciones', 'troll-politics');
  assertReplyContains(client, 'exclusivamente a la orientación', 'troll politics guard');

  await send(client, phone, 'Que chucha pasa mrd', 'troll-profanity');
  assertReplyContains(client, 'exclusivamente a la orientación', 'troll profanity guard');
  assertNoReplyContains(client, 'Industrias Alimentarias', 'troll should not receive faculty assignment');

  await send(client, phone, 'Cuánto es 10 x 17', 'troll-math');
  assertReplyContains(client, 'exclusivamente a la orientación', 'troll math guard');
}

async function scenarioLostUser(client: FakeClient, phone: string) {
  await send(client, phone, 'hola', 'lost-lang');
  assertReplyContains(client, 'Seleccione un idioma', 'lost language prompt');

  await send(client, phone, '1', 'lost-select-es');
  assertReplyContains(client, 'Menú Principal', 'lost welcome');

  await send(client, phone, 'Cómo tomo carro a la universidad', 'lost-offdomain');
  assertReplyContains(client, 'exclusivamente a la orientación', 'lost transport guard');

  await send(client, phone, 'No sé qué opción elegir', 'lost-confused');
  const last = client.replies[client.replies.length - 1];
  if (!last?.body) {
    throw new Error('lost confused: missing reply');
  }
  if (!last.body.includes('Contenido generado con IA.') && !last.body.includes('No tengo información sobre eso.')) {
    throw new Error(`lost confused: unexpected reply ${JSON.stringify(last.body)}`);
  }
}

async function scenarioHumanContactValidation(client: FakeClient, phone: string) {
  await send(client, phone, '1', 'human-lang');
  await send(client, phone, '5', 'human-start');
  assertReplyContains(client, 'nombre del representante', 'human start');

  await send(client, phone, 'aa', 'human-invalid-name');
  assertReplyContains(client, 'nombre válido', 'human invalid name');

  await send(client, phone, 'Maria Quispe', 'human-name');
  await send(client, phone, 'abc', 'human-invalid-phone');
  assertReplyContains(client, 'teléfono o WhatsApp válido', 'human invalid phone');

  await send(client, phone, '999888777', 'human-phone');
  await send(client, phone, 'xx', 'human-invalid-topic');
  assertReplyContains(client, 'tema entendible', 'human invalid topic');

  await send(client, phone, 'Agua potable', 'human-topic');
  await send(client, phone, 'Los', 'human-invalid-message');
  assertReplyContains(client, 'Describa un poco más', 'human invalid message');

  await send(client, phone, 'Necesitamos orientación para saneamiento', 'human-message');
  assertReplyContains(client, 'Pendiente', 'human request saved');
}

async function scenarioFormalRequestAndTracking(client: FakeClient, requestPhone: string, trackPhone: string) {
  await send(client, requestPhone, '1', 'request-lang');
  await send(client, requestPhone, '2', 'request-start');
  assertReplyContains(client, 'nombre completo', 'request start');
  await send(client, requestPhone, 'Juan Ramos', 'request-name');
  await send(client, requestPhone, '12345678', 'request-dni');
  await send(client, requestPhone, 'Comunidad Demo Smoke', 'request-institution');
  await send(client, requestPhone, 'Comunidad Campesina', 'request-type');
  await send(client, requestPhone, 'El Tambo / Anexo Demo', 'request-location');
  await send(client, requestPhone, 'Queremos capacitación para procesar lácteos', 'request-desc');
  assertReplyContains(client, 'código de seguimiento', 'request saved');

  const ticketMatch = client.replies[client.replies.length - 1].body.match(/```([^`]+)```/);
  if (!ticketMatch) {
    throw new Error('request saved: tracking code not found in response');
  }

  await send(client, trackPhone, '1', 'track-lang');
  await send(client, trackPhone, '4', 'track-start');
  assertReplyContains(client, 'código de seguimiento', 'track start');
  await send(client, trackPhone, ticketMatch[1], 'track-ticket');
  assertAnyReplyContains(client, 'Estado', 'ticket tracking');

  return ticketMatch[1];
}

async function scenarioInfoSubmenu(client: FakeClient, phone: string) {
  await send(client, phone, '1', 'info-lang');
  await send(client, phone, '3', 'info-open');
  assertReplyContains(client, 'Información útil', 'info submenu open');

  await send(client, phone, '2', 'hours-cost');
  assertReplyContains(client, 'Costo referencial del trámite: S/ 3.00', 'hours and cost');

  await send(client, phone, '5', 'scope');
  assertReplyContains(client, 'No reemplaza ADESA', 'scope from submenu');
  assertNoReplyContains(client, '*6*', 'scope should not reference stale human option');

  await send(client, phone, '6', 'back-main');
  assertReplyContains(client, 'Menú Principal', 'back to main menu');
}

async function main() {
  const client = new FakeClient();
  const villagerPhone = '51999900001@c.us';
  const humanPhone = '51999900002@c.us';
  const requestPhone = '51999900003@c.us';
  const trackPhone = '51999900004@c.us';
  const trollPhone = '51999900005@c.us';
  const lostPhone = '51999900006@c.us';
  const infoPhone = '51999900007@c.us';
  const studentPhone = '51999900008@c.us';

  clearStateCache();
  await getUserState(villagerPhone);
  await query(
    'DELETE FROM bot_conversation_history WHERE user_phone = ANY($1)',
    [[villagerPhone, humanPhone, requestPhone, trackPhone, trollPhone, lostPhone, infoPhone, studentPhone]],
  );
  await query(
    'DELETE FROM bot_conversation_states WHERE user_phone = ANY($1)',
    [[villagerPhone, humanPhone, requestPhone, trackPhone, trollPhone, lostPhone, infoPhone, studentPhone]],
  );
  clearStateCache();

  const startReplies = replyCount(client);

  await scenarioCommonVillager(client, villagerPhone);
  await send(client, villagerPhone, 'Consulta sin coincidencia exacta sobre biohuerto comunal', 'free-text');
  const last = client.replies[client.replies.length - 1];
  if (!last?.body || last.body.includes('Ocurrió un error')) {
    throw new Error(`free-text fallback failed: ${JSON.stringify(last?.body)}`);
  }
  if (
    !last.body.includes('No tengo información sobre eso.') &&
    !last.body.includes('Contenido generado con IA.') &&
    !last.body.includes('biohuertos') &&
    !last.body.includes('huertos escolares')
  ) {
    throw new Error(`free-text response unexpected: ${JSON.stringify(last.body)}`);
  }

  await scenarioTroublemaker(client, trollPhone);
  await scenarioLostUser(client, lostPhone);
  await scenarioStudentAndProjectIntent(client, studentPhone);
  await scenarioHumanContactValidation(client, humanPhone);
  await scenarioInfoSubmenu(client, infoPhone);
  const trackedTicket = await scenarioFormalRequestAndTracking(client, requestPhone, trackPhone);

  console.log(JSON.stringify({
    ok: true,
    scenarios: {
      villager: true,
      troll: true,
      lost: true,
      studentAndProject: true,
      humanValidation: true,
      infoSubmenu: true,
      requestTracking: true,
    },
    replies: client.replies.length - startReplies,
    trackedTicket,
  }));
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
