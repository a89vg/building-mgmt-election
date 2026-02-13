-- Database Schema for Candidate Management System
-- MariaDB/MySQL
-- Database: myapp

SET FOREIGN_KEY_CHECKS = 0;

-- Drop existing tables
DROP TABLE IF EXISTS preguntas;
DROP TABLE IF EXISTS candidato_telefonos;
DROP TABLE IF EXISTS candidato_correos;
DROP TABLE IF EXISTS candidato_tipos_condominio;
DROP TABLE IF EXISTS candidato_equipos;
DROP TABLE IF EXISTS candidato_canales;
DROP TABLE IF EXISTS candidato_cargos_adicionales;
DROP TABLE IF EXISTS candidatos;

SET FOREIGN_KEY_CHECKS = 1;

-- Main candidatos table (42 core fields + timestamps)
CREATE TABLE candidatos (
    id VARCHAR(100) PRIMARY KEY,
    origen_candidato TEXT,
    nombre VARCHAR(255) NOT NULL,
    tipo VARCHAR(50),
    estatus VARCHAR(50),
    ultima_actualizacion DATETIME,
    visito_condominio BOOLEAN,
    prosoc_estatus VARCHAR(50),
    prosoc_vigencia_hasta DATE,
    pagina_web TEXT,
    redes_sociales TEXT,
    experiencia_anios INT,
    condominios_actuales INT,
    similar_al_nuestro BOOLEAN,
    quejas_en_prosoc BOOLEAN,
    quejas_en_prosoc_detalles TEXT,
    removido_por_asamblea BOOLEAN,
    removido_por_asamblea_detalles TEXT,
    manejo_conflictos_vecinos TEXT,
    problemas_complejos_resueltos TEXT,
    referencias TEXT,
    servicios_incluidos TEXT,
    tamano_equipo VARCHAR(100),
    personal_apoyo TEXT,
    horarios_atencion TEXT,
    canal_app_portal_texto VARCHAR(255),
    canal_presencial_texto TEXT,
    flujo_incidencias TEXT,
    tiempo_respuesta_normal VARCHAR(100),
    tiempo_respuesta_emergencias VARCHAR(100),
    proceso_cobranza TEXT,
    propuesta_manejo_fondos TEXT,
    tiempo_publicacion_estados_cuenta VARCHAR(100),
    forma_entrega_estados_cuenta TEXT,
    plan_primeros_90_dias TEXT,
    costo_mensual DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_estatus (estatus),
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Normalized table for contact phones (1:N)
CREATE TABLE candidato_telefonos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidato_id VARCHAR(100) NOT NULL,
    telefono VARCHAR(50) NOT NULL,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE,
    INDEX idx_candidato (candidato_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Normalized table for contact emails (1:N)
CREATE TABLE candidato_correos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidato_id VARCHAR(100) NOT NULL,
    correo VARCHAR(255) NOT NULL,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE,
    INDEX idx_candidato (candidato_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Normalized table for condominium types (N:N)
CREATE TABLE candidato_tipos_condominio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidato_id VARCHAR(100) NOT NULL,
    tipo VARCHAR(100) NOT NULL,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE,
    INDEX idx_candidato (candidato_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Normalized table for equipment experience (N:N)
CREATE TABLE candidato_equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidato_id VARCHAR(100) NOT NULL,
    equipo VARCHAR(255) NOT NULL,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE,
    INDEX idx_candidato (candidato_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Normalized table for communication channels (N:N)
CREATE TABLE candidato_canales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidato_id VARCHAR(100) NOT NULL,
    canal VARCHAR(255) NOT NULL,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE,
    INDEX idx_candidato (candidato_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Normalized table for additional charges (1:N)
CREATE TABLE candidato_cargos_adicionales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidato_id VARCHAR(100) NOT NULL,
    cargo VARCHAR(255) NOT NULL,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE,
    INDEX idx_candidato (candidato_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Questions table
CREATE TABLE preguntas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidato_id VARCHAR(100) NOT NULL,
    pregunta TEXT NOT NULL,
    respuesta TEXT,
    fecha DATETIME NOT NULL,
    vecino VARCHAR(255),
    correo VARCHAR(255),
    respondida BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE,
    INDEX idx_candidato (candidato_id),
    INDEX idx_respondida (respondida)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
