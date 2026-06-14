<?php

namespace Database\Seeders;

use App\Models\BotSetting;
use App\Models\BotRequest;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\HumanContactRequest;
use App\Models\KnowledgeCategory;
use App\Models\OfficialLink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Servicios de proyección social' => 'Tipos de apoyo que pueden solicitar comunidades campesinas, urbanas y gobiernos locales.',
            'Modalidad y clasificación' => 'Orientación simple sobre cómo se deriva una necesidad y cuándo podría intervenir más de una facultad.',
            'Inicio de solicitud' => 'Datos básicos, canales oficiales y pasos previos para iniciar una solicitud de proyección social.',
            'Seguimiento y plazos' => 'Estados referenciales, continuidad del flujo y orientación sobre seguimiento del proceso.',
            'Contacto humano' => 'Derivación a una persona real cuando el representante necesita orientación específica.',
        ];

        foreach ($categories as $name => $description) {
            KnowledgeCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => $description, 'is_active' => true],
            );
        }

        $services = KnowledgeCategory::where('slug', 'servicios-de-proyeccion-social')->first();
        $modality = KnowledgeCategory::where('slug', 'modalidad-y-clasificacion')->first();
        $start = KnowledgeCategory::where('slug', 'inicio-de-solicitud')->first();
        $tracking = KnowledgeCategory::where('slug', 'seguimiento-y-plazos')->first();
        $human = KnowledgeCategory::where('slug', 'contacto-humano')->first();

        $faqs = [
            [
                'category' => $services,
                'question' => '¿Qué apoyo de proyección social puede solicitar mi comunidad?',
                'answer' => 'La UNCP puede orientar necesidades como capacitaciones, asesoría técnica, campañas sociales, apoyo productivo y acompañamiento institucional. Lo más útil es explicar con palabras simples qué problema tienen, a cuántas personas afecta y en qué comunidad o zona ocurre.',
                'keywords' => 'apoyo, comunidad, necesidad, proyeccion social, servicios',
            ],
            [
                'category' => $modality,
                'question' => '¿Qué diferencia hay entre una solicitud monovalente y polivalente?',
                'answer' => 'En términos simples, algunas necesidades pueden ser vistas por una sola especialidad y otras requieren varias. Usted no necesita decidir eso antes de escribirnos: lo importante es describir bien el problema, la ubicación y quién representa a la comunidad.',
                'keywords' => 'monovalente, polivalente, facultad, clasificacion',
            ],
            [
                'category' => $start,
                'question' => '¿Qué datos necesito para iniciar una orientación?',
                'answer' => 'Para comenzar, conviene tener: nombre del representante, nombre de la comunidad u organización, distrito o centro poblado, teléfono de contacto y una descripción breve del problema. Si tiene fotos o algún sustento simple, también puede ayudar más adelante.',
                'keywords' => 'iniciar, requisitos, representante, comunidad, ubicacion',
            ],
            [
                'category' => $start,
                'question' => 'Soy estudiante y quiero hacer mi proyección social',
                'answer' => 'Si usted es estudiante y quiere realizar su proyección social, lo más práctico es coordinar primero con su facultad o escuela profesional y con la oficina de Proyección Social para que le indiquen el canal interno, los plazos y dónde puede ejecutar la actividad. Si desea, revise contactos y canales oficiales desde el menú de información útil.',
                'keywords' => 'soy estudiante, estudiante, hacer mi proyeccion social, hacer mi proyección social, educacion inicial, educación inicial, escuela profesional, facultad, donde realizar mi proyeccion, dónde realizar mi proyección',
            ],
            [
                'category' => $tracking,
                'question' => '¿El bot reemplaza el sistema oficial de solicitudes?',
                'answer' => 'No. Este bot orienta, ayuda a preparar la información y reduce viajes innecesarios, pero no reemplaza ADESA, mesa de partes ni los procedimientos oficiales de la universidad.',
                'keywords' => 'ADESA, seguimiento, solicitud oficial, tramite',
            ],
            [
                'category' => $human,
                'question' => 'Necesito que una persona me oriente',
                'answer' => 'El bot puede registrar su pedido para que una persona lo contacte. Para eso ayuda dejar nombre, teléfono, comunidad u organización y una explicación breve de la necesidad.',
                'keywords' => 'persona, llamada, whatsapp, orientacion, contacto humano',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos apoyo para nuestro ganado, vacas, cuyes o animales',
                'answer' => 'Si el problema es sobre ganado, vacas, cuyes o sanidad animal, el apoyo probable sería de orientación pecuaria o productiva. Conviene preparar: qué animales crían, qué dificultad tienen, cuántas familias están involucradas y en qué comunidad ocurre. De forma referencial, podría corresponder a Zootecnia.',
                'keywords' => 'ganado, vacas, cuyes, animales, zootecnia, pecuario, agropecuario, veterinaria',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos mejorar nuestros cultivos y agricultura',
                'answer' => 'Si la necesidad es sobre cultivos, riego, plagas, suelo o siembra, el apoyo probable sería técnico-productivo. Conviene indicar qué cultivos tienen, cuál es el problema principal, desde cuándo ocurre y cuántas familias serían beneficiadas. De forma referencial, podría corresponder a Agronomía.',
                'keywords' => 'cultivos, plantas, abono, plagas, agricultura, siembra, cosecha, riego, chacra',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos apoyo en construcción o infraestructura',
                'answer' => 'Si el problema es de agua, saneamiento, local comunal, caminos u otra infraestructura, el apoyo probable sería de diagnóstico u orientación técnica. Conviene tener claro el lugar exacto, el tipo de obra o problema y a cuántas personas afecta. De forma referencial, podría corresponder a Ingeniería Civil o Arquitectura.',
                'keywords' => 'agua, saneamiento, carreteras, puente, colegio, plano, construccion, topografia, infraestructura',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos ayuda para mejorar la educación de nuestros alumnos',
                'answer' => 'Si la necesidad es sobre apoyo escolar, alfabetización, talleres o refuerzo educativo, el apoyo probable sería formativo. Ayuda indicar a qué grupo quieren apoyar, cuál es la dificultad principal y en qué institución o comunidad se necesita. De forma referencial, podría corresponder a Educación.',
                'keywords' => 'alumnos, estudiantes, aprender, lectura, comprension, docentes, enseñanza, alfabetizacion',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos una campaña médica o apoyo en salud',
                'answer' => 'Si buscan apoyo en salud, higiene, prevención o campañas, conviene explicar qué problema observan, a qué población quieren atender y si ya hubo coordinación previa con la posta o autoridad local. De forma referencial, podría corresponder a Medicina Humana, Enfermería o Trabajo Social.',
                'keywords' => 'salud, posta, centro medico, atencion, demora, rapidez, campaña medica, higiene, prevencion, anemia',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos apoyo para nuestro negocio o asociación',
                'answer' => 'Si necesitan apoyo para una asociación, emprendimiento o negocio comunal, ayuda indicar qué producen o venden, cuál es la dificultad y qué esperan mejorar: organización, costos, ventas o formalización. De forma referencial, podría corresponder a Administración, Contabilidad o Economía.',
                'keywords' => 'negocio, emprendimiento, ventas, contabilidad, impuestos, empresa, cooperativa, asociacion, finanzas, pyme',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos apoyo con la reforestación o medio ambiente',
                'answer' => 'Si el caso es de reforestación, residuos, contaminación o cuidado ambiental, conviene indicar el lugar, el tipo de afectación y si impacta a una comunidad, barrio o institución. De forma referencial, podría corresponder a Ciencias Forestales y del Ambiente.',
                'keywords' => 'arboles, deforestacion, basura, residuos, contaminacion, rio limpio, reforestacion, ecologia, medio ambiente',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos asesoría legal o manejo de conflictos',
                'answer' => 'Si la consulta es por derechos comunales, conflictos o temas legales básicos, ayuda explicar el problema sin entrar en demasiados detalles sensibles al inicio. De forma referencial, podría corresponder a Derecho o Sociología, según la necesidad específica.',
                'keywords' => 'legal, abogado, titulo, terrenos, conflicto, violencia, derechos, familia, divorcio, legalizacion, demandas',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos sistemas, software o alfabetización digital',
                'answer' => 'Si necesitan apoyo con computadoras, internet, páginas web, formularios o alfabetización digital, conviene indicar para qué lo necesitan y quiénes serían los usuarios. De forma referencial, podría corresponder a Ingeniería de Sistemas.',
                'keywords' => 'computadoras, internet, pagina web, sistema, red, alfabetizacion digital, tecnologia, software, excel, word',
            ],
            [
                'category' => $services,
                'question' => 'Queremos darle valor agregado a nuestros productos alimenticios',
                'answer' => 'Si buscan mejorar queso, yogurt, mermeladas, conservas u otros alimentos, el apoyo probable sería técnico-productivo para transformación o conservación. Conviene indicar qué producto trabajan, cuál es la dificultad y qué volumen manejan. De forma referencial, podría corresponder a Industrias Alimentarias.',
                'keywords' => 'yogurt, queso, mermelada, conservas, procesamiento, alimentos, valor agregado, envasado, derivados',
            ],
            [
                'category' => $start,
                'question' => 'No sé a qué facultad debo acudir',
                'answer' => 'No necesita saber la facultad antes de empezar. Lo importante es explicar el problema con palabras simples, indicar la comunidad o institución, la ubicación y un teléfono de contacto. Con eso se puede orientar el camino probable.',
                'keywords' => 'facultad, no se, no sé, a donde, dónde, acudir, oficina',
            ],
            [
                'category' => $start,
                'question' => '¿Cómo inicio una solicitud sin viajar a la universidad?',
                'answer' => 'Primero puede orientarse por este canal para ordenar su caso. Luego conviene tener nombre del representante, comunidad u organización, ubicación, teléfono y descripción breve del problema. Cuando corresponda, la formalización se realiza por los canales institucionales.',
                'keywords' => 'iniciar solicitud, viajar, universidad, como empiezo, cómo empiezo, formalizar',
            ],
            [
                'category' => $tracking,
                'question' => '¿Cómo sé si mi solicitud fue recibida o en qué estado está?',
                'answer' => 'Si ya registró su caso en este prototipo, puede consultar el código de seguimiento en la opción 4. Tenga en cuenta que el bot orienta sobre el estado referencial del registro preliminar y no reemplaza la trazabilidad oficial de la universidad.',
                'keywords' => 'seguimiento, estado, recibido, codigo, código, ticket, revisar solicitud',
            ],
            [
                'category' => $tracking,
                'question' => '¿Qué pasa después de presentar la necesidad?',
                'answer' => 'Después de registrar la necesidad, el caso debe ser revisado, orientado y derivado por la universidad según corresponda. El usuario suele necesitar visibilidad sobre si el caso fue recibido, revisado, observado o derivado; por eso el seguimiento es importante.',
                'keywords' => 'despues, después, presentar, necesidad, luego, revisar, derivar',
            ],
            [
                'category' => $start,
                'question' => '¿Qué documentos o información simple debería tener a la mano?',
                'answer' => 'Para una primera orientación no se exige un expediente complejo. Lo más útil es tener nombre del representante, comunidad u organización, ubicación, teléfono, problema principal, población beneficiaria y, si existe, una foto o evidencia simple.',
                'keywords' => 'documentos, informacion, información, mano, requisitos, datos, foto, evidencia',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos una visita técnica a nuestra comunidad',
                'answer' => 'Si desean una visita técnica, conviene explicar para qué tema sería la visita, en qué comunidad o zona, a cuántas personas beneficiaría y cuál es el problema principal. Con esa información se puede orientar si el caso corresponde a apoyo técnico, productivo o institucional.',
                'keywords' => 'visita tecnica, visita técnica, comunidad, inspeccion, inspección, diagnostico, diagnóstico',
            ],
            [
                'category' => $start,
                'question' => 'Soy docente o asesor y quiero saber cómo canalizar un caso de proyección social',
                'answer' => 'Si usted es docente o asesor, conviene coordinar con su facultad y con Proyección Social para validar el canal interno, el periodo de atención y la forma correcta de presentar o vincular el caso. Este bot puede servirle para ordenar el problema, pero no reemplaza la coordinación académica interna.',
                'keywords' => 'docente, asesor, canalizar caso, facultad, coordinacion interna, coordinación interna',
            ],
            [
                'category' => $start,
                'question' => 'Somos una municipalidad o gobierno local y queremos apoyo',
                'answer' => 'Si el pedido viene de una municipalidad o gobierno local, ayuda indicar qué problema buscan atender, qué población sería beneficiada, en qué distrito ocurre y qué tipo de coordinación esperan de la universidad. Con eso se puede orientar mejor el canal y el área probable.',
                'keywords' => 'municipalidad, gobierno local, alcaldia, alcaldía, distrito, gerencia, comuna',
            ],
            [
                'category' => $start,
                'question' => 'Somos una organización urbana, asociación o comité barrial',
                'answer' => 'Si son una organización urbana, asociación o comité barrial, no necesitan llegar con un expediente complejo. Lo más útil es indicar quién representa al grupo, dónde están ubicados, qué problema buscan resolver y a cuántas personas beneficiaría la intervención.',
                'keywords' => 'organizacion urbana, organización urbana, asociacion, asociación, comité, comite barrial, barrio',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos apoyo para agua potable, desagüe o saneamiento básico',
                'answer' => 'Si el caso es sobre agua potable, desagüe o saneamiento, conviene describir el problema actual, el lugar exacto, cuántas familias están afectadas y si ya hubo coordinación con autoridades locales. De forma referencial, podría corresponder a Ingeniería Civil, Arquitectura o áreas afines.',
                'keywords' => 'agua potable, desague, desagüe, saneamiento basico, saneamiento básico, alcantarillado',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos apoyo para biohuertos, huertos escolares o seguridad alimentaria',
                'answer' => 'Si la necesidad es sobre biohuertos, huertos escolares o seguridad alimentaria, ayuda indicar si el objetivo es producir alimentos, capacitar a familias o mejorar la alimentación de estudiantes. De forma referencial, podría corresponder a Agronomía, Educación o áreas de salud, según el caso.',
                'keywords' => 'biohuerto, biohuertos, biohuerto comunal, huerto escolar, seguridad alimentaria, alimentacion, hortalizas',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos campañas de limpieza, manejo de residuos o reciclaje',
                'answer' => 'Si buscan apoyo para limpieza pública, manejo de residuos o reciclaje, conviene explicar el tipo de problema, el espacio afectado y si la actividad busca educación, organización vecinal o solución técnica. De forma referencial, podría corresponder a Ciencias Ambientales, Ingeniería o Educación.',
                'keywords' => 'residuos, reciclaje, limpieza, basura, botadero, segregacion, segregación',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos apoyo para turismo, artesanía o promoción cultural',
                'answer' => 'Si el caso está relacionado con turismo, artesanía o promoción cultural, ayuda indicar qué producto, experiencia o actividad quieren fortalecer y cuál es la dificultad principal: organización, difusión, atención al visitante o mejora del producto. De forma referencial, podría corresponder a Turismo, Administración o Comunicación.',
                'keywords' => 'turismo, artesania, artesanía, cultura, promocion, promoción, visitante, ferias',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos orientación por violencia familiar, convivencia o apoyo social',
                'answer' => 'Si la consulta es sobre violencia familiar, convivencia, niñez, adultos mayores o apoyo social, conviene explicar el problema de forma breve y sin exponer datos sensibles en exceso. De forma referencial, podría corresponder a Trabajo Social, Psicología, Derecho o Sociología, según la necesidad.',
                'keywords' => 'violencia familiar, convivencia, apoyo social, niñez, ninez, adulto mayor, trabajo social',
            ],
            [
                'category' => $start,
                'question' => '¿Puedo iniciar por WhatsApp o primero debo ir presencialmente?',
                'answer' => 'Sí puede orientarse primero por WhatsApp para ordenar el caso y saber qué información preparar. Eso no reemplaza el trámite formal, pero sí puede evitar un viaje innecesario cuando todavía no tiene claro el canal o los requisitos básicos.',
                'keywords' => 'whatsapp, presencial, viajar, primero, canal, orientarse por whatsapp',
            ],
            [
                'category' => $start,
                'question' => '¿Qué pasa si todavía no tengo todos los documentos?',
                'answer' => 'Para una primera orientación no necesita tener todo resuelto. Si aún no cuenta con todos los documentos, igual conviene explicar el problema, la ubicación, quién representa el caso y un teléfono de contacto. Luego se le puede orientar sobre qué faltaría completar.',
                'keywords' => 'no tengo documentos, incompleto, falta, requisitos, expediente, todavia no tengo',
            ],
            [
                'category' => $tracking,
                'question' => '¿Cuánto tiempo demora la atención o la respuesta?',
                'answer' => 'El tiempo puede variar según el tipo de caso, la coordinación requerida y el canal formal que corresponda. Este bot no promete un plazo exacto, pero sí ayuda a ordenar la información inicial para reducir demoras por consultas incompletas.',
                'keywords' => 'cuanto demora, cuánto demora, tiempo de atencion, tiempo de atención, respuesta, plazo',
            ],
            [
                'category' => $tracking,
                'question' => '¿El costo de S/ 3.00 aplica a todo el proceso?',
                'answer' => 'El costo mostrado es solo una referencia informativa del trámite señalado en este prototipo. Antes de pagar o presentar documentos, conviene confirmar con un responsable si ese costo aplica a su caso específico y por cuál canal corresponde continuar.',
                'keywords' => '3 soles, s 3, costo, pago, tramite, trámite, arancel, monto',
            ],
            [
                'category' => $start,
                'question' => '¿Qué enlace o documento puedo revisar para orientarme mejor?',
                'answer' => 'Si desea revisar material de apoyo, puede usar el menú de información útil para ver enlaces oficiales, contactos y recibir un PDF de referencia. Eso le ayudará a entender mejor el alcance del canal antes de continuar con un registro o una derivación.',
                'keywords' => 'enlace, documento, pdf, referencia, link, revisar, material de apoyo',
            ],
            [
                'category' => $services,
                'question' => '¿La UNCP puede apoyar en una feria productiva comunal?',
                'answer' => 'Sí, el bot puede orientarte sobre qué apoyo podría corresponder, como capacitación, difusión técnica, ordenamiento de stands o charlas para productores. Prepara el nombre de la feria, fecha tentativa, lugar, productos principales y entidad organizadora. El siguiente paso es registrar la consulta para derivarla a evaluación.',
                'keywords' => 'feria productiva, feria comunal, productores, stands, capacitación',
            ],
            [
                'category' => $start,
                'question' => 'Queremos organizar un taller en mi comunidad, ¿qué datos debo mandar?',
                'answer' => 'Envía el tema del taller, nombre de la comunidad, cantidad aproximada de participantes, fecha tentativa, lugar y persona responsable. Con esos datos se puede orientar mejor el tipo de apoyo y el canal formal que corresponde.',
                'keywords' => 'taller, organizar, comunidad, participantes, fecha',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden ayudarnos con capacitación para líderes comunales?',
                'answer' => 'Sí, puede corresponder una orientación o taller sobre liderazgo, organización, comunicación y gestión básica. Prepara el nombre de la comunidad, número de dirigentes, temas que necesitan reforzar y disponibilidad de fechas.',
                'keywords' => 'liderazgo comunal, dirigentes, autoridades, capacitación',
            ],
            [
                'category' => $services,
                'question' => 'Somos una junta vecinal y queremos capacitarnos, ¿se puede?',
                'answer' => 'Sí, una junta vecinal puede solicitar orientación en organización, seguridad vecinal, gestión de reuniones, actas o coordinación con instituciones. Indica el sector, número de vecinos, tema principal y contacto responsable.',
                'keywords' => 'junta vecinal, vecinos, capacitación, seguridad, actas',
            ],
            [
                'category' => $services,
                'question' => '¿Hay apoyo para adultos mayores de una comunidad?',
                'answer' => 'Puede orientarse apoyo mediante charlas, actividades de bienestar, cuidado básico, derechos o acompañamiento social, según disponibilidad de equipos universitarios. Prepara cantidad de adultos mayores, ubicación, necesidad principal y contacto.',
                'keywords' => 'adultos mayores, ancianos, bienestar, cuidado, acompañamiento',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden orientar a personas con discapacidad?',
                'answer' => 'Sí, se puede orientar sobre actividades de inclusión, accesibilidad, apoyo educativo, sensibilización o derivación informativa. Indica el tipo de necesidad, cantidad de personas, lugar y si participan familia o institución local.',
                'keywords' => 'discapacidad, inclusión, accesibilidad, apoyo, sensibilización',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos orientación para un comedor popular, ¿la UNCP puede apoyar?',
                'answer' => 'Puede corresponder orientación en organización, manipulación de alimentos, nutrición básica, registros o mejora del servicio. Prepara nombre del comedor, ubicación, número de beneficiarios y necesidad principal.',
                'keywords' => 'comedor popular, alimentos, beneficiarios, nutrición, organización',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden dar una charla sobre nutrición infantil?',
                'answer' => 'Sí, puede evaluarse una charla o taller sobre alimentación saludable, loncheras, anemia o hábitos de cuidado infantil. Indica edades de los niños, cantidad aproximada, lugar y quién organiza la actividad.',
                'keywords' => 'nutrición infantil, niños, anemia, loncheras, alimentación',
            ],
            [
                'category' => $modality,
                'question' => '¿Mi pedido es una charla, taller o acompañamiento?',
                'answer' => 'Si dura poco y es informativo, suele ser charla. Si incluye práctica, es taller. Si requiere varias reuniones o seguimiento, puede ser acompañamiento. Describe el problema, el público y qué resultado esperan lograr.',
                'keywords' => 'charla, taller, acompañamiento, modalidad, clasificación',
            ],
            [
                'category' => $services,
                'question' => 'Queremos mejorar la organización de nuestra asociación, ¿pueden orientarnos?',
                'answer' => 'Sí, puede orientarse en roles, reuniones, libros básicos, acuerdos internos y planificación de actividades. No reemplaza trámites formales, pero ayuda a ordenar lo que deben preparar antes de acudir a la entidad correspondiente.',
                'keywords' => 'asociación, organización, roles, reuniones, acuerdos',
            ],
            [
                'category' => $services,
                'question' => 'Tenemos conflictos por el uso del agua entre sectores, ¿pueden ayudarnos?',
                'answer' => 'El bot puede orientar sobre qué información ordenar y qué instituciones podrían participar. Prepara ubicación, sectores involucrados, tipo de conflicto, acuerdos previos y evidencias disponibles. No reemplaza una mediación formal ni autoridad competente.',
                'keywords' => 'conflicto de agua, sectores, acuerdos, mediación, comunidad',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden apoyarnos con prevención de riesgos ante lluvias o huaicos?',
                'answer' => 'Puede corresponder una charla o taller sobre identificación de zonas de riesgo, rutas seguras, organización vecinal y preparación familiar. Indica el lugar, principales peligros, población afectada y si ya coordinan con el municipio.',
                'keywords' => 'prevención de riesgos, lluvias, huaicos, emergencia, rutas seguras',
            ],
            [
                'category' => $start,
                'question' => 'Queremos que la UNCP visite un anexo alejado, ¿qué debemos indicar?',
                'answer' => 'Indica nombre del anexo, distrito, referencia de llegada, cantidad de beneficiarios, tema solicitado y disponibilidad de fechas. La visita dependerá de evaluación, coordinación y disponibilidad del equipo correspondiente.',
                'keywords' => 'visita, anexo, centro poblado, comunidad alejada, beneficiarios',
            ],
            [
                'category' => $modality,
                'question' => '¿La atención puede ser presencial o virtual?',
                'answer' => 'Depende del tema, ubicación y disponibilidad. Algunas orientaciones pueden iniciar por WhatsApp o reunión virtual, y otras requieren actividad presencial. Indica el lugar, número de participantes y si tienen acceso a internet.',
                'keywords' => 'presencial, virtual, reunión, internet, atención',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden ayudarnos a preparar un proyecto pequeño para la comunidad?',
                'answer' => 'Puede brindarse orientación para ordenar la idea, objetivo, beneficiarios, actividades y recursos básicos. No asegura financiamiento ni aprobación, pero ayuda a presentar mejor la propuesta ante la instancia correspondiente.',
                'keywords' => 'proyecto pequeño, idea, beneficiarios, actividades, propuesta',
            ],
            [
                'category' => $services,
                'question' => 'Queremos coordinar una actividad con el colegio y la posta, ¿cómo se inicia?',
                'answer' => 'Primero identifica el tema, población beneficiaria, instituciones participantes y responsable de cada entidad. Luego registra la solicitud con esos datos para evaluar si corresponde apoyo universitario y qué coordinación formal se necesita.',
                'keywords' => 'colegio, posta, coordinación, actividad, instituciones',
            ],
            [
                'category' => $start,
                'question' => '¿Puede solicitar apoyo un centro poblado menor?',
                'answer' => 'Sí, puede iniciar una orientación si cuenta con un representante o responsable de coordinación. Debe preparar nombre del centro poblado, distrito, necesidad principal, población beneficiaria y datos de contacto.',
                'keywords' => 'centro poblado, representante, distrito, solicitud, apoyo',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden capacitar sobre higiene y manipulación de alimentos?',
                'answer' => 'Sí, puede corresponder una charla o taller para comedores, ferias, quioscos o grupos comunitarios. Indica quiénes participarán, cuántas personas son, lugar y qué alimentos preparan o venden.',
                'keywords' => 'higiene, manipulación de alimentos, comedor, feria, quiosco',
            ],
            [
                'category' => $services,
                'question' => 'Queremos una charla para prevenir el consumo de alcohol en jóvenes, ¿se puede?',
                'answer' => 'Puede evaluarse una actividad preventiva con enfoque educativo y comunitario. Prepara edad de los participantes, cantidad aproximada, institución o barrio, problema observado y persona responsable de coordinar.',
                'keywords' => 'alcohol, jóvenes, prevención, charla, comunidad',
            ],
            [
                'category' => $services,
                'question' => '¿La UNCP puede orientar sobre salud mental comunitaria?',
                'answer' => 'Puede orientarse mediante charlas de prevención, bienestar emocional, convivencia o rutas de ayuda. Si hay una emergencia o riesgo inmediato, se debe acudir a los servicios de salud o autoridades competentes.',
                'keywords' => 'salud mental, bienestar emocional, convivencia, prevención',
            ],
            [
                'category' => $modality,
                'question' => '¿Qué diferencia hay entre orientación y trámite formal?',
                'answer' => 'La orientación te ayuda a entender qué apoyo podría corresponder y qué datos preparar. El trámite formal se realiza por los canales oficiales de la universidad o entidad correspondiente. El bot no reemplaza ese proceso.',
                'keywords' => 'orientación, trámite formal, mesa de partes, solicitud oficial',
            ],
            [
                'category' => $human,
                'question' => '¿Cuándo necesito hablar con una persona y no solo con el bot?',
                'answer' => 'Conviene pedir contacto humano si tu caso ya tiene documentos, varias instituciones involucradas, fechas definidas o necesita revisión específica. El bot puede ayudarte a ordenar el resumen antes de derivar la consulta.',
                'keywords' => 'contacto humano, derivar, documentos, revisión, caso específico',
            ],
            [
                'category' => $start,
                'question' => '¿Qué debo escribir primero para que entiendan mi caso?',
                'answer' => 'Escribe en una frase qué necesitas, dónde será, quiénes se beneficiarán y quién coordina. Ejemplo: “Solicito un taller para 40 madres del comedor del barrio X sobre nutrición infantil”.',
                'keywords' => 'primer mensaje, explicar caso, beneficiarios, coordinar',
            ],
            [
                'category' => $tracking,
                'question' => 'Ya mandé mi solicitud por otro canal, ¿el bot puede decirme si fue aceptada?',
                'answer' => 'El bot puede orientarte sobre cómo consultar y qué datos tener a la mano, pero no confirma aprobación si no tiene acceso al registro correspondiente. Ten listo el número de expediente, fecha de envío y nombre del solicitante.',
                'keywords' => 'solicitud enviada, aceptada, expediente, consultar, estado',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden orientar a madres gestantes de una comunidad?',
                'answer' => 'Puede corresponder una charla educativa sobre cuidado, alimentación, controles y señales de alerta, coordinando con servicios de salud cuando corresponda. Indica cantidad de gestantes, lugar, tema principal y entidad que convoca.',
                'keywords' => 'madres gestantes, embarazo, cuidado, controles, charla',
            ],
            [
                'category' => $services,
                'question' => 'Queremos fortalecer la participación de mujeres en la comunidad, ¿hay apoyo?',
                'answer' => 'Sí, puede evaluarse una actividad sobre liderazgo, organización, autoestima, emprendimiento comunitario o participación ciudadana. Prepara el grupo beneficiario, número de participantes, lugar y objetivo de la actividad.',
                'keywords' => 'mujeres, participación, liderazgo, organización, comunidad',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden dar capacitación sobre primeros auxilios básicos?',
                'answer' => 'Puede evaluarse una charla o taller básico, especialmente para juntas, colegios, comedores o comunidades. Indica cantidad de participantes, edades, lugar y si cuentan con apoyo de una posta o personal de salud.',
                'keywords' => 'primeros auxilios, taller básico, emergencia, posta, capacitación',
            ],
            [
                'category' => $modality,
                'question' => '¿Mi pedido debe ir como capacitación o como asistencia técnica?',
                'answer' => 'Si buscan aprender un tema general, puede ser capacitación. Si necesitan revisar un caso concreto y recibir recomendaciones prácticas, puede ser asistencia técnica. Describe el problema y el resultado que esperan.',
                'keywords' => 'capacitación, asistencia técnica, recomendaciones, caso concreto',
            ],
            [
                'category' => $start,
                'question' => '¿Puedo pedir apoyo si todavía no tengo fecha para la actividad?',
                'answer' => 'Sí, puedes iniciar la orientación con una fecha tentativa o indicar que aún está por definir. Igual debes enviar tema, lugar, beneficiarios, responsable y motivo del pedido para evaluar la posibilidad de apoyo.',
                'keywords' => 'sin fecha, fecha tentativa, actividad, iniciar solicitud',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden ayudarnos a evaluar necesidades de nuestro barrio o comunidad?',
                'answer' => 'Puede orientarse un diagnóstico básico participativo para ordenar problemas, prioridades y posibles acciones. Prepara ubicación, grupo organizador, población aproximada y los principales problemas que desean revisar.',
                'keywords' => 'diagnóstico, necesidades, barrio, comunidad, prioridades',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden orientarnos para mejorar la convivencia entre vecinos sin hacer una denuncia?',
                'answer' => 'Sí, se puede orientar con pautas para diálogo, acuerdos de convivencia y reuniones ordenadas. Prepara el problema principal, quiénes participan, qué acuerdos intentaron y qué resultado esperan.',
                'keywords' => 'convivencia, vecinos, diálogo, acuerdos, reunión',
            ],
            [
                'category' => $services,
                'question' => 'Queremos hacer una feria escolar con padres y estudiantes, ¿pueden orientar?',
                'answer' => 'Puede corresponder orientación para organizar roles, cronograma, espacios, seguridad y participación de familias. Indica el colegio, objetivo de la feria, fecha tentativa y cantidad de participantes.',
                'keywords' => 'feria escolar, padres, estudiantes, organización, colegio',
            ],
            [
                'category' => $services,
                'question' => '¿Hay orientación para personas que cuidan adultos mayores o familiares enfermos?',
                'answer' => 'Sí, puede orientarse con información básica sobre cuidado, autocuidado del cuidador, organización familiar y señales de alerta. Indica a quién cuidan, cuántas personas participarían y dónde sería la actividad.',
                'keywords' => 'cuidador, cuidadora, cuidado familiar, autocuidado, adultos mayores',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden apoyar con actividades para adolescentes de mi comunidad?',
                'answer' => 'Puede evaluarse una actividad educativa o participativa sobre convivencia, proyecto de vida, hábitos saludables o uso responsable del tiempo libre. Prepara edades, cantidad de adolescentes, lugar y tema de mayor interés.',
                'keywords' => 'adolescentes, jóvenes, proyecto de vida, hábitos, comunidad',
            ],
            [
                'category' => $services,
                'question' => '¿La UNCP orienta a familias retornantes o migrantes?',
                'answer' => 'Puede brindarse orientación informativa para ordenar necesidades, servicios disponibles y posibles rutas de apoyo. Indica de dónde vienen, dónde viven ahora, necesidad principal y si hay niños, adultos mayores o personas con discapacidad.',
                'keywords' => 'migrantes, retornantes, familias, orientación, apoyo',
            ],
            [
                'category' => $services,
                'question' => 'Necesitamos mejorar la comunicación entre comuneros, ¿se puede pedir apoyo?',
                'answer' => 'Sí, puede corresponder una actividad sobre comunicación, escucha, acuerdos y organización de reuniones. Prepara el problema, número de participantes, lugar y quién convoca.',
                'keywords' => 'comunicación, comuneros, acuerdos, reuniones, convivencia',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden orientar sobre prevención de anemia en familias?',
                'answer' => 'Puede evaluarse una campaña informativa o charla preventiva. Prepara edades de los beneficiarios, cantidad aproximada, lugar y si hay coordinación con una posta o institución local.',
                'keywords' => 'anemia, prevención, familias, charla preventiva, posta',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden dar orientación sobre lavado de manos y enfermedades respiratorias?',
                'answer' => 'Sí, puede corresponder una actividad informativa para familias, estudiantes o grupos comunitarios. Indica público, cantidad de participantes, lugar y fecha tentativa.',
                'keywords' => 'lavado de manos, enfermedades respiratorias, prevención, familias',
            ],
            [
                'category' => $services,
                'question' => 'Queremos hacer huertos familiares en casas, ¿pueden orientar?',
                'answer' => 'Puede brindarse orientación básica sobre organización del espacio, cuidado inicial y participación familiar. Prepara cantidad de familias, zona, disponibilidad de espacio y objetivo del huerto.',
                'keywords' => 'huerto familiar, huertos familiares, casa, familias, orientación',
            ],
            [
                'category' => $services,
                'question' => 'Nuestro comité de agua necesita ordenarse mejor, ¿pueden orientar?',
                'answer' => 'Puede orientarse sobre roles, reuniones, acuerdos, registros básicos y comunicación con usuarios. No reemplaza trámites formales ni decisiones de autoridad. Prepara el nombre del comité, número de usuarios y necesidad principal.',
                'keywords' => 'comité de agua, usuarios, roles, registros, acuerdos',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden enseñar educación financiera básica para familias?',
                'answer' => 'Sí, puede evaluarse una actividad sobre presupuesto familiar, ahorro, deudas y planificación de gastos. Indica cantidad de familias, lugar, edad aproximada de participantes y tema más urgente.',
                'keywords' => 'educación financiera, presupuesto familiar, ahorro, deudas',
            ],
            [
                'category' => $services,
                'question' => 'Somos comerciantes de un mercado y queremos mejorar nuestra atención al público, ¿se puede?',
                'answer' => 'Puede corresponder orientación sobre atención, orden, higiene, trato al cliente y organización interna. Prepara nombre del mercado, número de comerciantes, tema principal y responsable de coordinación.',
                'keywords' => 'mercado, comerciantes, atención al cliente, orden, higiene',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden hacer actividades de inclusión para estudiantes con discapacidad?',
                'answer' => 'Puede evaluarse una actividad de sensibilización e inclusión con docentes, familias o estudiantes. Indica nivel educativo, cantidad de participantes, tipo de necesidad y objetivo de la actividad.',
                'keywords' => 'discapacidad, inclusión escolar, sensibilización, estudiantes',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden orientar para prevenir violencia escolar o bullying?',
                'answer' => 'Puede corresponder una actividad preventiva sobre respeto, convivencia y rutas de ayuda. Si hay riesgo inmediato, se debe acudir a la institución responsable o autoridad competente. Prepara edad, grado y situación general.',
                'keywords' => 'bullying, violencia escolar, convivencia escolar, prevención',
            ],
            [
                'category' => $services,
                'question' => 'Nuestro club de madres quiere organizarse mejor, ¿pueden apoyar?',
                'answer' => 'Puede orientarse sobre funciones, reuniones, acuerdos, actividades y participación de las socias. Prepara nombre del club, número de integrantes, lugar y principal dificultad.',
                'keywords' => 'club de madres, madres, organización, socias, reuniones',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden orientar sobre alimentación saludable en colegios?',
                'answer' => 'Puede evaluarse una actividad informativa para estudiantes, padres o quioscos escolares. Indica nivel educativo, cantidad de participantes, tema específico y si coordina la institución educativa.',
                'keywords' => 'alimentación saludable, colegio, quiosco escolar, padres',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden ayudarnos a ordenar documentos básicos de la comunidad?',
                'answer' => 'Puede orientarse sobre cómo organizar actas, padrones, acuerdos, cargos y archivos internos. No reemplaza trámites formales. Prepara qué documentos tienen, qué falta ordenar y quién es responsable.',
                'keywords' => 'documentos comunales, actas, padrón, acuerdos, archivo',
            ],
            [
                'category' => $services,
                'question' => 'Queremos una actividad de integración entre escuela y comunidad, ¿se puede?',
                'answer' => 'Sí, puede evaluarse una actividad participativa con estudiantes, familias y representantes locales. Indica objetivo, lugar, cantidad de participantes, instituciones involucradas y fecha tentativa.',
                'keywords' => 'integración, escuela, comunidad, familias, actividad',
            ],
            [
                'category' => $services,
                'question' => '¿Pueden orientar una campaña de limpieza de canales o acequias?',
                'answer' => 'Puede orientarse en organización, convocatoria, roles, seguridad básica y coordinación local. No reemplaza permisos ni responsabilidades de la autoridad competente. Prepara ubicación, tramo, participantes y fecha tentativa.',
                'keywords' => 'limpieza de canal, acequia, campaña, convocatoria, seguridad',
            ],
            [
                'category' => $services,
                'question' => '¿Hay apoyo para adultos mayores en barrios urbanos?',
                'answer' => 'Puede evaluarse una actividad de orientación, integración, bienestar o cuidado básico. Indica barrio, cantidad de adultos mayores, tema principal y quién coordina.',
                'keywords' => 'adultos mayores, barrio urbano, bienestar, integración, cuidado',
            ],
            [
                'category' => $services,
                'question' => 'Queremos priorizar los problemas de nuestra comunidad, ¿pueden ayudarnos?',
                'answer' => 'Puede orientarse un ejercicio participativo para listar problemas, ordenarlos por urgencia y definir próximos pasos. Prepara participantes, lugar, problemas principales y responsable de convocatoria.',
                'keywords' => 'priorizar problemas, diagnóstico, comunidad, urgencia, próximos pasos',
            ],
            [
                'category' => $start,
                'question' => '¿Qué información debo enviar si somos un grupo de familias?',
                'answer' => 'Envía nombre del grupo, lugar, cantidad de familias, necesidad principal, persona responsable y fecha tentativa si la tienen. Con eso se puede orientar mejor el tipo de apoyo posible.',
                'keywords' => 'grupo de familias, datos, solicitud, responsable, necesidad',
            ],
            [
                'category' => $start,
                'question' => '¿Puedo pedir orientación si aún no sabemos exactamente qué actividad hacer?',
                'answer' => 'Sí. Explica el problema, quiénes serían beneficiarios y qué cambio esperan lograr. Con esa información se puede sugerir si conviene una orientación, actividad grupal o derivación formal.',
                'keywords' => 'no sabemos, actividad, problema, beneficiarios, orientación',
            ],
            [
                'category' => $start,
                'question' => '¿Qué datos preparo para pedir apoyo en una feria comunitaria?',
                'answer' => 'Prepara lugar, fecha tentativa, organizadores, número de participantes, objetivo de la feria y tipo de apoyo que necesitan. El bot puede ayudarte a ordenar el pedido antes del canal formal.',
                'keywords' => 'feria comunitaria, datos, organizadores, participantes, apoyo',
            ],
            [
                'category' => $start,
                'question' => '¿Qué debo enviar si mi pedido involucra a varias instituciones?',
                'answer' => 'Indica qué instituciones participan, quién coordina por cada una, objetivo común, lugar, público beneficiario y fecha tentativa. Eso ayuda a evitar confusiones en la orientación.',
                'keywords' => 'varias instituciones, coordinación, colegio, posta, comunidad',
            ],
            [
                'category' => $start,
                'question' => '¿Puedo iniciar una consulta si soy dirigente nuevo y recién estoy ordenando mi gestión?',
                'answer' => 'Sí. Indica tu cargo, comunidad o sector, principales necesidades y qué documentos o acuerdos tienes disponibles. El bot puede orientarte sobre cómo ordenar el primer pedido.',
                'keywords' => 'dirigente nuevo, gestión, cargo, comunidad, primer pedido',
            ],
            [
                'category' => $start,
                'question' => '¿Qué datos debo preparar para una actividad con adolescentes?',
                'answer' => 'Prepara edades, cantidad aproximada, lugar, tema que preocupa, institución que convoca y persona responsable. Si hay una situación urgente, corresponde acudir a la autoridad o servicio competente.',
                'keywords' => 'adolescentes, datos, edades, cantidad, responsable',
            ],
            [
                'category' => $start,
                'question' => '¿Cómo explico mi pedido si es para un mercado o feria de comerciantes?',
                'answer' => 'Indica nombre del mercado o feria, cantidad de comerciantes, problema principal, tipo de orientación requerida y responsable de coordinación. Así se puede clasificar mejor el apoyo posible.',
                'keywords' => 'mercado, feria, comerciantes, pedido, coordinación',
            ],
            [
                'category' => $tracking,
                'question' => 'Si ya enviamos información, ¿qué debemos tener listo para continuar?',
                'answer' => 'Ten a la mano el resumen del pedido, nombre del solicitante, lugar, fecha de envío y cualquier respuesta recibida. El bot puede ayudarte a ordenar esos datos para la siguiente consulta.',
                'keywords' => 'continuar, información enviada, resumen, fecha, respuesta',
            ],
            [
                'category' => $tracking,
                'question' => '¿Qué hago si mi actividad tiene fecha cercana?',
                'answer' => 'Indica la fecha, lugar, cantidad de participantes y motivo de urgencia. El bot puede orientar el registro del pedido, pero no puede prometer atención inmediata ni aprobación.',
                'keywords' => 'fecha cercana, urgente, actividad, participantes, aprobación',
            ],
            [
                'category' => $tracking,
                'question' => '¿Puedo cambiar el tema de una solicitud que ya estaba preparando?',
                'answer' => 'Sí, pero conviene aclarar qué cambió, cuál es el nuevo tema y si se mantienen lugar, fecha y beneficiarios. Si ya ingresó por canal formal, consulta por ese mismo canal.',
                'keywords' => 'cambiar tema, solicitud, nuevo tema, beneficiarios, canal formal',
            ],
            [
                'category' => $tracking,
                'question' => '¿Qué pasa si la comunidad cambia de responsable de coordinación?',
                'answer' => 'Actualiza el nombre, cargo y contacto del nuevo responsable en el canal que corresponda. También conviene mantener el resumen del pedido para no perder continuidad.',
                'keywords' => 'cambio responsable, coordinación, continuidad, solicitud',
            ],
            [
                'category' => $modality,
                'question' => '¿Mi caso es orientación comunitaria o actividad educativa?',
                'answer' => 'Si buscan ordenar un problema y próximos pasos, es orientación comunitaria. Si buscan aprender un tema con un grupo, puede ser actividad educativa. Describe objetivo, participantes y resultado esperado.',
                'keywords' => 'orientación comunitaria, actividad educativa, objetivo, participantes',
            ],
            [
                'category' => $modality,
                'question' => '¿Una feria escolar se considera actividad de proyección social?',
                'answer' => 'Puede considerarse si busca beneficiar a estudiantes, familias o comunidad con orientación útil. Indica objetivo, público, institución responsable y qué apoyo se espera de la universidad.',
                'keywords' => 'feria escolar, proyección social, estudiantes, familias, apoyo',
            ],
            [
                'category' => $modality,
                'question' => '¿Una campaña informativa es lo mismo que una campaña de atención?',
                'answer' => 'No siempre. Una campaña informativa comparte orientación y prevención. Una campaña de atención implica servicios directos y requiere más coordinación. Indica qué esperan recibir y quiénes participarían.',
                'keywords' => 'campaña informativa, campaña de atención, prevención, servicios',
            ],
            [
                'category' => $modality,
                'question' => '¿Mi pedido puede ser solo para orientar a dirigentes?',
                'answer' => 'Sí, puede ser una orientación breve para ordenar ideas, datos y pasos siguientes. Indica cuántos dirigentes participarán, de qué lugar son y qué problema quieren abordar.',
                'keywords' => 'dirigentes, orientación, pasos, datos, comunidad',
            ],
            [
                'category' => $modality,
                'question' => '¿Se puede pedir apoyo para una actividad de integración familiar?',
                'answer' => 'Puede evaluarse si tiene un objetivo comunitario claro, como convivencia, participación o prevención. Prepara lugar, participantes, objetivo y entidad o grupo que convoca.',
                'keywords' => 'integración familiar, convivencia, participación, actividad',
            ],
            [
                'category' => $human,
                'question' => 'Mi caso tiene varias partes y no quiero explicarlo mal, ¿qué hago?',
                'answer' => 'Resume en orden: qué problema tienen, dónde ocurre, quiénes son beneficiarios, qué apoyo buscan y quién coordina. Si el caso requiere revisión específica, conviene derivarlo a atención humana.',
                'keywords' => 'caso complejo, resumir, beneficiarios, coordinación, atención humana',
            ],
            [
                'category' => $human,
                'question' => '¿Puedo pedir que revisen si mi solicitud está bien redactada?',
                'answer' => 'El bot puede ayudarte a ordenar la información básica y detectar datos faltantes. Si necesitas revisión formal del documento, corresponde usar el canal oficial o pedir orientación humana.',
                'keywords' => 'redacción, solicitud, revisar, datos faltantes, canal oficial',
            ],
            [
                'category' => $human,
                'question' => '¿Qué hago si mi consulta no encaja en ninguna opción del bot?',
                'answer' => 'Escribe un resumen corto con lugar, beneficiarios, problema y apoyo esperado. Si aun así no se puede clasificar, el caso puede requerir orientación humana o derivación al canal formal.',
                'keywords' => 'no encaja, otra consulta, clasificar, orientación humana',
            ],
            [
                'category' => $start,
                'question' => '¿Por qué este bot existe o qué problema resuelve?',
                'answer' => 'Este canal existe para que representantes de comunidades, organizaciones y gobiernos locales entiendan qué apoyo podrían solicitar a la UNCP, con quién comunicarse y cómo empezar sin viajar solo para pedir orientación. Su función principal es ordenar el primer contacto y reducir la desorientación inicial.',
                'keywords' => 'para que sirve, para qué sirve, problema, resuelve, viajes, presencial, como podriamos ayudar, cómo podríamos ayudar, representantes, comunidades, gobiernos locales, con quien comunicarse, con quién comunicarse, iniciar una solicitud, orientarse',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                [
                    'knowledge_category_id' => $faq['category']->id,
                    'answer' => $faq['answer'],
                    'keywords' => $faq['keywords'],
                    'is_active' => true,
                ],
            );
        }

        OfficialLink::whereIn('title', [
            'Portal institucional UNCP',
            'Desafío Transformagob 2026',
            'Mesa demo de orientación comunitaria',
            'Repositorio demo de guías comunitarias',
            'Seguimiento demo de tickets',
            'Agenda demo de llamadas',
        ])->orWhereIn('url', [
            'https://uncp.edu.pe/',
            'https://www.gob.pe/114679-desafios-de-innovacion-digital-hackaton-transformagob-2026',
            'https://demo-orientacion.example.test/mesa',
            'https://demo-orientacion.example.test/guias',
            'https://demo-orientacion.example.test/seguimiento',
            'https://demo-orientacion.example.test/agenda',
        ])->delete();

        Contact::whereIn('phone', [
            '939205127',
            '929241557',
            '912301577',
            '900000101',
            '900000202',
            '900000303',
            '900000404',
        ])->orWhereIn('email', [
            'gaylas@uncp.edu.pe',
            'omercado@uncp.edu.pe',
            'proyeccion@uncp.edu.pe',
            'equipo.norte@example.test',
            'equipo.centro@example.test',
            'equipo.sur@example.test',
            'equipo.satelite@example.test',
        ])->delete();

        BotRequest::whereIn('ticket_id', ['DEMO01', 'DEMO02', 'DEMO03', 'DEMO04'])
            ->orWhere('ticket_id', 'ilike', '%demo%')
            ->orWhere('representative_name', 'ilike', '%demo%')
            ->orWhere('institution_name', 'ilike', '%demo%')
            ->delete();

        HumanContactRequest::whereIn('phone', ['900111000', '900222000', '900333000', '900444000'])
            ->orWhere('citizen_name', 'ilike', '%demo%')
            ->orWhere('topic', 'ilike', '%demo%')
            ->delete();

        $links = [
            [
                'category' => $start,
                'title' => 'Mesa de orientación comunitaria',
                'description' => 'Canal ficticio para simular recepción de consultas preliminares.',
                'url' => 'https://orientacion-institucional.example.test/mesa',
                'keywords' => 'orientacion, solicitud, prototipo',
            ],
            [
                'category' => $services,
                'title' => 'Repositorio de guías comunitarias',
                'description' => 'Biblioteca ficticia de material de apoyo para pruebas de navegación.',
                'url' => 'https://orientacion-institucional.example.test/guias',
                'keywords' => 'guias, biblioteca, apoyo',
            ],
            [
                'category' => $tracking,
                'title' => 'Seguimiento de tickets',
                'description' => 'Panel ficticio para probar mensajes de seguimiento sin usar sistemas reales.',
                'url' => 'https://orientacion-institucional.example.test/seguimiento',
                'keywords' => 'seguimiento, ticket, estado',
            ],
            [
                'category' => $human,
                'title' => 'Agenda de llamadas',
                'description' => 'Calendario ficticio para simular coordinación de contacto humano.',
                'url' => 'https://orientacion-institucional.example.test/agenda',
                'keywords' => 'agenda, llamada, contacto humano',
            ],
        ];

        foreach ($links as $link) {
            OfficialLink::updateOrCreate(
                ['title' => $link['title']],
                [
                    'knowledge_category_id' => $link['category']->id,
                    'description' => $link['description'],
                    'url' => $link['url'],
                    'keywords' => $link['keywords'],
                    'is_active' => true,
                ],
            );
        }

        $contacts = [
            [
                'name' => 'Equipo Norte',
                'office' => 'Mesa ficticia de orientación inicial',
                'phone' => '900000101',
                'email' => 'equipo.norte@example.test',
                'attention_hours' => 'Lun-Vie 09:00 - 13:00',
                'topics' => 'Registro preliminar, dudas de requisitos y orientación comunitaria',
            ],
            [
                'name' => 'Equipo Centro',
                'office' => 'Mesa ficticia de seguimiento',
                'phone' => '900000202',
                'email' => 'equipo.centro@example.test',
                'attention_hours' => 'Lun-Vie 14:00 - 17:00',
                'topics' => 'Seguimiento de tickets, estados y derivación simulada',
            ],
            [
                'name' => 'Equipo Sur',
                'office' => 'Mesa ficticia de contacto humano',
                'phone' => '900000303',
                'email' => 'equipo.sur@example.test',
                'attention_hours' => 'Mar-Jue 10:00 - 16:00',
                'topics' => 'Llamadas de prueba, bitácora y priorización de casos',
            ],
            [
                'name' => 'Equipo Satélite',
                'office' => 'Mesa ficticia para comunidades alejadas',
                'phone' => '900000404',
                'email' => 'equipo.satelite@example.test',
                'attention_hours' => 'Mié-Vie 08:30 - 12:30',
                'topics' => 'Visitas técnicas simuladas y coordinación territorial',
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::updateOrCreate(['phone' => $contact['phone']], $contact + ['is_active' => true]);
        }

        $sampleRequests = [
            [
                'ticket_id' => 'SIM001',
                'representative_name' => 'Rosa Huamán',
                'representative_dni' => '00000001',
                'institution_name' => 'Comunidad Pukará',
                'institution_type' => 'Comunidad Campesina',
                'location' => 'Distrito ficticio de Andenes',
                'description' => 'Solicitan orientación para un taller de biohuertos familiares y organización de participantes.',
                'status' => 'Evaluando',
            ],
            [
                'ticket_id' => 'SIM002',
                'representative_name' => 'Marco Salazar',
                'representative_dni' => '00000002',
                'institution_name' => 'Asociación Los Alisos',
                'institution_type' => 'Organización Social',
                'location' => 'Barrio ficticio Santa Clara',
                'description' => 'Requieren apoyo referencial para ordenar una feria comunitaria y mejorar la atención al público.',
                'status' => 'Asignado',
            ],
            [
                'ticket_id' => 'SIM003',
                'representative_name' => 'Elena Cárdenas',
                'representative_dni' => '00000003',
                'institution_name' => 'Municipalidad de Río Seco',
                'institution_type' => 'Municipalidad / Gob. Local',
                'location' => 'Centro poblado ficticio Río Seco',
                'description' => 'Caso ficticio para probar seguimiento de una campaña de higiene, residuos y prevención comunitaria.',
                'status' => 'En Ejecución',
            ],
            [
                'ticket_id' => 'SIM004',
                'representative_name' => 'Luis Arrieta',
                'representative_dni' => '00000004',
                'institution_name' => 'Institución Educativa Horizonte',
                'institution_type' => 'Institución Educativa',
                'location' => 'Sector ficticio El Mirador',
                'description' => 'Prueba de solicitud para charla educativa sobre convivencia escolar y participación de familias.',
                'status' => 'Finalizado',
            ],
        ];

        foreach ($sampleRequests as $sampleRequest) {
            BotRequest::updateOrCreate(
                ['ticket_id' => $sampleRequest['ticket_id']],
                $sampleRequest,
            );
        }

        $humanContactRequests = [
            [
                'phone' => '900111000',
                'message' => 'Necesitamos ordenar un pedido ficticio de biohuertos y no sabemos qué datos preparar.',
                'citizen_name' => 'Ana Quispe',
                'topic' => 'Biohuertos familiares',
                'preferred_channel' => 'WhatsApp',
                'status' => 'Pendiente',
            ],
            [
                'phone' => '900222000',
                'message' => 'Queremos validar una llamada de prueba para una feria comunitaria.',
                'citizen_name' => 'Pedro Rojas',
                'topic' => 'Feria comunitaria',
                'preferred_channel' => 'Llamada',
                'status' => 'En Contacto',
            ],
            [
                'phone' => '900333000',
                'message' => 'Caso ficticio para revisar si corresponde convertirlo a solicitud formal.',
                'citizen_name' => 'María Torres',
                'topic' => 'Derivación simulada',
                'preferred_channel' => 'WhatsApp',
                'status' => 'Derivado a Trámite',
            ],
            [
                'phone' => '900444000',
                'message' => 'Consulta de prueba cerrada para mostrar histórico del panel.',
                'citizen_name' => 'Jorge Flores',
                'topic' => 'Consulta resuelta',
                'preferred_channel' => 'WhatsApp',
                'status' => 'Resuelto',
            ],
        ];

        foreach ($humanContactRequests as $humanContactRequest) {
            HumanContactRequest::updateOrCreate(
                ['phone' => $humanContactRequest['phone'], 'message' => $humanContactRequest['message']],
                $humanContactRequest,
            );
        }

        $systemPrompt = <<<'PROMPT'
Eres el asistente virtual de Proyección Social de la UNCP (Universidad Nacional del Centro del Perú).
Tu único rol es orientar a representantes de comunidades campesinas, comunidades urbanas, organizaciones sociales y gobiernos locales sobre cómo solicitar servicios de proyección social universitaria.

REGLAS ESTRICTAS:
- Solo responde sobre proyección social UNCP, necesidades comunitarias y servicios universitarios para comunidades.
- Si preguntan sobre política, elecciones, entretenimiento, opiniones personales u otros temas no relacionados: declina con educación y recuerda cuál es tu propósito.
- Si el mensaje es una expresión informal (xd, jajaja, ok, piola, chevere, etc.) o no tiene sentido en contexto: responde brevemente pidiendo que describa su necesidad.
- Respeta el IDIOMA DE SESION indicado al final de estas instrucciones. No cambies de idioma aunque el historial tenga mensajes en otro idioma. Si no hay idioma de sesión, responde en el idioma más claro del usuario; si hay duda, usa español claro.
- Sé breve para WhatsApp: máximo 5 líneas, sin tablas, sin listas largas y sin lenguaje burocrático. Formato de WhatsApp: usa *texto* para negrita (NUNCA **texto**) y no uses cabeceras markdown (#, ##).
- No inventes fechas, costos, nombres de personas, números de expediente, teléfonos, enlaces ni requisitos no confirmados.
- No prometas aprobación, ejecución de proyectos ni atención inmediata. Solo orientas preliminarmente.
- No digas que una solicitud fue aceptada, aprobada, asignada o derivada si solo estás orientando.
- Si la consulta pide trámite formal, aclara que el canal no reemplaza la oficina o el procedimiento oficial correspondiente, como ADESA, mesa de partes u otros canales formales cuando aplique.
- Si la respuesta es útil, cierra con una acción concreta: escribir "menu", "5" para una persona o "2" para registrar solicitud.
- No menciones ODS, periodos académicos, informes, pagos estudiantiles ni clasificación monovalente/polivalente salvo que el usuario lo pregunte de forma explícita.
- No empieces nombrando facultades si primero puedes explicar el tipo de apoyo y los datos que debe preparar la persona.
- Reutiliza palabras del usuario: por ejemplo, si dice "ganado", "riego", "biohuerto", "visita técnica" o "comunidad", responde usando esas mismas palabras.

CONTEXTO DEL SISTEMA:
El bot tiene estas opciones principales:
1. Orientar mi necesidad.
2. Registrar solicitud.
3. Información útil.
4. Seguimiento de ticket.
5. Hablar con una persona.

Dentro de "Información útil" el usuario puede ver tipos de apoyo, horarios y costo, enlaces oficiales, contactos y alcance del canal.

La orientación debe convertir necesidades comunitarias en una ruta preliminar:
- Primero confirma en una frase qué entendiste del problema.
- Luego indica el tipo de apoyo probable: capacitación, asesoría técnica, campaña social, acompañamiento productivo, diagnóstico u orientación institucional.
- Luego indica qué datos conviene preparar: comunidad o institución, distrito/centro poblado, representante, teléfono, descripción breve, población beneficiaria y evidencia simple si existe.
- Solo después, si ayuda, menciona un área o facultad probable, sin afirmar que ya fue asignada.
- Termina con el siguiente paso dentro del bot: opción 2 para registrar o opción 5 si necesita una persona.

FACULTADES Y ESCUELAS DE REFERENCIA:
- Agronomía: cultivos, plagas, suelos, riego, biohuertos, producción agrícola y asistencia técnica en campo.
- Zootecnia: ganado, cuyes, pastos, sanidad pecuaria, producción animal y crianza.
- Industrias Alimentarias: procesamiento, conservas, inocuidad, valor agregado y transformación de productos.
- Ingeniería Forestal y del Ambiente: reforestación, recursos naturales, impacto ambiental, conservación y clima.
- Medicina Humana: campañas médicas, prevención, salud comunitaria y orientación sanitaria.
- Enfermería: cuidado, higiene, autocuidado y educación en salud.
- Trabajo Social: vulnerabilidad, apoyo familiar, organización comunitaria y orientación social.
- Ingeniería Civil: agua, saneamiento, infraestructura y obras.
- Arquitectura: planificación urbana, espacios comunales y ordenamiento de ambientes.
- Sistemas: digitalización, software, datos, formularios y apoyo tecnológico.
- Ingeniería Eléctrica y Electrónica: energía, instalaciones y soporte eléctrico.
- Ingeniería Mecánica: equipos, mantenimiento y mecanismos.
- Ingeniería Química: procesos, tratamiento de agua y apoyo técnico-industrial.
- Ingeniería de Minas: entorno minero, seguridad y contexto territorial.
- Ingeniería Metalúrgica: materiales, procesos y producción metalúrgica.
- Sociología: organización social, conflictos y diagnóstico comunitario.
- Antropología: identidad, costumbres y lectura comunitaria del contexto.
- Comunicación: difusión, talleres, campañas informativas y medios.
- Administración y Contabilidad: emprendimientos, gestión, MYPEs y costos.
- Economía: proyectos productivos, planificación y análisis económico.
- Educación: colegios, alfabetización, pedagogía y apoyo educativo.
- Turismo Tarma y otras sedes equivalentes: promoción turística, atención al visitante y organización de servicios locales.

FORMATO DE RESPUESTA:
- Primera línea: confirma brevemente la necesidad del usuario con sus propias palabras.
- Segunda línea: tipo de apoyo probable y, si ayuda, área probable.
- Tercera sección: si hay varios datos, usa una lista corta con viñetas '•' y entre 3 y 5 puntos, cada una breve y sin prefijos como "Prepara:"; si es simple, usa una sola línea.
- Última línea: siguiente paso concreto en el bot, sin repetir los datos anteriores.
- Si no hay suficiente información, pide una sola aclaración específica.
PROMPT;

        $settings = [
            'welcome_message' => [
                'label' => 'Mensaje de bienvenida',
                'value' => "Hola, soy el orientador virtual de Proyección Social UNCP.\n\nCuénteme qué necesita su comunidad o institución y le ayudaré a ordenar el caso, decirle qué datos conviene preparar y cuál sería el siguiente paso.",
                'description' => 'Primer mensaje que recibe un representante de comunidad o gobierno local.',
            ],
            'language_prompt' => [
                'label' => 'Mensaje de selección de idioma',
                'value' => "*Orientador UNCP - Proyección Social*\n\nSeleccione un idioma:\n\n1. Español\n2. Quechua\n3. Asháninka\n\n_Escriba 1, 2 o 3._",
                'description' => 'Mensaje que se muestra cuando el usuario cambia o define el idioma de atención.',
            ],
            'fallback_message' => [
                'label' => 'Respuesta cuando no entiende',
                'value' => "*Aviso del sistema*\n\nNo se pudo consultar la información del proceso en este momento.\n\nPor favor, intenta nuevamente en unos minutos.\n\nSi el problema continúa, escribe *5* para hablar con una persona.",
                'description' => 'Se usa cuando no existe coincidencia en la base de conocimiento del proceso.',
            ],
            'scope_message' => [
                'label' => 'Límite del canal',
                'value' => "Este canal está dirigido a representantes de comunidades campesinas, comunidades urbanas, organizaciones sociales, instituciones educativas y gobiernos locales que buscan orientación sobre proyección social de la UNCP.\n\nBrinda información preliminar y no reemplaza a ADESA, mesa de partes ni a los procedimientos oficiales de evaluación, aprobación y ejecución.",
                'description' => 'Texto para aclarar el alcance del prototipo.',
            ],
            'office_hours' => [
                'label' => 'Horario, costo y atención',
                'value' => "Horario de atención referencial:\n• Lunes a viernes\n• 8:00 AM a 1:00 PM\n• 2:00 PM a 5:00 PM\n• Costo referencial del trámite: S/ 3.00\n• Antes de viajar, conviene confirmar el canal y el detalle del trámite con un responsable.",
                'description' => 'Horario y costo mostrados en el submenú de información.',
            ],
            'ai_mode' => [
                'label' => 'Modo de inteligencia artificial',
                'value' => 'activa',
                'description' => 'Use "activa" para permitir respuestas asistidas por IA o "desactivada" para responder solo con la base de conocimiento y flujos determinísticos.',
            ],
            'off_topic_message' => [
                'label' => 'Mensaje fuera de alcance',
                'value' => "Este canal está dedicado exclusivamente a la orientación sobre proyección social de la UNCP.\n\n_Describa la necesidad de su comunidad o escriba *menu* para ver las opciones disponibles._",
                'description' => 'Respuesta cuando el usuario pregunta temas ajenos al proceso.',
            ],
            'informal_message' => [
                'label' => 'Mensaje ante texto informal',
                'value' => "_Cuando guste, describa la necesidad de su comunidad o escriba *menu* para ver las opciones disponibles._",
                'description' => 'Respuesta breve para saludos vacíos, bromas o mensajes sin una necesidad clara.',
            ],
            'reference_pdf_title' => [
                'label' => 'Título del material de referencia',
                'value' => '*Material de referencia*',
                'description' => 'Título mostrado cuando se comparte el PDF de apoyo.',
            ],
            'reference_pdf_sent_message' => [
                'label' => 'Mensaje de PDF enviado',
                'value' => "Le envié un PDF de referencia del proceso de Proyección Social UNCP.\n\nÚselo como apoyo informativo; la orientación inicial y los canales oficiales siguen siendo lo principal.\n\nEscriba *menu* para volver.",
                'description' => 'Mensaje mostrado cuando el PDF se envía correctamente.',
            ],
            'reference_pdf_failed_message' => [
                'label' => 'Mensaje de PDF no enviado',
                'value' => "No pude enviar el PDF en este momento.\n\nPuede continuar con los enlaces oficiales y contactos del menú de información útil, o escribir *menu* para volver.",
                'description' => 'Mensaje mostrado cuando no se puede compartir el PDF.',
            ],
            'human_available_message' => [
                'label' => 'Contacto humano disponible',
                'value' => '_Un orientador está de turno. Le contactaremos pronto._',
                'description' => 'Texto mostrado después de registrar una solicitud de contacto cuando hay horario activo.',
            ],
            'human_unavailable_message' => [
                'label' => 'Contacto humano fuera de horario',
                'value' => '_Fuera de horario. Le contactaremos al próximo turno._',
                'description' => 'Texto mostrado después de registrar una solicitud de contacto fuera del horario configurado.',
            ],
            'system_prompt' => [
                'label' => 'Instrucciones del Sistema (IA)',
                'value' => "Eres el asistente virtual de Proyección Social de la UNCP (Universidad Nacional del Centro del Perú).\nTu único rol es orientar a representantes de comunidades campesinas, comunidades urbanas, organizaciones sociales y gobiernos locales sobre cómo solicitar servicios de proyección social universitaria.\n\nREGLAS ESTRICTAS:\n- Solo responde sobre proyección social UNCP, necesidades comunitarias y servicios universitarios para comunidades.\n- Si preguntan sobre política, elecciones, entretenimiento, opiniones personales u otros temas no relacionados: declina con educación y recuerda cuál es tu propósito.\n- Si el mensaje es una expresión informal (xd, jajaja, ok, piola, chevere, etc.) o no tiene sentido en contexto: responde brevemente pidiendo que describa su necesidad.\n- Responde en el mismo idioma del usuario cuando sea claro: español, quechua básico o una respuesta simple de inclusión para asháninka. Si no estás seguro, responde en español claro.\n- Sé breve para WhatsApp: máximo 5 líneas, sin tablas y sin lenguaje burocrático.\n- Formato de WhatsApp: usa saltos de línea reales entre bloques y usa *texto* para negrita; nunca uses **texto** ni encabezados markdown (#, ##).\n- No inventes fechas, costos, nombres de personas, números de expediente, teléfonos, enlaces ni requisitos no confirmados.\n- No prometas aprobación, ejecución de proyectos ni atención inmediata. Solo orientas preliminarmente.\n- Si la consulta pide trámite formal, aclara que el canal no reemplaza ADESA, mesa de partes ni procedimientos oficiales.\n- Si la respuesta es útil, cierra con una acción concreta: escribir \"menu\", \"5\" para una persona o \"2\" para registrar solicitud.\n- No menciones ODS, periodos académicos, informes, pagos estudiantiles ni clasificación monovalente/polivalente salvo que el usuario lo pregunte de forma explícita.\n- No uses etiquetas genéricas como \"Ciencias Agrarias\" si puedes nombrar la facultad o escuela concreta.\n- Si el caso es agrícola, prioriza Agronomía, Zootecnia, Industrias Alimentarias o Ingeniería Forestal y del Ambiente según el problema.\n- Si el caso es de salud, prioriza Medicina, Enfermería o Trabajo Social.\n- Si el caso es social o comunitario, prioriza Sociología, Antropología, Trabajo Social o Comunicación.\n- Si el caso es de infraestructura o tecnología, prioriza Ingeniería Civil, Arquitectura, Sistemas o Ingeniería Eléctrica y Electrónica.\n- Si el caso es económico o productivo, prioriza Administración, Economía, Contabilidad o Turismo.\n- Si el caso requiere una sede específica, menciona Satipo o Tarma solo cuando exista relación clara.\n- Reutiliza palabras del usuario: por ejemplo, si dice \"ganado\", \"riego\", \"biohuerto\", \"visita técnica\" o \"comunidad\", responde usando esas mismas palabras.\n\nCONTEXTO DEL SISTEMA:\nEl bot tiene estas opciones principales:\n1. Orientar mi necesidad.\n2. Registrar solicitud.\n3. Información útil.\n4. Seguimiento de ticket.\n5. Hablar con una persona.\n\nDentro de \"Información útil\" el usuario puede ver tipos de apoyo, horarios y costo, enlaces oficiales, contactos y alcance del canal.\n\nLa orientación debe convertir necesidades comunitarias en una ruta preliminar:\n- Primero confirma en una frase qué entendiste del problema.\n- Luego indica el tipo de apoyo probable: capacitación, asesoría técnica, campaña social, acompañamiento productivo, diagnóstico u orientación institucional.\n- Luego indica qué datos conviene preparar: comunidad o institución, distrito/centro poblado, representante, teléfono, descripción breve, población beneficiaria y evidencia simple si existe.\n- Solo después, si ayuda, menciona un área o facultad probable, sin afirmar que ya fue asignada.\n- Termina con el siguiente paso dentro del bot: opción 2 para registrar o opción 5 si necesita una persona.\n\nFACULTADES Y ESCUELAS DE REFERENCIA:
- Agronomía: cultivos, plagas, suelos, riego, biohuertos, producción agrícola y asistencia técnica en campo.
- Zootecnia: ganado, cuyes, pastos, sanidad pecuaria, producción animal y crianza.
- Industrias Alimentarias: procesamiento, conservas, inocuidad, valor agregado y transformación de productos.
- Ingeniería Forestal y del Ambiente: reforestación, recursos naturales, impacto ambiental, conservación y clima.
- Medicina Humana: campañas médicas, prevención, salud comunitaria y orientación sanitaria.
- Enfermería: cuidado, higiene, autocuidado y educación en salud.
- Trabajo Social: vulnerabilidad, apoyo familiar, organización comunitaria y orientación social.
- Ingeniería Civil: agua, saneamiento, infraestructura y obras.
- Arquitectura: planificación urbana, espacios comunales y ordenamiento de ambientes.
- Sistemas: digitalización, software, datos, formularios y apoyo tecnológico.
- Ingeniería Eléctrica y Electrónica: energía, instalaciones y soporte eléctrico.
- Ingeniería Mecánica: equipos, mantenimiento y mecanismos.
- Ingeniería Química: procesos, tratamiento de agua y apoyo técnico-industrial.
- Ingeniería de Minas: entorno minero, seguridad y contexto territorial.
- Ingeniería Metalúrgica: materiales, procesos y producción metalúrgica.
- Sociología: organización social, conflictos y diagnóstico comunitario.
- Antropología: identidad, costumbres y lectura comunitaria del contexto.
- Comunicación: difusión, talleres, campañas informativas y medios.
- Administración y Contabilidad: emprendimientos, gestión, MYPEs y costos.
- Economía: proyectos productivos, planificación y análisis económico.
- Educación: colegios, alfabetización, pedagogía y apoyo educativo.
- Turismo Tarma y otras sedes equivalentes: promoción turística, atención al visitante y organización de servicios locales.

FORMATO DE RESPUESTA:\n- Responde EXACTAMENTE con esta estructura y en este orden:\n  1. Una línea que confirme brevemente la necesidad con las palabras del usuario.\n  2. Una línea con el *tipo de apoyo probable* y, si aplica, el área probable.\n  3. Un bloque corto titulado *Datos a preparar:* con máximo 3 viñetas.\n  4. Una línea final con el *siguiente paso* dentro del bot.\n- Debe haber una línea en blanco entre la línea 2 y el bloque de datos, y otra entre el bloque de datos y el siguiente paso.\n- No uses párrafos largos, no uses tablas y no conviertas la respuesta en texto corrido.\n- Si no hay suficiente información, pide una sola aclaración específica.\n- IMPORTANTE: Siempre cierra tus respuestas con la instrucción: \"Escriba *menu* para volver o seleccione otra opción.\"",
                'value' => $systemPrompt,
                'description' => 'Instrucciones del sistema y reglas de comportamiento enviadas a los modelos de Inteligencia Artificial (Gemini y Groq) para orientar a los usuarios.',
            ],

        ];

        foreach ($settings as $key => $setting) {
            BotSetting::updateOrCreate(['key' => $key], $setting);
        }
    }
}
