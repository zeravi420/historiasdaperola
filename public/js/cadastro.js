document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.getElementById("form-cadastro");
    const nome = document.getElementById("nome");
    const email = document.getElementById("email");
    const senha = document.getElementById("senha");

    const erroNome = document.getElementById("erro-nome");
    const erroEmail = document.getElementById("erro-email");
    const erroSenha = document.getElementById("erro-senha");

    function emailValido(valor) {
        return /^[a-zA-Z0-9.]+@[a-zA-Z0-9.]+\.[a-zA-Z]{2,}$/.test(valor);
    }

    function nomeValido(valor) {
        return /^[a-zA-ZÀ-ÿ\s'-]+$/.test(valor.trim())
            && valor.trim().length >= 2;
    }

    // Validação do nome
    nome.addEventListener("input", function () {
        if (!this.value.trim()) {
            erroNome.textContent = "";
        } else if (!nomeValido(this.value)) {
            erroNome.textContent =
                "Digite um nome com pelo menos 2 caracteres, usando apenas letras.";
        } else {
            erroNome.textContent = "";
        }
    });

    // Validação do e-mail e bloqueio de símbolos proibidos
    email.addEventListener("input", function () {
        const inicio = this.selectionStart;

        this.value = this.value.replace(/[^a-zA-Z0-9.@]/g, "");

        if (inicio !== null) {
            const posicao = Math.min(inicio, this.value.length);
            this.setSelectionRange(posicao, posicao);
        }

        if (!this.value) {
            erroEmail.textContent = "";
        } else if (!emailValido(this.value)) {
            erroEmail.textContent =
                "Digite um e-mail válido. Exemplo: nome@email.com";
        } else {
            erroEmail.textContent = "";
        }
    });

    // Validação da senha
    senha.addEventListener("input", function () {
        if (this.value.length === 0) {
            erroSenha.textContent = "";
        } else if (this.value.length < 8) {
            erroSenha.textContent =
                "A senha precisa ter pelo menos 8 caracteres.";
        } else {
            erroSenha.textContent = "";
        }
    });

    // Validação antes de enviar o formulário
    formulario.addEventListener("submit", function (evento) {
        let valido = true;

        if (!nomeValido(nome.value)) {
            erroNome.textContent =
                "Digite um nome com pelo menos 2 caracteres, usando apenas letras.";
            valido = false;
        }

        if (!emailValido(email.value)) {
            erroEmail.textContent =
                "Digite um e-mail válido. Exemplo: nome@email.com";
            valido = false;
        }

        if (senha.value.length < 8 || senha.value.length > 30) {
            erroSenha.textContent =
                "A senha precisa ter entre 8 e 30 caracteres.";
            valido = false;
        }

        if (!valido) {
            evento.preventDefault();
        }
    });
});