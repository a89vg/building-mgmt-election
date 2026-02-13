<?php
/**
 * API endpoint to delete a document by its database ID
 * POST /api/delete-documento-db.php
 * Body: { "documentoId": 123 }
 */

session_start();

// TODO: CSRF token validation
// Verificar autenticación
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$documentoId = $input['documentoId'] ?? null;

if (empty($documentoId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Se requiere el ID del documento']);
    exit;
}

require_once __DIR__ . '/../../config/database.php';

try {
    $pdo = getDbConnection();

    // Get file path before deleting from DB
    $stmt = $pdo->prepare("SELECT archivo_url FROM candidato_documentos WHERE id = ?");
    $stmt->execute([$documentoId]);
    $documento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$documento) {
        http_response_code(404);
        echo json_encode(['error' => 'Documento no encontrado']);
        exit;
    }

    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM candidato_documentos WHERE id = ?");
    $stmt->execute([$documentoId]);

    // Delete physical file
    $filePath = __DIR__ . '/../../' . $documento['archivo_url'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Documento eliminado correctamente'
    ]);

} catch (PDOException $e) {
    error_log("Error deleting document: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al eliminar documento']);
}
?>
