<?php
require_once 'auth.php';
require_once 'config/track_visit.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Candidatos de Administración - Condominio</title>
  <link rel="stylesheet" href="styles.css" />
  <script src="js/utils.js?v=<?= filemtime('js/utils.js') ?>" defer></script>
  <script src="js/index.js?v=<?= filemtime('js/index.js') ?>" defer></script>
  <script src="js/menu.js?v=<?= filemtime('js/menu.js') ?>" defer></script>
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <div class="logo">
        <span class="logo-main">Condominio Ejemplo</span>
        <span class="logo-sub">Elección de Administración</span>
      </div>
      <button class="menu-toggle" aria-label="Abrir menú" aria-expanded="false">
        <span class="menu-icon"></span>
      </button>
      <nav class="main-nav" aria-label="Navegación principal">
        <a href="#inicio">Inicio</a>
        <a href="#candidatos">Candidatos</a>
        <a href="#comparativa">Comparativa</a>
        <a href="#documentos">Documentos</a>
        <a href="#faq">Preguntas frecuentes</a>
        <a href="logout.php" class="nav-logout">Cerrar Sesión</a>
      </nav>
    </div>
  </header>

  <main>
    <!-- INICIO / HERO -->
    <section id="inicio" class="section hero-section">
      <div class="container hero-grid">
        <div>
          <h1>Opciones de administración para el condominio</h1>
          <p class="lead">
            Este sitio recopila, de forma neutral y transparente, la información enviada por distintos
            candidatos de administración (empresas o personas físicas) invitados por los vecinos.
          </p>
          <p>
            El objetivo es que todos los condóminos tengan acceso a la misma información y puedan tomar
            una decisión informada en la próxima asamblea de elección de administración.
          </p>
          <div class="hero-actions">
            <a href="#candidatos" class="btn btn-primary">Ver candidatos</a>
            <a href="#comparativa" class="btn btn-secondary">Ver comparativa</a>
          </div>
          <div class="info-box">
            <strong>Nota importante:</strong>
            <p>
              Este sitio solo presenta información. La decisión sobre la nueva administración se tomará
              exclusivamente en la asamblea, conforme a la ley y a la voluntad de los condóminos.
            </p>
          </div>
        </div>
        <div class="hero-card">
          <h2>Resumen rápido del proceso</h2>
          <ol>
            <li>Invitación a varios candidatos de administración.</li>
            <li>Recepción de propuestas, entrevistas y documentos.</li>
            <li>Publicación aquí de la información recibida.</li>
            <li>Revisión por parte de los vecinos.</li>
          </ol>
          <p class="small-muted">
            Si notas algún error en la información, por favor contacta a:
            <a href="mailto:contacto@example.com" style="color: var(--accent); text-decoration: none; font-weight: 500;">contacto@example.com</a>
          </p>
        </div>
      </div>
    </section>

    <!-- LECTURA FÁCIL: DERECHOS Y OBLIGACIONES -->
    <section id="derechos-obligaciones" class="section section-alt">
      <div class="container">
        <div class="section-header">
          <h2>Derechos y Obligaciones</h2>
          <p>
            Información completa sobre las obligaciones del Administrador, obligaciones del Comité de Vigilancia,
            y los derechos y limitaciones de los Condóminos, según la Ley de Propiedad en Condominio de la Ciudad de México.
          </p>
        </div>

        <!-- Acordeón de Derechos y Obligaciones -->
        <div class="accordion-container">
          <!-- OBLIGACIONES DEL ADMINISTRADOR -->
          <div class="accordion-item">
            <div class="accordion-preview">
              <h3>Obligaciones del Administrador <span class="small-muted">(Art. 33 Fracc. VIII, Art. 42 y 43)</span></h3>
              <p class="small-muted accordion-intro">
                El administrador durará en su encargo un año, y siempre que a consideración del Comité de Vigilancia
                haya cumplido en sus términos el contrato, podrá ser reelecto solo por dos periodos más, renovando
                el contrato y fianza correspondiente.
              </p>

              <p class="small-muted">
                Sus obligaciones son las siguientes:
              </p>

              <ul class="mini-list">
                <li>Llevar un libro de actas de asamblea de condóminos, debidamente autorizado por la Procuraduría.</li>
                <li>Cuidar y vigilar los bienes del condominio y los servicios comunes, así como promover la integración, organización y desarrollo de la comunidad.</li>
                <li>Recabar y conservar los libros y documentación relacionada con el condominio, los cuales podrán ser consultados por condóminos al corriente en sus cuotas.</li>
              </ul>

              <button class="accordion-toggle">
                <span class="toggle-text">Ver todas las obligaciones</span>
                <svg class="accordion-icon" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
              </button>
            </div>

            <div class="accordion-content">
            <ul class="mini-list">
              <li>Realizar todos los actos de administración y conservación que el condominio requiera en sus áreas y bienes de uso común.</li>
              <li>Realizar las obras necesarias para mantener el condominio en buen estado de seguridad, estabilidad y conservación, obteniendo las autorizaciones correspondientes.</li>
              <li>Difundir y ejecutar los acuerdos de la Asamblea General, salvo que ésta designe otras personas.</li>
              <li>Recaudar de los condóminos las cuotas ordinarias y extraordinarias.</li>
              <li>Efectuar los gastos de mantenimiento y administración del condominio con cargo al fondo correspondiente.</li>
              <li>Otorgar un recibo por cualquier pago que reciba.</li>
              <li>Entregar mensualmente a cada condómino un estado de cuenta del condominio con visto bueno del Comité de Vigilancia, recabando constancia de quien lo reciba, que incluya:
                <ul class="accordion-sublist">
                  <li>Relación detallada de ingresos y egresos del mes anterior</li>
                  <li>Monto de las aportaciones y cuotas pendientes, con tratamiento adecuado de datos personales</li>
                  <li>Saldo de cuentas bancarias, recursos e inversiones, con mención de intereses</li>
                  <li>Relación detallada de cuotas por pagar a proveedores de bienes y servicios</li>
                  <li>Relación detallada de morosos y los montos de su deuda</li>
                </ul>
              </li>
              <li>Cuidar el cumplimiento del Reglamento Interno y de la escritura constitutiva.</li>
              <li>Cumplir, cuidar y exigir el cumplimiento de las disposiciones de la Ley y Reglamentos, solicitando en su caso apoyo de la autoridad.</li>
              <li>Tendrá facultades para pleitos, cobranzas y actos de administración de bienes respecto a los bienes comunes del condominio.</li>
              <li>Cumplir con las disposiciones de la Ley de Protección Civil y su Reglamento.</li>
              <li>Iniciar procedimientos administrativos o judiciales contra quienes incumplan sus obligaciones o incurran en violaciones a la normatividad.</li>
              <li>Impulsar y promover al menos una vez cada seis meses, en coordinación con la Procuraduría Social y Ambiental, jornadas de difusión sobre cultura condominal y cuidado del medio ambiente.</li>
              <li>Fomentar entre los condóminos, poseedores y habitantes el cumplimiento de la normatividad aplicable.</li>
              <li>Gestionar ante las Delegaciones la aplicación de recursos y servicios que correspondan.</li>
              <li>Emitir bajo su responsabilidad, de acuerdo con la contabilidad del condominio, las constancias de no adeudo por cuotas ordinarias y extraordinarias.</li>
              <li>Resolver controversias derivadas de actos de molestia entre condóminos, poseedores o habitantes para mantener la paz y tranquilidad.</li>
              <li>Tener la documentación necesaria disponible en cualquier momento, en caso de que la Asamblea General, Comité de Vigilancia, cualquier condómino o autoridad la solicite.</li>
              <li>Registrarse ante la Procuraduría como Administrador.</li>
              <li>Ser corresponsable en los servicios que contrate, según el Código Civil aplicable.</li>
              <li>Podrá revocarse el mandato al administrador a petición de al menos el 20% de los condóminos por incumplimiento de cualquiera de estas obligaciones, lo cual deberá ser ratificado por la asamblea.</li>
            </ul>
            </div>
          </div>

          <!-- OBLIGACIONES DEL COMITÉ DE VIGILANCIA -->
          <div class="accordion-item">
            <div class="accordion-preview">
              <h3>Obligaciones del Comité de Vigilancia <span class="small-muted">(Art. 33 Fracc. VIII, Art. 48 y 49)</span></h3>
              <p class="small-muted accordion-intro">
                El nombramiento de los miembros del Comité de Vigilancia será por un año, o hasta que la Asamblea General
                los remueva de su cargo, desempeñándose en forma honorífica. Podrán reelegirse sólo dos de sus miembros
                por un período consecutivo.
              </p>

              <p class="small-muted">
                Sus obligaciones son las siguientes:
              </p>

              <ul class="mini-list">
                <li>Cerciorarse de que el Administrador cumpla con los acuerdos de la Asamblea General.</li>
                <li>Tener acceso y revisar periódicamente todos los documentos, comprobantes, contabilidad, libros de actas, estados de cuenta y toda la documentación relacionada con el condominio.</li>
                <li>Supervisar que el Administrador lleve a cabo el cumplimiento de sus funciones.</li>
              </ul>

              <button class="accordion-toggle">
                <span class="toggle-text">Ver todas las obligaciones</span>
                <svg class="accordion-icon" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
              </button>
            </div>

            <div class="accordion-content">
            <ul class="mini-list">
              <li>Contratar y dar por terminados los servicios profesionales de administración.</li>
              <li>Dar su conformidad para la realización de obras necesarias para mantener el condominio en buen estado de seguridad, estabilidad y conservación, obteniendo las autorizaciones correspondientes.</li>
              <li>Verificar y emitir dictamen de los estados de cuenta que debe rendir el administrador ante la Asamblea General, señalando sus omisiones, errores o irregularidades.</li>
              <li>Constatar y supervisar la inversión de fondos.</li>
              <li>Dar cuenta a la Asamblea General de sus observaciones sobre la administración del condominio, y en caso de haber encontrado omisión, error o irregularidad en perjuicio del condominio, deberá hacerlo del conocimiento de la Procuraduría o autoridad competente.</li>
              <li>Coadyuvar con el administrador en observaciones a condóminos, poseedores o habitantes sobre el cumplimiento de sus obligaciones.</li>
              <li>Convocar a Asamblea General cuando, a requerimiento por escrito, el Administrador no lo haga dentro de los tres días siguientes a la petición.</li>
              <li>Cubrir las funciones del Administrador en los casos previstos en el párrafo segundo de la Fracción XVII del Artículo 43 de la Ley.</li>
              <li>Los miembros del Comité de Vigilancia serán responsables en forma solidaria entre ellos y subsidiaria respecto al Administrador, de los daños y perjuicios ocasionados a los condóminos por las omisiones, errores o irregularidades del administrador que habiéndolas conocido no hayan notificado oportunamente a la Asamblea General.</li>
              <li>Rendir un informe anual de actividades.</li>
            </ul>
            </div>
          </div>

          <!-- DERECHOS Y LIMITACIONES DE LOS CONDÓMINOS -->
          <div class="accordion-item">
            <div class="accordion-preview">
              <h3>Derechos de los Condóminos y sus Limitaciones <span class="small-muted">(Art. 16 y Art. 21)</span></h3>

              <h4>Derechos</h4>
              <ul class="mini-list">
                <li>Contar con el respeto de los demás condóminos sobre su unidad de propiedad privativa.</li>
                <li>Participar con voz y voto en las asambleas generales de condóminos, de conformidad con la normatividad aplicable.</li>
                <li>Usar y disfrutar en igualdad de circunstancias y en forma ordenada, las áreas y bienes de uso común del condominio, sin restringir el derecho de los demás.</li>
              </ul>

              <h4>Limitaciones o Prohibiciones</h4>
              <ul class="mini-list">
                <li>Destinar su unidad privativa a usos distintos al fin establecido en la Escritura Constitutiva.</li>
                <li>Realizar acto alguno que afecte la tranquilidad de los demás condóminos y/o poseedores, que comprometa la estabilidad, seguridad, salubridad y comodidad del condominio.</li>
                <li>Efectuar todo acto que impida o haga ineficaz la operación de los servicios comunes e instalaciones generales, o que ponga en riesgo la seguridad o tranquilidad de los condóminos.</li>
              </ul>

              <button class="accordion-toggle">
                <span class="toggle-text">Ver todos los derechos y limitaciones</span>
                <svg class="accordion-icon" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
              </button>
            </div>

            <div class="accordion-content">
            <h4>Derechos</h4>
            <ul class="mini-list">
              <li>Formar parte de la Administración del condominio en calidad de Administrador condómino, con la misma retribución y responsabilidad del administrador profesional, excepto la exhibición de la fianza.</li>
              <li>Solicitar a la Administración información respecto al estado que guardan los fondos de mantenimiento, administración y de reserva.</li>
              <li>Acudir ante la Procuraduría para solicitar su intervención por violaciones a la Ley, su Reglamento, al Reglamento Interno, por parte de condóminos, poseedores y/o autoridades al interior del condominio.</li>
              <li>Denunciar ante las autoridades competentes, hechos posiblemente constitutivos de algún delito, en agravio del condominio o conjunto condominal.</li>
              <li>Realizar las obras y reparaciones necesarias al interior de su unidad de propiedad privativa, quedando prohibida toda modificación que afecte la estructura, muros de carga u otros elementos esenciales del edificio, de conformidad con las leyes y reglamentos correspondientes.</li>
              <li>Formar parte de los comités de medio ambiente; educación y cultura; seguridad y protección civil; activación física y deporte; y de mediación.</li>
            </ul>

            <h4>Limitaciones o Prohibiciones</h4>

            <ul class="mini-list">
              <li>Realizar obras y reparaciones en horario nocturno, salvo los casos de fuerza mayor.</li>
              <li>Decorar, pintar o realizar obras que modifiquen la fachada o las paredes exteriores desentonando con el condominio o que contravengan lo establecido por la Asamblea General.</li>
              <li>Derribar, transplantar, podar, talar u ocasionar la muerte de árboles, cambiar el uso o naturaleza de las áreas verdes, ni aun por acuerdo de la Asamblea General.</li>
              <li>Delimitar con cualquier tipo de material, pintar señalamientos de exclusividad, techar o realizar construcciones en áreas de estacionamiento de uso común, excepto las áreas verdes que sí podrán delimitarse para su protección.</li>
              <li>Hacer uso de los estacionamientos y áreas de uso común para fines distintos.</li>
              <li>Poseer animales que por su número, tamaño o naturaleza afecten las condiciones de seguridad, salubridad o comodidad del condominio. Los condóminos serán absolutamente responsables de las acciones de los animales que introduzcan.</li>
              <li>Ocupar otro cajón de estacionamiento distinto al asignado.</li>
              <li>Incurrir en conductas discriminatorias, según la Ley para Prevenir y Eliminar la Discriminación de la Ciudad de México, en contra de condóminos, poseedores, habitantes, visitantes, personas trabajadoras, proveedores o cualquier otra persona en el condominio.</li>
            </ul>
            </div>
          </div>
        </div>

        <div class="legal-foundation-box">
          <p class="small-muted">
            <strong>Fundamentos Legales:</strong>
          </p>
          <ul class="small-muted">
            <li><strong>Obligaciones de la Administración:</strong> Artículo 33 Fracción VIII, Artículo 42 y Artículo 43</li>
            <li><strong>Obligaciones del Comité de Vigilancia:</strong> Artículo 33 Fracción VIII, Artículo 48 y Artículo 49</li>
            <li><strong>Derechos de los Condóminos:</strong> Artículo 16</li>
            <li><strong>Limitaciones y Prohibiciones:</strong> Artículo 21</li>
          </ul>
          <p class="small-muted">
            Todos los artículos citados corresponden a la <strong>Ley de Propiedad en Condominio de Inmuebles para el Distrito Federal</strong>.
            Para conocer el texto completo y las especificaciones detalladas, consulta la ley disponible en la sección de documentos útiles.
          </p>
        </div>
      </div>
    </section>

    <!-- CANDIDATOS -->
    <section id="candidatos" class="section">
      <div class="container">
        <div class="section-header">
          <h2>Candidatos participantes</h2>
          <p>
            A continuación se muestran los candidatos (empresas o personas físicas) que han enviado propuestas para administrar
            nuestro condominio. Haz clic en cualquiera de ellos para ver los detalles completos.
          </p>
        </div>

        <ul id="candidatos-list" class="candidatos-links-list" aria-live="polite">
          <!-- Los enlaces a candidatos se generan automáticamente desde index.js -->
        </ul>
      </div>
    </section>

    <!-- COMPARATIVA -->
    <section id="comparativa" class="section section-alt">
      <div class="container">
        <div class="section-header">
          <h2>Tabla comparativa</h2>
          <p>
            Esta tabla resume, de manera objetiva, algunos puntos clave de cada candidato, para facilitar la
            comparación entre opciones. Para más detalle, revisa la ficha individual de cada uno.
          </p>
        </div>

        <div class="table-wrapper">
          <table id="comparativa-table">
            <!-- La tabla se genera automáticamente desde index.js -->
          </table>
        </div>

      </div>
    </section>

    <!-- DOCUMENTOS -->
    <section id="documentos" class="section">
      <div class="container">
        <div class="section-header">
          <h2>Documentos útiles</h2>
          <p>
            En esta sección se incluyen formatos de documentos para la asamblea de elección de
            la nueva administración.
          </p>
        </div>
        <div class="grid-3 docs-grid">
          <div class="card">
            <h3>Formato de carta poder</h3>
            <p>
              Documento para que un condómino pueda designar a otra persona de confianza para que lo
              represente en la asamblea y ejerza su voto.
            </p>
            <ul class="mini-list">
              <li>Incluye datos del otorgante y del apoderado.</li>
              <li>Sirve para asambleas ordinarias y extraordinarias.</li>
              <li>Se debe firmar autógrafamente.</li>
            </ul>
            <a href="docs/carta-poder-ejemplo.docx" class="btn btn-ghost">
              Descargar formato de carta poder
            </a>
            <a href="manual-carta-poder.html" class="btn btn-ghost" style="display: inline-block; margin-top: 0.8rem;">
              Ver manual de llenado →
            </a>
          </div>
          <div class="card">
            <h3>Ley de Propiedad en Condominio</h3>
            <p>
              Marco legal que rige el funcionamiento de los condominios en la Ciudad de México,
              incluyendo derechos y obligaciones de los condóminos, del administrador y del comité de vigilancia.
            </p>
            <ul class="mini-list">
              <li>Normativa obligatoria para todos los condominios.</li>
              <li>Define funciones del administrador y asambleas.</li>
              <li>Establece derechos y obligaciones de condóminos.</li>
            </ul>
            <a href="docs/ley-propiedad-condominio.docx" class="btn btn-ghost">
              Descargar Ley de Propiedad en Condominio
            </a>
          </div>
          <div class="card">
            <h3>Reglamento de la Ley de Propiedad en Condominio</h3>
            <p>
              Reglamento que desarrolla y complementa la Ley de Propiedad en Condominio,
              estableciendo procedimientos específicos y disposiciones operativas.
            </p>
            <ul class="mini-list">
              <li>Procedimientos detallados de operación.</li>
              <li>Especificaciones para asambleas y actas.</li>
              <li>Normas de administración y mantenimiento.</li>
            </ul>
            <a href="docs/reglamento-ley-propiedad-condominio.docx" class="btn btn-ghost">
              Descargar Reglamento de la Ley
            </a>
          </div>
          <div class="card">
            <h3>Ley de la Procuraduría Social</h3>
            <p>
              Ley de la Procuraduía Social que establece las facultades generales de la PROSOC
            </p>
            <ul class="mini-list">
              <li>Normas de registro y administración de condominios.</li>
              <li>Protección de derechos de los condominios.</li>
              <li>Procedimientos ante PROSOC.</li>
            </ul>
            <a href="docs/ley-prosoc.docx" class="btn btn-ghost">
              Descargar Ley de PROSOC
            </a>
          </div>
          <div class="card">
            <h3>Reglamento de la Ley de PROSOC</h3>
            <p>
              Reglamento de la Ley de la Procuraduría Social que detalla los procedimientos,
              requisitos y normas específicas para la aplicación de la ley.
            </p>
            <ul class="mini-list">
              <li>Procedimientos administrativos detallados.</li>
              <li>Requisitos para registro de administradores.</li>
              <li>Normas operativas y de supervisión.</li>
            </ul>
            <a href="docs/reglamento-ley-prosoc.docx" class="btn btn-ghost">
              Descargar Reglamento de PROSOC
            </a>
          </div>
        </div>
        <p class="small-muted" style="margin-top: 2rem; text-align: center;">
          Las leyes y reglamentos aquí publicados se obtuvieron del sitio oficial de la
          <strong>Suprema Corte de Justicia de la Nación</strong>.
        </p>
      </div>
    </section>

    <!-- PREGUNTAS FRECUENTES -->
    <section id="faq" class="section section-alt">
      <div class="container">
        <div class="section-header">
          <h2>Preguntas frecuentes</h2>
        </div>

        <div class="faq-list">
          <details>
            <summary>¿Quién creó este sitio y con qué objetivo?</summary>
            <p>
              Según lo acordado en la asamblea, un vecino del condominio
              se comprometió a recopilar la información de distintos candidatos para la elección de una nueva administración.
              Este sitio fue creado utilizando recursos propios con el fin de presentar la información recopilada de forma ordenada y transparente,
              para que todos los vecinos puedan tomar una decisión informada en la asamblea.
            </p>
          </details>

          <details>
            <summary>¿Puedo proponer que se invite a otro candidato de administración?</summary>
            <p>
              Sí. Puedes sugerirlo directamente enviando los datos del candidato a <a href="mailto:contacto@example.com" style="color: var(--accent); text-decoration: none; font-weight: 500;">contacto@example.com</a>.
              Se realizará la misma verificación para los nuevos candidatos que para los candidatos ya contactados actualmente.
            </p>
          </details>

          <details>
            <summary>¿Qué pasa si no puedo asistir a la asamblea?</summary>
            <p>
              Puedes usar una carta poder para designar a un representante que asista y vote en tu nombre.
              Revisa el formato disponible en la sección de documentos. También puedes contactarme personalmente
              si necesitas que te proporcione el formato de forma física.
            </p>
          </details>

          <details>
            <summary>¿Puedo obtener la información de manera física?</summary>
            <p>
              Sí. Si prefieres revisar la información impresa o necesitas copias físicas de los documentos,
              con gusto te las puedo proporcionar. Solo contáctame al correo
              <a href="mailto:contacto@example.com" style="color: var(--accent); text-decoration: none; font-weight: 500;">contacto@example.com</a>
              o por teléfono al <span style="color: var(--accent); font-weight: 500;">[teléfono de contacto]</span> (WhatsApp)
              y coordinamos la entrega.
            </p>
          </details>
        </div>
      </div>
    </section>

    <!-- CONTACTO -->
    <section id="contacto" class="section">
      <div class="container">
        <div class="section-header">
          <h2>¿Dudas o sugerencias?</h2>
          <p>
            Vecino, si tienes dudas, información adicional que compartir, sugerencias de mejora para este sitio, o quieres proponer otro candidato de administración,
            no dudes en contactarme.
          </p>
        </div>

        <div class="contact-card">
          <p class="contact-label">Correo de contacto:</p>
          <a href="mailto:contacto@example.com" class="contact-email">
            contacto@example.com
          </a>
          <p class="contact-note">
            Responderé a la brevedad posible para resolver tus dudas o procesar tus sugerencias.
          </p>
        </div>
      </div>
    </section>

  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <p>
        Sitio informativo para apoyar el proceso de elección de administración del condominio.
      </p>
      <p class="small-muted">
        La información de los candidatos es responsabilidad de cada proveedor. La decisión final corresponde
        únicamente a la asamblea de condóminos.
      </p>
      <p class="small-muted">
        Sitio creado por [Nombre del vecino], [Departamento]
      </p>
    </div>
  </footer>

  <!-- Modal de Bienvenida -->
  <div id="welcome-modal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Bienvenido</h2>
      </div>
      <div class="modal-body">
        <p>Según lo acordado en asamblea, un vecino del condominio se comprometió a
          recopilar la información de distintos candidatos para la elección de una nueva administración en la asamblea respectiva, para lo cual
          se creó el correo electrónico <a href="mailto:contacto@example.com" style="color: var(--accent); text-decoration: none; font-weight: 500;">contacto@example.com</a>.
          Así mismo, se fueron explorando algunas opciones identificadas y otras que fueron remitidas con anterioridad a la
          creación de este correo.
        </p>

        <p>Este sitio fue creado utilizando recursos propios, con el objetivo de que todos los vecinos puedan tomar una decisión informada
          en la próxima asamblea de elección de administración.</p>

        <p>La información de cada candidato va a ser actualizada en cuanto nos sea proporcionada, por lo que sería importante que revises el 
          sitio con frecuencia.</p>
      </div>
      <div class="modal-footer">
        <button id="close-welcome-modal" class="btn btn-primary">OK</button>
      </div>
    </div>
  </div>
</body>
</html>
