<?php
/**
 * Admin Login Page
 * Handles authentication for the admin panel
 */

session_start();

// If already logged in, redirect to admin panel
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin/index.php');
    exit;
}

// Load admin configuration
require_once __DIR__ . '/config/admin.php';

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (verifyAdminCredentials($username, $password)) {
        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        // Set session variables
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;

        // Redirect to admin panel
        header('Location: admin/index.php');
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Condominio Ejemplo</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .admin-login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .admin-login-box {
            background: var(--bg-alt);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            padding: 3rem;
            max-width: 400px;
            width: 100%;
        }

        .admin-login-box h1 {
            margin-bottom: 0.5rem;
            color: var(--accent);
        }

        .admin-login-box p {
            margin-bottom: 2rem;
            color: var(--text-muted);
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
        }

        .admin-login-box input[type="text"],
        .admin-login-box input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem;
            background: var(--bg);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            color: var(--text-main);
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .admin-login-box input[type="text"]:focus,
        .admin-login-box input[type="password"]:focus {
            outline: none;
            border-color: var(--accent);
        }

        .admin-login-box .form-group {
            margin-bottom: 1.5rem;
        }

        .admin-login-box label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-main);
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-login-box">
            <h1>Panel de Administración</h1>
            <p>Condominio Ejemplo</p>

            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="username">Usuario</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Iniciar Sesión</button>
            </form>

            <p class="small-muted" style="margin-top: 2rem; text-align: center;">
                <a href="index.php" style="color: var(--accent);">← Volver al sitio público</a>
            </p>
        </div>
    </div>
</body>
</html>
