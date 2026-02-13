<?php
session_start();

// TODO: CSRF token validation
// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$candidatoId = isset($input['candidatoId']) ? $input['candidatoId'] : null;
$preguntaId = isset($input['preguntaId']) ? $input['preguntaId'] : null;
$respuesta = isset($input['respuesta']) ? trim($input['respuesta']) : '';

if (!$candidatoId || !$preguntaId || !$respuesta) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan datos requeridos']);
    exit;
}

try {
    $pdo = getDbConnection();

    // Find the pregunta
    $stmt = $pdo->prepare("SELECT * FROM preguntas WHERE id = ? AND candidato_id = ?");
    $stmt->execute([$preguntaId, $candidatoId]);
    $pregunta = $stmt->fetch();

    if (!$pregunta) {
        http_response_code(404);
        echo json_encode(['error' => 'Pregunta no encontrada']);
        exit;
    }

    // Update the pregunta with respuesta and set fecha_respuesta
    $stmt = $pdo->prepare("UPDATE preguntas SET respuesta = ?, respondida = TRUE, fecha_respuesta = NOW() WHERE id = ?");
    $stmt->execute([$respuesta, $preguntaId]);

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Respuesta guardada correctamente'
    ]);

} catch (PDOException $e) {
    error_log('Error al guardar respuesta en DB: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar la respuesta']);
}
?>
