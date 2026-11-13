document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.password-toggle').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.target);
            const risco = button.querySelector('.eye-slash');
            if (!input) return;
            const mostrar = input.type === 'password';
            input.type = mostrar ? 'text' : 'password';
            if (risco) risco.style.display = mostrar ? 'block' : 'none';
            button.setAttribute('aria-label', mostrar ? 'Ocultar senha' : 'Mostrar senha');
            button.setAttribute('title', mostrar ? 'Ocultar senha' : 'Mostrar senha');
        });
    });
});
