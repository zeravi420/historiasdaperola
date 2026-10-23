
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login do Administrador</title>

    <link rel="stylesheet" href="<?= URL ?>/public/css/login_adm.css">

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
        <div class="lado-direito">
            <img src="<?= URL ?>/public/img/icone_logo_menu.png" alt="Logo">
            <h2>HISTÓRIAS NA PÉROLA</h2>
            <p>Área do administrador</p>
        </div>

        <div class="formulario">
            <h1>Bem-vindo, administrador!</h1>
            <p>Entre para continuar</p>

            <?php Sessao::exibirFlash(); ?>

            <form id="form-login-adm"
                  action="<?= URL ?>/administrador/login"
                  method="POST">

                <label for="email">E-mail</label>

                <input
                    type="text"
                    id="email"
                    name="email"
                    value="<?= Html::e($_SESSION['antigo_email'] ?? '') ?>"
                    autocomplete="username"
                    placeholder="Digite seu e-mail"
                    required
                >

                <small class="erro-campo" id="erro-email"></small>

                <label for="senha">Senha</label>

                <input
                    type="password"
                    id="senha"
                    name="senha"
                    autocomplete="current-password"
                    placeholder="Digite sua senha"
                    required
                >

                <small class="erro-campo" id="erro-senha"></small>

                <button type="submit">Entrar</button>
            </form>
        </div>
    </div>

    <script src="<?= URL ?>/public/js/filtro-caracteres.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const formulario = document.getElementById("form-login-adm");
            const email = document.getElementById("email");
            const senha = document.getElementById("senha");
            const erroEmail = document.getElementById("erro-email");
            const erroSenha = document.getElementById("erro-senha");

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

            senha.addEventListener("input", function () {
                if (this.value.length < 8) {
                    erroSenha.textContent =
                        "A senha precisa ter pelo menos 8 caracteres.";
                } else {
                    erroSenha.textContent = "";
                }
            });

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
    </script>
<?php unset($_SESSION['antigo_email']); ?>
</body>
</html>
