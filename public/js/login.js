// Valida os campos de e-mail e senha antes de enviar o formulário ao servidor.
document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.getElementById("form-login");
    const email = document.getElementById("email");
    const senha = document.getElementById("senha");
    const erroEmail = document.getElementById("erro-email");
    const erroSenha = document.getElementById("erro-senha");

    // Confere o formato básico do e-mail digitado.
    function emailValido(valor) {
        return /^[a-zA-Z0-9.]+@[a-zA-Z0-9.]+\.[a-zA-Z]{2,}$/.test(valor);
    }

    // Mostra ou remove o aviso enquanto o e-mail é digitado.
    email.addEventListener("input", function () {
        this.value = this.value.replace(/[^a-zA-Z0-9.@]/g, "");

        if (this.value && !emailValido(this.value)) {
            erroEmail.textContent =
                "Digite um e-mail válido. Exemplo: nome@email.com";
        } else {
            erroEmail.textContent = "";
        }
    });

    // Confere o tamanho mínimo da senha enquanto ela é digitada.
    senha.addEventListener("input", function () {
        if (this.value.length < 8) {
            erroSenha.textContent =
                "A senha precisa ter pelo menos 8 caracteres.";
        } else {
            erroSenha.textContent = "";
        }
    });

    // Impede o envio apenas quando os campos estão preenchidos incorretamente.
    formulario.addEventListener("submit", function (evento) {
        let valido = true;

        if (!emailValido(email.value)) {
            erroEmail.textContent = "Digite um e-mail válido.";
            valido = false;
        }

        if (senha.value.length < 8) {
            erroSenha.textContent =
                "A senha precisa ter pelo menos 8 caracteres.";
            valido = false;
        }

        if (!valido) {
            evento.preventDefault();
        }
    });
});