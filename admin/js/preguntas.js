/**
 * Admin functions for preguntas (questions) management
 * Note: This file expects preguntas and candidatoNames to be defined globally by the PHP page
 */

/**
 * Create an element with text content that respects line breaks (uses <br>)
 * Safe against XSS because it uses createTextNode
 */
function createMultilineElement(tag, text, className = '') {
    const el = document.createElement(tag);
    if (className) el.className = className;

    const safeText = text || '';
    const lines = safeText.split(/\r?\n/);
    lines.forEach((line, index) => {
        el.appendChild(document.createTextNode(line));
        if (index < lines.length - 1) {
            el.appendChild(document.createElement('br'));
        }
    });

    return el;
}

/**
 * Create a form group with label and value using DOM (safe against XSS)
 */
function createFormGroup(labelText, value, options = {}) {
    const group = document.createElement('div');
    group.className = 'form-group';

    const label = document.createElement('label');
    const strong = document.createElement('strong');
    strong.textContent = labelText;
    label.appendChild(strong);
    group.appendChild(label);

    const p = options.multiline
        ? createMultilineElement('p', value)
        : document.createElement('p');

    if (!options.multiline) {
        p.textContent = value || 'No proporcionado';
    }

    if (options.style) {
        p.style.cssText = options.style;
    }

    group.appendChild(p);
    return group;
}

function openAnswerModal(candidatoId, preguntaId, index) {
    const pregunta = preguntas[candidatoId][index];
    const modalContent = document.getElementById('modal-content');
    modalContent.innerHTML = '';

    // Candidato
    modalContent.appendChild(createFormGroup('Candidato:', candidatoNames[candidatoId]));

    // Nombre del vecino
    modalContent.appendChild(createFormGroup('Nombre del vecino:', pregunta.nombre));

    // Correo
    modalContent.appendChild(createFormGroup('Correo:', pregunta.correo));

    // Pregunta (with multiline and background style)
    modalContent.appendChild(createFormGroup('Pregunta:', pregunta.comentario, {
        multiline: true,
        style: 'background: var(--bg-alt); padding: 1rem; border-radius: var(--radius-md);'
    }));

    // Form
    const form = document.createElement('form');
    form.id = 'answer-form';
    form.onsubmit = (e) => saveAnswer(e, candidatoId, preguntaId);

    // Textarea group
    const textareaGroup = document.createElement('div');
    textareaGroup.className = 'form-group';

    const textareaLabel = document.createElement('label');
    textareaLabel.htmlFor = 'respuesta';
    const textareaStrong = document.createElement('strong');
    textareaStrong.textContent = 'Respuesta del Proveedor: *';
    textareaLabel.appendChild(textareaStrong);
    textareaGroup.appendChild(textareaLabel);

    const textarea = document.createElement('textarea');
    textarea.id = 'respuesta';
    textarea.name = 'respuesta';
    textarea.rows = 6;
    textarea.required = true;
    textarea.placeholder = 'Escribe la respuesta del proveedor aquí...';
    textarea.value = pregunta.respuesta || '';
    textareaGroup.appendChild(textarea);

    form.appendChild(textareaGroup);

    // Buttons
    const actionsDiv = document.createElement('div');
    actionsDiv.className = 'admin-form-actions';

    const submitBtn = document.createElement('button');
    submitBtn.type = 'submit';
    submitBtn.className = 'btn btn-primary';
    submitBtn.textContent = 'Guardar Respuesta';
    actionsDiv.appendChild(submitBtn);

    const cancelBtn = document.createElement('button');
    cancelBtn.type = 'button';
    cancelBtn.className = 'btn btn-secondary';
    cancelBtn.textContent = 'Cancelar';
    cancelBtn.onclick = closeAnswerModal;
    actionsDiv.appendChild(cancelBtn);

    form.appendChild(actionsDiv);
    modalContent.appendChild(form);

    document.getElementById('answer-modal').style.display = 'block';
}

function closeAnswerModal() {
    document.getElementById('answer-modal').style.display = 'none';
}

async function saveAnswer(event, candidatoId, preguntaId) {
    event.preventDefault();
    const form = event.target;
    const respuesta = form.respuesta.value.trim();

    try {
        const response = await fetch('api/save-respuesta.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                candidatoId,
                preguntaId,
                respuesta
            })
        });

        const result = await response.json();

        if (result.success) {
            showAlert('Respuesta guardada correctamente', 'success');
            closeAnswerModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert(result.error || 'Error al guardar la respuesta', 'error');
        }
    } catch (error) {
        showAlert('Error de conexión: ' + error.message, 'error');
    }
}

async function deletePregunta(candidatoId, preguntaId) {
    if (!confirm('¿Estás seguro de que quieres eliminar esta pregunta?')) {
        return;
    }

    try {
        const response = await fetch('api/delete-pregunta.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ candidatoId, preguntaId })
        });

        const result = await response.json();

        if (result.success) {
            showAlert('Pregunta eliminada correctamente', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(result.error || 'Error al eliminar la pregunta', 'error');
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
