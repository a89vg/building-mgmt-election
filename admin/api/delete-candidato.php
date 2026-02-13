<?php
session_start();

// TODO: CSRF token validation
// Verificar autenticación
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

// Leer datos enviados
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de candidato es requerido']);
    exit;
}

$candidatoId = $input['id'];

try {
    $pdo = getDbConnection();

    // Verificar si existe el candidato
    $stmt = $pdo->prepare("SELECT id FROM candidatos WHERE id = ?");
    $stmt->execute([$candidatoId]);

    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['error' => 'Candidato no encontrado']);
        exit;
    }

    // Eliminar candidato (CASCADE eliminará automáticamente los registros relacionados)
    $stmt = $pdo->prepare("DELETE FROM candidatos WHERE id = ?");
    $stmt->execute([$candidatoId]);

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Candidato eliminado correctamente'
    ]);

} catch (PDOException $e) {
    error_log('Error al eliminar candidato de DB: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al eliminar el candidato']);
}
?>
