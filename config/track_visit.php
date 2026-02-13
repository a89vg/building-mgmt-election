<?php
/**
 * Script para registrar visitas de forma anonimizada
 * No almacena IPs ni información personal identificable
 */

// Configurar zona horaria local (Ciudad de México)
date_default_timezone_set('America/Mexico_City');

require_once __DIR__ . '/database.php';

function trackVisit() {
    // Skip tracking if admin is viewing
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        return;
    }

    try {
        $pdo = getDbConnection();

        // Crear un hash anónimo del visitante basado en:
        // - IP (solo para diferenciar visitantes, pero hasheada)
        // - User Agent
        // - Un salt aleatorio por día (para que el mismo usuario tenga diferente hash cada día)
        $dateStr = date('Y-m-d');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        // Crear hash que cambia cada día (no permite rastrear al usuario entre días)
        $visitorSalt = getenv('VISITOR_SALT') ?: 'change_this_salt';
        $visitorHash = hash('sha256', $ip . $userAgent . $dateStr . $visitorSalt);

        // Verificar si este visitante ya fue registrado hoy
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count
            FROM site_visits
            WHERE visitor_hash = ? AND DATE(created_at) = CURDATE()
        ");
        $stmt->execute([$visitorHash]);
        $result = $stmt->fetch();

        // Solo registrar si no ha visitado hoy (evita contar múltiples visitas del mismo usuario)
        if ($result['count'] == 0) {
            $stmt = $pdo->prepare("
                INSERT INTO site_visits (visitor_hash)
                VALUES (?)
            ");
            $stmt->execute([$visitorHash]);
        }

    } catch (Exception $e) {
        // Log error but don't break the site
        error_log("Error tracking visit: " . $e->getMessage());
    }
}

// Ejecutar el tracking
trackVisit();
