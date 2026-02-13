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

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

// Validar campos requeridos
if (empty($input['nombre'])) {
    http_response_code(400);
    echo json_encode(['error' => 'El nombre es requerido']);
    exit;
}

if (empty($input['tipo'])) {
    http_response_code(400);
    echo json_encode(['error' => 'El tipo es requerido']);
    exit;
}

/**
 * Convert date format from DD/MM/YYYY HH:MM to YYYY-MM-DD HH:MM:SS
 */
function convertDateFormat($dateStr) {
    if (empty($dateStr)) return null;

    $parts = explode(' ', $dateStr);
    if (count($parts) !== 2) return null;

    $dateParts = explode('/', $parts[0]);
    if (count($dateParts) !== 3) return null;

    $day = str_pad($dateParts[0], 2, '0', STR_PAD_LEFT);
    $month = str_pad($dateParts[1], 2, '0', STR_PAD_LEFT);
    $year = $dateParts[2];
    $time = $parts[1] . ':00';

    return "$year-$month-$day $time";
}

/**
 * Convert boolean values
 */
function convertBoolean($value) {
    if ($value === null) return null;
    return $value ? 1 : 0;
}

try {
    $pdo = getDbConnection();
    $pdo->beginTransaction();

    // Determinar si es actualización o nuevo candidato
    $isUpdate = false;

    if (!empty($input['id'])) {
        // Verificar si existe
        $stmt = $pdo->prepare("SELECT id FROM candidatos WHERE id = ?");
        $stmt->execute([$input['id']]);
        $isUpdate = $stmt->fetch() !== false;
    }

    // Si no tiene ID o no existe, crear uno nuevo
    if (empty($input['id'])) {
        $input['id'] = 'candidato-' . uniqid();
    }

    // Si no se proporciona estatus, asignar "En Revisión"
    if (empty($input['estatus'])) {
        $input['estatus'] = 'En Revisión';
    }

    if ($isUpdate) {
        // Actualizar candidato existente (updated_at se actualiza automáticamente)
        $sql = "UPDATE candidatos SET
            origen_candidato = ?, nombre = ?, tipo = ?, estatus = ?, mostrar_en_listado = ?, mostrar_en_comparativa = ?,
            visito_condominio = ?, fecha_visita_condominio = ?, prosoc_estatus = ?, prosoc_vigencia_hasta = ?,
            pagina_web = ?, redes_sociales = ?, experiencia_anios = ?, condominios_actuales = ?,
            similar_al_nuestro = ?, quejas_en_prosoc = ?, quejas_en_prosoc_detalles = ?,
            removido_por_asamblea = ?, removido_por_asamblea_detalles = ?,
            manejo_conflictos_vecinos = ?, problemas_complejos_resueltos = ?, referencias = ?,
            referencias_contactadas = ?, referencias_contactadas_comentario = ?, cliente_visitado = ?,
            tamano_equipo = ?, personal_apoyo = ?, horarios_atencion = ?,
            canal_app_portal_texto = ?, canal_presencial_texto = ?, flujo_incidencias = ?,
            tiempo_respuesta_normal = ?, tiempo_respuesta_emergencias = ?, proceso_cobranza = ?,
            propuesta_manejo_fondos = ?, tiempo_publicacion_estados_cuenta = ?,
            forma_entrega_estados_cuenta = ?, plan_primeros_90_dias = ?, costo_mensual = ?,
            cuota_mantenimiento_propuesta = ?, costos_info_adicional = ?, cargos_adicionales_texto = ?,
            incluye_limpieza = ?, costo_propuesta_limpieza = ?, propuesta_limpieza = ?,
            incluye_mantenimiento = ?, costo_propuesta_mantenimiento = ?, propuesta_mantenimiento = ?,
            incluye_vigilancia = ?, costo_propuesta_vigilancia = ?, propuesta_vigilancia = ?,
            incluye_otros_servicios = ?, propuesta_otros_servicios = ?
            WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $input['origenCandidato'] ?? null,
            $input['nombre'],
            $input['tipo'],
            $input['estatus'],
            convertBoolean($input['mostrarEnListado'] ?? true),
            convertBoolean($input['mostrarEnComparativa'] ?? true),
            convertBoolean($input['visitoCondominio'] ?? null),
            $input['fechaVisitaCondominio'] ?? null,
            $input['prosocEstatus'] ?? null,
            $input['prosocVigenciaHasta'] ?? null,
            $input['paginaWeb'] ?? null,
            $input['redesSociales'] ?? null,
            $input['experienciaAnios'] ?? null,
            $input['condominiosActuales'] ?? null,
            convertBoolean($input['similarAlNuestro'] ?? null),
            convertBoolean($input['quejasEnPROSOC'] ?? null),
            $input['quejasEnPROSOCDetalles'] ?? null,
            convertBoolean($input['removidoPorAsamblea'] ?? null),
            $input['removidoPorAsambleaDetalles'] ?? null,
            $input['manejoConflictosVecinos'] ?? null,
            $input['problemasComplejosResueltos'] ?? null,
            $input['referencias'] ?? null,
            convertBoolean($input['referenciasContactadas'] ?? null),
            $input['referenciasContactadasComentario'] ?? null,
            convertBoolean($input['clienteVisitado'] ?? null),
            $input['tamanoEquipo'] ?? null,
            $input['personalApoyo'] ?? null,
            $input['horariosAtencion'] ?? null,
            $input['canalAppPortalTexto'] ?? null,
            $input['canalPresencialTexto'] ?? null,
            $input['flujoIncidencias'] ?? null,
            $input['tiempoRespuestaNormal'] ?? null,
            $input['tiempoRespuestaEmergencias'] ?? null,
            $input['procesoCobranza'] ?? null,
            $input['propuestaManejoFondos'] ?? null,
            $input['tiempoPublicacionEstadosCuenta'] ?? null,
            $input['formaEntregaEstadosCuenta'] ?? null,
            $input['planPrimeros90Dias'] ?? null,
            $input['costoMensual'] ?? null,
            $input['cuotaMantenimientoPropuesta'] ?? null,
            $input['costosInfoAdicional'] ?? null,
            $input['cargosAdicionales'] ?? null,
            convertBoolean($input['incluyeLimpieza'] ?? false),
            $input['costoPropuestaLimpieza'] ?? null,
            $input['propuestaLimpieza'] ?? null,
            convertBoolean($input['incluyeMantenimiento'] ?? false),
            $input['costoPropuestaMantenimiento'] ?? null,
            $input['propuestaMantenimiento'] ?? null,
            convertBoolean($input['incluyeVigilancia'] ?? false),
            $input['costoPropuestaVigilancia'] ?? null,
            $input['propuestaVigilancia'] ?? null,
            convertBoolean($input['incluyeOtrosServicios'] ?? false),
            $input['propuestaOtrosServicios'] ?? null,
            $input['id']
        ]);

        // Delete related records before re-inserting
        $pdo->prepare("DELETE FROM candidato_telefonos WHERE candidato_id = ?")->execute([$input['id']]);
        $pdo->prepare("DELETE FROM candidato_correos WHERE candidato_id = ?")->execute([$input['id']]);
        $pdo->prepare("DELETE FROM candidato_tipos_condominio WHERE candidato_id = ?")->execute([$input['id']]);
        $pdo->prepare("DELETE FROM candidato_equipos WHERE candidato_id = ?")->execute([$input['id']]);
        $pdo->prepare("DELETE FROM candidato_canales WHERE candidato_id = ?")->execute([$input['id']]);
        $pdo->prepare("DELETE FROM candidato_cargos_adicionales WHERE candidato_id = ?")->execute([$input['id']]);

    } else {
        // Insertar nuevo candidato (updated_at se establece automáticamente)
        $sql = "INSERT INTO candidatos (
            id, origen_candidato, nombre, tipo, estatus, mostrar_en_listado, mostrar_en_comparativa,
            visito_condominio, fecha_visita_condominio, prosoc_estatus, prosoc_vigencia_hasta,
            pagina_web, redes_sociales, experiencia_anios, condominios_actuales,
            similar_al_nuestro, quejas_en_prosoc, quejas_en_prosoc_detalles,
            removido_por_asamblea, removido_por_asamblea_detalles,
            manejo_conflictos_vecinos, problemas_complejos_resueltos, referencias,
            referencias_contactadas, referencias_contactadas_comentario, cliente_visitado,
            tamano_equipo, personal_apoyo, horarios_atencion,
            canal_app_portal_texto, canal_presencial_texto, flujo_incidencias,
            tiempo_respuesta_normal, tiempo_respuesta_emergencias, proceso_cobranza,
            propuesta_manejo_fondos, tiempo_publicacion_estados_cuenta,
            forma_entrega_estados_cuenta, plan_primeros_90_dias, costo_mensual, cuota_mantenimiento_propuesta, costos_info_adicional, cargos_adicionales_texto,
            incluye_limpieza, costo_propuesta_limpieza, propuesta_limpieza,
            incluye_mantenimiento, costo_propuesta_mantenimiento, propuesta_mantenimiento,
            incluye_vigilancia, costo_propuesta_vigilancia, propuesta_vigilancia,
            incluye_otros_servicios, propuesta_otros_servicios
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $input['id'],
            $input['origenCandidato'] ?? null,
            $input['nombre'],
            $input['tipo'],
            $input['estatus'],
            convertBoolean($input['mostrarEnListado'] ?? true),
            convertBoolean($input['mostrarEnComparativa'] ?? true),
            convertBoolean($input['visitoCondominio'] ?? null),
            $input['fechaVisitaCondominio'] ?? null,
            $input['prosocEstatus'] ?? null,
            $input['prosocVigenciaHasta'] ?? null,
            $input['paginaWeb'] ?? null,
            $input['redesSociales'] ?? null,
            $input['experienciaAnios'] ?? null,
            $input['condominiosActuales'] ?? null,
            convertBoolean($input['similarAlNuestro'] ?? null),
            convertBoolean($input['quejasEnPROSOC'] ?? null),
            $input['quejasEnPROSOCDetalles'] ?? null,
            convertBoolean($input['removidoPorAsamblea'] ?? null),
            $input['removidoPorAsambleaDetalles'] ?? null,
            $input['manejoConflictosVecinos'] ?? null,
            $input['problemasComplejosResueltos'] ?? null,
            $input['referencias'] ?? null,
            convertBoolean($input['referenciasContactadas'] ?? null),
            $input['referenciasContactadasComentario'] ?? null,
            convertBoolean($input['clienteVisitado'] ?? null),
            $input['tamanoEquipo'] ?? null,
            $input['personalApoyo'] ?? null,
            $input['horariosAtencion'] ?? null,
            $input['canalAppPortalTexto'] ?? null,
            $input['canalPresencialTexto'] ?? null,
            $input['flujoIncidencias'] ?? null,
            $input['tiempoRespuestaNormal'] ?? null,
            $input['tiempoRespuestaEmergencias'] ?? null,
            $input['procesoCobranza'] ?? null,
            $input['propuestaManejoFondos'] ?? null,
            $input['tiempoPublicacionEstadosCuenta'] ?? null,
            $input['formaEntregaEstadosCuenta'] ?? null,
            $input['planPrimeros90Dias'] ?? null,
            $input['costoMensual'] ?? null,
            $input['cuotaMantenimientoPropuesta'] ?? null,
            $input['costosInfoAdicional'] ?? null,
            $input['cargosAdicionales'] ?? null,
            convertBoolean($input['incluyeLimpieza'] ?? false),
            $input['costoPropuestaLimpieza'] ?? null,
            $input['propuestaLimpieza'] ?? null,
            convertBoolean($input['incluyeMantenimiento'] ?? false),
            $input['costoPropuestaMantenimiento'] ?? null,
            $input['propuestaMantenimiento'] ?? null,
            convertBoolean($input['incluyeVigilancia'] ?? false),
            $input['costoPropuestaVigilancia'] ?? null,
            $input['propuestaVigilancia'] ?? null,
            convertBoolean($input['incluyeOtrosServicios'] ?? false),
            $input['propuestaOtrosServicios'] ?? null
        ]);

        // Transferir documentos del ID temporal al ID permanente
        if (!empty($input['tempId'])) {
            $pdo->prepare("UPDATE candidato_documentos SET candidato_id = ? WHERE candidato_id = ?")
                ->execute([$input['id'], $input['tempId']]);
        }
    }

    // Insert related records (for both insert and update)
    $stmtTelefono = $pdo->prepare("INSERT INTO candidato_telefonos (candidato_id, telefono) VALUES (?, ?)");
    $stmtCorreo = $pdo->prepare("INSERT INTO candidato_correos (candidato_id, correo) VALUES (?, ?)");
    $stmtTipo = $pdo->prepare("INSERT INTO candidato_tipos_condominio (candidato_id, tipo) VALUES (?, ?)");
    $stmtEquipo = $pdo->prepare("INSERT INTO candidato_equipos (candidato_id, equipo) VALUES (?, ?)");
    $stmtCanal = $pdo->prepare("INSERT INTO candidato_canales (candidato_id, canal) VALUES (?, ?)");

    // Telefonos
    if (!empty($input['telefonosContacto']) && is_array($input['telefonosContacto'])) {
        foreach ($input['telefonosContacto'] as $telefono) {
            if (!empty($telefono)) {
                $stmtTelefono->execute([$input['id'], $telefono]);
            }
        }
    }

    // Correos
    if (!empty($input['correosContacto']) && is_array($input['correosContacto'])) {
        foreach ($input['correosContacto'] as $correo) {
            if (!empty($correo)) {
                $stmtCorreo->execute([$input['id'], $correo]);
            }
        }
    }

    // Tipos de condominio
    if (!empty($input['tiposCondominio']) && is_array($input['tiposCondominio'])) {
        foreach ($input['tiposCondominio'] as $tipo) {
            if (!empty($tipo)) {
                $stmtTipo->execute([$input['id'], $tipo]);
            }
        }
    }

    // Equipos
    if (!empty($input['experienciaEquipos']) && is_array($input['experienciaEquipos'])) {
        foreach ($input['experienciaEquipos'] as $equipo) {
            if (!empty($equipo)) {
                $stmtEquipo->execute([$input['id'], $equipo]);
            }
        }
    }

    // Canales
    if (!empty($input['canalesComunicacion']) && is_array($input['canalesComunicacion'])) {
        foreach ($input['canalesComunicacion'] as $canal) {
            if (!empty($canal)) {
                $stmtCanal->execute([$input['id'], $canal]);
            }
        }
    }

    // Nota: cargosAdicionales ahora se guarda como texto en la columna cargos_adicionales_texto

    $pdo->commit();

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'candidato' => $input,
        'message' => $isUpdate ? 'Candidato actualizado correctamente' : 'Candidato agregado correctamente'
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Error al guardar candidato en DB: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar el candidato']);
}
?>
