// 1. Dark/Light Mode Toggle
const themeToggle = document.getElementById('theme-toggle');
const themeIcon = document.getElementById('theme-icon');
const body = document.body;

// Cargar tema guardado
const savedTheme = localStorage.getItem('theme') || 'dark';
body.setAttribute('data-theme', savedTheme);
updateThemeIcon(savedTheme);

themeToggle.addEventListener('click', () => {
  const current = body.getAttribute('data-theme');
  const newTheme = current === 'dark' ? 'light' : 'dark';
  body.setAttribute('data-theme', newTheme);
  localStorage.setItem('theme', newTheme);
  updateThemeIcon(newTheme);
});

function updateThemeIcon(theme) {
  themeIcon.className = theme === 'dark' 
    ? 'bi bi-moon-fill text-warning' 
    : 'bi bi-sun-fill text-warning';
}

// 2. Scroll animations (fade-in)
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('appear');
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.fade-in').forEach(el => {
  observer.observe(el);
});

// 3. Validación en tiempo real
document.getElementById('formReserva').addEventListener('submit', function(e) {
  let valid = true;
  const nombre = document.getElementById('nombre');
  const telefono = document.getElementById('telefono');
  const comensales = document.getElementById('comensales');

  // Reset
  [nombre, telefono, comensales].forEach(el => {
    el.classList.remove('is-invalid');
  });

  // Validar nombre
  if (!nombre.value.trim()) {
    nombre.classList.add('is-invalid');
    valid = false;
  }

  // Validar teléfono (solo números, 9-12 dígitos)
  const phoneRegex = /^[0-9]{9,12}$/;
  if (!phoneRegex.test(telefono.value)) {
    telefono.classList.add('is-invalid');
    valid = false;
  }

  // Validar comensales
  if (!comensales.value) {
    comensales.classList.add('is-invalid');
    valid = false;
  }

  if (!valid) {
    e.preventDefault();
    alert('Por favor, corrige los campos marcados.');
  }
});