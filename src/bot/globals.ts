import { Client } from '@open-wa/wa-automate';

export const Globals = {
  botClient: null as Client | null,
  currentQrCode: null as string | null,
  currentStatus: 'INITIALIZING' as string,
  hostNumber: null as string | null,
};
