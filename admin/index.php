<?php require_once '../admin-auth.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Condominio Ejemplo</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <div class="admin-header-inner">
                <h1>Panel de Administración</h1>
                <div class="admin-user-info">
                    <span>Admin: <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="../admin-logout.php" class="btn btn-secondary btn-sm">Cerrar Sesión</a>
                </div>
            </div>
        </header>

        <main class="admin-main">
            <div class="admin-dashboard">
                <h2>Gestión del Sitio</h2>

                <div class="admin-cards">
                    <a href="candidatos.php" class="admin-card">
                        <div class="admin-card-icon">📋</div>
                        <h3>Gestionar Candidatos</h3>
                        <p>Agregar, editar o eliminar candidatos de administración</p>
                    </a>

                    <a href="preguntas.php" class="admin-card">
                        <div class="admin-card-icon">💬</div>
                        <h3>Gestionar Preguntas</h3>
                        <p>Responder preguntas de los vecinos y enviar notificaciones</p>
                    </a>

                    <a href="estadisticas.php" class="admin-card">
                        <div class="admin-card-icon">📊</div>
                        <h3>Estadísticas de Visitas</h3>
                        <p>Ver estadísticas anónimas de visitantes del sitio</p>
                    </a>

                    <a href="../index.php" class="admin-card" target="_blank">
                        <div class="admin-card-icon">🏠</div>
                        <h3>Ver Sitio Principal</h3>
                        <p>Acceder al sitio público sin contar visitas en estadísticas</p>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
