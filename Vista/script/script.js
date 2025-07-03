document.addEventListener('DOMContentLoaded', () => {
    const showRegisterLink = document.getElementById('show-register');
    const showLoginLink = document.getElementById('show-login');
    const loginForm = document.querySelector('.login-form');
    const registerForm = document.querySelector('.register-form');

    // Función para mostrar el formulario de registro
    showRegisterLink.addEventListener('click', (e) => {
        e.preventDefault(); // Previene el comportamiento predeterminado del enlace
        loginForm.classList.add('hidden'); // Oculta el formulario de login
        registerForm.classList.remove('hidden'); // Muestra el formulario de registro
    });

    // Función para mostrar el formulario de login
    showLoginLink.addEventListener('click', (e) => {
        e.preventDefault(); // Previene el comportamiento predeterminado del enlace
        registerForm.classList.add('hidden'); // Oculta el formulario de registro
        loginForm.classList.remove('hidden'); // Muestra el formulario de login
    });
});