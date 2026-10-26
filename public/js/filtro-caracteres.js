
document.addEventListener("DOMContentLoaded", function () {
    const camposEmail = document.querySelectorAll(
        'input[name="email"], input[type="email"]'
    );

    camposEmail.forEach(function (campo) {
        function limparEmail() {
            const inicio = campo.selectionStart;

            campo.value = campo.value.replace(/[^a-zA-Z0-9.@]/g, "");

            if (inicio !== null) {
                const posicao = Math.min(inicio, campo.value.length);
                campo.setSelectionRange(posicao, posicao);
            }
        }

        campo.addEventListener("input", limparEmail);

        campo.addEventListener("paste", function (evento) {
            evento.preventDefault();

            const texto = evento.clipboardData
                .getData("text")
                .replace(/[^a-zA-Z0-9.@]/g, "");

            campo.setRangeText(
                texto,
                campo.selectionStart,
                campo.selectionEnd,
                "end"
            );

            campo.dispatchEvent(new Event("input", {
                bubbles: true
            }));
        });
    });
});
