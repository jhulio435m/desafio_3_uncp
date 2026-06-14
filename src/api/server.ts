import * as http from 'http';
import * as fs from 'fs';
import * as path from 'path';
import { Globals } from '../bot/globals';

type ShutdownFn = (signal: string) => Promise<void>;

const API_PORT = 3000;

export function createApiServer(gracefulShutdown: ShutdownFn): http.Server {
  const server = http.createServer(async (req, res) => {
    const url = req.url || '';

    if (url === '/status') {
      res.writeHead(200, { 'Content-Type': 'application/json' });
      res.end(
        JSON.stringify({
          connected: !!Globals.botClient,
          state: Globals.currentStatus,
          qrCode: Globals.currentQrCode,
          number: Globals.hostNumber,
        }),
      );

    } else if (url === '/logout' && req.method === 'POST') {
      console.log('[API] Logout requested');
      try {
        if (Globals.botClient) {
          await Globals.botClient.logout();
        }
      } catch (e) {
        console.error('[API] Error during client.logout():', e);
      }

      try {
        const sessionFile = '/sessions/UNCP_BOT.data.json';
        if (fs.existsSync(sessionFile)) {
          fs.unlinkSync(sessionFile);
          console.log('[API] Manually deleted session file');
        }
      } catch (err) {
        console.error('[API] Error deleting session file:', err);
      }

      Globals.currentStatus = 'UNPAIRED';
      Globals.currentQrCode = null;
      Globals.hostNumber = null;

      res.writeHead(200, { 'Content-Type': 'application/json' });
      res.end(JSON.stringify({ success: true, message: 'Logged out successfully' }));

      setTimeout(() => {
        gracefulShutdown('API_LOGOUT');
      }, 1500);

    } else if (url === '/restart' && req.method === 'POST') {
      console.log('[API] Restart requested. Exiting process...');
      res.writeHead(200, { 'Content-Type': 'application/json' });
      res.end(JSON.stringify({ success: true, message: 'Restarting bot process' }));
      setTimeout(() => {
        gracefulShutdown('API_RESTART');
      }, 1000);

    } else if (url.startsWith('/send-test-pdf') && req.method === 'POST') {
      const requestUrl = new URL(url, 'http://localhost');
      const to = requestUrl.searchParams.get('to') || '';
      if (!to) {
        res.writeHead(400, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ success: false, error: 'Missing ?to= chat id' }));
        return;
      }

      if (!Globals.botClient) {
        res.writeHead(503, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ success: false, error: 'Bot client not connected' }));
        return;
      }

      const docName = requestUrl.searchParams.get('doc') || 'reglamento';
      const fileMap: Record<string, string> = {
        reglamento: '/usr/src/app/documentos/reglamento_proyeccion_social.pdf',
        resumen: '/usr/src/app/documentos/RESUMEN-HACKATON-HACKUNCP.pdf',
        guia: '/usr/src/app/documentos/GUIA-RAPIDA-BOT-HACKUNCP.pdf',
      };
      const selectedPath = fileMap[docName] || fileMap.reglamento;
      const pdfPath = path.resolve(selectedPath);

      try {
        let fileData: string = pdfPath;
        if (fs.existsSync(pdfPath)) {
          const fileContent = fs.readFileSync(pdfPath).toString('base64');
          fileData = `data:application/pdf;base64,${fileContent}`;
        }
        
        // Intentar enviar un mensaje de texto primero para inicializar el chat
        await (Globals.botClient as any).sendText(to, 'Hola, adjunto el PDF solicitado.');
        
        const result = await (Globals.botClient as any).sendFile(
          to,
          fileData,
          path.basename(pdfPath),
          'PDF de referencia del proceso de Proyección Social UNCP.',
        );
        console.log(`[API] Test PDF send to=${to} doc=${docName} result=`, result);
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ success: result !== false, result }));
      } catch (e: any) {
        console.error('[API] Error sending test PDF:', e);
        res.writeHead(500, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ success: false, error: e?.message || String(e) }));
      }

    } else {
      res.writeHead(404, { 'Content-Type': 'application/json' });
      res.end(JSON.stringify({ error: 'Not Found' }));
    }
  });

  server.listen(API_PORT, '0.0.0.0', () => {
    console.log(`[API] Server listening on port ${API_PORT}`);
  });

  return server;
}
