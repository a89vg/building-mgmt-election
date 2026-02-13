<?php
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

if (!isset($input['path']) || empty($input['path'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Se requiere la ruta del archivo']);
    exit;
}

$filePath = __DIR__ . '/../../' . $input['path'];

// Verificar que el archivo existe y está en el directorio permitido
if (!file_exists($filePath)) {
    http_response_code(404);
    echo json_encode(['error' => 'Archivo no encontrado']);
    exit;
}

// Verificar que está en el directorio correcto (seguridad)
$realPath = realpath($filePath);
$allowedDirs = [
    realpath(__DIR__ . '/../../data/candidatos-documentos/'),
    realpath(__DIR__ . '/../../data/videos/')
];

$isAllowed = false;
foreach ($allowedDirs as $allowedDir) {
    if ($allowedDir && strpos($realPath, $allowedDir) === 0) {
        $isAllowed = true;
        break;
    }
}

if (!$isAllowed) {
    http_response_code(403);
    echo json_encode(['error' => 'Operación no permitida']);
    exit;
}

// Eliminar archivo
if (!unlink($filePath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al eliminar el archivo']);
    exit;
}

http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'Archivo eliminado correctamente'
]);
?>
