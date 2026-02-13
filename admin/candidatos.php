<?php
require_once '../admin-auth.php';
require_once __DIR__ . '/../config/database.php';

// Load candidatos from database
$candidatos = [];

try {
    $pdo = getDbConnection();

    // Get all candidatos
    $stmt = $pdo->query("SELECT * FROM candidatos ORDER BY fecha_visita_condominio IS NULL, fecha_visita_condominio ASC");
    $candidatosDb = $stmt->fetchAll();

    foreach ($candidatosDb as $candidato) {
        // Get telefonos
        $stmt = $pdo->prepare("SELECT telefono FROM candidato_telefonos WHERE candidato_id = ?");
        $stmt->execute([$candidato['id']]);
        $telefonos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Get correos
        $stmt = $pdo->prepare("SELECT correo FROM candidato_correos WHERE candidato_id = ?");
        $stmt->execute([$candidato['id']]);
        $correos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Get tipos de condominio
        $stmt = $pdo->prepare("SELECT tipo FROM candidato_tipos_condominio WHERE candidato_id = ?");
        $stmt->execute([$candidato['id']]);
        $tiposCondominio = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Get equipos
        $stmt = $pdo->prepare("SELECT equipo FROM candidato_equipos WHERE candidato_id = ?");
        $stmt->execute([$candidato['id']]);
        $equipos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Get canales
        $stmt = $pdo->prepare("SELECT canal FROM candidato_canales WHERE candidato_id = ?");
        $stmt->execute([$candidato['id']]);
        $canales = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Build candidato object for display
        $candidatos[] = [
            'id' => $candidato['id'],
            'nombre' => $candidato['nombre'],
            'tipo' => $candidato['tipo'],
            'estatus' => $candidato['estatus'],
            'experienciaAnios' => $candidato['experiencia_anios'],
            'telefonosContacto' => $telefonos,
            'correosContacto' => $correos,
            'tiposCondominio' => $tiposCondominio,
            'experienciaEquipos' => $equipos,
            'canalesComunicacion' => $canales,
            'cargosAdicionales' => $candidato['cargos_adicionales_texto'],
            'visitoCondominio' => (bool)$candidato['visito_condominio'],
            'fechaVisitaCondominio' => $candidato['fecha_visita_condominio'],
            'mostrarEnListado' => (bool)$candidato['mostrar_en_listado'],
            'mostrarEnComparativa' => (bool)$candidato['mostrar_en_comparativa'],
            // Include all other fields for edit functionality
            'origenCandidato' => $candidato['origen_candidato'],
            'ultimaActualizacion' => $candidato['updated_at'],
            'prosocEstatus' => $candidato['prosoc_estatus'],
            'prosocVigenciaHasta' => $candidato['prosoc_vigencia_hasta'],
            'paginaWeb' => $candidato['pagina_web'],
            'redesSociales' => $candidato['redes_sociales'],
            'condominiosActuales' => $candidato['condominios_actuales'],
            'similarAlNuestro' => (bool)$candidato['similar_al_nuestro'],
            'quejasEnPROSOC' => (bool)$candidato['quejas_en_prosoc'],
            'quejasEnPROSOCDetalles' => $candidato['quejas_en_prosoc_detalles'],
            'removidoPorAsamblea' => (bool)$candidato['removido_por_asamblea'],
            'removidoPorAsambleaDetalles' => $candidato['removido_por_asamblea_detalles'],
            'manejoConflictosVecinos' => $candidato['manejo_conflictos_vecinos'],
            'problemasComplejosResueltos' => $candidato['problemas_complejos_resueltos'],
            'referencias' => $candidato['referencias'],
            'referenciasContactadas' => isset($candidato['referencias_contactadas']) ? (bool)$candidato['referencias_contactadas'] : null,
            'referenciasContactadasComentario' => $candidato['referencias_contactadas_comentario'],
            'clienteVisitado' => isset($candidato['cliente_visitado']) ? (bool)$candidato['cliente_visitado'] : null,
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
            'incluyeLimpieza' => (bool)$candidato['incluye_limpieza'],
            'costoPropuestaLimpieza' => $candidato['costo_propuesta_limpieza'],
            'propuestaLimpieza' => $candidato['propuesta_limpieza'],
            'incluyeMantenimiento' => (bool)$candidato['incluye_mantenimiento'],
            'costoPropuestaMantenimiento' => $candidato['costo_propuesta_mantenimiento'],
            'propuestaMantenimiento' => $candidato['propuesta_mantenimiento'],
            'incluyeVigilancia' => (bool)$candidato['incluye_vigilancia'],
            'costoPropuestaVigilancia' => $candidato['costo_propuesta_vigilancia'],
            'propuestaVigilancia' => $candidato['propuesta_vigilancia'],
            'incluyeOtrosServicios' => (bool)$candidato['incluye_otros_servicios'],
            'propuestaOtrosServicios' => $candidato['propuesta_otros_servicios'],
            'curriculumUrl' => $candidato['curriculum_url'],
            'certificacionProsocUrl' => $candidato['certificacion_prosoc_url'],
            'rfcCsfUrl' => $candidato['rfc_csf_url'],
            'presentacionServiciosUrl' => $candidato['presentacion_servicios_url'],
            'formatoEstadosCuentaUrl' => $candidato['formato_estados_cuenta_url'],
            'videoEntrevistaUrl' => $candidato['video_entrevista_url']
        ];
    }

} catch (PDOException $e) {
    error_log('Error loading candidatos from DB: ' . $e->getMessage());
    // Keep $candidatos as empty array
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Candidatos - Admin</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <div class="admin-header-inner">
                <h1>Gestionar Candidatos</h1>
                <div class="admin-user-info">
                    <span>Admin: <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="../admin-logout.php" class="btn btn-secondary btn-sm">Cerrar Sesión</a>
                </div>
            </div>
        </header>

        <main class="admin-main">
            <div class="admin-breadcrumb">
                <a href="index.php">← Volver al Dashboard</a>
            </div>

            <div id="alert-container"></div>

            <div style="margin-bottom: 2rem;">
                <button class="btn btn-primary" onclick="showAddForm()">+ Agregar Nuevo Candidato</button>
            </div>

            <!-- Lista de candidatos -->
            <div id="candidatos-list">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Nombre</th>
                            <th style="width: 15%;">Tipo</th>
                            <th style="width: 10%;">Estatus</th>
                            <th style="width: 15%;">Experiencia</th>
                            <th style="width: 15%;">Contacto</th>
                            <th style="width: 10%;">Visitó</th>
                            <th style="width: 10%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($candidatos as $candidato): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($candidato['nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($candidato['tipo'] ?? ''); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($candidato['estatus'] ?? 'En Revisión'); ?>
                                </td>
                                <td><?php echo isset($candidato['experienciaAnios']) ? $candidato['experienciaAnios'] . '+ años' : 'N/A'; ?></td>
                                <td><?php echo htmlspecialchars(is_array($candidato['telefonosContacto'] ?? null) ? implode(', ', $candidato['telefonosContacto']) : ($candidato['telefonosContacto'] ?? 'N/A')); ?></td>
                                <td><?php echo isset($candidato['visitoCondominio']) && $candidato['visitoCondominio'] ? 'Sí' : 'No'; ?></td>
                                <td class="admin-actions">
                                    <button class="btn btn-primary btn-sm" onclick='editCandidato(<?php echo json_encode($candidato, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>Editar</button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteCandidato('<?php echo htmlspecialchars($candidato['id']); ?>')">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if (empty($candidatos)): ?>
                    <div class="card" style="text-align: center; padding: 3rem; margin-top: 2rem;">
                        <p style="color: var(--text-muted); font-size: 1.1rem;">No hay candidatos todavía. Agrega el primero.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Modal de formulario -->
            <div id="candidato-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.9); z-index: 1000; padding: 2rem; overflow-y: auto;">
                <div style="max-width: 1400px; margin: 2rem auto; background: var(--bg); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                        <h2 id="modal-title">Agregar Candidato</h2>
                        <button onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
                    </div>

                    <div id="warning-container"></div>

                    <form id="candidato-form" class="admin-form">
                        <input type="hidden" id="candidato-id" name="id">

                        <!-- SISTEMA DE TABS -->
                        <div class="tab-container">
                            <div class="tab-buttons">
                                <button type="button" class="tab-button active" data-tab="tab-basico">Información Básica</button>
                                <button type="button" class="tab-button" data-tab="tab-contacto">Datos de Contacto</button>
                                <button type="button" class="tab-button" data-tab="tab-experiencia">Experiencia</button>
                                <button type="button" class="tab-button" data-tab="tab-servicio">Características del Servicio</button>
                                <button type="button" class="tab-button" data-tab="tab-costos">Costos y Honorarios</button>
                                <button type="button" class="tab-button" data-tab="tab-documentos">Documentos Solicitados</button>
                            </div>

                            <!-- TAB 1: INFORMACIÓN BÁSICA -->
                            <div id="tab-basico" class="tab-pane active">
                                <div class="form-section">
                                    <div class="form-group">
                                        <label for="origenCandidato">Origen del candidato</label>
                                        <input type="text" id="origenCandidato" name="origenCandidato" placeholder="Vecino A-101">
                                    </div>
                                </div>

                                <div class="form-divider"></div>

                                <div class="form-section">
                                    <h3>Datos Básicos</h3>
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label for="nombre">Nombre / Razón Social <span class="required">*</span></label>
                                            <input type="text" id="nombre" name="nombre" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="tipo">Tipo <span class="required">*</span></label>
                                            <select id="tipo" name="tipo" required>
                                                <option value="">Seleccionar...</option>
                                                <option value="Persona Física">Persona Física</option>
                                                <option value="Empresa">Empresa</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="estatus">Estatus</label>
                                            <input type="text" id="estatus" name="estatus" placeholder="En revisión">
                                        </div>

                                        <div class="form-group">
                                            <label for="mostrarEnListado">Mostrar en listado público</label>
                                            <select id="mostrarEnListado" name="mostrarEnListado">
                                                <option value="true">Sí</option>
                                                <option value="false">No</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="mostrarEnComparativa">Mostrar en tabla comparativa</label>
                                            <select id="mostrarEnComparativa" name="mostrarEnComparativa">
                                                <option value="true">Sí</option>
                                                <option value="false">No</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="visitoCondominio">Ya visitó el condominio</label>
                                            <select id="visitoCondominio" name="visitoCondominio">
                                                <option value="">No especificado</option>
                                                <option value="true">Sí</option>
                                                <option value="false">No</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="fechaVisitaCondominio">Fecha de visita al condominio</label>
                                            <input type="date" id="fechaVisitaCondominio" name="fechaVisitaCondominio">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-divider"></div>

                                <div class="form-section">
                                    <h3>Certificación PROSOC</h3>
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label for="prosocEstatus">Certificación PROSOC</label>
                                            <select id="prosocEstatus" name="prosocEstatus">
                                                <option value="">No especificado</option>
                                                <option value="Si">Sí</option>
                                                <option value="En Trámite">En Trámite</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="prosocVigenciaHasta">Vigencia Registro</label>
                                            <input type="date" id="prosocVigenciaHasta" name="prosocVigenciaHasta">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 2: DATOS DE CONTACTO -->
                            <div id="tab-contacto" class="tab-pane">
                                <div class="form-section">
                                    <h3>Información de Contacto</h3>
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label for="telefonosContacto">Teléfonos (separados por coma)</label>
                                            <input type="text" id="telefonosContacto" name="telefonosContacto" placeholder="55-1234-5678, 55-8765-4321">
                                            <span class="form-help-text">Formato: XX-XXXX-XXXX</span>
                                        </div>

                                        <div class="form-group">
                                            <label for="correosContacto">Correo Electrónico (separados por coma)</label>
                                            <input type="text" id="correosContacto" name="correosContacto" placeholder="contacto@ejemplo.com, admin@ejemplo.com">
                                        </div>

                                        <div class="form-group">
                                            <label for="paginaWeb">Página Web</label>
                                            <input type="url" id="paginaWeb" name="paginaWeb" placeholder="https://ejemplo.com">
                                        </div>

                                        <div class="form-group">
                                            <label for="redesSociales">Redes Sociales</label>
                                            <input type="text" id="redesSociales" name="redesSociales" placeholder="@ejemplo, facebook.com/ejemplo">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 3: EXPERIENCIA -->
                            <div id="tab-experiencia" class="tab-pane">
                                <div class="form-section">
                                    <h3>Experiencia General</h3>
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label for="experienciaAnios">Años de Experiencia</label>
                                            <input type="number" id="experienciaAnios" name="experienciaAnios" min="0" max="99">
                                        </div>

                                        <div class="form-group">
                                            <label for="condominiosActuales">¿Cuántos condominios administra actualmente?</label>
                                            <input type="number" id="condominiosActuales" name="condominiosActuales" min="0">
                                        </div>
                                    </div>

                                    <div class="form-group full-width">
                                        <label>Tipos de Condominios</label>
                                        <div class="checkbox-group">
                                            <label><input type="checkbox" name="tiposCondominio[]" value="Habitacional"> Habitacional</label>
                                            <label><input type="checkbox" name="tiposCondominio[]" value="Comercial"> Comercial</label>
                                            <label><input type="checkbox" name="tiposCondominio[]" value="Mixto"> Mixto</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="similarAlNuestro">¿Ha administrado condominios similares?</label>
                                        <select id="similarAlNuestro" name="similarAlNuestro">
                                            <option value="">No especificado</option>
                                            <option value="true">Sí</option>
                                            <option value="false">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group full-width">
                                        <label>Experiencia con equipos y sistemas</label>
                                        <div class="checkbox-group">
                                            <label><input type="checkbox" name="experienciaEquipos[]" value="Elevadores"> Elevadores</label>
                                            <label><input type="checkbox" name="experienciaEquipos[]" value="CCTV / Cámaras de seguridad"> CCTV / Cámaras de seguridad</label>
                                            <label><input type="checkbox" name="experienciaEquipos[]" value="Control de acceso"> Control de acceso</label>
                                            <label><input type="checkbox" name="experienciaEquipos[]" value="Cisternas y sistemas de bombeo"> Cisternas y sistemas de bombeo</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-divider"></div>

                                <div class="form-section">
                                    <h3>Historial</h3>
                                    <div class="form-group">
                                        <label for="quejasEnPROSOC">¿Ha tenido quejas en PROSOC?</label>
                                        <select id="quejasEnPROSOC" name="quejasEnPROSOC">
                                            <option value="">No especificado</option>
                                            <option value="true">Sí</option>
                                            <option value="false">No</option>
                                        </select>
                                    </div>

                                    <div id="quejasDetallesGroup" class="conditional-field" style="display:none;">
                                        <div class="form-group">
                                            <label for="quejasEnPROSOCDetalles">Detalles de las quejas</label>
                                            <textarea id="quejasEnPROSOCDetalles" name="quejasEnPROSOCDetalles" rows="2" placeholder="Describa las quejas..."></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="removidoPorAsamblea">¿Ha sido removido por asamblea en algún condominio?</label>
                                        <select id="removidoPorAsamblea" name="removidoPorAsamblea">
                                            <option value="">No especificado</option>
                                            <option value="true">Sí</option>
                                            <option value="false">No</option>
                                        </select>
                                    </div>

                                    <div id="removidoDetallesGroup" class="conditional-field" style="display:none;">
                                        <div class="form-group">
                                            <label for="removidoPorAsambleaDetalles">Detalles de la remoción</label>
                                            <textarea id="removidoPorAsambleaDetalles" name="removidoPorAsambleaDetalles" rows="2" placeholder="Describa las circunstancias..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-divider"></div>

                                <div class="form-section">
                                    <h3>Capacidades y Referencias</h3>
                                    <div class="form-group">
                                        <label for="manejoConflictosVecinos">¿Cómo abordan conflictos entre vecinos?</label>
                                        <textarea id="manejoConflictosVecinos" name="manejoConflictosVecinos" rows="3" placeholder="Describa su enfoque para resolver conflictos..."></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="problemasComplejosResueltos">Ejemplos de problemas complejos y cómo los resolvieron</label>
                                        <textarea id="problemasComplejosResueltos" name="problemasComplejosResueltos" rows="3" placeholder="Describa casos específicos..."></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="referencias">Referencias</label>
                                        <textarea id="referencias" name="referencias" rows="2" placeholder="Nombres de condominios o clientes de referencia"></textarea>
                                        <span class="form-help-text">Por privacidad, no incluir direcciones completas</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="referenciasContactadas">¿Se contactaron las referencias?</label>
                                        <select id="referenciasContactadas" name="referenciasContactadas">
                                            <option value="">No especificado</option>
                                            <option value="true">Sí</option>
                                            <option value="false">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="referenciasContactadasComentario">Comentario sobre referencias contactadas</label>
                                        <textarea id="referenciasContactadasComentario" name="referenciasContactadasComentario" rows="3" placeholder="Notas sobre el contacto con las referencias..."></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="clienteVisitado">¿Se visitó algún condominio cliente?</label>
                                        <select id="clienteVisitado" name="clienteVisitado">
                                            <option value="">No especificado</option>
                                            <option value="true">Sí</option>
                                            <option value="false">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 4: CARACTERÍSTICAS DEL SERVICIO -->
                            <div id="tab-servicio" class="tab-pane">
                                <div class="form-section">
                                    <h3>Descripción del Servicio</h3>
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label for="tamanoEquipo">Tamaño del equipo</label>
                                            <input type="text" id="tamanoEquipo" name="tamanoEquipo" placeholder="Ej: 5 personas">
                                        </div>

                                        <div class="form-group">
                                            <label for="personalApoyo">Personal de apoyo</label>
                                            <input type="text" id="personalApoyo" name="personalApoyo" placeholder="Ej: 1 administrador, 2 asistentes, 1 contador">
                                        </div>

                                        <div class="form-group">
                                            <label for="horariosAtencion">Horarios de Atención</label>
                                            <input type="text" id="horariosAtencion" name="horariosAtencion" placeholder="Ej: Lun-Vie 9:00-18:00">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-divider"></div>

                                <div class="form-section">
                                    <h3>Canales de Comunicación</h3>
                                    <div class="form-group full-width">
                                        <div class="checkbox-group-inline">
                                            <!-- Checkbox CON campo condicional -->
                                            <div class="checkbox-with-field">
                                                <label class="checkbox-label">
                                                    <input type="checkbox" name="canalesComunicacion[]" value="App / Portal en línea" id="checkAppPortal">
                                                    App / Portal en línea
                                                </label>
                                                <div class="conditional-input-inline" id="canalAppPortalTextoGroup" style="display: none;">
                                                    <input type="text" id="canalAppPortalTexto" name="canalAppPortalTexto" placeholder="Nombre (ej: Vivook, sistema propio)" class="inline-text-field">
                                                </div>
                                            </div>

                                            <!-- Checkboxes SIN campo condicional -->
                                            <div class="checkbox-with-field">
                                                <label class="checkbox-label">
                                                    <input type="checkbox" name="canalesComunicacion[]" value="WhatsApp">
                                                    WhatsApp
                                                </label>
                                            </div>

                                            <div class="checkbox-with-field">
                                                <label class="checkbox-label">
                                                    <input type="checkbox" name="canalesComunicacion[]" value="Correo Electrónico">
                                                    Correo Electrónico
                                                </label>
                                            </div>

                                            <div class="checkbox-with-field">
                                                <label class="checkbox-label">
                                                    <input type="checkbox" name="canalesComunicacion[]" value="Teléfono">
                                                    Teléfono
                                                </label>
                                            </div>

                                            <div class="checkbox-with-field">
                                                <label class="checkbox-label">
                                                    <input type="checkbox" name="canalesComunicacion[]" value="Buzón de quejas y/o sugerencias">
                                                    Buzón de quejas y/o sugerencias
                                                </label>
                                            </div>

                                            <!-- Checkbox CON campo condicional -->
                                            <div class="checkbox-with-field">
                                                <label class="checkbox-label">
                                                    <input type="checkbox" name="canalesComunicacion[]" value="Atención presencial" id="checkPresencial">
                                                    Atención presencial
                                                </label>
                                                <div class="conditional-input-inline" id="canalPresencialTextoGroup" style="display: none;">
                                                    <input type="text" id="canalPresencialTexto" name="canalPresencialTexto" placeholder="Ubicación/horarios (ej: Torre A, PB - Lun-Vie 9-14h)" class="inline-text-field">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-divider"></div>

                                <div class="form-section">
                                    <h3>Manejo de Incidencias</h3>
                                    <div class="form-group">
                                        <label for="flujoIncidencias">Flujo de incidencias</label>
                                        <textarea id="flujoIncidencias" name="flujoIncidencias" rows="3" placeholder="Ej: Se levanta ticket, clasificación, asignación y seguimiento"></textarea>
                                    </div>

                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label for="tiempoRespuestaNormal">Tiempo de respuesta normal</label>
                                            <input type="text" id="tiempoRespuestaNormal" name="tiempoRespuestaNormal" placeholder="Ej: 48 horas">
                                        </div>

                                        <div class="form-group">
                                            <label for="tiempoRespuestaEmergencias">Tiempo de respuesta emergencias</label>
                                            <input type="text" id="tiempoRespuestaEmergencias" name="tiempoRespuestaEmergencias" placeholder="Ej: 6 horas">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-divider"></div>

                                <div class="form-section">
                                    <h3>Gestión Financiera y Administrativa</h3>
                                    <div class="form-group">
                                        <label for="procesoCobranza">Proceso de cobranza</label>
                                        <textarea id="procesoCobranza" name="procesoCobranza" rows="3" placeholder="Ej: Aviso día 5, recordatorio día 10, convenio día 15..."></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="propuestaManejoFondos">Propuesta de manejo de fondos</label>
                                        <textarea id="propuestaManejoFondos" name="propuestaManejoFondos" rows="3" placeholder="Ej: Separación de fondos en cuentas distintas con reportes mensuales..."></textarea>
                                    </div>

                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label for="tiempoPublicacionEstadosCuenta">¿En cuánto tiempo suele publicar estados de cuenta?</label>
                                            <input type="text" id="tiempoPublicacionEstadosCuenta" name="tiempoPublicacionEstadosCuenta" placeholder="Ej: Primeros 5 días del mes">
                                        </div>

                                        <div class="form-group">
                                            <label for="formaEntregaEstadosCuenta">Forma de entrega</label>
                                            <input type="text" id="formaEntregaEstadosCuenta" name="formaEntregaEstadosCuenta" placeholder="Ej: Portal en línea y correo electrónico">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-divider"></div>

                                <div class="form-section">
                                    <h3>Plan Inicial</h3>
                                    <div class="form-group">
                                        <label for="planPrimeros90Dias">Plan primeros 90 días</label>
                                        <textarea id="planPrimeros90Dias" name="planPrimeros90Dias" rows="4" placeholder="Ej: Diagnóstico de finanzas, actualización de padrón, plan de mantenimiento..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 5: COSTOS Y HONORARIOS -->
                            <div id="tab-costos" class="tab-pane">
                                <div class="form-section">
                                    <h3>Costos y Honorarios</h3>
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label for="costoMensual">Honorarios mensuales de administración (MXN)</label>
                                            <input type="number" id="costoMensual" name="costoMensual" min="0" step="0.01" placeholder="0.00">
                                            <span class="form-help-text">Monto en pesos mexicanos</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="cuotaMantenimientoPropuesta">Cuota de Mantenimiento Propuesta (MXN)</label>
                                            <input type="text" id="cuotaMantenimientoPropuesta" name="cuotaMantenimientoPropuesta" placeholder="Ej: $15,000 MXN">
                                            <span class="form-help-text">Monto mensual propuesto para el fondo de mantenimiento</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="cargosAdicionales">Cargos adicionales (uno por línea)</label>
                                        <textarea id="cargosAdicionales" name="cargosAdicionales" rows="4" placeholder="Ej:&#10;Asambleas extraordinarias&#10;Proyectos especiales&#10;Gestión de proveedores"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="costosInfoAdicional">Información adicional sobre costos</label>
                                        <textarea id="costosInfoAdicional" name="costosInfoAdicional" rows="3" placeholder="Información adicional sobre honorarios, descuentos, formas de pago, etc."></textarea>
                                    </div>

                                    <h4 style="margin-top: 1.5rem; margin-bottom: 1rem; color: var(--text-secondary);">Propuesta de Otros Servicios</h4>

                                    <div class="form-group">
                                        <label class="checkbox-label">
                                            <input type="checkbox" id="incluyeLimpieza" name="incluyeLimpieza">
                                            Incluye servicio de Limpieza
                                        </label>
                                    </div>
                                    <div class="form-group" id="grupo-propuestaLimpieza" style="display: none;">
                                        <label for="costoPropuestaLimpieza">Costo mensual de Limpieza</label>
                                        <input type="text" id="costoPropuestaLimpieza" name="costoPropuestaLimpieza" placeholder="Ej: $5,000 MXN">
                                        <label for="propuestaLimpieza" style="margin-top: 1rem;">Detalles de propuesta de Limpieza</label>
                                        <textarea id="propuestaLimpieza" name="propuestaLimpieza" rows="3" placeholder="Descripción del servicio de limpieza, frecuencia, etc."></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="checkbox-label">
                                            <input type="checkbox" id="incluyeMantenimiento" name="incluyeMantenimiento">
                                            Incluye servicio de Mantenimiento
                                        </label>
                                    </div>
                                    <div class="form-group" id="grupo-propuestaMantenimiento" style="display: none;">
                                        <label for="costoPropuestaMantenimiento">Costo mensual de Mantenimiento</label>
                                        <input type="text" id="costoPropuestaMantenimiento" name="costoPropuestaMantenimiento" placeholder="Ej: $8,000 MXN">
                                        <label for="propuestaMantenimiento" style="margin-top: 1rem;">Detalles de propuesta de Mantenimiento</label>
                                        <textarea id="propuestaMantenimiento" name="propuestaMantenimiento" rows="3" placeholder="Descripción del servicio de mantenimiento, alcance, etc."></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="checkbox-label">
                                            <input type="checkbox" id="incluyeVigilancia" name="incluyeVigilancia">
                                            Incluye servicio de Vigilancia
                                        </label>
                                    </div>
                                    <div class="form-group" id="grupo-propuestaVigilancia" style="display: none;">
                                        <label for="costoPropuestaVigilancia">Costo mensual de Vigilancia</label>
                                        <input type="text" id="costoPropuestaVigilancia" name="costoPropuestaVigilancia" placeholder="Ej: $12,000 MXN">
                                        <label for="propuestaVigilancia" style="margin-top: 1rem;">Detalles de propuesta de Vigilancia</label>
                                        <textarea id="propuestaVigilancia" name="propuestaVigilancia" rows="3" placeholder="Descripción del servicio de vigilancia, horarios, etc."></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="checkbox-label">
                                            <input type="checkbox" id="incluyeOtrosServicios" name="incluyeOtrosServicios">
                                            Incluye otros servicios adicionales
                                        </label>
                                    </div>
                                    <div class="form-group" id="grupo-propuestaOtrosServicios" style="display: none;">
                                        <label for="propuestaOtrosServicios">Detalles de otros servicios</label>
                                        <textarea id="propuestaOtrosServicios" name="propuestaOtrosServicios" rows="3" placeholder="Otros servicios adicionales que ofrece el candidato..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 6: DOCUMENTOS SOLICITADOS -->
                            <div id="tab-documentos" class="tab-pane">
                                <div class="form-section">
                                    <h3>Documentos del Candidato</h3>
                                    <p class="form-help-text" style="margin-bottom: 1.5rem;">Sube los documentos que el candidato ha proporcionado. Puedes subir múltiples archivos del mismo tipo. Si hay documentos subidos, se mostrarán en la vista pública.</p>

                                    <div class="form-group">
                                        <label>Currículum <span class="file-count" id="curriculum-count"></span></label>
                                        <input type="hidden" id="curriculumUrl" name="curriculumUrl">
                                        <div id="curriculum-list" class="document-list-container"></div>
                                        <div class="file-upload-container" style="margin-top: 0.5rem;">
                                            <input type="file" id="curriculumFile" accept=".pdf,.doc,.docx" class="file-input" multiple>
                                            <div class="file-upload-info">
                                                <span class="file-icon">📄</span>
                                                <span class="file-text">Agregar currículum (PDF, DOC, DOCX)</span>
                                            </div>
                                        </div>
                                        <div id="curriculumFile-preview" class="file-preview"></div>
                                    </div>

                                    <div class="form-group">
                                        <label>Certificación PROSOC <span class="file-count" id="certificacion-prosoc-count"></span></label>
                                        <input type="hidden" id="certificacionProsocUrl" name="certificacionProsocUrl">
                                        <div id="certificacion-prosoc-list" class="document-list-container"></div>
                                        <div class="file-upload-container" style="margin-top: 0.5rem;">
                                            <input type="file" id="certificacionProsocFile" accept=".pdf,.jpg,.jpeg,.png" class="file-input" multiple>
                                            <div class="file-upload-info">
                                                <span class="file-icon">📜</span>
                                                <span class="file-text">Agregar certificación PROSOC (PDF o imagen)</span>
                                            </div>
                                        </div>
                                        <div id="certificacionProsocFile-preview" class="file-preview"></div>
                                    </div>

                                    <div class="form-group">
                                        <label>RFC / CSF <span class="file-count" id="rfc-csf-count"></span></label>
                                        <input type="hidden" id="rfcCsfUrl" name="rfcCsfUrl">
                                        <div id="rfc-csf-list" class="document-list-container"></div>
                                        <div class="file-upload-container" style="margin-top: 0.5rem;">
                                            <input type="file" id="rfcCsfFile" accept=".pdf,.jpg,.jpeg,.png" class="file-input" multiple>
                                            <div class="file-upload-info">
                                                <span class="file-icon">🆔</span>
                                                <span class="file-text">Agregar RFC/CSF (PDF o imagen)</span>
                                            </div>
                                        </div>
                                        <div id="rfcCsfFile-preview" class="file-preview"></div>
                                    </div>

                                    <div class="form-group">
                                        <label>Presentación de servicios <span class="file-count" id="presentacion-servicios-count"></span></label>
                                        <input type="hidden" id="presentacionServiciosUrl" name="presentacionServiciosUrl">
                                        <div id="presentacion-servicios-list" class="document-list-container"></div>
                                        <div class="file-upload-container" style="margin-top: 0.5rem;">
                                            <input type="file" id="presentacionServiciosFile" accept=".pdf,.ppt,.pptx,.doc,.docx" class="file-input" multiple>
                                            <div class="file-upload-info">
                                                <span class="file-icon">📊</span>
                                                <span class="file-text">Agregar presentación (PDF, PPT, DOC)</span>
                                            </div>
                                        </div>
                                        <div id="presentacionServiciosFile-preview" class="file-preview"></div>
                                    </div>

                                    <div class="form-group">
                                        <label>Formato estados de cuenta <span class="file-count" id="formato-estado-cuenta-count"></span></label>
                                        <input type="hidden" id="formatoEstadosCuentaUrl" name="formatoEstadosCuentaUrl">
                                        <div id="formato-estado-cuenta-list" class="document-list-container"></div>
                                        <div class="file-upload-container" style="margin-top: 0.5rem;">
                                            <input type="file" id="formatoEstadosCuentaFile" accept=".pdf,.xls,.xlsx,.doc,.docx" class="file-input" multiple>
                                            <div class="file-upload-info">
                                                <span class="file-icon">📑</span>
                                                <span class="file-text">Agregar formato (PDF, Excel, DOC)</span>
                                            </div>
                                        </div>
                                        <div id="formatoEstadosCuentaFile-preview" class="file-preview"></div>
                                    </div>

                                    <div class="form-group">
                                        <label>Propuesta formal <span class="file-count" id="propuesta-formal-count"></span></label>
                                        <input type="hidden" id="propuestaFormalUrl" name="propuestaFormalUrl">
                                        <div id="propuesta-formal-list" class="document-list-container"></div>
                                        <div class="file-upload-container" style="margin-top: 0.5rem;">
                                            <input type="file" id="propuestaFormalFile" accept=".pdf,.doc,.docx" class="file-input" multiple>
                                            <div class="file-upload-info">
                                                <span class="file-icon">📋</span>
                                                <span class="file-text">Agregar propuesta (PDF, DOC)</span>
                                            </div>
                                        </div>
                                        <div id="propuestaFormalFile-preview" class="file-preview"></div>
                                    </div>

                                    <div class="form-group">
                                        <label>Presentación de propuesta<span class="file-count" id="video-entrevista-count"></span></label>
                                        <input type="hidden" id="videoEntrevistaUrl" name="videoEntrevistaUrl">
                                        <div id="video-entrevista-list" class="document-list-container"></div>
                                        <div class="file-upload-container" style="margin-top: 0.5rem;">
                                            <input type="file" id="videoEntrevistaFile" accept=".mp4,.webm,.mov" class="file-input" multiple>
                                            <div class="file-upload-info">
                                                <span class="file-icon">🎥</span>
                                                <span class="file-text">Agregar video de entrevista (MP4, WEBM, MOV)</span>
                                            </div>
                                        </div>
                                        <div id="videoEntrevistaFile-preview" class="file-preview"></div>
                                    </div>

                                    <div class="form-group">
                                        <label>Cartas de Recomendación <span class="file-count" id="cartas-recomendacion-count"></span></label>
                                        <input type="hidden" id="cartasRecomendacionUrl" name="cartasRecomendacionUrl">
                                        <div id="cartas-recomendacion-list" class="document-list-container"></div>
                                        <div class="file-upload-container" style="margin-top: 0.5rem;">
                                            <input type="file" id="cartasRecomendacionFile" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="file-input" multiple>
                                            <div class="file-upload-info">
                                                <span class="file-icon">📝</span>
                                                <span class="file-text">Agregar carta de recomendación (PDF, imagen o DOC)</span>
                                            </div>
                                        </div>
                                        <div id="cartasRecomendacionFile-preview" class="file-preview"></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="admin-form-actions" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-subtle);">
                            <button type="submit" class="btn btn-primary">Guardar Candidato</button>
                            <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Cargar scripts del formulario -->
    <script src="js/candidatos-form.js?v=<?= filemtime('js/candidatos-form.js') ?>"></script>
    <script src="js/candidatos-admin.js?v=<?= filemtime('js/candidatos-admin.js') ?>"></script>
</body>
</html>
