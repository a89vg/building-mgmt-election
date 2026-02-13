<?php
/**
 * API endpoint to list all documents for a candidate
 * GET /api/list-documentos.php?candidatoId=XXX
 * Optional: &tipo=curriculum (to filter by document type)
 */

session_start();

// Verificar autenticación
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$candidatoId = $_GET['candidatoId'] ?? '';
$tipoDocumento = $_GET['tipo'] ?? null;

if (empty($candidatoId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Se requiere el ID del candidato']);
    exit;
}

require_once __DIR__ . '/../../config/database.php';

try {
    $pdo = getDbConnection();

    if ($tipoDocumento) {
        $stmt = $pdo->prepare("
            SELECT id, tipo_documento, archivo_url, archivo_nombre, created_at
            FROM candidato_documentos
            WHERE candidato_id = ? AND tipo_documento = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$candidatoId, $tipoDocumento]);
    } else {
        $stmt = $pdo->prepare("
            SELECT id, tipo_documento, archivo_url, archivo_nombre, created_at
            FROM candidato_documentos
            WHERE candidato_id = ?
            ORDER BY tipo_documento, created_at DESC
        ");
        $stmt->execute([$candidatoId]);
    }

    $documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group by tipo_documento if no filter
    if (!$tipoDocumento) {
        $grouped = [];
        foreach ($documentos as $doc) {
            $tipo = $doc['tipo_documento'];
            if (!isset($grouped[$tipo])) {
                $grouped[$tipo] = [];
            }
            $grouped[$tipo][] = $doc;
        }
        echo json_encode(['success' => true, 'documentos' => $grouped]);
    } else {
        echo json_encode(['success' => true, 'documentos' => $documentos]);
    }

} catch (PDOException $e) {
    error_log("Error listing documents: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener documentos']);
}
?>
