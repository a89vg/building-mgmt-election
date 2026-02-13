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

// Verificar que se envió un archivo
if (!isset($_FILES['documento'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No se recibió ningún archivo']);
    exit;
}

if ($_FILES['documento']['error'] !== UPLOAD_ERR_OK) {
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE => 'El archivo excede el tamaño máximo permitido por PHP (upload_max_filesize)',
        UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño máximo permitido por el formulario',
        UPLOAD_ERR_PARTIAL => 'El archivo solo se subió parcialmente',
        UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo',
        UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal',
        UPLOAD_ERR_CANT_WRITE => 'Error al escribir el archivo en disco',
        UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la carga del archivo'
    ];
    $errorCode = $_FILES['documento']['error'];
    $errorMsg = $errorMessages[$errorCode] ?? 'Error desconocido en la carga (código: ' . $errorCode . ')';
    http_response_code(400);
    echo json_encode(['error' => $errorMsg]);
    exit;
}

$file = $_FILES['documento'];
$candidatoId = $_POST['candidatoId'] ?? '';
$tipoDocumento = $_POST['tipoDocumento'] ?? 'general';

if (empty($candidatoId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Se requiere el ID del candidato']);
    exit;
}

// Validar tamaño (máximo 50MB)
$maxSize = 50 * 1024 * 1024; // 50MB
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['error' => 'El archivo es demasiado grande. Máximo 50MB']);
    exit;
}

// Validar tipo de archivo
$allowedExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'mp4', 'mov', 'avi', 'zip', 'xls', 'xlsx'];
$fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($fileExtension, $allowedExtensions)) {
    http_response_code(400);
    echo json_encode(['error' => 'Tipo de archivo no permitido. Permitidos: ' . implode(', ', $allowedExtensions)]);
    exit;
}

// Determinar directorio según tipo de archivo (videos van a carpeta separada)
$videoExtensions = ['mp4', 'mov', 'avi', 'webm'];
$isVideo = in_array($fileExtension, $videoExtensions);

if ($isVideo) {
    $uploadDir = __DIR__ . '/../../data/videos/';
    $relativeDir = 'data/videos/';
} else {
    $uploadDir = __DIR__ . '/../../data/candidatos-documentos/';
    $relativeDir = 'data/candidatos-documentos/';
}

// Crear directorio si no existe
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generar nombre único para el archivo
$fileName = $candidatoId . '_' . $tipoDocumento . '_' . uniqid() . '.' . $fileExtension;
$filePath = $uploadDir . $fileName;

// Mover archivo
if (!move_uploaded_file($file['tmp_name'], $filePath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar el archivo']);
    exit;
}

// Retornar ruta relativa
$relativePath = $relativeDir . $fileName;

// Guardar en la tabla candidato_documentos para permitir múltiples archivos
require_once __DIR__ . '/../../config/database.php';
try {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("
        INSERT INTO candidato_documentos (candidato_id, tipo_documento, archivo_url, archivo_nombre)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$candidatoId, $tipoDocumento, $relativePath, $file['name']]);
    $documentoId = $pdo->lastInsertId();
} catch (PDOException $e) {
    error_log("Error saving document to DB: " . $e->getMessage());
    // Continue anyway - file was uploaded successfully
    $documentoId = null;
}

http_response_code(200);
echo json_encode([
    'success' => true,
    'path' => $relativePath,
    'filename' => $file['name'],
    'documentoId' => $documentoId,
    'message' => 'Archivo subido correctamente'
]);
?>
