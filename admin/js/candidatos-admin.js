/**
 * Admin functions for candidatos management
 */

function showAddForm() {
    document.getElementById('modal-title').textContent = 'Agregar Nuevo Candidato';
    document.getElementById('candidato-form').reset();
    document.getElementById('candidato-id').value = '';

    // Limpiar checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);

    // Ocultar textareas de servicios adicionales
    document.getElementById('grupo-propuestaLimpieza').style.display = 'none';
    document.getElementById('grupo-propuestaMantenimiento').style.display = 'none';
    document.getElementById('grupo-propuestaVigilancia').style.display = 'none';
    document.getElementById('grupo-propuestaOtrosServicios').style.display = 'none';

    // Limpiar listas de documentos múltiples
    const docLists = ['curriculum-list', 'certificacion-prosoc-list', 'rfc-csf-list',
                      'presentacion-servicios-list', 'formato-estado-cuenta-list', 'video-entrevista-list'];
    docLists.forEach(listId => {
        const listDiv = document.getElementById(listId);
        if (listDiv) listDiv.innerHTML = '';
    });

    // Limpiar contadores de documentos
    const docCounts = ['curriculum-count', 'certificacion-prosoc-count', 'rfc-csf-count',
                       'presentacion-servicios-count', 'formato-estado-cuenta-count', 'video-entrevista-count'];
    docCounts.forEach(countId => {
        const countSpan = document.getElementById(countId);
        if (countSpan) countSpan.textContent = '';
    });

    // Activar primera pestaña
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
    document.querySelector('.tab-button[data-tab="tab-basico"]').classList.add('active');
    document.getElementById('tab-basico').classList.add('active');

    document.getElementById('candidato-modal').style.display = 'block';
}

function editCandidato(candidato) {
    document.getElementById('modal-title').textContent = 'Editar Candidato';
    document.getElementById('candidato-id').value = candidato.id || '';

    // Sección 1: Información Básica
    document.getElementById('origenCandidato').value = candidato.origenCandidato || '';
    document.getElementById('nombre').value = candidato.nombre || '';
    document.getElementById('tipo').value = candidato.tipo || '';
    document.getElementById('estatus').value = candidato.estatus || 'En revisión';
    document.getElementById('mostrarEnListado').value = candidato.mostrarEnListado === true ? 'true' : 'false';
    document.getElementById('mostrarEnComparativa').value = candidato.mostrarEnComparativa === true ? 'true' : 'false';
    document.getElementById('visitoCondominio').value = candidato.visitoCondominio === true ? 'true' : (candidato.visitoCondominio === false ? 'false' : '');
    document.getElementById('fechaVisitaCondominio').value = candidato.fechaVisitaCondominio || '';
    document.getElementById('prosocEstatus').value = candidato.prosocEstatus || '';
    document.getElementById('prosocVigenciaHasta').value = candidato.prosocVigenciaHasta || '';

    // Sección 2: Datos de Contacto
    document.getElementById('telefonosContacto').value = Array.isArray(candidato.telefonosContacto) ? candidato.telefonosContacto.join(', ') : (candidato.telefonosContacto || '');
    document.getElementById('correosContacto').value = Array.isArray(candidato.correosContacto) ? candidato.correosContacto.join(', ') : (candidato.correosContacto || '');
    document.getElementById('paginaWeb').value = candidato.paginaWeb || '';
    document.getElementById('redesSociales').value = candidato.redesSociales || '';

    // Sección 3: Experiencia
    document.getElementById('experienciaAnios').value = candidato.experienciaAnios || '';
    document.getElementById('condominiosActuales').value = candidato.condominiosActuales || '';

    if (Array.isArray(candidato.tiposCondominio)) {
        candidato.tiposCondominio.forEach(tipo => {
            const cb = document.querySelector(`input[name="tiposCondominio[]"][value="${tipo}"]`);
            if (cb) cb.checked = true;
        });
    }

    document.getElementById('similarAlNuestro').value = candidato.similarAlNuestro === true ? 'true' : (candidato.similarAlNuestro === false ? 'false' : '');

    if (Array.isArray(candidato.experienciaEquipos)) {
        candidato.experienciaEquipos.forEach(equipo => {
            const cb = document.querySelector(`input[name="experienciaEquipos[]"][value="${equipo}"]`);
            if (cb) cb.checked = true;
        });
    }

    document.getElementById('quejasEnPROSOC').value = candidato.quejasEnPROSOC === true ? 'true' : (candidato.quejasEnPROSOC === false ? 'false' : '');
    document.getElementById('quejasEnPROSOCDetalles').value = candidato.quejasEnPROSOCDetalles || '';
    document.getElementById('removidoPorAsamblea').value = candidato.removidoPorAsamblea === true ? 'true' : (candidato.removidoPorAsamblea === false ? 'false' : '');
    document.getElementById('removidoPorAsambleaDetalles').value = candidato.removidoPorAsambleaDetalles || '';
    document.getElementById('manejoConflictosVecinos').value = candidato.manejoConflictosVecinos || '';
    document.getElementById('problemasComplejosResueltos').value = candidato.problemasComplejosResueltos || '';
    document.getElementById('referencias').value = candidato.referencias || '';
    document.getElementById('referenciasContactadas').value = candidato.referenciasContactadas === true ? 'true' : (candidato.referenciasContactadas === false ? 'false' : '');
    document.getElementById('referenciasContactadasComentario').value = candidato.referenciasContactadasComentario || '';
    document.getElementById('clienteVisitado').value = candidato.clienteVisitado === true ? 'true' : (candidato.clienteVisitado === false ? 'false' : '');

    // Sección 4: Características del Servicio
    document.getElementById('tamanoEquipo').value = candidato.tamanoEquipo || '';
    document.getElementById('personalApoyo').value = candidato.personalApoyo || '';
    document.getElementById('horariosAtencion').value = candidato.horariosAtencion || '';

    if (Array.isArray(candidato.canalesComunicacion)) {
        candidato.canalesComunicacion.forEach(canal => {
            const cb = document.querySelector(`input[name="canalesComunicacion[]"][value="${canal}"]`);
            if (cb) cb.checked = true;
        });
    }

    document.getElementById('canalAppPortalTexto').value = candidato.canalAppPortalTexto || '';
    document.getElementById('canalPresencialTexto').value = candidato.canalPresencialTexto || '';

    // Disparar eventos change para activar campos condicionales inline
    setTimeout(() => {
        const checkAppPortal = document.getElementById('checkAppPortal');
        const checkPresencial = document.getElementById('checkPresencial');

        if (checkAppPortal) checkAppPortal.dispatchEvent(new Event('change'));
        if (checkPresencial) checkPresencial.dispatchEvent(new Event('change'));
    }, 100);

    document.getElementById('flujoIncidencias').value = candidato.flujoIncidencias || '';
    document.getElementById('tiempoRespuestaNormal').value = candidato.tiempoRespuestaNormal || '';
    document.getElementById('tiempoRespuestaEmergencias').value = candidato.tiempoRespuestaEmergencias || '';
    document.getElementById('procesoCobranza').value = candidato.procesoCobranza || '';
    document.getElementById('propuestaManejoFondos').value = candidato.propuestaManejoFondos || '';
    document.getElementById('tiempoPublicacionEstadosCuenta').value = candidato.tiempoPublicacionEstadosCuenta || '';
    document.getElementById('formaEntregaEstadosCuenta').value = candidato.formaEntregaEstadosCuenta || '';
    document.getElementById('planPrimeros90Dias').value = candidato.planPrimeros90Dias || '';

    // Sección 5: Costos y Honorarios
    document.getElementById('costoMensual').value = candidato.costoMensual || '';
    document.getElementById('cuotaMantenimientoPropuesta').value = candidato.cuotaMantenimientoPropuesta || '';
    document.getElementById('cargosAdicionales').value = candidato.cargosAdicionales || '';
    document.getElementById('costosInfoAdicional').value = candidato.costosInfoAdicional || '';
    document.getElementById('incluyeLimpieza').checked = candidato.incluyeLimpieza === true;
    document.getElementById('costoPropuestaLimpieza').value = candidato.costoPropuestaLimpieza || '';
    document.getElementById('propuestaLimpieza').value = candidato.propuestaLimpieza || '';
    document.getElementById('grupo-propuestaLimpieza').style.display = candidato.incluyeLimpieza ? 'block' : 'none';
    document.getElementById('incluyeMantenimiento').checked = candidato.incluyeMantenimiento === true;
    document.getElementById('costoPropuestaMantenimiento').value = candidato.costoPropuestaMantenimiento || '';
    document.getElementById('propuestaMantenimiento').value = candidato.propuestaMantenimiento || '';
    document.getElementById('grupo-propuestaMantenimiento').style.display = candidato.incluyeMantenimiento ? 'block' : 'none';
    document.getElementById('incluyeVigilancia').checked = candidato.incluyeVigilancia === true;
    document.getElementById('costoPropuestaVigilancia').value = candidato.costoPropuestaVigilancia || '';
    document.getElementById('propuestaVigilancia').value = candidato.propuestaVigilancia || '';
    document.getElementById('grupo-propuestaVigilancia').style.display = candidato.incluyeVigilancia ? 'block' : 'none';
    document.getElementById('incluyeOtrosServicios').checked = candidato.incluyeOtrosServicios === true;
    document.getElementById('propuestaOtrosServicios').value = candidato.propuestaOtrosServicios || '';
    document.getElementById('grupo-propuestaOtrosServicios').style.display = candidato.incluyeOtrosServicios ? 'block' : 'none';

    // Nota: Los documentos se cargan desde candidato_documentos via loadCandidatoDocumentos()

    // Activar primera pestaña
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
    document.querySelector('.tab-button[data-tab="tab-basico"]').classList.add('active');
    document.getElementById('tab-basico').classList.add('active');

    document.getElementById('candidato-modal').style.display = 'block';

    // Cargar documentos múltiples desde la base de datos
    if (candidato.id && typeof loadCandidatoDocumentos === 'function') {
        loadCandidatoDocumentos(candidato.id);
    }
}

function closeModal() {
    document.getElementById('candidato-modal').style.display = 'none';
}

async function deleteCandidato(id) {
    if (!confirm('¿Estás seguro de que quieres eliminar este candidato?\n\nEsta acción no se puede deshacer.')) {
        return;
    }

    try {
        const response = await fetch('api/delete-candidato.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });

        const result = await response.json();

        if (result.success) {
            showAlert('Candidato eliminado correctamente', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(result.error || 'Error al eliminar el candidato', 'error');
        }
    } catch (error) {
        showAlert('Error de conexión: ' + error.message, 'error');
    }
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;

    const container = document.getElementById('alert-container');
    container.innerHTML = '';
    container.appendChild(alertDiv);

    setTimeout(() => alertDiv.remove(), 5000);
}
