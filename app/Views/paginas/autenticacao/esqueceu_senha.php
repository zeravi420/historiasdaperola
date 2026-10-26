
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Esqueceu a senha?</title>

        <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
<link rel="stylesheet" href="<?= URL ?>/public/css/esqueceu_senha.css">

    <style>
        .erro-campo {
            display: block;
            color: #ff0000 !important;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.4;
            text-align: left;
            margin: 4px 0 10px;
            padding: 0;
        }

        .erro-campo:empty {
            display: none;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="formulario">

            <h1>Esqueceu a senha?</h1>
            <p>Digite seu e-mail para recuperar sua conta</p>

            <form id="form-esqueceu-senha">

                <label for="email">E-mail</label>

                <input
                    type="text"
                    id="email"
                    name="email"
                    autocomplete="email"
                    placeholder="Digite seu e-mail"
                    required
                >

                <small class="erro-campo" id="erro-email"></small>

                <button type="submit">Enviar</button>

            </form>

        </div>

        <div class="lado-direito">

            <img
                src="<?= URL ?>/public/img/icone_logo_menu.png"
                alt="Logo"
            >

            <h2>HISTÓRIAS NA PÉROLA</h2>

            <p>Conectando culturas através de línguas.</p>

        </div>

    </div>

    <script src="<?= URL ?>/public/js/filtro-caracteres.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const formulario = document.getElementById("form-esqueceu-senha");
            const email = document.getElementById("email");
            const erroEmail = document.getElementById("erro-email");

            function emailValido(valor) {
                return /^[a-zA-Z0-9.]+@[a-zA-Z0-9.]+\.[a-zA-Z]{2,}$/.test(valor);
            }

            email.addEventListener("input", function () {

                this.value = this.value.replace(/[^a-zA-Z0-9.@]/g, "");

                if (this.value && !emailValido(this.value)) {
                    erroEmail.textContent =
                        "Digite um e-mail válido. Exemplo: nome@email.com";
                } else {
                    erroEmail.textContent = "";
                }

            });

            formulario.addEventListener("submit", function (evento) {

                if (!emailValido(email.value)) {
                    evento.preventDefault();

                    erroEmail.textContent =
                        "Digite um e-mail válido. Exemplo: nome@email.com";

                    email.focus();
                }

            });

        });
    </script>

    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>
</body>
</html>
