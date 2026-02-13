// Manejo del menú hamburguesa en móvil
document.addEventListener('DOMContentLoaded', () => {
  const menuToggle = document.querySelector('.menu-toggle');
  const mainNav = document.querySelector('.main-nav');
  const navLinks = document.querySelectorAll('.main-nav a');

  if (!menuToggle || !mainNav) return;

  // Toggle del menú al hacer click en el botón hamburguesa
  menuToggle.addEventListener('click', () => {
    const isOpen = mainNav.classList.toggle('nav-open');
    menuToggle.setAttribute('aria-expanded', isOpen);
    menuToggle.setAttribute('aria-label', isOpen ? 'Cerrar menú' : 'Abrir menú');
  });

  // Cerrar menú al hacer click en un enlace
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      mainNav.classList.remove('nav-open');
      menuToggle.setAttribute('aria-expanded', 'false');
      menuToggle.setAttribute('aria-label', 'Abrir menú');
    });
  });

  // Cerrar menú al hacer click fuera de él
  document.addEventListener('click', (e) => {
    if (!mainNav.contains(e.target) && !menuToggle.contains(e.target)) {
      if (mainNav.classList.contains('nav-open')) {
        mainNav.classList.remove('nav-open');
        menuToggle.setAttribute('aria-expanded', 'false');
        menuToggle.setAttribute('aria-label', 'Abrir menú');
      }
    }
  });

  // Funcionalidad del acordeón para Derechos y Obligaciones
  const accordionToggles = document.querySelectorAll('.accordion-toggle');

  accordionToggles.forEach(toggle => {
    toggle.addEventListener('click', () => {
      const accordionItem = toggle.closest('.accordion-item');
      const toggleText = toggle.querySelector('.toggle-text');
      const isActive = accordionItem.classList.contains('active');

      // Toggle el acordeón actual
      if (isActive) {
        accordionItem.classList.remove('active');
        // Restaurar el texto original según el contenido
        if (toggleText.textContent.includes('derechos')) {
          toggleText.textContent = 'Ver todos los derechos y limitaciones';
        } else {
          toggleText.textContent = 'Ver todas las obligaciones';
        }
      } else {
        accordionItem.classList.add('active');
        toggleText.textContent = 'Ver menos';
      }
    });
  });
});
