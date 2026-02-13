<?php
require_once '../admin-auth.php';
require_once __DIR__ . '/../config/database.php';

// Load questions and candidatos from database
$preguntas = [];
$candidatoNames = [];
$totalPreguntas = 0;
$sinResponder = 0;

try {
    $pdo = getDbConnection();

    // Get all candidatos (for the name mapping)
    $stmt = $pdo->query("SELECT id, nombre FROM candidatos");
    $candidatos = $stmt->fetchAll();

    foreach ($candidatos as $candidato) {
        $candidatoNames[$candidato['id']] = $candidato['nombre'];
    }

    // Get all preguntas
    $stmt = $pdo->query("SELECT * FROM preguntas ORDER BY fecha DESC");
    $preguntasDb = $stmt->fetchAll();

    // Group preguntas by candidato_id
    foreach ($preguntasDb as $pregunta) {
        $candidatoId = $pregunta['candidato_id'];

        if (!isset($preguntas[$candidatoId])) {
            $preguntas[$candidatoId] = [];
        }

        // Map database fields to expected format
        $preguntas[$candidatoId][] = [
            'id' => $pregunta['id'],
            'fecha' => $pregunta['fecha'],
            'nombre' => $pregunta['vecino'],
            'correo' => $pregunta['correo'],
            'comentario' => $pregunta['pregunta'],
            'respuesta' => $pregunta['respuesta']
        ];

        $totalPreguntas++;
        if (empty($pregunta['respuesta'])) {
            $sinResponder++;
        }
    }

} catch (PDOException $e) {
    error_log('Error loading preguntas from DB: ' . $e->getMessage());
    // Keep empty arrays
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Preguntas - Admin</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <div class="admin-header-inner">
                <h1>Gestionar Preguntas</h1>
                <div class="admin-user-info">
                    <span>Admin: <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="../admin-logout.php" class="btn btn-secondary btn-sm">Cerrar Sesión</a>
                </div>
            </div>
        </header>

        <main class="admin-main">
            <div class="admin-breadcrumb">
                <a href="index.php">← Volver al Dashboard</a>
            </div>

            <div id="alert-container"></div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                <div class="card">
                    <h4>Total de Preguntas</h4>
                    <p style="font-size: 2rem; font-weight: bold; color: var(--accent);"><?php echo $totalPreguntas; ?></p>
                </div>
                <div class="card">
                    <h4>Sin Responder</h4>
                    <p style="font-size: 2rem; font-weight: bold; color: #fbbf24;"><?php echo $sinResponder; ?></p>
                </div>
            </div>

            <?php foreach ($preguntas as $candidatoId => $candidatoPreguntas): ?>
                <?php if (empty($candidatoPreguntas)) continue; ?>

                <div class="admin-section" style="margin-bottom: 3rem;">
                    <h2><?php echo isset($candidatoNames[$candidatoId]) ? htmlspecialchars($candidatoNames[$candidatoId]) : 'Candidato Desconocido'; ?></h2>

                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th style="width: 15%;">Fecha</th>
                                <th style="width: 15%;">Nombre</th>
                                <th style="width: 20%;">Correo</th>
                                <th style="width: 30%;">Pregunta</th>
                                <th style="width: 10%;">Estado</th>
                                <th style="width: 10%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidatoPreguntas as $index => $pregunta): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pregunta['fecha']); ?></td>
                                    <td><?php echo htmlspecialchars($pregunta['nombre']); ?></td>
                                    <td><a href="mailto:<?php echo htmlspecialchars($pregunta['correo'] ?? ''); ?>" style="color: var(--accent);">
                                        <?php echo htmlspecialchars($pregunta['correo'] ?? 'No proporcionado'); ?>
                                    </a></td>
                                    <td><?php echo htmlspecialchars(substr($pregunta['comentario'], 0, 100)) . (strlen($pregunta['comentario']) > 100 ? '...' : ''); ?></td>
                                    <td>
                                        <?php if (!empty($pregunta['respuesta'])): ?>
                                            <span style="color: #86efac;">✓ Respondida</span>
                                        <?php else: ?>
                                            <span style="color: #fbbf24;">⏳ Pendiente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="admin-actions">
                                        <button class="btn btn-primary btn-sm" onclick="openAnswerModal('<?php echo $candidatoId; ?>', '<?php echo $pregunta['id']; ?>', <?php echo $index; ?>)">
                                            <?php echo !empty($pregunta['respuesta']) ? 'Ver/Editar' : 'Responder'; ?>
                                        </button>
                                        <button class="btn btn-ghost btn-sm" onclick="deletePregunta('<?php echo $candidatoId; ?>', '<?php echo $pregunta['id']; ?>')">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>

            <?php if ($totalPreguntas === 0): ?>
                <div class="card" style="text-align: center; padding: 3rem;">
                    <p style="color: var(--text-muted); font-size: 1.1rem;">No hay preguntas todavía.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Modal for answering questions -->
    <div id="answer-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 1000; padding: 2rem; overflow-y: auto;">
        <div style="max-width: 800px; margin: 2rem auto; background: var(--bg); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Responder Pregunta</h2>
                <button onclick="closeAnswerModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>

            <div id="modal-content"></div>
        </div>
    </div>

    <!-- Embed PHP data for JavaScript -->
    <script>
    const preguntas = <?php echo json_encode($preguntas); ?>;
    const candidatoNames = <?php echo json_encode($candidatoNames); ?>;
    </script>

    <!-- Load preguntas management script -->
    <script src="js/preguntas.js?v=<?= filemtime('js/preguntas.js') ?>"></script>
</body>
</html>
