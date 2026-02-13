<?php
/**
 * Script to create the candidato_documentos table
 * Run this once to enable multiple file uploads per document type
 */

require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = getDbConnection();

    // Create the candidato_documentos table
    $sql = "
        CREATE TABLE IF NOT EXISTS candidato_documentos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            candidato_id VARCHAR(100) NOT NULL,
            tipo_documento VARCHAR(50) NOT NULL,
            archivo_url TEXT NOT NULL,
            archivo_nombre VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_candidato_tipo (candidato_id, tipo_documento)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";

    $pdo->exec($sql);

    // Add foreign key if not exists (might fail if table already has data with orphaned references)
    try {
        $pdo->exec("
            ALTER TABLE candidato_documentos
            ADD CONSTRAINT fk_candidato_documentos_candidato
            FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE
        ");
    } catch (PDOException $e) {
        // Foreign key might already exist or have issues - continue anyway
        error_log("Foreign key notice: " . $e->getMessage());
    }

    echo json_encode([
        'success' => true,
        'message' => 'Tabla candidato_documentos creada correctamente'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    error_log('Error creating documentos table: ' . $e->getMessage());
    echo json_encode([
        'error' => 'Error al crear la tabla'
    ]);
}
?>
