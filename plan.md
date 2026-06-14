Claro. Aquí tienes un **plan completo y enfocado al desafío**, usando tu idea del **bot de WhatsApp con OpenWA para proyección social de la UNCP**.

# Plan de solución

## Bot de WhatsApp para orientar solicitudes de proyección social de la UNCP

## 1. Título de la solución

**Asistente virtual de proyección social UNCP por WhatsApp**

## 2. Problema identificado

Los representantes de comunidades campesinas, organizaciones urbanas y gobiernos locales de Huancayo muchas veces desconocen qué tipo de apoyo pueden solicitar a la Universidad Nacional del Centro del Perú, a qué oficina o facultad deben comunicarse y cuáles son los pasos para iniciar una solicitud de proyección social.

Actualmente, para recibir orientación, muchas personas deben acudir presencialmente a la universidad o buscar información dispersa, lo que genera pérdida de tiempo, desinformación y dificultad para acceder a los servicios de proyección social.

## 3. Objetivo general

Implementar un bot de WhatsApp que brinde orientación clara, rápida y verificable sobre los servicios de proyección social de la UNCP, permitiendo que comunidades campesinas, organizaciones urbanas y gobiernos locales de Huancayo conozcan qué apoyo pueden solicitar, con quién comunicarse y cómo iniciar una solicitud formal sin acudir presencialmente.

## 4. Objetivos específicos

* Informar sobre los tipos de apoyo que la UNCP puede brindar mediante proyección social.
* Orientar al usuario según el tipo de institución que representa: comunidad campesina, organización urbana o gobierno local.
* Indicar los requisitos básicos para iniciar una solicitud.
* Mostrar los contactos correspondientes de oficinas, facultades o responsables.
* Guiar al usuario paso a paso para preparar su solicitud.
* Proporcionar información basada en documentos oficiales y verificables.
* Registrar consultas frecuentes para mejorar la atención institucional.

## 5. Usuarios beneficiarios

Los principales usuarios beneficiarios serán:

* Representantes de comunidades campesinas de Huancayo.
* Dirigentes de organizaciones urbanas.
* Autoridades o trabajadores de gobiernos locales.
* Población interesada en solicitar apoyo de proyección social.
* Personal administrativo de la UNCP, porque reducirá consultas repetitivas.

## 6. Descripción de la solución

La solución consiste en desarrollar un bot de WhatsApp utilizando **OpenWA**, conectado a una base de datos en **PostgreSQL**, donde se almacenará información oficial sobre proyección social de la UNCP.

El usuario podrá escribir al número de WhatsApp del bot y recibir orientación automática. El bot hará preguntas simples para identificar la necesidad del usuario y luego brindará una respuesta adecuada.

Por ejemplo, el bot puede preguntar:

* ¿Qué tipo de institución representa?
* ¿Qué apoyo necesita?
* ¿En qué distrito o comunidad se encuentra?
* ¿Desea recibir requisitos, contactos o un modelo de solicitud?

Según las respuestas, el sistema podrá indicar qué servicio de proyección social corresponde, qué documentos se requieren y con qué oficina o facultad debe comunicarse.

## 7. Alcance del bot

El bot estará enfocado exclusivamente en temas de **proyección social de la UNCP**.

Podrá responder sobre:

* Tipos de apoyo que puede brindar la universidad.
* Charlas, capacitaciones y talleres.
* Asistencia técnica o asesoría básica.
* Campañas sociales, educativas, ambientales o de salud.
* Participación de estudiantes y docentes en actividades comunitarias.
* Requisitos para solicitar apoyo.
* Modelo básico de solicitud.
* Contactos institucionales.
* Pasos para iniciar el trámite.

No estará enfocado en matrícula, admisión, notas, pagos, certificados u otros trámites universitarios que no correspondan a proyección social.

## 8. Funcionamiento general

El funcionamiento del bot será el siguiente:

1. El usuario escribe al WhatsApp institucional.
2. El bot saluda y explica que atiende consultas sobre proyección social de la UNCP.
3. El bot muestra un menú de opciones.
4. El usuario selecciona el tipo de consulta.
5. El bot solicita datos básicos para orientar mejor.
6. El sistema consulta la base de datos.
7. El bot responde con información clara y verificable.
8. Si corresponde, entrega requisitos, contactos o modelo de solicitud.
9. El usuario puede guardar la información o solicitar ayuda adicional.
10. Las consultas quedan registradas para análisis posterior.

## 9. Menú inicial propuesto

**Bienvenido al asistente virtual de proyección social de la UNCP.**
Por favor, seleccione una opción:

1. ¿Qué apoyo puedo solicitar a la UNCP?
2. Requisitos para presentar una solicitud.
3. Contactos de oficinas o facultades.
4. Modelo de solicitud.
5. Consultar según mi tipo de institución.
6. Preguntas frecuentes.
7. Comunicarme con un responsable.

## 10. Flujo de atención propuesto

### Caso 1: Comunidad campesina

Usuario:
“Soy representante de una comunidad campesina.”

Bot:
“Gracias. ¿Qué tipo de apoyo necesita?”

Opciones:

1. Capacitación
2. Asesoría técnica
3. Campaña social
4. Apoyo ambiental
5. Apoyo educativo
6. Otro

Luego el bot responde con el tipo de apoyo posible, requisitos y contacto correspondiente.

### Caso 2: Gobierno local

Usuario:
“Soy de una municipalidad.”

Bot:
“Indique el tipo de orientación que necesita.”

Opciones:

1. Convenios
2. Capacitaciones
3. Asistencia técnica
4. Proyectos sociales
5. Contacto institucional

El bot entrega los pasos para iniciar la coordinación formal.

### Caso 3: Organización urbana

Usuario:
“Represento una junta vecinal.”

Bot:
“¿Qué necesidad desea atender?”

Opciones:

1. Seguridad ciudadana
2. Medio ambiente
3. Educación
4. Salud
5. Gestión comunitaria

El bot orienta sobre posibles actividades de proyección social y cómo solicitarlas.

## 11. Información que debe contener la base de datos

La base de datos en PostgreSQL deberá almacenar información como:

* Tipos de usuarios solicitantes.
* Tipos de apoyo disponibles.
* Facultades relacionadas.
* Oficinas responsables.
* Requisitos para solicitud.
* Correos y teléfonos de contacto.
* Modelos de documentos.
* Preguntas frecuentes.
* Documentos oficiales de respaldo.
* Historial de consultas realizadas.

## 12. Verificación de la información

Para evitar que el bot brinde información incorrecta, las respuestas deberán basarse en fuentes oficiales cargadas previamente en el sistema.

Las fuentes pueden ser:

* Reglamentos de proyección social de la UNCP.
* Directivas internas.
* Formatos oficiales de solicitud.
* Directorio institucional.
* Información validada por la oficina responsable.
* Documentos proporcionados por facultades o áreas de proyección social.

Cada respuesta importante del bot debe estar asociada a una fuente o registro oficial dentro de la base de datos.

## 13. Tecnologías propuestas

### Canal de atención

**WhatsApp**, porque es una herramienta conocida y accesible para la mayoría de representantes comunitarios.

### Librería de conexión

**OpenWA**, para conectar el bot con WhatsApp y gestionar los mensajes.

### Base de datos

**PostgreSQL**, para almacenar información oficial, usuarios, consultas y respuestas verificadas.

### Backend

Puede desarrollarse con:

* Node.js
* Express.js
* TypeScript

### Sistema de consulta

El bot puede funcionar con respuestas estructuradas mediante menús y, en una segunda etapa, incorporar inteligencia artificial para responder preguntas más naturales usando documentos oficiales como base.

## 14. Módulos del sistema

### Módulo 1: Atención por WhatsApp

Permite recibir mensajes, mostrar menús y responder consultas.

### Módulo 2: Gestión de información oficial

Permite registrar y actualizar los servicios, requisitos, contactos y documentos oficiales.

### Módulo 3: Orientación de solicitudes

Clasifica al usuario según su necesidad y le muestra el procedimiento adecuado.

### Módulo 4: Generación de modelo de solicitud

Entrega al usuario una estructura básica para redactar su solicitud.

### Módulo 5: Registro de consultas

Guarda las preguntas frecuentes, tipo de usuario, fecha y tema consultado.

### Módulo 6: Reportes

Permite analizar qué necesidades son más consultadas por comunidades, organizaciones y gobiernos locales.

## 15. Fases de implementación

### Fase 1: Análisis de información

* Revisar documentos oficiales de proyección social.
* Identificar servicios que ofrece la UNCP.
* Identificar oficinas responsables.
* Definir requisitos y contactos.
* Clasificar preguntas frecuentes.

### Fase 2: Diseño del flujo conversacional

* Crear el menú principal.
* Diseñar preguntas y respuestas.
* Definir rutas para cada tipo de usuario.
* Validar que el lenguaje sea simple y comprensible.

### Fase 3: Diseño de base de datos

* Crear tablas para usuarios, servicios, requisitos, contactos, documentos y consultas.
* Relacionar cada respuesta con una fuente oficial.
* Preparar datos iniciales.

### Fase 4: Desarrollo del bot

* Configurar OpenWA.
* Crear el backend.
* Conectar el bot con PostgreSQL.
* Programar menús y respuestas.
* Registrar consultas.

### Fase 5: Pruebas

* Probar con casos de comunidades campesinas.
* Probar con gobiernos locales.
* Probar con organizaciones urbanas.
* Corregir respuestas confusas.
* Validar la información con responsables de proyección social.

### Fase 6: Implementación piloto

* Usar un número de WhatsApp institucional o de prueba.
* Difundir el número en un grupo reducido.
* Recoger comentarios de usuarios.
* Mejorar el flujo de atención.

### Fase 7: Mejora continua

* Actualizar información oficial.
* Agregar nuevas preguntas frecuentes.
* Mejorar reportes.
* Incorporar respuestas más inteligentes si se requiere.

## 16. Ejemplo de respuesta del bot

Usuario:
“Quiero pedir una capacitación para mi comunidad.”

Bot:
“Sí, puede solicitar apoyo de proyección social para capacitaciones comunitarias. Para iniciar, debe preparar una solicitud dirigida a la oficina correspondiente de la UNCP, indicando el nombre de la comunidad, el tema solicitado, la cantidad aproximada de beneficiarios y los datos de contacto del representante.

Los requisitos básicos son:

1. Solicitud formal.
2. Datos del representante.
3. Descripción de la necesidad.
4. Lugar donde se realizará la actividad.
5. Fecha tentativa.

¿Desea que le muestre un modelo de solicitud?”

## 17. Resultados esperados

Con esta solución se espera:

* Mejorar el acceso a información sobre proyección social.
* Reducir la necesidad de acudir presencialmente solo para consultar.
* Orientar correctamente a comunidades y gobiernos locales.
* Mejorar la comunicación entre la UNCP y la sociedad.
* Evitar respuestas informales o desactualizadas.
* Generar datos útiles sobre las necesidades más frecuentes de la población.

## 18. Impacto social

El bot facilitará que más comunidades y organizaciones conozcan los servicios de proyección social de la UNCP. Esto permitirá fortalecer el vínculo entre universidad y sociedad, promoviendo que el conocimiento académico llegue de manera más directa a la población.

Además, ayudará a reducir barreras de acceso a la información, especialmente para representantes que viven lejos o que no pueden acudir fácilmente a la universidad.

## 19. Riesgos y medidas de control

### Riesgo 1: Información desactualizada

Medida: actualizar periódicamente la base de datos con información validada por la oficina responsable.

### Riesgo 2: El bot no entiende una consulta

Medida: usar menús claros y permitir derivar al usuario a un responsable humano.

### Riesgo 3: Respuestas inventadas

Medida: limitar las respuestas a información registrada en la base de datos oficial.

### Riesgo 4: Uso de un número no institucional

Medida: iniciar con un número de prueba y luego migrar a un número institucional validado.

## 20. Conclusión

La propuesta de un bot de WhatsApp con OpenWA es una solución viable, accesible y alineada al desafío “Construyendo soluciones junto a las comunidades”, porque permite orientar a representantes de comunidades campesinas, organizaciones urbanas y gobiernos locales sobre los servicios de proyección social de la UNCP.

Esta herramienta no reemplaza el trámite formal, sino que facilita la orientación inicial, indicando qué apoyo se puede solicitar, qué requisitos se necesitan y con quién comunicarse. De esta manera, se mejora la atención, se reduce la desinformación y se fortalece la relación entre la universidad y la comunidad.
