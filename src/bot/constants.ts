import { BotTexts, LanguageCode } from './types';

export const LANGUAGE_PROMPT = `*Orientador UNCP - Proyección Social*

Seleccione un idioma:

1. Español
2. Quechua
3. Asháninka

_Escriba 1, 2 o 3._`;

export const BOT_COPY: Record<LanguageCode, Omit<BotTexts, 'welcome' | 'fallback' | 'scope' | 'officeHours'>> = {
  es: {
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
5. Recibir PDF de referencia
6. Alcance del canal
7. Volver al menú principal

> _Escriba un número del 1 al 7._`,
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
    trackPrompt: 'Ingrese su *código de seguimiento* (ej. ```ABCDEF```).',
    trackNotFound: '*Aviso del Sistema*\n\nCódigo no encontrado. Verifique e intente nuevamente.',
  },
  qu: {
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
5. PDF willakuyta chaskiy
6. Canalpa alcance
7. Menú principalman kutiy

> _1 manta 7 kama huk yupayta qillqay._`,
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
    trackPrompt: 'Ticketniykita qillqay (ej. ```ABCDEF```).',
    trackNotFound: '*Aviso del Sistema*\n\nTicket mana tarisqachu. Kaqmanta qillqay.',
  },
  ash: {
    menu: `*Menú - Ñantsi de orientación*

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
    needPrompt: `Describa en una frase la necesidad de su comunidad.

_Ej: "Necesitamos apoyo para producción" o "Necesitamos capacitación"_`,
    humanName: 'Indique el *nombre del representante* o escriba "sin nombre".',
    humanPhone: 'Indique el *teléfono o WhatsApp* de contacto.',
    humanTopic: 'Indique el *tema o necesidad* de proyección social.',
    humanMessage: 'Describa brevemente qué orientación necesita.',
    humanSaved: '*Registro Exitoso*\n\nSu pedido de orientación humana fue registrado como *Pendiente*.',
    error: '*Aviso del Sistema*\n\nNo se pudo consultar la información. Intente nuevamente en unos minutos.',
    reqRepName: 'Indique su *nombre completo* (Representante).',
    reqRepDni: 'Indique su número de *DNI*.',
    reqInstName: 'Indique el *nombre de su Comunidad o Institución*.',
    reqInstType: 'Indique el tipo: _Comunidad Campesina_, _Gobierno Local_ u _Organización Urbana_.',
    reqLocation: 'Indique la ubicación (_Distrito / Centro Poblado_).',
    reqDesc: 'Describa brevemente la necesidad o problema que busca resolver.',
    reqSaved: `*Registro Exitoso*\n\nSu código de seguimiento es:\n\n\`\`\`{ticket}\`\`\`\n\n_Úselo para consultar el estado en la opción *4*._`,
    trackPrompt: 'Ingrese su *código de seguimiento* (ej. ```ABCDEF```).',
    trackNotFound: '*Aviso del Sistema*\n\nCódigo no encontrado. Verifique e intente nuevamente.',
  },
};
