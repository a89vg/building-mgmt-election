// Datos cargados desde la API
let candidatos = [];

// Cargar datos desde la API
async function cargarCandidatos() {
  try {
    const response = await fetch('api/candidatos.php');
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    candidatos = await response.json();
  } catch (error) {
    console.error('Error al cargar candidatos:', error);
  }
}

function booleanToText(value) {
  return value ? "Sí" : "No";
}

function formatCurrencyMXN(value) {
  if (value == null || value === "") return "Por definir";
  try {
    return new Intl.NumberFormat("es-MX", {
      style: "currency",
      currency: "MXN",
      maximumFractionDigits: 0,
    }).format(value);
  } catch {
    return value + " MXN";
  }
}

// Función para abrir modal de video
function abrirModalVideo(videoUrl) {
  // Crear modal
  const modal = document.createElement("div");
  modal.className = "video-modal";
  modal.onclick = (e) => {
    if (e.target === modal) cerrarModal();
  };

  const modalContent = document.createElement("div");
  modalContent.className = "video-modal-content";

  const closeBtn = document.createElement("button");
  closeBtn.className = "video-modal-close";
  closeBtn.textContent = "✕";
  closeBtn.onclick = cerrarModal;
  modalContent.appendChild(closeBtn);

  const video = document.createElement("video");
  video.controls = true;
  video.autoplay = true;
  video.style.width = "100%";
  video.style.maxHeight = "80vh";
  video.src = videoUrl;
  modalContent.appendChild(video);

  modal.appendChild(modalContent);
  document.body.appendChild(modal);

  // Prevenir scroll del body
  document.body.style.overflow = "hidden";

  function cerrarModal() {
    document.body.removeChild(modal);
    document.body.style.overflow = "";
  }
}
