# Panel Laravel del Orientador

Este panel administra el contenido del bot de WhatsApp para el proceso de proyección social.

## Alcance

- Mantener preguntas frecuentes o guías del proceso.
- Registrar canales oficiales.
- Registrar responsables y contactos.
- Administrar pedidos de orientación humana.
- Revisar consultas sin respuesta.
- Editar mensajes base del bot.

No gestiona solicitudes formales ni reemplaza ADESA o mesa de partes.

## Acceso local

- URL: `http://localhost:8080`
- Usuario: `admin@uncp.edu.pe`
- Contraseña: `uncp123456`

## Estructura

- [routes/web.php](/home/blink/hackuncp/admin-panel/routes/web.php): rutas del panel.
- [app/Http/Controllers/BotManagementController.php](/home/blink/hackuncp/admin-panel/app/Http/Controllers/BotManagementController.php): lógica de gestión.
- [resources/views/dashboard.blade.php](/home/blink/hackuncp/admin-panel/resources/views/dashboard.blade.php): tablero principal.
- [resources/views/bot/faqs.blade.php](/home/blink/hackuncp/admin-panel/resources/views/bot/faqs.blade.php): guías del proceso.
- [resources/views/bot/links.blade.php](/home/blink/hackuncp/admin-panel/resources/views/bot/links.blade.php): canales oficiales.
- [resources/views/bot/contacts.blade.php](/home/blink/hackuncp/admin-panel/resources/views/bot/contacts.blade.php): responsables y contactos.
- [resources/views/bot/human-contacts.blade.php](/home/blink/hackuncp/admin-panel/resources/views/bot/human-contacts.blade.php): pedidos de orientación humana.
- [resources/views/bot/unknown-queries.blade.php](/home/blink/hackuncp/admin-panel/resources/views/bot/unknown-queries.blade.php): consultas sin respuesta.
- [resources/views/bot/settings.blade.php](/home/blink/hackuncp/admin-panel/resources/views/bot/settings.blade.php): mensajes base y alcance.

## Comandos útiles

```bash
php artisan migrate:fresh --seed
php artisan test
php artisan view:cache
```

## Datos semilla

El seeder carga contenido de ejemplo para el caso de comunidades y gobiernos locales que buscan orientación sobre proyección social. Sustituye esos datos por los reales antes de un uso institucional.
