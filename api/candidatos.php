<?php
/**
 * Public API endpoint to get all candidatos
 * Returns candidatos with all related data in the same format as JSON file
 */

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

/**
 * Convert date format from YYYY-MM-DD HH:MM:SS to DD/MM/YYYY HH:MM
 */
function formatDateForOutput($dateStr) {
    if (empty($dateStr)) return null;

    $timestamp = strtotime($dateStr);
    if (!$timestamp) return null;

    return date('d/m/Y H:i', $timestamp);
}

/**
 * Convert DB boolean to JS boolean
 */
function formatBoolean($value) {
    if ($value === null) return null;
    return (bool)$value;
}

try {
    $pdo = getDbConnection();

    // Get all candidatos
    $stmt = $pdo->query("SELECT * FROM candidatos ORDER BY fecha_visita_condominio IS NULL, fecha_visita_condominio ASC");
    $candidatos = $stmt->fetchAll();

    $result = [];

    foreach ($candidatos as $candidato) {
        // Build candidato object
        $candidatoData = [
            'id' => $candidato['id'],
            'origenCandidato' => $candidato['origen_candidato'],
            'nombre' => $candidato['nombre'],
            'tipo' => $candidato['tipo'],
            'estatus' => $candidato['estatus'],
            'ultimaActualizacion' => formatDateForOutput($candidato['updated_at']),
            'visitoCondominio' => formatBoolean($candidato['visito_condominio']),
            'fechaVisitaCondominio' => $candidato['fecha_visita_condominio'],
            'mostrarEnListado' => formatBoolean($candidato['mostrar_en_listado']),
            'mostrarEnComparativa' => formatBoolean($candidato['mostrar_en_comparativa']),
            'prosocEstatus' => $candidato['prosoc_estatus'],
            'prosocVigenciaHasta' => $candidato['prosoc_vigencia_hasta'],
            'paginaWeb' => $candidato['pagina_web'],
            'redesSociales' => $candidato['redes_sociales'],
            'experienciaAnios' => $candidato['experiencia_anios'],
            'condominiosActuales' => $candidato['condominios_actuales'],
            'similarAlNuestro' => formatBoolean($candidato['similar_al_nuestro']),
            'quejasEnPROSOC' => formatBoolean($candidato['quejas_en_prosoc']),
            'quejasEnPROSOCDetalles' => $candidato['quejas_en_prosoc_detalles'],
            'removidoPorAsamblea' => formatBoolean($candidato['removido_por_asamblea']),
            'removidoPorAsambleaDetalles' => $candidato['removido_por_asamblea_detalles'],
            'manejoConflictosVecinos' => $candidato['manejo_conflictos_vecinos'],
            'problemasComplejosResueltos' => $candidato['problemas_complejos_resueltos'],
            'referencias' => $candidato['referencias'],
            'referenciasContactadas' => formatBoolean($candidato['referencias_contactadas']),
            'referenciasContactadasComentario' => $candidato['referencias_contactadas_comentario'],
            'clienteVisitado' => formatBoolean($candidato['cliente_visitado']),
            'tamanoEquipo' => $candidato['tamano_equipo'],
            'personalApoyo' => $candidato['personal_apoyo'],
            'horariosAtencion' => $candidato['horarios_atencion'],
            'canalAppPortalTexto' => $candidato['canal_app_portal_texto'],
            'canalPresencialTexto' => $candidato['canal_presencial_texto'],
            'flujoIncidencias' => $candidato['flujo_incidencias'],
            'tiempoRespuestaNormal' => $candidato['tiempo_respuesta_normal'],
            'tiempoRespuestaEmergencias' => $candidato['tiempo_respuesta_emergencias'],
            'procesoCobranza' => $candidato['proceso_cobranza'],
            'propuestaManejoFondos' => $candidato['propuesta_manejo_fondos'],
            'tiempoPublicacionEstadosCuenta' => $candidato['tiempo_publicacion_estados_cuenta'],
            'formaEntregaEstadosCuenta' => $candidato['forma_entrega_estados_cuenta'],
            'planPrimeros90Dias' => $candidato['plan_primeros_90_dias'],
            'costoMensual' => $candidato['costo_mensual'] ? (float)$candidato['costo_mensual'] : null,
            'cuotaMantenimientoPropuesta' => $candidato['cuota_mantenimiento_propuesta'] ?: null,
            'costosInfoAdicional' => $candidato['costos_info_adicional'],
            'incluyeLimpieza' => formatBoolean($candidato['incluye_limpieza']),
            'costoPropuestaLimpieza' => $candidato['costo_propuesta_limpieza'],
            'propuestaLimpieza' => $candidato['propuesta_limpieza'],
            'incluyeMantenimiento' => formatBoolean($candidato['incluye_mantenimiento']),
            'costoPropuestaMantenimiento' => $candidato['costo_propuesta_mantenimiento'],
            'propuestaMantenimiento' => $candidato['propuesta_mantenimiento'],
            'incluyeVigilancia' => formatBoolean($candidato['incluye_vigilancia']),
            'costoPropuestaVigilancia' => $candidato['costo_propuesta_vigilancia'],
            'propuestaVigilancia' => $candidato['propuesta_vigilancia'],
            'incluyeOtrosServicios' => formatBoolean($candidato['incluye_otros_servicios']),
            'propuestaOtrosServicios' => $candidato['propuesta_otros_servicios']
        ];

        // Get multiple documents from candidato_documentos table
        $stmtDocs = $pdo->prepare("
            SELECT tipo_documento, archivo_url, archivo_nombre
            FROM candidato_documentos
            WHERE candidato_id = ?
            ORDER BY tipo_documento, created_at DESC
        ");
        $stmtDocs->execute([$candidato['id']]);
        $documentos = $stmtDocs->fetchAll(PDO::FETCH_ASSOC);

        // Group documents by type
        $documentosPorTipo = [];
        foreach ($documentos as $doc) {
            $tipo = $doc['tipo_documento'];
            if (!isset($documentosPorTipo[$tipo])) {
                $documentosPorTipo[$tipo] = [];
            }
            $documentosPorTipo[$tipo][] = [
                'url' => $doc['archivo_url'],
                'nombre' => $doc['archivo_nombre']
            ];
        }

        // Add document arrays to response
        $candidatoData['documentos'] = [
            'curriculum' => $documentosPorTipo['curriculum'] ?? [],
            'certificacionProsoc' => $documentosPorTipo['certificacion-prosoc'] ?? [],
            'rfcCsf' => $documentosPorTipo['rfc-csf'] ?? [],
            'presentacionServicios' => $documentosPorTipo['presentacion-servicios'] ?? [],
            'formatoEstadoCuenta' => $documentosPorTipo['formato-estado-cuenta'] ?? [],
            'propuestaFormal' => $documentosPorTipo['propuesta-formal'] ?? [],
            'videoEntrevista' => $documentosPorTipo['video-entrevista'] ?? [],
            'cartasRecomendacion' => $documentosPorTipo['cartas-recomendacion'] ?? []
        ];

        // Get telefonos
        $stmt = $pdo->prepare("SELECT telefono FROM candidato_telefonos WHERE candidato_id = ?");
        $stmt->execute([$candidato['id']]);
        $candidatoData['telefonosContacto'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Get correos
        $stmt = $pdo->prepare("SELECT correo FROM candidato_correos WHERE candidato_id = ?");
        $stmt->execute([$candidato['id']]);
        $candidatoData['correosContacto'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Get tipos de condominio
        $stmt = $pdo->prepare("SELECT tipo FROM candidato_tipos_condominio WHERE candidato_id = ?");
        $stmt->execute([$candidato['id']]);
        $candidatoData['tiposCondominio'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Get equipos
        $stmt = $pdo->prepare("SELECT equipo FROM candidato_equipos WHERE candidato_id = ?");
        $stmt->execute([$candidato['id']]);
        $candidatoData['experienciaEquipos'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Get canales
        $stmt = $pdo->prepare("SELECT canal FROM candidato_canales WHERE candidato_id = ?");
        $stmt->execute([$candidato['id']]);
        $candidatoData['canalesComunicacion'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Cargos adicionales (now stored as text)
        $candidatoData['cargosAdicionales'] = $candidato['cargos_adicionales_texto'];

        $result[] = $candidatoData;
    }

    http_response_code(200);
    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (PDOException $e) {
    error_log('Error fetching candidatos from DB: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al obtener candidatos'
    ]);
}
