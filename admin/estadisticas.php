<?php
require_once '../admin-auth.php';
require_once '../config/database.php';

// Configurar zona horaria local (Ciudad de México)
date_default_timezone_set('America/Mexico_City');

// Obtener estadísticas
$pdo = getDbConnection();

// Total de visitas únicas
$stmt = $pdo->query("SELECT COUNT(*) as total FROM site_visits");
$totalVisits = $stmt->fetch()['total'];

// Visitas únicas hoy (usando created_at)
$stmt = $pdo->query("SELECT COUNT(*) as total FROM site_visits WHERE DATE(created_at) = CURDATE()");
$visitsToday = $stmt->fetch()['total'];

// Visitas esta semana
$stmt = $pdo->query("SELECT COUNT(*) as total FROM site_visits WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$visitsThisWeek = $stmt->fetch()['total'];

// Visitas este mes
$stmt = $pdo->query("SELECT COUNT(*) as total FROM site_visits WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
$visitsThisMonth = $stmt->fetch()['total'];

// Visitas por día (últimos 30 días) - usando created_at
$stmt = $pdo->query("
    SELECT DATE(created_at) as visit_date, COUNT(*) as count
    FROM site_visits
    WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY visit_date DESC
");
$visitsByDay = $stmt->fetchAll();

// Visitas de hoy con hora exacta - usando created_at
$stmt = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%H:%i') as visit_time
    FROM site_visits
    WHERE DATE(created_at) = CURDATE()
    ORDER BY created_at DESC
");
$visitsToday_list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Visitas - Panel Admin</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 {
            font-size: 0.9rem;
            color: #666;
            margin: 0 0 0.5rem 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-card .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--accent, #2563eb);
            margin: 0;
        }
        .table-container {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 2rem 0;
        }
        .table-container p {
            color: #374151;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }
        .stats-table th,
        .stats-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            color: #1f2937;
        }
        .stats-table th {
            background: #f9fafb;
            font-weight: 600;
            color: #111827;
        }
        .stats-table td {
            color: #374151;
        }
        .stats-table tr:hover {
            background: #f9fafb;
        }
        .table-container h3 {
            color: #111827;
            margin-top: 0;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 1.5rem;
            color: var(--accent, #2563eb);
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .admin-main h2 {
            color: #111827;
        }
        .admin-main p {
            color: #374151;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <div class="admin-header-inner">
                <h1>Estadísticas de Visitas</h1>
                <div class="admin-user-info">
                    <span>Admin: <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="../admin-logout.php" class="btn btn-secondary btn-sm">Cerrar Sesión</a>
                </div>
            </div>
        </header>

        <main class="admin-main">
            <a href="index.php" class="back-link">← Volver al Panel</a>

            <h2>Resumen General</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total de Visitas</h3>
                    <p class="stat-value"><?php echo number_format($totalVisits); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Visitas Hoy</h3>
                    <p class="stat-value"><?php echo number_format($visitsToday); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Últimos 7 días</h3>
                    <p class="stat-value"><?php echo number_format($visitsThisWeek); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Últimos 30 días</h3>
                    <p class="stat-value"><?php echo number_format($visitsThisMonth); ?></p>
                </div>
            </div>

            <div class="table-container">
                <h3>Visitas por Día (Últimos 30 días)</h3>
                <?php if (count($visitsByDay) > 0): ?>
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Visitas Únicas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($visitsByDay as $row): ?>
                        <tr>
                            <td><?php echo date('d/m/Y (l)', strtotime($row['visit_date'])); ?></td>
                            <td><?php echo number_format($row['count']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>No hay datos disponibles para este período.</p>
                <?php endif; ?>
            </div>

            <?php if (count($visitsToday_list) > 0): ?>
            <div class="table-container">
                <h3>Visitas de Hoy (<?php echo count($visitsToday_list); ?> visitas)</h3>
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($visitsToday_list as $index => $row): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo $row['visit_time']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <div class="info-box" style="margin-top: 2rem;">
                <strong>Nota sobre privacidad:</strong>
                <p>
                    Todas las visitas son registradas de forma completamente anónima. El sistema no almacena
                    direcciones IP ni información personal identificable. Solo se registra un hash anónimo
                    que cambia cada día, lo que permite contar visitantes únicos sin comprometer su privacidad.
                </p>
            </div>
        </main>
    </div>
</body>
</html>
