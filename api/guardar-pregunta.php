<?php
require_once __DIR__ . '/../config/database.php';

// Configurar zona horaria local (Ciudad de México)
date_default_timezone_set('America/Mexico_City');

// Permitir solicitudes CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


/**
 * Convert date format from YYYY-MM-DD HH:MM:SS to DD/MM/YYYY HH:MM
 */
function formatDateForOutput($dateStr, $includeTime = true) {
    if (empty($dateStr)) return null;

    $timestamp = strtotime($dateStr);
    if (!$timestamp) return null;

    return $includeTime ? date('d/m/Y H:i', $timestamp) : date('d/m/Y', $timestamp);
}

// Manejo de GET - obtener preguntas
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $candidatoId = isset($_GET['candidatoId']) ? $_GET['candidatoId'] : null;

    if (!$candidatoId) {
        http_response_code(400);
        echo json_encode(['error' => 'candidatoId es requerido']);
        exit;
    }

    try {
        $pdo = getDbConnection();

        $stmt = $pdo->prepare("
            SELECT id, vecino as nombre, correo, pregunta as comentario, fecha,
                   respuesta, fecha_respuesta as fechaRespuesta
            FROM preguntas
            WHERE candidato_id = ?
            ORDER BY fecha DESC
        ");
        $stmt->execute([$candidatoId]);
        $preguntas = $stmt->fetchAll();

        // Format dates for output (with time)
        foreach ($preguntas as &$pregunta) {
            $pregunta['fecha'] = formatDateForOutput($pregunta['fecha']);
            $pregunta['fechaRespuesta'] = formatDateForOutput($pregunta['fechaRespuesta']);
        }

        echo json_encode(['success' => true, 'preguntas' => $preguntas]);
    } catch (PDOException $e) {
        error_log('Error fetching preguntas from DB: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener preguntas']);
    }
    exit;
}

// Manejo de POST - guardar pregunta y enviar email
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $candidatoId = isset($input['candidatoId']) ? $input['candidatoId'] : null;
    $candidatoNombre = isset($input['candidatoNombre']) ? trim($input['candidatoNombre']) : 'Candidato desconocido';
    $nombre = isset($input['nombre']) ? trim($input['nombre']) : '';
    $correo = isset($input['correo']) ? trim($input['correo']) : '';
    $comentario = isset($input['comentario']) ? trim($input['comentario']) : '';

    if (!$candidatoId || !$nombre || !$correo || !$comentario) {
        http_response_code(400);
        echo json_encode(['error' => 'Todos los campos son requeridos (nombre, correo y comentario)']);
        exit;
    }

    // Validar formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'El correo electrónico no tiene un formato válido']);
        exit;
    }

    // Guardar la pregunta en la base de datos
    try {
        $pdo = getDbConnection();

        $stmt = $pdo->prepare("
            INSERT INTO preguntas (candidato_id, vecino, correo, pregunta, fecha, respondida)
            VALUES (?, ?, ?, ?, NOW(), FALSE)
        ");
        $stmt->execute([$candidatoId, $nombre, $correo, $comentario]);

        $preguntaId = $pdo->lastInsertId();

        // Crear objeto de pregunta para respuesta
        $pregunta = [
            'id' => $preguntaId,
            'nombre' => $nombre,
            'correo' => $correo,
            'comentario' => $comentario,
            'fecha' => date('d/m/Y')
        ];

    } catch (PDOException $e) {
        error_log('Error al guardar pregunta en DB: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Error al guardar la pregunta. Por favor intenta más tarde.']);
        exit;
    }

    http_response_code(200);
    echo json_encode(['success' => true, 'pregunta' => $pregunta, 'message' => 'Tu pregunta ha sido enviada correctamente']);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Método no permitido']);
?>
