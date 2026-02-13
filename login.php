<?php
require_once __DIR__ . '/auth-functions.php';

session_start();

/**
 * Validate that a redirect target is a safe relative path.
 */
function safeRedirect($target) {
    // Only allow relative paths (no protocol, no //)
    if (empty($target) || preg_match('#^https?://#i', $target) || strpos($target, '//') === 0) {
        return 'index.php';
    }
    return $target;
}

// If already authenticated, redirect to home
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    $redirect = safeRedirect(isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php');
    header('Location: ' . $redirect);
    exit;
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (authenticate($username, $password)) {
        $redirect = safeRedirect(isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php');
        header('Location: ' . $redirect);
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Iniciar Sesión - Condominio Ejemplo</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    .login-box {
      background: var(--bg-alt);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      padding: 2.5rem;
      max-width: 420px;
      width: 100%;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    .login-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .login-logo {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--accent);
      margin-bottom: 0.5rem;
    }

    .login-subtitle {
      font-size: 0.95rem;
      color: var(--text-muted);
    }

    .login-form .form-group {
      margin-bottom: 1.5rem;
    }

    .login-form label {
      display: block;
      margin-bottom: 0.5rem;
      color: var(--text-main);
      font-weight: 500;
      font-size: 0.95rem;
    }

    .login-form input {
      width: 100%;
      padding: 0.75rem 1rem;
      background: var(--bg);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 6px;
      color: var(--text-main);
      font-size: 1rem;
      transition: border-color 0.2s;
    }

    .login-form input:focus {
      outline: none;
      border-color: var(--accent);
    }

    .login-error {
      background: rgba(239, 68, 68, 0.15);
      color: #fca5a5;
      padding: 0.75rem 1rem;
      border-radius: 6px;
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
      border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .login-btn {
      width: 100%;
      padding: 0.875rem;
      background: var(--accent);
      color: #0a0a0a;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: opacity 0.2s;
    }

    .login-btn:hover {
      opacity: 0.9;
    }

    .login-info {
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      text-align: center;
      font-size: 0.85rem;
      color: var(--text-muted);
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-box">
      <div class="login-header">
        <div class="login-logo">Condominio Ejemplo</div>
        <div class="login-subtitle">Elección de Administración</div>
      </div>

      <?php if ($error): ?>
        <div class="login-error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form method="POST" class="login-form">
        <div class="form-group">
          <label for="username">Usuario</label>
          <input
            type="text"
            id="username"
            name="username"
            required
            autofocus
            autocomplete="username"
          />
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <input
            type="password"
            id="password"
            name="password"
            required
            autocomplete="current-password"
          />
        </div>

        <button type="submit" class="login-btn">Iniciar Sesión</button>
      </form>

      <div class="login-info">
        <p>Acceso exclusivo para residentes del Condominio Ejemplo</p>
        <p style="margin-top: 0.5rem;">Si no tienes las credenciales, contacta a <a href="mailto:contacto@example.com" style="color: var(--accent); text-decoration: none;">contacto@example.com</a></p>
      </div>
    </div>
  </div>
</body>
</html>
