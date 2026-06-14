# YanapayBot - Orientador Proyección Social UNCP

Asistente virtual de WhatsApp y panel de administración para orientar sobre proyectos de proyección social en la UNCP.

### Estructura
- `src/`: Core de YanapayBot (Node.js).
- `admin-panel/`: Panel de gestión administrativa (Laravel).
- `documentos/`: Base de conocimientos y documentos oficiales.

### Configuración rápida
1. Copiar `.env.example` a `.env` y configurar las llaves de IA (Gemini/Grok).
2. Levantar con Docker:
   ```bash
   docker compose up --build
   ```
3. Escanear el QR del bot en `http://localhost:3001` o desde la consola.

### Accesos
- **Bot QR Interface:** `http://localhost:3001`
- **Panel Administrativo:** `http://localhost:8080` (admin@uncp.edu.pe / uncp123456)
- **Base de datos:** Puerto 5439 (Postgres)

### Documentación
- [Manual de Usuario](MANUAL_DE_USUARIO.md)
- [Plan de solución](plan.md)
- [Resumen HackUNCP](documentos/RESUMEN-HACKATON-HACKUNCP.md)

---
_**Nota:** Las versiones en Quechua y Asháninka de este sistema son referenciales y serán validadas con hablantes nativos antes de su uso oficial institucional._
