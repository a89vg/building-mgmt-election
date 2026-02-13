<?php require_once 'auth.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Detalles del Candidato - Condominio Ejemplo</title>
  <link rel="stylesheet" href="styles.css" />
  <script src="js/utils.js?v=<?= filemtime('js/utils.js') ?>" defer></script>
  <script src="js/candidato.js?v=<?= filemtime('js/candidato.js') ?>" defer></script>
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
        <a href="index.php#inicio">Inicio</a>
        <a href="index.php#candidatos">Candidatos</a>
        <a href="index.php#comparativa">Comparativa</a>
        <a href="index.php#documentos">Documentos</a>
        <a href="index.php#faq">Preguntas frecuentes</a>
        <a href="logout.php" class="nav-logout">Cerrar Sesión</a>
      </nav>
    </div>
  </header>

  <main>
    <section class="section">
      <div class="container">
        <a href="index.php#candidatos" class="btn btn-secondary mb-2">
          ← Volver a candidatos
        </a>

        <div class="info-disclaimer">
          <strong class="disclaimer-title">ℹ️ Información del candidato:</strong> La información mostrada a continuación ha sido proporcionada directamente por el candidato.
        </div>

        <article id="candidato-detalle">
          <!-- El contenido se genera dinámicamente desde script.js -->
        </article>

        <section id="preguntas-section">
          <h2>Preguntas de los vecinos</h2>

          <div id="preguntas-lista" class="preguntas-lista">
            <!-- Las preguntas se cargarán dinámicamente desde script.js -->
            <p class="small-muted">Aún no hay preguntas. ¡Sé el primero en hacer una!</p>
          </div>

          <div class="preguntas-form-container">
            <h3>Haz tu pregunta</h3>
            <p class="small-muted">
              Comparte cualquier pregunta o comentario específicos que tengas sobre este candidato. Tu retroalimentación nos ayuda a tener información más completa para todos los vecinos.
            </p>

            <form id="feedback-form" class="feedback-form">
              <div class="form-group">
                <label for="feedback-nombre">Tu nombre *</label>
                <input type="text" id="feedback-nombre" name="nombre" placeholder="Tu nombre" required>
              </div>

              <div class="form-group">
                <label for="feedback-correo">Tu correo electrónico (para contactarte) *</label>
                <input type="email" id="feedback-correo" name="correo" placeholder="tu@correo.com" required>
              </div>

              <div class="form-group">
                <label for="feedback-comentario">Tu pregunta o comentario *</label>
                <textarea id="feedback-comentario" name="comentario" placeholder="Por ejemplo: ¿Cuál es el proceso de facturación? ¿Incluyen reparaciones menores en el servicio? ¿Cómo es su atención al cliente?" rows="5" required></textarea>
              </div>

              <button type="submit" class="btn btn-primary">Enviar pregunta</button>
              <p id="feedback-message" class="feedback-message"></p>
            </form>
          </div>
        </section>
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
</body>
</html>
