import { create, Client, NotificationLanguage, ev } from '@open-wa/wa-automate';
import * as fs from 'fs';

import { Globals } from './bot/globals';
import { cleanUpChromium } from './bot/utils';
import { onMessage } from './bot/messageHandler';
import { createApiServer } from './api/server';

// ─── Boot cleanup ────────────────────────────────────────────────────────────
cleanUpChromium();

// ─── Browser config ──────────────────────────────────────────────────────────
const chromeVersion = process.env.CHROME_VERSION || '149.0.7827.102';
const whatsappUserAgent =
  process.env.WA_CUSTOM_USER_AGENT ||
  `Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/${chromeVersion} Safari/537.36`;

// ─── Graceful shutdown ───────────────────────────────────────────────────────
async function gracefulShutdown(signal: string) {
  console.log(`[SHUTDOWN] Signal received: ${signal}. Initiating clean shutdown...`);

  // Hard-kill timer — ensures Docker restart: always fires
  setTimeout(() => {
    console.log('[SHUTDOWN] Hard timeout reached. Forcing process exit...');
    cleanUpChromium();
    process.exit(1);
  }, 3000);

  if (Globals.botClient) {
    try {
      await Globals.botClient.kill();
    } catch (err) {}
  }

  cleanUpChromium();
  process.exit(1);
}

// ─── API server ───────────────────────────────────────────────────────────────
createApiServer(gracefulShutdown);

// ─── QR code event listener ──────────────────────────────────────────────────
ev.on('qr.**', async (qrcode, sessionId) => {
  if (sessionId === 'UNCP_BOT') {
    console.log('[QR] New QR code generated (event)');
    Globals.currentQrCode = qrcode;
    Globals.currentStatus = 'QR_CODE';
    const base64Data = qrcode.replace(/^data:image\/png;base64,/, '');
    fs.writeFileSync('bot-qr.png', base64Data, 'base64');
  }
});

// ─── WhatsApp client ─────────────────────────────────────────────────────────
create({
  sessionId: 'UNCP_BOT',
  multiDevice: true,
  inDocker: true,
  sessionDataPath: '/sessions',
  authTimeout: 0,
  qrTimeout: 0,
  blockCrashLogs: true,
  disableSpins: true,
  headless: true,
  hostNotificationLang: NotificationLanguage.ES,
  logConsole: true,
  useChrome: true,
  executablePath: '/usr/bin/google-chrome-stable',
  customUserAgent: whatsappUserAgent,
  popup: 3001,
  qrPopUpOnly: true,
  qrLogSkip: false,
  deleteSessionDataOnLogout: true,
  chromiumArgs: [
    '--no-sandbox',
    '--disable-setuid-sandbox',
    '--disable-dev-shm-usage',
    '--disable-gpu',
  ],
  onQr: (qrCode: string) => {
    console.log('[QR] New QR code generated');
    Globals.currentQrCode = qrCode;
    Globals.currentStatus = 'QR_CODE';
    const base64Data = qrCode.replace(/^data:image\/png;base64,/, '');
    fs.writeFileSync('bot-qr.png', base64Data, 'base64');
  },
})
  .then(async (client: Client) => {
    Globals.botClient = client;
    Globals.currentStatus = 'CONNECTED';
    Globals.currentQrCode = null;

    try {
      Globals.hostNumber = await client.getHostNumber();
      console.log(`[BOT] Connected as ${Globals.hostNumber}`);
    } catch (e) {}

    client.onStateChanged((state) => {
      console.log(`[STATE] ${state}`);
      Globals.currentStatus = state;

      if (state === 'UNPAIRED') {
        Globals.hostNumber = null;
        try {
          const sessionFile = '/sessions/UNCP_BOT.data.json';
          if (fs.existsSync(sessionFile)) fs.unlinkSync(sessionFile);
        } catch (err) {}
        setTimeout(() => process.exit(1), 1500);
      } else if (state === 'CONNECTED') {
        Globals.currentQrCode = null;
      }
    });

    // Delegate all message handling to the message handler module
    client.onMessage((message) => onMessage(client, message));
  })
  .catch((err) => {
    console.error('[BOT] Failed to start:', err);
    Globals.currentStatus = 'FAILED';
    process.exit(1);
  });

// ─── OS signal handlers ───────────────────────────────────────────────────────
process.on('SIGTERM', () => gracefulShutdown('SIGTERM'));
process.on('SIGINT', () => gracefulShutdown('SIGINT'));
