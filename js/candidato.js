// Renderizar detalle de candidato en página individual
function renderCandidatoDetalle() {
  const container = document.getElementById("candidato-detalle");
  if (!container) return;

  const urlParams = new URLSearchParams(window.location.search);
  const candidatoId = urlParams.get('id');

  if (!candidatoId) {
    container.innerHTML = '<p class="small-muted">No se encontró el candidato solicitado.</p>';
    return;
  }

  const candidato = candidatos.find(e => e.id === candidatoId);

  if (!candidato) {
    container.innerHTML = '<p class="small-muted">El candidato solicitado no existe.</p>';
    return;
  }

  // Header con título y badges
  const header = document.createElement("div");
  header.className = "candidato-detalle-header";

  const title = document.createElement("h1");
  title.textContent = candidato.nombre || "Candidato sin nombre";
  header.appendChild(title);

  const etiquetas = document.createElement("div");
  etiquetas.className = "candidato-etiquetas";

  if (candidato.tipo) {
    const tipoBadge = document.createElement("span");
    tipoBadge.className = "badge badge-accent";
    tipoBadge.textContent = candidato.tipo;
    etiquetas.appendChild(tipoBadge);
  }

  header.appendChild(etiquetas);
  container.appendChild(header);

  // Última actualización
  if (candidato.ultimaActualizacion) {
    const updateInfo = document.createElement("p");
    updateInfo.className = "small-muted";
    updateInfo.style.marginTop = "0.75rem";
    updateInfo.style.marginBottom = "0";
    updateInfo.innerHTML = `📅 <strong>Última actualización:</strong> ${candidato.ultimaActualizacion}`;
    container.appendChild(updateInfo);
  }

  // Origen del candidato (info box)
  if (candidato.origenCandidato) {
    const origenBox = document.createElement("div");
    origenBox.className = "info-box";
    origenBox.style.marginTop = "1rem";
    origenBox.innerHTML = `<strong>📍 Propuesto por:</strong> ${candidato.origenCandidato}`;
    container.appendChild(origenBox);
  }

  // Función helper para agregar secciones en formato lista
  function addSection(titleText, items) {
    const section = document.createElement("section");
    section.className = "candidato-info-section";

    const h2 = document.createElement("h2");
    h2.textContent = titleText;
    section.appendChild(h2);

    const ul = document.createElement("ul");
    ul.className = "checklist";

    items.forEach((item) => {
      const li = document.createElement("li");
      const strong = document.createElement("strong");
      strong.textContent = item.label + ": ";
      li.appendChild(strong);

      const valueSpan = document.createElement("span");
      let displayValue = "No especificado";

      if (item.value !== null && item.value !== undefined && item.value !== "") {
        if (Array.isArray(item.value)) {
          displayValue = item.value.length > 0 ? item.value.join(", ") : "No especificado";
        } else if (typeof item.value === 'boolean') {
          displayValue = item.value ? "Sí" : "No";
        } else {
          displayValue = item.value;
        }
      }

      valueSpan.textContent = displayValue;
      li.appendChild(valueSpan);
      ul.appendChild(li);
    });

    section.appendChild(ul);
    container.appendChild(section);
  }

  // Función helper para agregar secciones en formato cards
  function addSectionAsCards(titleText, items, icon = '', colorClass = '') {
    const section = document.createElement("section");
    section.className = "candidato-info-section";

    const h2 = document.createElement("h2");
    if (icon) {
      const iconSpan = document.createElement("span");
      iconSpan.className = "section-icon";
      iconSpan.textContent = icon + " ";
      h2.appendChild(iconSpan);
    }
    const titleSpan = document.createElement("span");
    titleSpan.textContent = titleText;
    h2.appendChild(titleSpan);
    section.appendChild(h2);

    const grid = document.createElement("div");
    grid.className = "info-cards-grid" + (colorClass ? " " + colorClass : "");

    items.forEach((item) => {
      const card = document.createElement("div");
      card.className = "info-card";

      const label = document.createElement("div");
      label.className = "info-card-label";
      label.textContent = item.label;
      card.appendChild(label);

      const value = document.createElement("div");
      value.className = "info-card-value";
      let displayValue = "No especificado";

      if (item.value !== null && item.value !== undefined && item.value !== "") {
        if (Array.isArray(item.value)) {
          displayValue = item.value.length > 0 ? item.value.join(", ") : "No especificado";
        } else if (typeof item.value === 'boolean') {
          displayValue = item.value ? "Sí" : "No";
        } else {
          displayValue = item.value;
        }
      }

      value.textContent = displayValue;
      card.appendChild(value);

      grid.appendChild(card);
    });

    section.appendChild(grid);
    container.appendChild(section);
  }

  // Función helper para agregar bloques de texto largo
  function addTextBlock(label, value, colorClass = '') {
    if (!value || value === "" || (Array.isArray(value) && value.length === 0)) {
      return; // No mostrar bloques vacíos
    }

    const block = document.createElement("div");
    block.className = "text-block" + (colorClass ? " " + colorClass : "");

    const labelEl = document.createElement("div");
    labelEl.className = "text-block-label";
    labelEl.textContent = label;
    block.appendChild(labelEl);

    const valueEl = document.createElement("div");
    valueEl.className = "text-block-value";

    let displayValue = value;
    if (Array.isArray(value)) {
      displayValue = value.join(", ");
    }

    valueEl.textContent = displayValue;
    block.appendChild(valueEl);

    container.appendChild(block);
  }

  // SECCIÓN 1: Información Básica
  addSectionAsCards("Información Básica", [
    { label: "Tipo", value: candidato.tipo },
    { label: "Estatus", value: candidato.estatus },
    { label: "Ya visitó el condominio", value: candidato.visitoCondominio },
    { label: "Fecha de visita", value: candidato.fechaVisitaCondominio },
    { label: "Certificación PROSOC", value: candidato.prosocEstatus },
    { label: "Vigencia Registro PROSOC", value: candidato.prosocVigenciaHasta }
  ], "📋", "cards-blue");

  // SECCIÓN 2: Datos de Contacto
  addSectionAsCards("Datos de Contacto", [
    { label: "Teléfonos", value: candidato.telefonosContacto },
    { label: "Correos Electrónicos", value: candidato.correosContacto },
    { label: "Página Web", value: candidato.paginaWeb },
    { label: "Redes Sociales", value: candidato.redesSociales }
  ], "📞", "cards-green");

  // SECCIÓN 3: Experiencia
  // Campos cortos en cards
  const experienciaItemsCortos = [
    { label: "Años de Experiencia", value: candidato.experienciaAnios != null ? `${candidato.experienciaAnios}+` : null },
    { label: "Condominios que administra actualmente", value: candidato.condominiosActuales },
    { label: "Tipos de Condominios", value: candidato.tiposCondominio },
    { label: "Ha administrado condominios similares", value: candidato.similarAlNuestro },
    { label: "Experiencia con equipos y sistemas", value: candidato.experienciaEquipos },
    { label: "Ha tenido quejas en PROSOC", value: candidato.quejasEnPROSOC },
    { label: "Ha sido removido por asamblea", value: candidato.removidoPorAsamblea }
  ];

  addSectionAsCards("Experiencia", experienciaItemsCortos, "💼", "cards-purple");

  // Campos largos en bloques de texto
  if (candidato.quejasEnPROSOC === true) {
    addTextBlock("Detalles de quejas PROSOC", candidato.quejasEnPROSOCDetalles, "text-purple");
  }
  if (candidato.removidoPorAsamblea === true) {
    addTextBlock("Detalles de remoción por asamblea", candidato.removidoPorAsambleaDetalles, "text-purple");
  }
  addTextBlock("Cómo abordan conflictos entre vecinos", candidato.manejoConflictosVecinos, "text-purple");
  addTextBlock("Ejemplos de problemas complejos resueltos", candidato.problemasComplejosResueltos, "text-purple");

  // Referencias contactadas al final de la sección
  const refContactadasValue = candidato.referenciasContactadas === true ? "Sí" : (candidato.referenciasContactadas === false ? "No" : "No especificado");
  addTextBlock("Referencias contactadas", refContactadasValue, "text-purple");
  if (candidato.referenciasContactadasComentario) {
    addTextBlock("Comentario sobre referencias contactadas", candidato.referenciasContactadasComentario, "text-purple");
  }

  // SECCIÓN 4: Costos y Honorarios
  addSectionAsCards("Costos y Honorarios", [
    { label: "Honorarios mensuales", value: candidato.costoMensual != null ? formatCurrencyMXN(candidato.costoMensual) : null },
    { label: "Cargos adicionales", value: candidato.cargosAdicionales }
  ], "💰", "cards-teal");
  addTextBlock("Información adicional sobre costo de honorarios", candidato.costosInfoAdicional, "text-teal");
  // Solo mostrar propuestas si el checkbox correspondiente está activado
  if (candidato.incluyeLimpieza === true) {
    addTextBlock("Propuesta de Limpieza", candidato.propuestaLimpieza, "text-teal");
  }
  if (candidato.incluyeMantenimiento === true) {
    addTextBlock("Propuesta de Mantenimiento", candidato.propuestaMantenimiento, "text-teal");
  }
  if (candidato.incluyeVigilancia === true) {
    addTextBlock("Propuesta de Vigilancia", candidato.propuestaVigilancia, "text-teal");
  }
  if (candidato.incluyeOtrosServicios === true) {
    addTextBlock("Propuesta de Otros Servicios", candidato.propuestaOtrosServicios, "text-teal");
  }

  // SECCIÓN 5: Características del Servicio
  // Campos cortos en cards
  const servicioItemsCortos = [
    { label: "Tamaño del equipo", value: candidato.tamanoEquipo },
    { label: "Personal de apoyo", value: candidato.personalApoyo },
    { label: "Horarios de Atención", value: candidato.horariosAtencion },
    { label: "Canales de Comunicación", value: candidato.canalesComunicacion },
    { label: "Cuota de Mantenimiento Propuesta", value: candidato.cuotaMantenimientoPropuesta }
  ];

  // Solo mostrar nombre de App/Portal si ese canal está seleccionado
  if (candidato.canalesComunicacion && candidato.canalesComunicacion.includes("App / Portal en línea")) {
    servicioItemsCortos.push({ label: "Nombre de App/Portal", value: candidato.canalAppPortalTexto });
  }

  // Solo mostrar detalles de atención presencial si ese canal está seleccionado
  if (candidato.canalesComunicacion && candidato.canalesComunicacion.includes("Atención presencial")) {
    servicioItemsCortos.push({ label: "Atención presencial (ubicación/horarios)", value: candidato.canalPresencialTexto });
  }

  servicioItemsCortos.push(
    { label: "Tiempo de respuesta normal", value: candidato.tiempoRespuestaNormal },
    { label: "Tiempo de respuesta emergencias", value: candidato.tiempoRespuestaEmergencias },
    { label: "Tiempo publicación estados de cuenta", value: candidato.tiempoPublicacionEstadosCuenta },
    { label: "Forma de entrega estados de cuenta", value: candidato.formaEntregaEstadosCuenta }
  );

  addSectionAsCards("Características del Servicio", servicioItemsCortos, "⚙️", "cards-orange");

  // Campos largos en bloques de texto
  addTextBlock("Flujo de incidencias", candidato.flujoIncidencias, "text-orange");
  addTextBlock("Proceso de cobranza", candidato.procesoCobranza, "text-orange");
  addTextBlock("Propuesta de manejo de fondos", candidato.propuestaManejoFondos, "text-orange");
  addTextBlock("Plan primeros 90 días", candidato.planPrimeros90Dias, "text-orange");

  // SECCIÓN 6: Documentación Entregada (solo mostrar documentos que fueron entregados)
  const tiposDocumentos = [
    { label: "Currículum", key: "curriculum", publico: false },
    { label: "Certificación PROSOC", key: "certificacionProsoc", publico: true },
    { label: "RFC / CSF", key: "rfcCsf", publico: false },
    { label: "Presentación de servicios", key: "presentacionServicios", publico: true },
    { label: "Formato estados de cuenta", key: "formatoEstadoCuenta", publico: true },
    { label: "Propuesta formal", key: "propuestaFormal", publico: true },
    { label: "Cartas de Recomendación", key: "cartasRecomendacion", publico: true }
  ];

  // Obtener videos de entrevista
  const videosEntrevista = (candidato.documentos && candidato.documentos.videoEntrevista && candidato.documentos.videoEntrevista.length > 0)
    ? candidato.documentos.videoEntrevista
    : [];

  // Filtrar solo documentos que tienen archivos
  const documentosEntregados = tiposDocumentos.filter(tipoDoc => {
    const archivos = candidato.documentos && candidato.documentos[tipoDoc.key] ? candidato.documentos[tipoDoc.key] : [];
    return archivos.length > 0;
  });

  // Solo mostrar la sección si hay al menos un documento entregado
  if (documentosEntregados.length > 0 || videosEntrevista.length > 0) {
    const docsSection = document.createElement("section");
    docsSection.className = "candidato-info-section";

    const docsTitle = document.createElement("h2");
    const docsIcon = document.createElement("span");
    docsIcon.className = "section-icon";
    docsIcon.textContent = "📄 ";
    docsTitle.appendChild(docsIcon);
    const docsTitleText = document.createElement("span");
    docsTitleText.textContent = "Documentación Entregada";
    docsTitle.appendChild(docsTitleText);
    docsSection.appendChild(docsTitle);

    const docsUl = document.createElement("ul");
    docsUl.className = "document-list";

    // Solo mostrar documentos entregados
    documentosEntregados.forEach((tipoDoc) => {
      const archivos = candidato.documentos[tipoDoc.key];
      const li = document.createElement("li");

      const checkbox = document.createElement("span");
      checkbox.textContent = "✅ ";
      li.appendChild(checkbox);

      const strong = document.createElement("strong");
      strong.textContent = tipoDoc.label + ": ";
      li.appendChild(strong);

      if (tipoDoc.publico) {
        // Mostrar enlaces para cada documento
        const linksContainer = document.createElement("span");
        linksContainer.className = "document-links";
        archivos.forEach((archivo, index) => {
          const link = document.createElement("a");
          link.href = archivo.url;
          link.target = "_blank";
          link.rel = "noopener noreferrer";
          link.className = "btn btn-ghost btn-sm";
          link.textContent = archivos.length > 1 ? `Ver (${index + 1})` : "Ver documento";
          link.title = archivo.nombre || "Documento";
          link.style.marginLeft = "0.3rem";
          linksContainer.appendChild(link);
        });
        li.appendChild(linksContainer);
      } else {
        // Documento(s) entregado(s) pero no público(s)
        const span = document.createElement("span");
        span.textContent = archivos.length > 1 ? `Entregados (${archivos.length})` : "Entregado";
        span.style.color = "var(--text-success)";
        li.appendChild(span);
      }

      docsUl.appendChild(li);
    });

    // Video de propuesta (solo si hay videos)
    if (videosEntrevista.length > 0) {
      const videoLi = document.createElement("li");
      const checkbox = document.createElement("span");
      checkbox.textContent = "✅ ";
      videoLi.appendChild(checkbox);

      const strong = document.createElement("strong");
      strong.textContent = "Video de propuesta: ";
      videoLi.appendChild(strong);

      videosEntrevista.forEach((video, index) => {
        const videoBtn = document.createElement("button");
        videoBtn.className = "btn btn-ghost btn-sm";
        videoBtn.textContent = videosEntrevista.length > 1 ? `▶️ Ver (${index + 1})` : "▶️ Ver propuesta";
        videoBtn.title = video.nombre || "Video de propuesta";
        videoBtn.style.marginLeft = "0.3rem";
        videoBtn.style.cursor = "pointer";
        videoBtn.onclick = () => abrirModalVideo(video.url);
        videoLi.appendChild(videoBtn);
      });

      docsUl.appendChild(videoLi);
    }

    docsSection.appendChild(docsUl);
    container.appendChild(docsSection);
  }
}

// Cargar preguntas desde el servidor
async function cargarPreguntas(candidatoId) {
  try {
    const response = await fetch(`api/guardar-pregunta.php?candidatoId=${candidatoId}`);
    if (response.ok) {
      const data = await response.json();
      return data.preguntas || [];
    }
  } catch (error) {
    console.error('Error al cargar preguntas:', error);
  }
  return [];
}

// Crear elemento de texto que respeta saltos de línea (usa <br>)
function createMultilineElement(tag, text, className = "") {
  const el = document.createElement(tag);
  if (className) {
    el.className = className;
  }

  const safeText = text || "";
  const lines = safeText.split(/\r?\n/);
  lines.forEach((line, index) => {
    el.appendChild(document.createTextNode(line));
    if (index < lines.length - 1) {
      el.appendChild(document.createElement("br"));
    }
  });

  return el;
}

// Renderizar lista de preguntas
async function renderPreguntas(candidatoId) {
  const container = document.getElementById("preguntas-lista");
  if (!container) return;

  const preguntas = await cargarPreguntas(candidatoId);
  container.innerHTML = "";

  if (preguntas.length === 0) {
    const p = document.createElement("p");
    p.className = "small-muted";
    p.textContent = "¡Aún no hay preguntas. Sé el primero en hacer una!";
    container.appendChild(p);
    return;
  }

  preguntas.forEach((pregunta) => {
    const div = document.createElement("div");
    div.className = "pregunta-item";

    const header = document.createElement("div");
    header.className = "pregunta-header";

    const autor = document.createElement("span");
    autor.className = "pregunta-autor";
    autor.textContent = pregunta.nombre;

    const fecha = document.createElement("span");
    fecha.className = "pregunta-fecha";
    fecha.textContent = pregunta.fecha;

    header.appendChild(autor);
    header.appendChild(fecha);
    div.appendChild(header);

    const texto = createMultilineElement("p", pregunta.comentario, "pregunta-texto");
    div.appendChild(texto);

    // Mostrar respuesta si existe
    if (pregunta.respuesta) {
      const respuestaDiv = document.createElement("div");
      respuestaDiv.className = "pregunta-respuesta";

      const respuestaHeader = document.createElement("div");
      respuestaHeader.className = "respuesta-header";

      const respuestaLabel = document.createElement("strong");
      respuestaLabel.textContent = "Respuesta del proveedor";
      respuestaHeader.appendChild(respuestaLabel);

      if (pregunta.fechaRespuesta) {
        const respuestaFecha = document.createElement("span");
        respuestaFecha.className = "respuesta-fecha";
        respuestaFecha.textContent = pregunta.fechaRespuesta;
        respuestaHeader.appendChild(respuestaFecha);
      }

      respuestaDiv.appendChild(respuestaHeader);

      const respuestaTexto = createMultilineElement("p", pregunta.respuesta, "respuesta-texto");
      respuestaDiv.appendChild(respuestaTexto);

      div.appendChild(respuestaDiv);
    }

    container.appendChild(div);
  });
}

// Enviar pregunta y guardar en el servidor
async function enviarPregunta(candidatoId, candidatoNombre, nombre, correo, comentario) {
  try {
    const response = await fetch('api/guardar-pregunta.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        candidatoId: candidatoId,
        candidatoNombre: candidatoNombre,
        nombre: nombre,
        correo: correo,
        comentario: comentario
      })
    });

    if (response.ok) {
      const data = await response.json();
      return { success: true, message: data.message || 'Pregunta enviada correctamente' };
    } else {
      const error = await response.json();
      return { success: false, message: error.error || 'Error al enviar la pregunta' };
    }
  } catch (error) {
    console.error('Error al enviar pregunta:', error);
    return { success: false, message: 'Error de conexión. Intenta de nuevo.' };
  }
}

// Configurar formulario de feedback
function setupFeedbackForm() {
  const form = document.getElementById("feedback-form");
  if (!form) return;

  const urlParams = new URLSearchParams(window.location.search);
  const candidatoId = urlParams.get('id');
  const candidato = candidatos.find(e => e.id === candidatoId);
  const candidatoNombre = candidato ? candidato.nombre : "Candidato desconocido";

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const nombre = document.getElementById("feedback-nombre").value.trim();
    const correo = document.getElementById("feedback-correo").value.trim();
    const comentario = document.getElementById("feedback-comentario").value.trim();
    const messageEl = document.getElementById("feedback-message");

    if (!comentario) {
      messageEl.textContent = "Por favor, escribe tu pregunta.";
      messageEl.className = "feedback-message error";
      return;
    }

    messageEl.textContent = "Enviando tu pregunta...";
    messageEl.className = "feedback-message success";

    const resultado = await enviarPregunta(candidatoId, candidatoNombre, nombre, correo, comentario);

    if (!resultado.success) {
      messageEl.textContent = resultado.message;
      messageEl.className = "feedback-message error";
      return;
    }

    messageEl.textContent = resultado.message;
    messageEl.className = "feedback-message success";

    setTimeout(() => {
      renderPreguntas(candidatoId);
      form.reset();
      messageEl.textContent = "";
      messageEl.className = "feedback-message";
    }, 2500);
  });
}

document.addEventListener("DOMContentLoaded", async () => {
  await cargarCandidatos();

  const urlParams = new URLSearchParams(window.location.search);
  const candidatoId = urlParams.get('id');

  renderCandidatoDetalle();

  if (candidatoId) {
    renderPreguntas(candidatoId);
  }

  setupFeedbackForm();
});
