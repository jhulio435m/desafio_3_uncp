import { BotTexts, LanguageCode } from './types';

export const LANGUAGE_PROMPT = `*Orientador UNCP - Proyección Social*

Seleccione un idioma:

1. Español
2. Quechua
3. Asháninka

_Escriba 1, 2 o 3._`;

export const BOT_COPY: Record<LanguageCode, BotTexts> = {
  es: {
    welcome: 'Hola, soy el orientador virtual para solicitudes de proyección social. Puedo ayudarte a entender qué apoyo podrías solicitar, qué datos preparar, qué canal oficial usar y cuándo pedir contacto con una persona.',
    fallback: 'No tengo una respuesta segura para esa consulta. Puedo registrar tu pedido para que una persona te oriente sobre el proceso de proyección social.',
    scope: 'Este canal orienta sobre el proceso de proyección social. No reemplaza ADESA, mesa de partes ni los procedimientos oficiales de aprobación y ejecución.',
    officeHours: 'Lunes a Viernes de 8:00 AM a 1:00 PM y de 2:00 PM a 5:00 PM.',
    processServices: '*Tipos de apoyo disponibles*\n\n- Capacitación y talleres\n- Asesoría técnica\n- Campañas sociales\n- Apoyo productivo\n\n_Escriba *6* para volver al menú de información o *menu* para el principal._',
    officialChannelsTitle: '*Canales oficiales*',
    contactsTitle: '*Contactos de orientación*',
    backToMenu: '_Escriba *menu* para volver._',
    humanContactHint: '_Si desea que una persona le contacte, escriba *menu* y luego *5*._',
    invalidName: 'Indique un nombre válido o escriba "sin nombre".',
    invalidPhone: 'Indique un teléfono o WhatsApp válido.',
    invalidTopic: 'Indique un tema entendible de proyección social.',
    invalidMessage: 'Describa un poco más la orientación que necesita.',
    needMoreDetail: 'Por favor describa la necesidad con más detalle.\n\n_Ej: "Queremos mejorar la crianza de cuyes" o "Necesitamos apoyo para el agua"_',
    noSpecificOrientation: 'No encontré orientación específica para eso.\n\n_Intente con más detalle, por ejemplo: "Queremos vender queso" o "Nuestro ganado necesita ayuda"._\n\nO escriba *5* para hablar directamente con una persona.',
    noInformation: `Puedo orientarlo con una respuesta general. Puedo ayudarte de otra forma:

• *1* — Orientar mi necesidad
• *2* — Registrar solicitud
• *5* — Hablar con una persona

_O escriba *menu* para ver todas las opciones._`,
    offTopicMessage: 'Este canal está dedicado exclusivamente a la orientación sobre proyección social de la UNCP.\n\n_Describa la necesidad de su comunidad o escriba *menu* para ver las opciones disponibles._',
    informalMessage: 'No logré identificar una solicitud relacionada con proyección social.\n\nEscriba *menu* para ver opciones o describa brevemente la necesidad de su comunidad.',
    trackingStatusTitle: '*Estado de tu solicitud*',
    trackingInstitution: 'Institución',
    trackingStatus: 'Estado',
    trackingDate: 'Fecha',
    menu: `*Menú Principal*

1. Orientar mi necesidad
2. *Registrar solicitud*
3. Información útil
4. *Seguimiento de ticket*
5. Hablar con una persona

> _*menu* para volver. *idioma* para cambiar idioma._`,
    infoMenu: `*Información útil*

1. Qué apoyo puedo solicitar
2. Horarios y costo
3. Enlaces oficiales
4. Contactos del proceso
5. Alcance del canal
6. Volver al menú principal

> _Escriba un número del 1 al 6._`,
    needPrompt: `Describa en una frase la necesidad de su comunidad.

_Ej: "Queremos mejorar la crianza de cuyes" o "Necesitamos apoyo para el agua"_`,
    humanName: 'Indique el *nombre del representante* o escriba "sin nombre".',
    humanPhone: 'Indique el *teléfono o WhatsApp* de contacto.',
    humanTopic: 'Indique el *tema o necesidad* de proyección social.',
    humanMessage: 'Describa brevemente qué orientación necesita.',
    humanSaved: '*Registro Exitoso*\n\nSu pedido de orientación humana fue registrado como *Pendiente*. Un orientador se pondrá en contacto pronto.',
    error: '*Aviso del Sistema*\n\nOcurrió un error al consultar la información del proceso. Intente nuevamente en unos minutos.',
    reqRepName: 'Indique su *nombre completo* (Representante).',
    reqRepDni: 'Indique su número de *DNI*.',
    reqInstName: 'Indique el *nombre de su Comunidad o Institución*.',
    reqInstType: 'Indique el tipo: _Comunidad Campesina_, _Gobierno Local_ u _Organización Urbana_.',
    reqLocation: 'Indique la ubicación (_Distrito / Centro Poblado_).',
    reqDesc: 'Describa brevemente la necesidad o problema que busca resolver.',
    reqSaved: `*Registro Exitoso*\n\nSu código de seguimiento es:\n\n\`\`\`{ticket}\`\`\`\n\n_Úselo para consultar el estado en la opción *4*._`,
    reqConsent: `*Consentimiento de Privacidad*

Para registrar su solicitud, se solicitarán datos básicos del representante y la comunidad. Esta información se usará únicamente para orientar, registrar y hacer seguimiento.

Escriba *ACEPTAR* para continuar o *CANCELAR* para salir.`,
    trackPrompt: 'Ingrese su *código de seguimiento* (ej. ```ABCDEF```).',
    trackNotFound: '*Aviso del Sistema*\n\nCódigo no encontrado. Verifique e intente nuevamente.',
    confirmPrompt: '¿Los datos son correctos? (Responda *SÍ* para enviar o *NO* para cancelar).',
    cancelHint: '_Proceso cancelado. Escriba *menu* para volver._',
    confirmSummary: '*Resumen de su solicitud*',
    aiFooter: 'Esta orientación es referencial y no reemplaza la evaluación oficial de la UNCP.',
  },
  qu: {
    welcome: 'Allinllachu, Proyección Social UNCP yanapakuqmi kani. Ima yanapayta mañakuyta atinki, ima willakuyta wakichinki, may canal oficialta llamkanki, chaykunapi yanapasayki.',
    fallback: 'Manam kay tapukuypaq allin kutichiyta tarinichu. Runa yanapakuqwan rimayta qillqayta atini.',
    scope: 'Kay canalqa proyección socialmanta orientacionllapaqmi. Manam ADESA, mesa de partes nitaq trámite oficialkunata rantinchanchu.',
    officeHours: 'Lunesmanta vierneskama 8:00 AM - 1:00 PM hinaspa 2:00 PM - 5:00 PM.',
    processServices: '*Yanapay laya kuna*\n\n- Yachachikuy tallerkuna\n- Asesoría técnica\n- Campaña socialkuna\n- Productivo yanapay\n\n_Yanapakuq willakuykunaman kutinaykipaq *6* qillqay utaq *menu* qillqay._',
    officialChannelsTitle: '*Canal oficialkuna*',
    contactsTitle: '*Orientacionpaq contactokuna*',
    backToMenu: '_Kutiyta munaspa *menu* qillqay._',
    humanContactHint: '_Runa yanapakuqwan rimayta munaspa, *menu* hinaspa *5* qillqay._',
    invalidName: 'Allin sutita qillqay utaq "sin nombre" qillqay.',
    invalidPhone: 'Allin telefono utaq WhatsApp yupayta qillqay.',
    invalidTopic: 'Proyección socialmanta allin temata qillqay.',
    invalidMessage: 'Ima orientacionta munanki, aswan sutita qillqay.',
    needMoreDetail: 'Necesidadniykita aswan sutita willaway.\n\n_Ej: "Cuy uywayta allinchayta munayku" o "Yakumanta yanapayta munayku"_',
    noSpecificOrientation: 'Manam chaypaq orientacion sutita tarinichu.\n\n_Aswan sutita qillqay, kayhina: "Quesota rantikuyta munayku" utaq "Ganadunchik yanapayta munan"._\n\nRuna yanapakuqwan rimayta munaspa *5* qillqay.',
    noInformation: `Huk kutichiyta quyta atisayki. Kaykunapi yanapasayki:

• *1* — Necesidadniyta riqsichiy
• *2* — Mañakuyta qillqay
• *5* — Runa yanapakuqwan rimay

_Utaq *menu* qillqay akllanakunata qawanaykipaq._`,
    offTopicMessage: 'Kay canalqa UNCP proyección socialmanta orientacionllapaqmi.\n\n_Comunidadniykipa necesidadninta qillqay utaq akllanakunata qawanaykipaq *menu* qillqay._',
    informalMessage: 'Manam proyección socialmanta mañakuyta tarinichu.\n\nAkllanakunata qawanaykipaq *menu* qillqay utaq comunidadniykipa necesidadninta pisillapi willaway.',
    trackingStatusTitle: '*Mañakuyniykipa estado*',
    trackingInstitution: 'Institución',
    trackingStatus: 'Estado',
    trackingDate: 'Pacha',
    menu: `*Akllana / Menú*

1. Necesidadniyta riqsichiy
2. *Mañakuyta qillqay*
3. Yanapakuq willakuy
4. *Ticketniyta qatipay*
5. Runa yanapakuqwan rimay

> _*menu* qillqay kutinaykipaq. *idioma* simita tikranaykipaq._`,
    infoMenu: `*Yanapakuq willakuy*

1. Ima yanapaytam mañakuyman
2. Horariokuna hinaspa costo
3. Canal oficialkuna
4. Responsablekunawan rimay
5. Canalpa alcance
6. Menú principalman kutiy

> _1 manta 6 kama huk yupayta qillqay._`,
    needPrompt: `Comunidadniykipa necesidadninta pisillapi willaway.

_Ej: "Cuy uywayta allinchayta munayku" o "Yakumanta yanapayta munayku"_`,
    humanName: 'Representantepa sutinta qillqay utaq "sin nombre" qillqay.',
    humanPhone: 'Telefono utaq WhatsApp yupayta qillqay.',
    humanTopic: 'Proyección socialmanta ima necesidadtaq kachkan, chayta qillqay.',
    humanMessage: 'Ima orientaciónta munanki, pisillapi qillqay.',
    humanSaved: '*Registro Exitoso*\n\nRuna yanapakuqwan rimay mañakuyniyki *Pendiente* hina qillqasqa.',
    error: '*Aviso del Sistema*\n\nPantaymi karqan. Aswan qhipaman kaqmanta qillqay.',
    reqRepName: 'Sutiykita qillqay (Representante).',
    reqRepDni: 'DNI yupaynikita qillqay.',
    reqInstName: 'Comunidadniykipa utaq Instituciónniykipa sutinta qillqay.',
    reqInstType: 'Ima laya: _Comunidad Campesina_, _Gobierno Local_ utaq _Organización Urbana_.',
    reqLocation: 'Maypiraq kachkanki (_Distrito / Centro Poblado_).',
    reqDesc: 'Imapaqtaq yanapayta munanki, pisillapi qillqay.',
    reqSaved: `*Registro Exitoso*\n\nTicketniyki:\n\n\`\`\`{ticket}\`\`\`\n\n_Option *4* kaqpi qatipay._`,
    reqConsent: `*Datosmanta Consentimiento*

Mañakuyta qillqanaykipaq, representantepa hinaspa comunidadpa datonkunata mañakusaykiku. Kay willakuykunataqa orientanallapaqmi hinaspa seguimiento ruranallapaqmi llamkasaiku.

Kutinaykipaq *ACEPTAR* qillqay utaq lluqsinaykipaq *CANCELAR* qillqay.`,
    trackPrompt: 'Ticketniykita qillqay (ej. ```ABCDEF```).',
    trackNotFound: '*Aviso del Sistema*\n\nTicket mana tarisqachu. Kaqmanta qillqay.',
    confirmPrompt: '¿Allinchu kachkan? (*SÍ* qillqay apachinaykipaq utaq *NO* qillqay cancelanaykipaq).',
    cancelHint: '_Cancelasqa. Kutinaykipaq *menu* qillqay._',
    confirmSummary: '*Mañakuyniykipa resumen*',
    aiFooter: 'Kay orientacionqa referencialllami, manam UNCP oficial evaluaciontachu rantinchan.',
  },
  ash: {
    welcome: 'Kitaiteri, Abiro. Nopoki YanapayBot, obamentari UNCP (Proyección Social). Amitakotantsi: puedo orientar de forma simple sobre apoyo para nampitsi, datos necesarios, canales oficiales y contacto humano.',
    fallback: 'Pasonki por su mensaje. No tengo una respuesta segura para esa consulta. Puedo registrar tu pedido para que una persona te oriente.',
    scope: 'Aviso: este canal solo orienta sobre proyección social UNCP. No reemplaza ADESA, mesa de partes ni trámites oficiales.',
    officeHours: 'Atención: lunes a viernes, 8:00 AM - 1:00 PM y 2:00 PM - 5:00 PM.',
    processServices: '*Amitakotantsi (Apoyos disponibles)*\n\n- Capacitación\n- Asesoría técnica\n- Campañas sociales\n- Apoyo productivo\n\n_Escriba *6* para volver al menú de información o *menu* para el principal._',
    officialChannelsTitle: '*Canales oficiales*',
    contactsTitle: '*Contactos de orientación*',
    backToMenu: '_Escriba *menu* para volver._',
    humanContactHint: '_Para que una persona le contacte, escriba *menu* y luego *5*._',
    invalidName: 'Indique un nombre válido o escriba "sin nombre".',
    invalidPhone: 'Indique un teléfono o WhatsApp válido.',
    invalidTopic: 'Indique un tema entendible de proyección social.',
    invalidMessage: 'Describa un poco más la orientación que necesita.',
    needMoreDetail: 'Describa la necesidad con más detalle.\n\n_Ej: "Necesitamos apoyo para producción" o "Necesitamos capacitación"_',
    noSpecificOrientation: 'Pasonki. No encontré orientación específica para eso.\n\n_Escriba más detalle o marque *5* para hablar con una persona._',
    noInformation: `Amitakotantsi (puedo ayudarle). Puedo ayudarte de otra forma:

• *1* — Orientar mi necesidad
• *2* — Registrar solicitud
• *5* — Hablar con una persona

_O escriba *menu* para ver todas las opciones._`,
    offTopicMessage: 'Este canal orienta solo sobre proyección social UNCP.\n\n_Describa la necesidad de su nampitsi o escriba *menu*._',
    informalMessage: 'No logré identificar una solicitud de proyección social.\n\nEscriba *menu* o describa la necesidad de su nampitsi.',
    trackingStatusTitle: '*Estado de su solicitud*',
    trackingInstitution: 'Institución (Nampitsi)',
    trackingStatus: 'Estado',
    trackingDate: 'Fecha',
    menu: `*Avotsi (Menú de orientación)*

1. Orientar mi necesidad
2. *Registrar solicitud*
3. Información útil
4. *Seguimiento de ticket*
5. Hablar con una persona

> _Escriba *idioma* para cambiar de idioma._`,
    infoMenu: `*Información útil*

1. Qué apoyo puedo solicitar
2. Horarios y costo
3. Enlaces oficiales
4. Contactos del proceso
5. Alcance del canal
6. Volver al menú principal

> _Escriba un número del 1 al 6._`,
    needPrompt: `Describa en una frase la necesidad de su nampitsi.

_Ej: "Necesitamos apoyo para producción" o "Necesitamos capacitación"_`,
    humanName: 'Indique el *nombre del representante* o escriba "sin nombre".',
    humanPhone: 'Indique el *teléfono o WhatsApp* de contacto.',
    humanTopic: 'Indique el *tema o necesidad* de proyección social.',
    humanMessage: 'Describa brevemente qué orientación necesita.',
    humanSaved: '*Kameetsa - Registro Exitoso*\n\nSu pedido de orientación humana fue registrado como *Pendiente*. Pasonki.',
    error: '*Aviso del Sistema*\n\nNo se pudo consultar la información. Intente nuevamente en unos minutos.',
    reqRepName: 'Indique su *nombre completo* (Representante).',
    reqRepDni: 'Indique su número de *DNI*.',
    reqInstName: 'Indique el *nombre de su Nampitsi o Institución*.',
    reqInstType: 'Indique el tipo: _Comunidad Campesina_, _Gobierno Local_ u _Organización Urbana_.',
    reqLocation: 'Indique la ubicación (_Distrito / Centro Poblado_).',
    reqDesc: 'Describa brevemente la necesidad o problema que busca resolver.',
    reqSaved: `*Kameetsa - Registro Exitoso*\n\nSu código de seguimiento es:\n\n\`\`\`{ticket}\`\`\`\n\n_Úselo para consultar el estado en la opción *4*. Pasonki._`,
    reqConsent: `*Consentimiento de Privacidad*

Para registrar su solicitud, se solicitarán datos básicos. Esta información se usará únicamente para orientar y registrar su pedido.

Escriba *ACEPTAR* para continuar o *CANCELAR* para salir.`,
    trackPrompt: 'Ingrese su *código de seguimiento* (ej. ```ABCDEF```).',
    trackNotFound: '*Aviso del Sistema*\n\nCódigo no encontrado. Verifique e intente nuevamente.',
    confirmPrompt: '¿Los datos son correctos? (SÍ / NO).',
    cancelHint: '_Cancelado. Escriba *menu*._',
    confirmSummary: '*Resumen de su solicitud*',
    aiFooter: 'Esta orientación es referencial y no reemplaza la evaluación oficial de la UNCP.',
  },
};
