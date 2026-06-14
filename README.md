# Orientador Proyección Social UNCP

Bot de WhatsApp y panel de administración para orientar sobre proyectos de proyección social.

### Estructura
- `src/`: Bot de WhatsApp (Node.js).
- `admin-panel/`: Panel de gestión (Laravel).

### Configuración rápida
1. Copiar `.env.example` a `.env` y configurar las llaves de IA.
2. Levantar con Docker:
   ```bash
   docker compose up --build
   ```
3. Escanear el QR del bot (aparece en la consola o en `http://localhost:3001`).

### Accesos
- **Bot QR:** `http://localhost:3001`
- **Panel Web:** `http://localhost:8080` (admin@uncp.edu.pe / uncp123456)
- **Base de datos:** Puerto 5439
