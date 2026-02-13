// Gestión del formulario de candidatos con tabs y validaciones
document.addEventListener('DOMContentLoaded', () => {
  // Variable para tracking de ID temporal usado en uploads de candidatos nuevos
  let uploadTempId = null;

  // ===================
  // SISTEMA DE TABS
  // ===================
  const tabButtons = document.querySelectorAll('.tab-button');
  const tabPanes = document.querySelectorAll('.tab-pane');

  tabButtons.forEach(button => {
    button.addEventListener('click', () => {
      const targetTab = button.dataset.tab;

      // Remover active de todos
      tabButtons.forEach(btn => btn.classList.remove('active'));
      tabPanes.forEach(pane => pane.classList.remove('active'));

      // Activar el seleccionado
      button.classList.add('active');
      document.getElementById(targetTab).classList.add('active');
    });
  });

  // ===================
  // CAMPOS CONDICIONALES
  // ===================

  // Quejas en PROSOC: Mostrar detalles si es Sí
  const quejasEnPROSOC = document.getElementById('quejasEnPROSOC');
  const quejasDetallesGroup = document.getElementById('quejasDetallesGroup');

  if (quejasEnPROSOC && quejasDetallesGroup) {
    quejasEnPROSOC.addEventListener('change', (e) => {
      quejasDetallesGroup.style.display = e.target.value === 'true' ? 'block' : 'none';

      if (e.target.value !== 'true') {
        document.getElementById('quejasEnPROSOCDetalles').value = '';
      }
    });

    // Trigger inicial
    quejasDetallesGroup.style.display = quejasEnPROSOC.value === 'true' ? 'block' : 'none';
  }

  // Removido por asamblea: Mostrar detalles si es Sí
  const removidoPorAsamblea = document.getElementById('removidoPorAsamblea');
  const removidoDetallesGroup = document.getElementById('removidoDetallesGroup');

  if (removidoPorAsamblea && removidoDetallesGroup) {
    removidoPorAsamblea.addEventListener('change', (e) => {
      removidoDetallesGroup.style.display = e.target.value === 'true' ? 'block' : 'none';

      if (e.target.value !== 'true') {
        document.getElementById('removidoPorAsambleaDetalles').value = '';
      }
    });

    // Trigger inicial
    removidoDetallesGroup.style.display = removidoPorAsamblea.value === 'true' ? 'block' : 'none';
  }

  // Canales de comunicación inline: App/Portal y Atención presencial
  const conditionalChannelFields = [
    {
      checkboxId: 'checkAppPortal',
      fieldGroupId: 'canalAppPortalTextoGroup',
      fieldId: 'canalAppPortalTexto'
    },
    {
      checkboxId: 'checkPresencial',
      fieldGroupId: 'canalPresencialTextoGroup',
      fieldId: 'canalPresencialTexto'
    }
  ];

  conditionalChannelFields.forEach(config => {
    const checkbox = document.getElementById(config.checkboxId);
    const fieldGroup = document.getElementById(config.fieldGroupId);
    const field = document.getElementById(config.fieldId);

    if (checkbox && fieldGroup && field) {
      // Evento change del checkbox
      checkbox.addEventListener('change', function() {
        if (this.checked) {
          fieldGroup.style.display = 'block';
          setTimeout(() => field.focus(), 100); // Auto-focus con pequeño delay para la animación
        } else {
          fieldGroup.style.display = 'none';
          field.value = ''; // Limpiar valor cuando se desmarca
        }
      });

      // Estado inicial: si el campo tiene valor, marcar checkbox y mostrar campo
      // (útil al editar un candidato existente)
      if (field.value.trim() !== '') {
        checkbox.checked = true;
        fieldGroup.style.display = 'block';
      }
    }
  });

  // Servicios adicionales: mostrar/ocultar textarea según checkbox
  const conditionalServiceFields = [
    { checkboxId: 'incluyeLimpieza', fieldGroupId: 'grupo-propuestaLimpieza', fieldId: 'propuestaLimpieza' },
    { checkboxId: 'incluyeMantenimiento', fieldGroupId: 'grupo-propuestaMantenimiento', fieldId: 'propuestaMantenimiento' },
    { checkboxId: 'incluyeVigilancia', fieldGroupId: 'grupo-propuestaVigilancia', fieldId: 'propuestaVigilancia' },
    { checkboxId: 'incluyeOtrosServicios', fieldGroupId: 'grupo-propuestaOtrosServicios', fieldId: 'propuestaOtrosServicios' }
  ];

  conditionalServiceFields.forEach(config => {
    const checkbox = document.getElementById(config.checkboxId);
    const fieldGroup = document.getElementById(config.fieldGroupId);
    const field = document.getElementById(config.fieldId);

    if (checkbox && fieldGroup && field) {
      checkbox.addEventListener('change', function() {
        fieldGroup.style.display = this.checked ? 'block' : 'none';
        if (this.checked) {
          setTimeout(() => field.focus(), 100);
        }
      });
    }
  });

  // ===================
  // MANEJO DE ARCHIVOS (MÚLTIPLES POR TIPO)
  // ===================

  // Mapeo de inputs de archivo a sus campos de URL y contenedores de lista
  const fileInputs = {
    'curriculumFile': { urlField: 'curriculumUrl', tipo: 'curriculum', previewId: 'curriculumFile-preview', listId: 'curriculum-list', countId: 'curriculum-count' },
    'certificacionProsocFile': { urlField: 'certificacionProsocUrl', tipo: 'certificacion-prosoc', previewId: 'certificacionProsocFile-preview', listId: 'certificacion-prosoc-list', countId: 'certificacion-prosoc-count' },
    'rfcCsfFile': { urlField: 'rfcCsfUrl', tipo: 'rfc-csf', previewId: 'rfcCsfFile-preview', listId: 'rfc-csf-list', countId: 'rfc-csf-count' },
    'presentacionServiciosFile': { urlField: 'presentacionServiciosUrl', tipo: 'presentacion-servicios', previewId: 'presentacionServiciosFile-preview', listId: 'presentacion-servicios-list', countId: 'presentacion-servicios-count' },
    'formatoEstadosCuentaFile': { urlField: 'formatoEstadosCuentaUrl', tipo: 'formato-estado-cuenta', previewId: 'formatoEstadosCuentaFile-preview', listId: 'formato-estado-cuenta-list', countId: 'formato-estado-cuenta-count' },
    'propuestaFormalFile': { urlField: 'propuestaFormalUrl', tipo: 'propuesta-formal', previewId: 'propuestaFormalFile-preview', listId: 'propuesta-formal-list', countId: 'propuesta-formal-count' },
    'videoEntrevistaFile': { urlField: 'videoEntrevistaUrl', tipo: 'video-entrevista', previewId: 'videoEntrevistaFile-preview', listId: 'video-entrevista-list', countId: 'video-entrevista-count' },
    'cartasRecomendacionFile': { urlField: 'cartasRecomendacionUrl', tipo: 'cartas-recomendacion', previewId: 'cartasRecomendacionFile-preview', listId: 'cartas-recomendacion-list', countId: 'cartas-recomendacion-count' }
  };

  // Función para cargar documentos existentes de un candidato
  window.loadCandidatoDocumentos = async function(candidatoId) {
    if (!candidatoId) return;

    try {
      const response = await fetch(`api/list-documentos.php?candidatoId=${encodeURIComponent(candidatoId)}`);
      const result = await response.json();

      if (result.success && result.documentos) {
        // Limpiar todas las listas primero
        Object.values(fileInputs).forEach(config => {
          const listDiv = document.getElementById(config.listId);
          if (listDiv) listDiv.innerHTML = '';
        });

        // Renderizar documentos por tipo
        Object.entries(result.documentos).forEach(([tipo, docs]) => {
          const config = Object.values(fileInputs).find(c => c.tipo === tipo);
          if (config && docs.length > 0) {
            renderDocumentList(config.listId, config.countId, docs);
          }
        });
      }
    } catch (error) {
      console.error('Error loading documents:', error);
    }
  };

  // Función para renderizar lista de documentos
  function renderDocumentList(listId, countId, documentos) {
    const listDiv = document.getElementById(listId);
    const countSpan = document.getElementById(countId);
    if (!listDiv) return;

    listDiv.innerHTML = '';

    documentos.forEach(doc => {
      const docItem = document.createElement('div');
      docItem.className = 'file-uploaded';
      docItem.dataset.docId = doc.id;
      docItem.innerHTML = `
        <span class="file-name">✅ ${doc.archivo_nombre}</span>
        <a href="../${doc.archivo_url}" target="_blank" class="file-link">Ver</a>
        <button type="button" class="file-delete" onclick="deleteDocumentById(${doc.id}, '${listId}', '${countId}')">🗑️</button>
      `;
      listDiv.appendChild(docItem);
    });

    // Actualizar contador
    if (countSpan) {
      countSpan.textContent = documentos.length > 0 ? `(${documentos.length})` : '';
    }
  }

  // Función para añadir un documento a la lista visualmente
  function addDocumentToList(listId, countId, doc) {
    const listDiv = document.getElementById(listId);
    const countSpan = document.getElementById(countId);
    if (!listDiv) return;

    const docItem = document.createElement('div');
    docItem.className = 'file-uploaded';
    docItem.dataset.docId = doc.documentoId;
    docItem.innerHTML = `
      <span class="file-name">✅ ${doc.filename}</span>
      <a href="../${doc.path}" target="_blank" class="file-link">Ver</a>
      <button type="button" class="file-delete" onclick="deleteDocumentById(${doc.documentoId}, '${listId}', '${countId}')">🗑️</button>
    `;
    listDiv.appendChild(docItem);

    // Actualizar contador
    if (countSpan) {
      const count = listDiv.querySelectorAll('.file-uploaded').length;
      countSpan.textContent = count > 0 ? `(${count})` : '';
    }
  }

  // Configurar listeners para todos los inputs de archivo (ahora soporta múltiples)
  Object.keys(fileInputs).forEach(inputId => {
    const fileInput = document.getElementById(inputId);
    if (!fileInput) return;

    fileInput.addEventListener('change', async (e) => {
      const files = Array.from(e.target.files);
      if (files.length === 0) return;

      const config = fileInputs[inputId];
      // Usar ID existente, o crear/reutilizar tempId para candidatos nuevos
      const existingId = document.getElementById('candidato-id').value;
      const candidatoId = existingId || (uploadTempId = uploadTempId || 'temp-' + Date.now());
      const previewDiv = document.getElementById(config.previewId);

      // Subir cada archivo
      for (const file of files) {
        previewDiv.innerHTML = `<div class="file-uploading">⏳ Subiendo ${file.name}...</div>`;

        try {
          const formData = new FormData();
          formData.append('documento', file);
          formData.append('candidatoId', candidatoId);
          formData.append('tipoDocumento', config.tipo);

          const response = await fetch('api/upload-documento.php', {
            method: 'POST',
            body: formData
          });

          const result = await response.json();

          if (result.success) {
            // Añadir a la lista visual
            addDocumentToList(config.listId, config.countId, result);
            previewDiv.innerHTML = '';
          } else {
            previewDiv.innerHTML = `<div class="file-error">❌ Error: ${result.error}</div>`;
          }
        } catch (error) {
          previewDiv.innerHTML = `<div class="file-error">❌ Error al subir: ${error.message}</div>`;
        }
      }

      // Limpiar el input
      e.target.value = '';
    });
  });

  // Función global para eliminar documentos por ID de base de datos
  window.deleteDocumentById = async function(documentoId, listId, countId) {
    if (!confirm('¿Estás seguro de eliminar este archivo?')) return;

    const listDiv = document.getElementById(listId);
    const docItem = listDiv.querySelector(`[data-doc-id="${documentoId}"]`);

    if (docItem) {
      docItem.innerHTML = '<span class="file-uploading">⏳ Eliminando...</span>';
    }

    try {
      const response = await fetch('api/delete-documento-db.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ documentoId })
      });

      const result = await response.json();

      if (result.success) {
        if (docItem) docItem.remove();

        // Actualizar contador
        const countSpan = document.getElementById(countId);
        if (countSpan) {
          const count = listDiv.querySelectorAll('.file-uploaded').length;
          countSpan.textContent = count > 0 ? `(${count})` : '';
        }
      } else {
        if (docItem) {
          docItem.innerHTML = `<div class="file-error">❌ Error: ${result.error}</div>`;
        }
      }
    } catch (error) {
      if (docItem) {
        docItem.innerHTML = `<div class="file-error">❌ Error: ${error.message}</div>`;
      }
    }
  };

  // Mantener compatibilidad con función antigua para archivos legacy
  window.deleteFile = async function(path, urlFieldId, previewId) {
    if (!confirm('¿Estás seguro de eliminar este archivo?')) return;

    const previewDiv = document.getElementById(previewId);
    previewDiv.innerHTML = '<div class="file-uploading">⏳ Eliminando...</div>';

    try {
      const response = await fetch('api/delete-documento.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ path })
      });

      const result = await response.json();

      if (result.success) {
        document.getElementById(urlFieldId).value = '';
        previewDiv.innerHTML = '';
      } else {
        previewDiv.innerHTML = `<div class="file-error">❌ Error: ${result.error}</div>`;
      }
    } catch (error) {
      previewDiv.innerHTML = `<div class="file-error">❌ Error: ${error.message}</div>`;
    }
  };

  // ===================
  // VALIDACIONES
  // ===================

  const form = document.getElementById('candidato-form');

  if (form) {
    form.addEventListener('submit', (e) => {
      e.preventDefault();

      // Limpiar errores previos
      document.querySelectorAll('.field-error').forEach(el => el.remove());
      document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

      let hasErrors = false;

      // Validar nombre
      const nombre = document.getElementById('nombre');
      if (!nombre.value || nombre.value.trim().length < 3) {
        showFieldError(nombre, 'El nombre debe tener al menos 3 caracteres');
        hasErrors = true;
      }

      // Validar tipo
      const tipo = document.getElementById('tipo');
      if (!tipo.value) {
        showFieldError(tipo, 'Debe seleccionar un tipo');
        hasErrors = true;
      }

      // Validar emails
      const correosContacto = document.getElementById('correosContacto');
      if (correosContacto.value) {
        const emails = correosContacto.value.split(',').map(s => s.trim());
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const invalidEmails = emails.filter(email => email && !emailRegex.test(email));

        if (invalidEmails.length > 0) {
          showFieldError(correosContacto, `Emails inválidos: ${invalidEmails.join(', ')}`);
          hasErrors = true;
        }
      }

      // Validar URLs
      const urlFields = ['videoUrl', 'presentacionUrl', 'contratoMuestraUrl', 'webRedes'];
      urlFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field && field.value) {
          try {
            new URL(field.value);
          } catch {
            showFieldError(field, 'URL inválida');
            hasErrors = true;
          }
        }
      });

      // Validar números positivos
      const numberFields = ['experienciaAnios', 'condominiosActuales', 'cuotaMensualAprox'];
      numberFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field && field.value && parseFloat(field.value) < 0) {
          showFieldError(field, 'Debe ser un número positivo');
          hasErrors = true;
        }
      });

      // Validaciones de negocio (advertencias, no bloquean guardado)
      showBusinessWarnings();

      if (hasErrors) {
        // Scroll al primer error
        const firstError = document.querySelector('.input-error');
        if (firstError) {
          firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return;
      }

      // Si todo está bien, procesar el formulario
      submitCandidatoForm();
    });
  }

  // ===================
  // FUNCIONES HELPER
  // ===================

  function showFieldError(field, message) {
    field.classList.add('input-error');

    const error = document.createElement('div');
    error.className = 'field-error';
    error.textContent = message;

    field.parentNode.appendChild(error);
  }

  function showBusinessWarnings() {
    // Limpiar advertencias previas
    document.querySelectorAll('.field-warning').forEach(el => el.remove());

    // Cuota muy alta
    const cuotaMensualAprox = document.getElementById('cuotaMensualAprox');
    if (cuotaMensualAprox && parseFloat(cuotaMensualAprox.value) > 5000) {
      showWarning('La cuota mensual es mayor a $5,000 MXN');
    }

    // Poca experiencia
    const experienciaAnios = document.getElementById('experienciaAnios');
    if (experienciaAnios && parseFloat(experienciaAnios.value) < 3 && experienciaAnios.value !== '') {
      showWarning('El candidato tiene menos de 3 años de experiencia');
    }
  }

  function showWarning(message) {
    const warningContainer = document.getElementById('warning-container');
    if (!warningContainer) return;

    const warning = document.createElement('div');
    warning.className = 'field-warning';
    warning.innerHTML = `<strong>⚠️ Advertencia:</strong> ${message}`;

    warningContainer.appendChild(warning);
  }

  function getCurrentDateTime() {
    const now = new Date();
    const day = String(now.getDate()).padStart(2, '0');
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const year = now.getFullYear();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    return `${day}/${month}/${year} ${hours}:${minutes}`;
  }

  function submitCandidatoForm() {
    const formData = new FormData(document.getElementById('candidato-form'));

    // Construir objeto candidato con nueva estructura
    const candidato = {
      id: formData.get('id') || 'candidato-' + Date.now(),
      tempId: uploadTempId,  // Para transferir documentos subidos con ID temporal
      origenCandidato: formData.get('origenCandidato') || '',

      // Sección 1: Información Básica
      nombre: formData.get('nombre'),
      tipo: formData.get('tipo'),
      estatus: formData.get('estatus') || 'En revisión',
      mostrarEnListado: formData.get('mostrarEnListado') === 'true' ? true : false,
      mostrarEnComparativa: formData.get('mostrarEnComparativa') === 'true' ? true : false,
      visitoCondominio: formData.get('visitoCondominio') === 'true' ? true : (formData.get('visitoCondominio') === 'false' ? false : null),
      fechaVisitaCondominio: formData.get('fechaVisitaCondominio') || null,
      prosocEstatus: formData.get('prosocEstatus') || '',
      prosocVigenciaHasta: formData.get('prosocVigenciaHasta') || '',

      // Sección 2: Datos de Contacto
      telefonosContacto: formData.get('telefonosContacto') ? formData.get('telefonosContacto').split(',').map(s => s.trim()).filter(s => s) : [],
      correosContacto: formData.get('correosContacto') ? formData.get('correosContacto').split(',').map(s => s.trim()).filter(s => s) : [],
      paginaWeb: formData.get('paginaWeb') || '',
      redesSociales: formData.get('redesSociales') || '',

      // Sección 3: Experiencia
      experienciaAnios: formData.get('experienciaAnios') ? parseInt(formData.get('experienciaAnios')) : null,
      condominiosActuales: formData.get('condominiosActuales') ? parseInt(formData.get('condominiosActuales')) : null,
      tiposCondominio: Array.from(formData.getAll('tiposCondominio[]')),
      similarAlNuestro: formData.get('similarAlNuestro') === 'true' ? true : (formData.get('similarAlNuestro') === 'false' ? false : null),
      experienciaEquipos: Array.from(formData.getAll('experienciaEquipos[]')),
      quejasEnPROSOC: formData.get('quejasEnPROSOC') === 'true' ? true : (formData.get('quejasEnPROSOC') === 'false' ? false : null),
      quejasEnPROSOCDetalles: formData.get('quejasEnPROSOCDetalles') || '',
      removidoPorAsamblea: formData.get('removidoPorAsamblea') === 'true' ? true : (formData.get('removidoPorAsamblea') === 'false' ? false : null),
      removidoPorAsambleaDetalles: formData.get('removidoPorAsambleaDetalles') || '',
      manejoConflictosVecinos: formData.get('manejoConflictosVecinos') || '',
      problemasComplejosResueltos: formData.get('problemasComplejosResueltos') || '',
      referencias: formData.get('referencias') || '',
      referenciasContactadas: formData.get('referenciasContactadas') === 'true' ? true : (formData.get('referenciasContactadas') === 'false' ? false : null),
      referenciasContactadasComentario: formData.get('referenciasContactadasComentario') || null,
      clienteVisitado: formData.get('clienteVisitado') === 'true' ? true : (formData.get('clienteVisitado') === 'false' ? false : null),

      // Sección 4: Características del Servicio
      tamanoEquipo: formData.get('tamanoEquipo') || '',
      personalApoyo: formData.get('personalApoyo') || '',
      horariosAtencion: formData.get('horariosAtencion') || '',
      canalesComunicacion: Array.from(formData.getAll('canalesComunicacion[]')),
      canalAppPortalTexto: formData.get('canalAppPortalTexto') || '',
      canalPresencialTexto: formData.get('canalPresencialTexto') || '',
      flujoIncidencias: formData.get('flujoIncidencias') || '',
      tiempoRespuestaNormal: formData.get('tiempoRespuestaNormal') || '',
      tiempoRespuestaEmergencias: formData.get('tiempoRespuestaEmergencias') || '',
      procesoCobranza: formData.get('procesoCobranza') || '',
      propuestaManejoFondos: formData.get('propuestaManejoFondos') || '',
      tiempoPublicacionEstadosCuenta: formData.get('tiempoPublicacionEstadosCuenta') || '',
      formaEntregaEstadosCuenta: formData.get('formaEntregaEstadosCuenta') || '',
      planPrimeros90Dias: formData.get('planPrimeros90Dias') || '',

      // Sección 5: Costos y Honorarios
      costoMensual: formData.get('costoMensual') ? parseFloat(formData.get('costoMensual')) : null,
      cuotaMantenimientoPropuesta: formData.get('cuotaMantenimientoPropuesta') || null,
      cargosAdicionales: formData.get('cargosAdicionales') || null,
      costosInfoAdicional: formData.get('costosInfoAdicional') || null,
      incluyeLimpieza: document.getElementById('incluyeLimpieza').checked,
      costoPropuestaLimpieza: formData.get('costoPropuestaLimpieza') || null,
      propuestaLimpieza: formData.get('propuestaLimpieza') || null,
      incluyeMantenimiento: document.getElementById('incluyeMantenimiento').checked,
      costoPropuestaMantenimiento: formData.get('costoPropuestaMantenimiento') || null,
      propuestaMantenimiento: formData.get('propuestaMantenimiento') || null,
      incluyeVigilancia: document.getElementById('incluyeVigilancia').checked,
      costoPropuestaVigilancia: formData.get('costoPropuestaVigilancia') || null,
      propuestaVigilancia: formData.get('propuestaVigilancia') || null,
      incluyeOtrosServicios: document.getElementById('incluyeOtrosServicios').checked,
      propuestaOtrosServicios: formData.get('propuestaOtrosServicios') || null
      // Nota: Los documentos se manejan por separado en la tabla candidato_documentos
    };

    // Enviar al servidor
    fetch('api/save-candidato.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(candidato)
    })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        showAlert('Candidato guardado correctamente', 'success');
        setTimeout(() => location.reload(), 1000);
      } else {
        showAlert(result.error || 'Error al guardar el candidato', 'error');
      }
    })
    .catch(error => {
      showAlert('Error de conexión: ' + error.message, 'error');
    });
  }

  function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;

    const container = document.getElementById('alert-container');
    if (container) {
      container.innerHTML = '';
      container.appendChild(alertDiv);

      setTimeout(() => {
        alertDiv.remove();
      }, 5000);
    }
  }
});
