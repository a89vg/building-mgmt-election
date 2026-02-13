// Renderizar lista de links en la página principal
function renderCandidatosLinks() {
  const container = document.getElementById("candidatos-list");
  if (!container) return;

  container.innerHTML = "";

  // Filtrar solo candidatos que deben mostrarse en el listado público
  const candidatosListado = candidatos.filter(c => c.mostrarEnListado !== false);

  if (!Array.isArray(candidatosListado) || candidatosListado.length === 0) {
    const li = document.createElement("li");
    const p = document.createElement("p");
    p.className = "small-muted";
    p.textContent =
      "Por el momento no hay candidatos registrados. Agrega datos en data/candidatos.json para que aparezcan aquí.";
    li.appendChild(p);
    container.appendChild(li);
    return;
  }

  candidatosListado.forEach((candidato) => {
    const li = document.createElement("li");
    const a = document.createElement("a");
    a.href = `candidato.php?id=${candidato.id}`;
    a.className = "candidato-card-link";

    // Título
    const title = document.createElement("div");
    title.className = "candidato-card-link-title";
    title.textContent = candidato.nombre || "Candidato sin nombre";
    a.appendChild(title);

    // Badge (tipo)
    if (candidato.tipo) {
      const badgesContainer = document.createElement("div");
      badgesContainer.className = "candidato-card-link-badges";

      // Badge de tipo
      const tipoBadge = document.createElement("span");
      tipoBadge.className = "badge badge-accent";
      tipoBadge.textContent = candidato.tipo;
      badgesContainer.appendChild(tipoBadge);

      // Badge de visita (si ya visitó)
      if (candidato.visitoCondominio === true) {
        const visitaBadge = document.createElement("span");
        visitaBadge.className = "badge badge-verificado";
        visitaBadge.textContent = "✓ Visitó condominio";
        badgesContainer.appendChild(visitaBadge);
      }

      // Badge de referencias contactadas
      if (candidato.referenciasContactadas === true) {
        const refBadge = document.createElement("span");
        refBadge.className = "badge badge-referencias";
        refBadge.textContent = "✓ Referencias contactadas";
        badgesContainer.appendChild(refBadge);
      }

      // Badge de cliente visitado
      if (candidato.clienteVisitado === true) {
        const clienteBadge = document.createElement("span");
        clienteBadge.className = "badge badge-cliente-visitado";
        clienteBadge.textContent = "✓ Cliente visitado";
        badgesContainer.appendChild(clienteBadge);
      }

      a.appendChild(badgesContainer);
    }

    // Última actualización
    if (candidato.ultimaActualizacion) {
      const updateInfo = document.createElement("div");
      updateInfo.className = "small-muted";
      updateInfo.style.marginTop = "0.5rem";
      updateInfo.style.marginBottom = "0.75rem";
      updateInfo.style.fontSize = "0.75rem";
      updateInfo.textContent = `Actualizado: ${candidato.ultimaActualizacion}`;
      a.appendChild(updateInfo);
    }

    // CTA button
    const cta = document.createElement("div");
    cta.className = "candidato-card-link-cta";
    cta.textContent = "Ver detalles →";
    a.appendChild(cta);

    // Video button (only if candidate has videos)
    const hasVideo = candidato.documentos &&
                     candidato.documentos.videoEntrevista &&
                     candidato.documentos.videoEntrevista.length > 0;

    if (hasVideo) {
      const videoBtn = document.createElement("button");
      videoBtn.className = "candidato-card-link-cta candidato-card-video-btn";
      videoBtn.style.marginTop = "0.5rem";
      videoBtn.textContent = "▶️ Ver video de reunión por Zoom";
      videoBtn.onclick = (e) => {
        e.preventDefault();
        e.stopPropagation();
        abrirModalVideo(candidato.documentos.videoEntrevista[0].url);
      };
      a.appendChild(videoBtn);
    }

    // Propuesta formal
    const hasPropuesta = candidato.documentos?.propuestaFormal?.length > 0;
    if (hasPropuesta) {
      const propuestaSection = document.createElement("div");
      propuestaSection.className = "candidato-card-propuesta";
      propuestaSection.style.marginTop = "0.75rem";
      propuestaSection.style.padding = "0.5rem";
      propuestaSection.style.background = "var(--bg-muted)";
      propuestaSection.style.borderRadius = "var(--radius-sm)";

      const propuestaLinks = document.createElement("div");
      propuestaLinks.style.fontSize = "0.85rem";
      candidato.documentos.propuestaFormal.forEach((doc, idx) => {
        const link = document.createElement("a");
        link.href = doc.url;
        link.target = "_blank";
        link.style.display = "inline-flex";
        link.style.alignItems = "center";
        link.style.gap = "0.4rem";
        link.style.marginRight = "0.5rem";
        link.style.padding = "0.5rem 1rem";
        link.style.background = "linear-gradient(135deg, #3b82f6, #1d4ed8)";
        link.style.color = "white";
        link.style.borderRadius = "var(--radius-sm)";
        link.style.fontWeight = "600";
        link.style.textDecoration = "none";
        link.style.boxShadow = "0 2px 8px rgba(59, 130, 246, 0.4)";
        link.style.transition = "transform 0.15s, box-shadow 0.15s";
        link.textContent = candidato.documentos.propuestaFormal.length > 1
          ? `📄 Ver propuesta formal ${idx + 1}`
          : "📄 Ver propuesta formal";
        link.onmouseenter = () => {
          link.style.transform = "translateY(-1px)";
          link.style.boxShadow = "0 4px 12px rgba(59, 130, 246, 0.5)";
        };
        link.onmouseleave = () => {
          link.style.transform = "translateY(0)";
          link.style.boxShadow = "0 2px 8px rgba(59, 130, 246, 0.4)";
        };
        link.onclick = (e) => e.stopPropagation();
        propuestaLinks.appendChild(link);
      });
      propuestaSection.appendChild(propuestaLinks);

      a.appendChild(propuestaSection);
    }

    // Cartas de recomendación
    const hasCartas = candidato.documentos?.cartasRecomendacion?.length > 0;
    if (hasCartas) {
      const cartasSection = document.createElement("div");
      cartasSection.className = "candidato-card-cartas";
      cartasSection.style.marginTop = "0.75rem";
      cartasSection.style.padding = "0.5rem";
      cartasSection.style.background = "var(--bg-muted)";
      cartasSection.style.borderRadius = "var(--radius-sm)";

      const cartasTitle = document.createElement("div");
      cartasTitle.style.fontSize = "0.85rem";
      cartasTitle.style.fontWeight = "500";
      cartasTitle.style.marginBottom = "0.25rem";
      cartasTitle.textContent = `📝 ${candidato.documentos.cartasRecomendacion.length} Carta${candidato.documentos.cartasRecomendacion.length > 1 ? 's' : ''} de Recomendación`;
      cartasSection.appendChild(cartasTitle);

      const cartasLinks = document.createElement("div");
      cartasLinks.style.fontSize = "0.8rem";
      candidato.documentos.cartasRecomendacion.forEach((carta, idx) => {
        const link = document.createElement("a");
        link.href = carta.url;
        link.target = "_blank";
        link.style.display = "inline-block";
        link.style.marginRight = "0.5rem";
        link.style.color = "var(--accent)";
        link.textContent = `Ver carta ${idx + 1}`;
        link.onclick = (e) => e.stopPropagation();
        cartasLinks.appendChild(link);
      });
      cartasSection.appendChild(cartasLinks);

      a.appendChild(cartasSection);
    }

    li.appendChild(a);
    container.appendChild(li);
  });
}

function renderComparativa() {
  const table = document.getElementById("comparativa-table");
  if (!table) return;

  table.innerHTML = "";

  // Filtrar solo candidatos que deben mostrarse en comparativa
  const candidatosComparativa = candidatos.filter(c => c.mostrarEnComparativa !== false);

  if (!Array.isArray(candidatosComparativa) || candidatosComparativa.length === 0) {
    const caption = document.createElement("caption");
    caption.className = "small-muted";
    caption.textContent =
      "Por el momento no hay suficientes datos para mostrar una tabla comparativa.";
    table.appendChild(caption);
    return;
  }

  const thead = document.createElement("thead");
  const headRow = document.createElement("tr");

  const emptyTh = document.createElement("th");
  emptyTh.textContent = "Característica";
  headRow.appendChild(emptyTh);

  candidatosComparativa.forEach((candidato) => {
    const th = document.createElement("th");
    th.textContent = candidato.nombre || "Candidato";
    headRow.appendChild(th);
  });

  thead.appendChild(headRow);
  table.appendChild(thead);

  const tbody = document.createElement("tbody");

  // Helper function to create boolean icon
  const createBoolIcon = (value, label) => {
    const span = document.createElement("span");
    span.className = value ? "icon-yes" : "icon-no";
    span.textContent = label || (value ? "✓" : "✗");
    span.title = value ? "Sí" : "No";
    return span;
  };

  // Helper function to create propuestas list (only shows sent ones)
  const createPropuestasHtml = (candidato) => {
    const container = document.createElement("span");
    container.style.display = "inline-flex";
    container.style.gap = "0.3rem";
    container.style.flexWrap = "wrap";

    const propuestas = [
      { key: "incluyeLimpieza", label: "Limpieza" },
      { key: "incluyeMantenimiento", label: "Mantenimiento" },
      { key: "incluyeVigilancia", label: "Vigilancia" }
    ];

    const enviadas = propuestas.filter(p => candidato[p.key] === true);

    if (enviadas.length === 0) {
      container.textContent = "Ninguna";
      container.style.color = "var(--text-muted)";
      return container;
    }

    enviadas.forEach(p => {
      const badge = document.createElement("span");
      badge.className = "icon-yes";
      badge.textContent = p.label;
      container.appendChild(badge);
    });

    return container;
  };

  // Main rows (always visible) - 6 rows
  const mainRows = [
    {
      label: "Tipo",
      getValue: (e) => e.tipo || "No especificado",
      isText: true
    },
    {
      label: "Años de experiencia",
      getValue: (e) => e.experienciaAnios != null ? `${e.experienciaAnios}+` : "No especificado",
      isText: true
    },
    {
      label: "Certificación PROSOC",
      getValue: null,
      isHtml: true,
      getHtml: (candidato) => {
        const container = document.createElement("span");
        container.style.display = "inline-flex";
        container.style.alignItems = "center";
        container.style.gap = "0.4rem";

        const status = candidato.prosocEstatus;
        if (!status) {
          container.textContent = "No especificado";
          container.style.color = "var(--text-muted)";
        } else if (status.toLowerCase().includes("certificado") || status.toLowerCase() === "sí" || status.toLowerCase() === "si") {
          const icon = document.createElement("span");
          icon.className = "icon-yes";
          icon.textContent = "✓";
          icon.title = status;
          container.appendChild(icon);
        } else if (status.toLowerCase() === "no" || status.toLowerCase().includes("no certificado")) {
          const icon = document.createElement("span");
          icon.className = "icon-no";
          icon.textContent = "✗";
          icon.title = status;
          container.appendChild(icon);
        } else {
          // For other statuses like "En trámite", show text
          const textSpan = document.createElement("span");
          textSpan.textContent = status;
          container.appendChild(textSpan);
        }

        // Add document link if available
        const docs = candidato.documentos?.certificacionProsoc;
        if (docs && docs.length > 0) {
          const link = document.createElement("a");
          link.href = docs[0].url;
          link.target = "_blank";
          link.title = "Ver documento";
          link.className = "doc-link-icon";
          link.innerHTML = "📄";
          container.appendChild(link);
        }

        return container;
      }
    },
    {
      label: "Honorarios mensuales",
      getValue: (e) => e.costoMensual != null ? `$${Number(e.costoMensual).toLocaleString('es-MX')} MXN` : "No especificado",
      isText: true
    },
    {
      label: "Cuota de Mantenimiento Propuesta",
      getValue: (e) => e.cuotaMantenimientoPropuesta || "No especificado",
      isText: true
    },
    {
      label: "Propuestas enviadas",
      getValue: null,
      isHtml: true,
      getHtml: createPropuestasHtml
    }
  ];

  // Secondary rows (collapsible) - proposal details with costs
  const secondaryRows = [
    {
      label: "Costo de Limpieza",
      getValue: (e) => e.costoPropuestaLimpieza || "No especificado",
      isText: true
    },
    {
      label: "Detalle de Limpieza",
      getValue: (e) => e.propuestaLimpieza || "No especificado",
      isText: true,
      preserveWhitespace: true
    },
    {
      label: "Costo de Mantenimiento",
      getValue: (e) => e.costoPropuestaMantenimiento || "No especificado",
      isText: true
    },
    {
      label: "Detalle de Mantenimiento",
      getValue: (e) => e.propuestaMantenimiento || "No especificado",
      isText: true,
      preserveWhitespace: true
    },
    {
      label: "Costo de Vigilancia",
      getValue: (e) => e.costoPropuestaVigilancia || "No especificado",
      isText: true
    },
    {
      label: "Detalle de Vigilancia",
      getValue: (e) => e.propuestaVigilancia || "No especificado",
      isText: true,
      preserveWhitespace: true
    }
  ];

  // Render main rows with special handling for "Honorarios mensuales"
  mainRows.forEach((row) => {
    const tr = document.createElement("tr");
    tr.className = "row-main";
    const labelTd = document.createElement("td");
    labelTd.textContent = row.label;
    tr.appendChild(labelTd);

    candidatosComparativa.forEach((candidato) => {
      const td = document.createElement("td");
      if (row.isHtml && row.getHtml) {
        td.appendChild(row.getHtml(candidato));
      } else if (row.isBoolean) {
        td.appendChild(createBoolIcon(row.getBool(candidato)));
      } else {
        td.textContent = row.getValue(candidato);
      }
      tr.appendChild(td);
    });

    tbody.appendChild(tr);

    // After "Honorarios mensuales", add cost info rows
    if (row.label === "Honorarios mensuales") {
      // Row for cost info
      const costosInfoRow = document.createElement("tr");
      const costosInfoLabelTd = document.createElement("td");
      costosInfoLabelTd.textContent = "Info. adicional sobre costos";
      costosInfoRow.appendChild(costosInfoLabelTd);

      candidatosComparativa.forEach((candidato) => {
        const td = document.createElement("td");
        td.textContent = candidato.costosInfoAdicional || "No especificado";
        td.style.whiteSpace = "pre-wrap";
        costosInfoRow.appendChild(td);
      });

      tbody.appendChild(costosInfoRow);

      // Row for cargos adicionales
      const cargosRow = document.createElement("tr");
      const cargosLabelTd = document.createElement("td");
      cargosLabelTd.textContent = "Cargos adicionales";
      cargosRow.appendChild(cargosLabelTd);

      candidatosComparativa.forEach((candidato) => {
        const td = document.createElement("td");
        td.textContent = candidato.cargosAdicionales || "No especificado";
        td.style.whiteSpace = "pre-wrap";
        cargosRow.appendChild(td);
      });

      tbody.appendChild(cargosRow);
    }
  });

  // Render secondary rows (proposal details)
  secondaryRows.forEach((row) => {
    const tr = document.createElement("tr");
    const labelTd = document.createElement("td");
    labelTd.textContent = row.label;
    tr.appendChild(labelTd);

    candidatosComparativa.forEach((candidato) => {
      const td = document.createElement("td");
      if (row.isBoolean) {
        td.appendChild(createBoolIcon(row.getBool(candidato)));
      } else {
        td.textContent = row.getValue(candidato);
      }
      if (row.preserveWhitespace) {
        td.style.whiteSpace = "pre-wrap";
      }
      tr.appendChild(td);
    });

    tbody.appendChild(tr);
  });

  table.appendChild(tbody);
}

document.addEventListener("DOMContentLoaded", async () => {
  await cargarCandidatos();
  renderCandidatosLinks();
  renderComparativa();
  initWelcomeModal();
});

// Modal de bienvenida - se muestra siempre al cargar la página
function initWelcomeModal() {
  const modal = document.getElementById('welcome-modal');
  const closeBtn = document.getElementById('close-welcome-modal');

  if (!modal || !closeBtn) return;

  // Omitir modal si se navega a una sección específica (ej. desde candidato.php)
  if (window.location.hash) {
    return;
  }

  // Mostrar modal siempre
  modal.style.display = 'flex';
  document.body.style.overflow = 'hidden'; // Prevenir scroll

  // Cerrar modal al hacer clic en el botón
  closeBtn.addEventListener('click', function() {
    modal.style.display = 'none';
    document.body.style.overflow = ''; // Restaurar scroll
  });

  // Cerrar modal al hacer clic fuera del contenido
  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      closeBtn.click();
    }
  });
}
