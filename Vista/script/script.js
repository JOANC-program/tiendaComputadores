document.addEventListener('DOMContentLoaded', () => {
    const showRegisterLink = document.getElementById('show-register');
    const showLoginLink = document.getElementById('show-login');
    const loginForm = document.querySelector('.login-form');
    const registerForm = document.querySelector('.register-form');

    // Gráfica de productos más vendidos
    if (typeof productos !== "undefined" && typeof cantidades !== "undefined" && document.getElementById('masVendidos')) {
        new Chart(document.getElementById('masVendidos'), {
            type: 'pie',
            data: {
                labels: productos,
                datasets: [{
                    label: 'Más vendidos',
                    data: cantidades,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)'
                    ]
                }]
            }
        });
    }
});

// Función para mostrar el formulario de registro
document.addEventListener('DOMContentLoaded', () => {
    const showRegisterLink = document.getElementById('show-register');
    const showLoginLink = document.getElementById('show-login');
    const loginForm = document.querySelector('.login-form');
    const registerForm = document.querySelector('.register-form');

    showRegisterLink.addEventListener('click', (e) => {
        e.preventDefault(); 
        loginForm.classList.add('hidden'); 
        registerForm.classList.remove('hidden'); 
    });

    showLoginLink.addEventListener('click', (e) => {
        e.preventDefault(); 
        registerForm.classList.add('hidden');
        loginForm.classList.remove('hidden'); 
    });
});
