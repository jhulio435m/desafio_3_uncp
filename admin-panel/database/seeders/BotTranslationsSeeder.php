<?php

namespace Database\Seeders;

use App\Models\BotTranslationKey;
use App\Models\BotTranslation;
use Illuminate\Database\Seeder;

class BotTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // WELCOME / SCOPE / GENERAL
            'welcome' => [
                'group' => 'welcome_scope',
                'label' => 'Mensaje de Bienvenida',
                'description' => 'Mensaje inicial del orientador virtual.',
                'translations' => [
                    'es' => 'Hola, soy el orientador virtual para solicitudes de proyección social. Puedo ayudarte a entender qué apoyo podrías solicitar, qué datos preparar, qué canal oficial usar y cuándo pedir contacto con una persona.',
                    'qu' => 'Allinllachu, Proyección Social UNCP yanapakuqmi kani. Ima yanapayta mañakuyta atinki, ima willakuyta wakichinki, may canal oficialta llamkanki, chaykunapi yanapasayki.',
                    'ash' => 'Kitaiteri, Abiro. Nopoki YanapayBot, obamentari UNCP (Proyección Social). Amitakotantsi: puedo orientar de forma simple sobre apoyo para nampitsi, datos necesarios, canales oficiales y contacto humano.'
                ]
            ],
            'fallback' => [
                'group' => 'welcome_scope',
                'label' => 'Respuesta Fallback (No se entiende)',
                'description' => 'Mensaje enviado cuando el bot no entiende la solicitud.',
                'translations' => [
                    'es' => 'No tengo una respuesta segura para esa consulta. Puedo registrar tu pedido para que una persona te oriente sobre el proceso de proyección social.',
                    'qu' => 'Manam kay tapukuypaq allin kutichiyta tarinichu. Runa yanapakuqwan rimayta qillqayta atini.',
                    'ash' => 'Pasonki por su mensaje. No tengo una respuesta segura para esa consulta. Puedo registrar tu pedido para que una persona te oriente.'
                ]
            ],
            'scope' => [
                'group' => 'welcome_scope',
                'label' => 'Alcance / Límite del Canal',
                'description' => 'Mensaje que aclara el alcance del orientador y que no reemplaza ADESA.',
                'translations' => [
                    'es' => 'Este canal orienta sobre el proceso de proyección social. No reemplaza ADESA, mesa de partes ni los procedimientos oficiales de aprobación y ejecución.',
                    'qu' => 'Kay canalqa proyección socialmanta orientacionllapaqmi. Manam ADESA, mesa de partes nitaq trámite oficialkunata rantinchanchu.',
                    'ash' => 'Aviso: este canal solo orienta sobre proyección social UNCP. No reemplaza ADESA, mesa de partes ni trámites oficiales.'
                ]
            ],
            'officeHours' => [
                'group' => 'welcome_scope',
                'label' => 'Horario de Atención',
                'description' => 'Horarios administrativos del bot/oficina.',
                'translations' => [
                    'es' => 'Lunes a Viernes de 8:00 AM a 1:00 PM y de 2:00 PM a 5:00 PM.',
                    'qu' => 'Lunesmanta vierneskama 8:00 AM - 1:00 PM hinaspa 2:00 PM - 5:00 PM.',
                    'ash' => 'Atención: lunes a viernes, 8:00 AM - 1:00 PM y 2:00 PM - 5:00 PM.'
                ]
            ],
            'offTopicMessage' => [
                'group' => 'welcome_scope',
                'label' => 'Mensaje Fuera de Tema (Off-topic)',
                'description' => 'Mensaje cuando el usuario habla de temas no relacionados.',
                'translations' => [
                    'es' => 'Este canal está dedicado exclusivamente a la orientación sobre proyección social de la UNCP.\n\n_Describa la necesidad de su comunidad o escriba *menu* para ver las opciones disponibles._',
                    'qu' => 'Kay canalqa UNCP proyección socialmanta orientacionllapaqmi.\n\n_Comunidadniykipa necesidadninta qillqay utaq akllanakunata qawanaykipaq *menu* qillqay._',
                    'ash' => 'Este canal orienta solo sobre proyección social UNCP.\n\n_Describa la necesidad de su nampitsi o escriba *menu*._'
                ]
            ],
            'informalMessage' => [
                'group' => 'welcome_scope',
                'label' => 'Mensaje para Mensajes Informales',
                'description' => 'Respuesta a saludos cortos, bromas o expresiones informales.',
                'translations' => [
                    'es' => '_Cuando guste, describa la necesidad de su comunidad o escriba *menu* para ver las opciones disponibles._',
                    'qu' => 'Manam proyección socialmanta mañakuyta tarinichu.\n\nAkllanakunata qawanaykipaq *menu* qillqay utaq comunidadniykipa necesidadninta pisillapi willaway.',
                    'ash' => 'No logré identificar una solicitud de proyección social.\n\nEscriba *menu* o describa la necesidad de su nampitsi.'
                ]
            ],
            'error' => [
                'group' => 'welcome_scope',
                'label' => 'Aviso de Error del Sistema',
                'description' => 'Mensaje enviado cuando falla alguna consulta.',
                'translations' => [
                    'es' => '*Aviso del Sistema*\n\nOcurrió un error al consultar la información del proceso. Intente nuevamente en unos minutos.',
                    'qu' => '*Aviso del Sistema*\n\nPantaymi karqan. Aswan qhipaman kaqmanta qillqay.',
                    'ash' => '*Aviso del Sistema*\n\nNo se pudo consultar la información. Intente nuevamente en unos minutos.'
                ]
            ],
            'aiFooter' => [
                'group' => 'welcome_scope',
                'label' => 'Pie de página para respuestas de IA',
                'description' => 'Mensaje de descargo de responsabilidad adjunto al final de las respuestas generadas con Inteligencia Artificial.',
                'translations' => [
                    'es' => 'Esta orientación es referencial y no reemplaza la evaluación oficial de la UNCP.',
                    'qu' => 'Kay orientacionqa referencialllami, manam UNCP oficial evaluaciontachu rantinchan.',
                    'ash' => 'Esta orientación es referencial y no reemplaza la evaluación oficial de la UNCP.'
                ]
            ],

            // MENUS & NAVIGATION
            'menu' => [
                'group' => 'menus',
                'label' => 'Menú Principal',
                'description' => 'Opciones principales del bot orientador.',
                'translations' => [
                    'es' => "*Menú Principal*\n\n1. Orientar mi necesidad\n2. *Registrar solicitud*\n3. Información útil\n4. *Seguimiento de ticket*\n5. Hablar con una persona\n\n> _*menu* para volver. *idioma* para cambiar idioma._",
                    'qu' => "*Akllana / Menú*\n\n1. Necesidadniyta riqsichiy\n2. *Mañakuyta qillqay*\n3. Yanapakuq willakuy\n4. *Ticketniyta qatipay*\n5. Runa yanapakuqwan rimay\n\n> _*menu* qillqay kutinaykipaq. *idioma* simita tikranaykipaq._",
                    'ash' => "*Avotsi (Menú de orientación)*\n\n1. Orientar mi necesidad\n2. *Registrar solicitud*\n3. Información útil\n4. *Seguimiento de ticket*\n5. Hablar con una persona\n\n> _Escriba *idioma* para cambiar de idioma._"
                ]
            ],
            'infoMenu' => [
                'group' => 'menus',
                'label' => 'Menú de Información Útil',
                'description' => 'Submenú con enlaces, contactos, alcances y costos.',
                'translations' => [
                    'es' => "*Información útil*\n\n1. Qué apoyo puedo solicitar\n2. Horarios y costo\n3. Enlaces oficiales\n4. Contactos del proceso\n5. Alcance del canal\n6. Volver al menú principal\n\n> _Escriba un número del 1 al 6._",
                    'qu' => "*Yanapakuq willakuy*\n\n1. Ima yanapaytam mañakuyman\n2. Horariokuna hinaspa costo\n3. Canal oficialkuna\n4. Responsablekunawan rimay\n5. Canalpa alcance\n6. Menú principalman kutiy\n\n> _1 manta 6 kama huk yupayta qillqay._",
                    'ash' => "*Información útil*\n\n1. Qué apoyo puedo solicitar\n2. Horarios y costo\n3. Enlaces oficiales\n4. Contactos del proceso\n5. Alcance del canal\n6. Volver al menú principal\n\n> _Escriba un número del 1 al 6._"
                ]
            ],
            'processServices' => [
                'group' => 'menus',
                'label' => 'Tipos de Apoyo Disponibles',
                'description' => 'Texto sobre los tipos de apoyo ofrecidos en proyección social.',
                'translations' => [
                    'es' => "*Tipos de apoyo disponibles*\n\n- Capacitación y talleres\n- Asesoría técnica\n- Campañas sociales\n- Apoyo productivo\n\n_Escriba *2* para orientar su necesidad específica._",
                    'qu' => "*Yanapay laya kuna*\n\n- Yachachikuy tallerkuna\n- Asesoría técnica\n- Campaña socialkuna\n- Productivo yanapay\n\n_Necesidadniykita orientanaykipaq *2* qillqay._",
                    'ash' => "*Amitakotantsi (Apoyos disponibles)*\n\n- Capacitación\n- Asesoría técnica\n- Campañas sociales\n- Apoyo productivo\n\n_Escriba *2* para orientar su necesidad._"
                ]
            ],
            'officialChannelsTitle' => [
                'group' => 'menus',
                'label' => 'Título de Canales Oficiales',
                'description' => 'Título listado en canales oficiales.',
                'translations' => [
                    'es' => '*Canales oficiales*',
                    'qu' => '*Canal oficialkuna*',
                    'ash' => '*Canales oficiales*'
                ]
            ],
            'contactsTitle' => [
                'group' => 'menus',
                'label' => 'Título de Contactos',
                'description' => 'Título listado en contactos.',
                'translations' => [
                    'es' => '*Contactos de orientación*',
                    'qu' => '*Orientacionpaq contactokuna*',
                    'ash' => '*Contactos de orientación*'
                ]
            ],
            'backToMenu' => [
                'group' => 'menus',
                'label' => 'Pista para Volver',
                'description' => 'Instrucción para escribir "menu".',
                'translations' => [
                    'es' => '_Escriba *menu* para volver._',
                    'qu' => '_Kutiyta munaspa *menu* qillqay._',
                    'ash' => '_Escriba *menu* para volver._'
                ]
            ],
            'humanContactHint' => [
                'group' => 'menus',
                'label' => 'Pista para Contacto Humano',
                'description' => 'Instrucción corta sobre cómo solicitar contacto de un orientador.',
                'translations' => [
                    'es' => '_Si desea que una persona le contacte, escriba *menu* y luego *5*._',
                    'qu' => '_Runa yanapakuqwan rimayta munaspa, *menu* hinaspa *5* qillqay._',
                    'ash' => '_Para que una persona le contacte, escriba *menu* y luego *5*._'
                ]
            ],
            'noInformation' => [
                'group' => 'menus',
                'label' => 'Respuesta General sin Coincidencia',
                'description' => 'Mensaje alternativo cuando no se encuentra FAQ específica.',
                'translations' => [
                    'es' => "Puedo orientarlo con una respuesta general. Puedo ayudarte de otra forma:\n\n• *1* — Orientar mi necesidad\n• *2* — Registrar solicitud\n• *5* — Hablar con una persona\n\n_O escriba *menu* para ver todas las opciones._",
                    'qu' => "Huk kutichiyta quyta atisayki. Kaykunapi yanapasayki:\n\n• *1* — Necesidadniyta riqsichiy\n• *2* — Mañakuyta qillqay\n• *5* — Runa yanapakuqwan rimay\n\n_Utaq *menu* qillqay akllanakunata qawanaykipaq._",
                    'ash' => "Amitakotantsi (puedo ayudarle). Puedo ayudarte de otra forma:\n\n• *1* — Orientar mi necesidad\n• *2* — Registrar solicitud\n• *5* — Hablar con una persona\n\n_O escriba *menu* para ver todas las opciones._"
                ]
            ],
            'noSpecificOrientation' => [
                'group' => 'menus',
                'label' => 'Mensaje Sin Orientación Específica',
                'description' => 'Texto enviado cuando el bot no halla sugerencia de área.',
                'translations' => [
                    'es' => 'No encontré orientación específica para eso.\n\n_Intente con más detalle, por ejemplo: "Queremos vender queso" o "Nuestro ganado necesita ayuda"._\n\nO escriba *5* para hablar directamente con una persona.',
                    'qu' => 'Manam chaypaq orientacion sutita tarinichu.\n\n_Aswan sutita qillqay, kayhina: "Quesota rantikuyta munayku" utaq "Ganadunchik yanapayta munan"._\n\nRuna yanapakuqwan rimayta munaspa *5* qillqay.',
                    'ash' => 'Pasonki. No encontré orientación específica para eso.\n\n_Escriba más detalle o marque *5* para hablar con una persona._'
                ]
            ],

            // VALIDATIONS
            'invalidName' => [
                'group' => 'validations',
                'label' => 'Validación: Nombre Inválido',
                'description' => 'Mensaje cuando el nombre ingresado no es válido.',
                'translations' => [
                    'es' => 'Indique un nombre válido o escriba "sin nombre".',
                    'qu' => 'Allin sutita qillqay utaq "sin nombre" qillqay.',
                    'ash' => 'Indique un nombre válido o escriba "sin nombre".'
                ]
            ],
            'invalidPhone' => [
                'group' => 'validations',
                'label' => 'Validación: Teléfono Inválido',
                'description' => 'Mensaje cuando el teléfono ingresado es incorrecto.',
                'translations' => [
                    'es' => 'Indique un teléfono o WhatsApp válido.',
                    'qu' => 'Allin telefono utaq WhatsApp yupayta qillqay.',
                    'ash' => 'Indique un teléfono o WhatsApp válido.'
                ]
            ],
            'invalidTopic' => [
                'group' => 'validations',
                'label' => 'Validación: Tema Inválido',
                'description' => 'Mensaje cuando el tema ingresado es demasiado corto o inválido.',
                'translations' => [
                    'es' => 'Indique un tema entendible de proyección social.',
                    'qu' => 'Proyección socialmanta allin temata qillqay.',
                    'ash' => 'Indique un tema entendible de proyección social.'
                ]
            ],
            'invalidMessage' => [
                'group' => 'validations',
                'label' => 'Validación: Mensaje/Necesidad Inválida',
                'description' => 'Mensaje de error si la descripción es muy corta.',
                'translations' => [
                    'es' => 'Describa un poco más la orientación que necesita.',
                    'qu' => 'Ima orientacionta munanki, aswan sutita qillqay.',
                    'ash' => 'Describa un poco más la orientación que necesita.'
                ]
            ],
            'needMoreDetail' => [
                'group' => 'validations',
                'label' => 'Validación: Más Detalle Requerido',
                'description' => 'Pedir más detalles de la necesidad del usuario.',
                'translations' => [
                    'es' => 'Por favor describa la necesidad con más detalle.\n\n_Ej: "Queremos mejorar la crianza de cuyes" o "Necesitamos apoyo para el agua"_',
                    'qu' => 'Necesidadniykita aswan sutita willaway.\n\n_Ej: "Cuy uywayta allinchayta munayku" o "Yakumanta yanapayta munayku"_',
                    'ash' => 'Describa la necesidad con más detalle.\n\n_Ej: "Necesitamos apoyo para producción" o "Necesitamos capacitación"_'
                ]
            ],
            'needPrompt' => [
                'group' => 'validations',
                'label' => 'Solicitud de frase descriptiva',
                'description' => 'Mensaje inicial para pedir la necesidad en una sola frase.',
                'translations' => [
                    'es' => "Describa en una frase la necesidad de su comunidad.\n\n_Ej: \"Queremos mejorar la crianza de cuyes\" o \"Necesitamos apoyo para el agua\"_",
                    'qu' => "Comunidadniykipa necesidadninta pisillapi willaway.\n\n_Ej: \"Cuy uywayta allinchayta munayku\" o \"Yakumanta yanapayta munayku\"_",
                    'ash' => "Describa en una frase la necesidad de su nampitsi.\n\n_Ej: \"Necesitamos apoyo para producción\" o \"Necesitamos capacitación\"_"
                ]
            ],

            // WIZARD HUMANO
            'humanName' => [
                'group' => 'wizard_humano',
                'label' => 'Paso 1: Nombre del Representante (Humano)',
                'description' => 'Wizard de contacto humano - solicitud de nombre.',
                'translations' => [
                    'es' => 'Indique el *nombre del representante* o escriba "sin nombre".\n\n_Escriba *cancelar* para salir._',
                    'qu' => 'Representantepa sutinta qillqay utaq "sin nombre" qillqay.',
                    'ash' => 'Indique el *nombre del representante* o escriba "sin nombre".'
                ]
            ],
            'humanPhone' => [
                'group' => 'wizard_humano',
                'label' => 'Paso 2: Teléfono (Humano)',
                'description' => 'Wizard de contacto humano - solicitud de teléfono.',
                'translations' => [
                    'es' => 'Indique el *teléfono o WhatsApp* de contacto.\n\n_Escriba *cancelar* para salir._',
                    'qu' => 'Telefono utaq WhatsApp yupayta qillqay.',
                    'ash' => 'Indique el *teléfono o WhatsApp* de contacto.'
                ]
            ],
            'humanTopic' => [
                'group' => 'wizard_humano',
                'label' => 'Paso 3: Tema/Necesidad (Humano)',
                'description' => 'Wizard de contacto humano - solicitud de tema.',
                'translations' => [
                    'es' => 'Indique el *tema o necesidad* de proyección social.\n\n_Escriba *cancelar* para salir._',
                    'qu' => 'Proyección socialmanta ima necesidadtaq kachkan, chayta qillqay.',
                    'ash' => 'Indique el *tema o necesidad* de proyección social.'
                ]
            ],
            'humanMessage' => [
                'group' => 'wizard_humano',
                'label' => 'Paso 4: Mensaje Adicional (Humano)',
                'description' => 'Wizard de contacto humano - solicitud de mensaje descriptivo.',
                'translations' => [
                    'es' => 'Describa brevemente qué orientación necesita.\n\n_Escriba *cancelar* para salir._',
                    'qu' => 'Ima orientaciónta munanki, pisillapi qillqay.',
                    'ash' => 'Describa brevemente qué orientación necesita.'
                ]
            ],
            'humanSaved' => [
                'group' => 'wizard_humano',
                'label' => 'Éxito: Registro Humano Exitoso',
                'description' => 'Mensaje final de éxito tras registrar solicitud de contacto.',
                'translations' => [
                    'es' => '*Registro Exitoso*\n\nSu pedido de orientación humana fue registrado como *Pendiente*. Un orientador se pondrá en contacto pronto.',
                    'qu' => '*Registro Exitoso*\n\nRuna yanapakuqwan rimay mañakuyniyki *Pendiente* hina qillqasqa.',
                    'ash' => '*Kameetsa - Registro Exitoso*\n\nSu pedido de orientación humana fue registrado como *Pendiente*. Pasonki.'
                ]
            ],

            // WIZARD REGISTRO
            'reqConsent' => [
                'group' => 'wizard_registro',
                'label' => 'Paso 0: Consentimiento de Privacidad',
                'description' => 'Solicitud de aceptación de tratamiento de datos.',
                'translations' => [
                    'es' => "*Consentimiento de Privacidad*\n\nPara registrar su solicitud, se solicitarán datos básicos del representante y la comunidad. Esta información se usará únicamente para orientar, registrar y hacer seguimiento.\n\nEscriba *ACEPTAR* para continuar o *CANCELAR* para salir.",
                    'qu' => "*Datosmanta Consentimiento*\n\nMañakuyta qillqanaykipaq, representantepa hinaspa comunidadpa datonkunata mañakusaykiku. Kay willakuykunataqa orientanallapaqmi hinaspa seguimiento ruranallapaqmi llamkasaiku.\n\nKutinaykipaq *ACEPTAR* qillqay utaq lluqsinaykipaq *CANCELAR* qillqay.",
                    'ash' => "*Consentimiento de Privacidad*\n\nPara registrar su solicitud, se solicitarán datos básicos. Esta información se usará únicamente para orientar y registrar su pedido.\n\nEscriba *ACEPTAR* para continuar o *CANCELAR* para salir."
                ]
            ],
            'reqRepName' => [
                'group' => 'wizard_registro',
                'label' => 'Paso 1: Nombre del Representante (Registro)',
                'description' => 'Wizard de registro - solicitud de nombre.',
                'translations' => [
                    'es' => 'Indique su *nombre completo* (Representante).\n\n_Escriba *cancelar* para salir._',
                    'qu' => 'Sutiykita qillqay (Representante).',
                    'ash' => 'Indique su *nombre completo* (Representante).'
                ]
            ],
            'reqRepDni' => [
                'group' => 'wizard_registro',
                'label' => 'Paso 2: DNI del Representante (Registro)',
                'description' => 'Wizard de registro - solicitud de DNI.',
                'translations' => [
                    'es' => 'Indique su número de *DNI*.\n\n_Escriba *cancelar* para salir._',
                    'qu' => 'DNI yupaynikita qillqay.',
                    'ash' => 'Indique su número de *DNI*.'
                ]
            ],
            'reqInstName' => [
                'group' => 'wizard_registro',
                'label' => 'Paso 3: Nombre de la Institución/Comunidad',
                'description' => 'Wizard de registro - solicitud de nombre de comunidad.',
                'translations' => [
                    'es' => 'Indique el *nombre de su Comunidad o Institución*.\n\n_Escriba *cancelar* para salir._',
                    'qu' => 'Comunidadniykipa utaq Instituciónniykipa sutinta qillqay.',
                    'ash' => 'Indique el *nombre de su Nampitsi o Institución*.'
                ]
            ],
            'reqInstType' => [
                'group' => 'wizard_registro',
                'label' => 'Paso 4: Tipo de Institución',
                'description' => 'Wizard de registro - solicitud de tipo.',
                'translations' => [
                    'es' => 'Indique el tipo: _Comunidad Campesina_, _Gobierno Local_ u _Organización Urbana_.\n\n_Escriba *cancelar* para salir._',
                    'qu' => 'Ima laya: _Comunidad Campesina_, _Gobierno Local_ utaq _Organización Urbana_.',
                    'ash' => 'Indique el tipo: _Comunidad Campesina_, _Gobierno Local_ u _Organización Urbana_.'
                ]
            ],
            'reqLocation' => [
                'group' => 'wizard_registro',
                'label' => 'Paso 5: Ubicación Distrito / Anexo',
                'description' => 'Wizard de registro - solicitud de distrito.',
                'translations' => [
                    'es' => 'Indique la ubicación (_Distrito / Centro Poblado_).\n\n_Escriba *cancelar* para salir._',
                    'qu' => 'Maypiraq kachkanki (_Distrito / Centro Poblado_).',
                    'ash' => 'Indique la ubicación (_Distrito / Centro Poblado_).'
                ]
            ],
            'reqDesc' => [
                'group' => 'wizard_registro',
                'label' => 'Paso 6: Descripción del Problema',
                'description' => 'Wizard de registro - solicitud de descripción.',
                'translations' => [
                    'es' => 'Describa brevemente la necesidad o problema que busca resolver.\n\n_Escriba *cancelar* para salir._',
                    'qu' => 'Imapaqtaq yanapayta munanki, pisillapi qillqay.',
                    'ash' => 'Describa brevemente la necesidad o problema que busca resolver.'
                ]
            ],
            'reqSaved' => [
                'group' => 'wizard_registro',
                'label' => 'Éxito: Registro de Solicitud Exitoso',
                'description' => 'Mensaje final de éxito con el ticket generado.',
                'translations' => [
                    'es' => "*Registro Exitoso*\n\nSu código de seguimiento es:\n\n``` {ticket} ```\n\n_Úselo para consultar el estado en la opción *4*._",
                    'qu' => "*Registro Exitoso*\n\nTicketniyki:\n\n``` {ticket} ```\n\n_Option *4* kaqpi qatipay._",
                    'ash' => "*Kameetsa - Registro Exitoso*\n\nSu código de seguimiento es:\n\n``` {ticket} ```\n\n_Úselo para consultar el estado en la opción *4*. Pasonki._"
                ]
            ],

            // TRACKING & CONFIRMATION
            'trackPrompt' => [
                'group' => 'tracking',
                'label' => 'Solicitud de Código de Ticket',
                'description' => 'Mensaje para pedir el ticket al usuario.',
                'translations' => [
                    'es' => 'Ingrese su *código de seguimiento* (ej. ```ABCDEF```).',
                    'qu' => 'Ticketniykita qillqay (ej. ```ABCDEF```).',
                    'ash' => 'Ingrese su *código de seguimiento* (ej. ```ABCDEF```).'
                ]
            ],
            'trackNotFound' => [
                'group' => 'tracking',
                'label' => 'Aviso: Ticket No Encontrado',
                'description' => 'Mensaje enviado si el ticket no existe.',
                'translations' => [
                    'es' => '*Aviso del Sistema*\n\nCódigo no encontrado. Verifique e intente nuevamente.',
                    'qu' => '*Aviso del Sistema*\n\nTicket mana tarisqachu. Kaqmanta qillqay.',
                    'ash' => '*Aviso del Sistema*\n\nCódigo no encontrado. Verifique e intente nuevamente.'
                ]
            ],
            'confirmPrompt' => [
                'group' => 'tracking',
                'label' => 'Pregunta de Confirmación de Datos',
                'description' => 'Pregunta si los datos del resumen son correctos (SÍ / NO).',
                'translations' => [
                    'es' => '¿Los datos son correctos? (Responda *SÍ* para enviar o *NO* para cancelar).',
                    'qu' => '¿Allinchu kachkan? (*SÍ* qillqay apachinaykipaq utaq *NO* qillqay cancelanaykipaq).',
                    'ash' => '¿Los datos son correctos? (SÍ / NO).'
                ]
            ],
            'cancelHint' => [
                'group' => 'tracking',
                'label' => 'Pista de Cancelación de Proceso',
                'description' => 'Mensaje tras cancelar el flujo.',
                'translations' => [
                    'es' => '_Proceso cancelado. Escriba *menu* para volver._',
                    'qu' => '_Cancelasqa. Kutinaykipaq *menu* qillqay._',
                    'ash' => '_Cancelado. Escriba *menu*._'
                ]
            ],
            'confirmSummary' => [
                'group' => 'tracking',
                'label' => 'Título del Resumen de Confirmación',
                'description' => 'Título del resumen mostrado antes de enviar.',
                'translations' => [
                    'es' => '*Resumen de su solicitud*',
                    'qu' => '*Mañakuyniykipa resumen*',
                    'ash' => '*Resumen de su solicitud*'
                ]
            ],
            'trackingStatusTitle' => [
                'group' => 'tracking',
                'label' => 'Título de Estado de Ticket',
                'description' => 'Título de cabecera del estado consultado.',
                'translations' => [
                    'es' => '*Estado de tu solicitud*',
                    'qu' => '*Mañakuyniykipa estado*',
                    'ash' => '*Estado de su solicitud*'
                ]
            ],
            'trackingInstitution' => [
                'group' => 'tracking',
                'label' => 'Etiqueta: Institución',
                'description' => 'Etiqueta para campo de institución.',
                'translations' => [
                    'es' => 'Institución',
                    'qu' => 'Institución',
                    'ash' => 'Institución (Nampitsi)'
                ]
            ],
            'trackingStatus' => [
                'group' => 'tracking',
                'label' => 'Etiqueta: Estado',
                'description' => 'Etiqueta para campo de estado.',
                'translations' => [
                    'es' => 'Estado',
                    'qu' => 'Estado',
                    'ash' => 'Estado'
                ]
            ],
            'trackingDate' => [
                'group' => 'tracking',
                'label' => 'Etiqueta: Fecha',
                'description' => 'Etiqueta para campo de fecha.',
                'translations' => [
                    'es' => 'Fecha',
                    'qu' => 'Pacha',
                    'ash' => 'Fecha'
                ]
            ]
        ];

        foreach ($data as $key => $info) {
            $keyModel = BotTranslationKey::updateOrCreate(
                ['key' => $key],
                [
                    'group' => $info['group'],
                    'label' => $info['label'],
                    'description' => $info['description'],
                ]
            );

            foreach ($info['translations'] as $lang => $value) {
                BotTranslation::updateOrCreate(
                    [
                        'bot_translation_key_id' => $keyModel->id,
                        'lang' => $lang,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }
        }
    }
}
