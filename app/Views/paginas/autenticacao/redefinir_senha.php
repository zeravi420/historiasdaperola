
<?php
// Recebe o token enviado pelo controlador.
// Caso não esteja disponível, tenta recuperar o token da URL.
$token = (string) ($token ?? ($_GET['token'] ?? ''));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="no-referrer">

    <title>Criar nova senha</title>

    <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
    <link rel="stylesheet" href="<?= URL ?>/public/css/esqueceu_senha.css">

    <style>
        .formulario p {
            margin-bottom: 18px;
        }

        .erro-campo {
            display: block;
            color: #ff0000 !important;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.4;
            text-align: left;
            margin: 4px 0 6px;
        }

        .erro-campo:empty {
            display: none;
        }

        .dica-senha {
            font-size: 13px;
            color: #666;
            margin-top: 4px;
        }

        .campo-senha {
            position: relative;
            width: 100%;
        }

        .campo-senha input {
            padding-right: 45px;
        }

        .campo-senha .password-toggle {
            position: absolute;
            top: 0;
            right: 6px;
            width: 34px;
            height: 40px;
            margin: 0;
            padding: 0;
            background: transparent;
            color: #555;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .campo-senha .password-toggle:hover {
            background: transparent;
        }

        .voltar-login {
            text-align: center;
            margin: 14px 0 0 !important;
        }

        .voltar-login a {
            color: #2f5d34;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="formulario">

            <h1>Criar nova senha</h1>
            <p>A nova senha não pode ser igual à senha atual.</p>

            <?php Sessao::exibirFlash(); ?>

            <form id="form-redefinir-senha"
                  action="<?= URL ?>/paginas/redefinir_senha"
                  method="POST">

                <!-- Token de recuperação recebido pelo controlador -->
                <input type="hidden" name="token" value="<?= Html::e($token) ?>">

                <label for="nova_senha">Nova senha</label>

                <div class="campo-senha">
                    <input
                        type="password"
                        id="nova_senha"
                        name="nova_senha"
                        autocomplete="new-password"
                        placeholder="Digite a nova senha"
                        minlength="8"
                        maxlength="30"
                        required
                    >

                    <button type="button"
                            class="password-toggle"
                            data-target="nova_senha"
                            aria-label="Mostrar senha"
                            title="Mostrar senha">

                        <svg viewBox="0 0 24 24"
                             width="22"
                             height="22"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.5"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/>
                            <circle cx="12" cy="12" r="2.5"/>
                            <path class="eye-slash" d="M4 4 20 20" style="display: none;"/>

                        </svg>
                    </button>
                </div>

                <small class="dica-senha">
                    Entre 8 e 30 caracteres, contendo pelo menos um número e um caractere especial.
                </small>

                <small class="erro-campo" id="erro-nova"></small>

                <label for="confirmar_senha">Confirmar nova senha</label>

                <div class="campo-senha">
                    <input
                        type="password"
                        id="confirmar_senha"
                        name="confirmar_senha"
                        autocomplete="new-password"
                        placeholder="Repita a nova senha"
                        minlength="8"
                        maxlength="30"
                        required
                    >

                    <button type="button"
                            class="password-toggle"
                            data-target="confirmar_senha"
                            aria-label="Mostrar senha"
                            title="Mostrar senha">

                        <svg viewBox="0 0 24 24"
                             width="22"
                             height="22"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.5"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/>
                            <circle cx="12" cy="12" r="2.5"/>
                            <path class="eye-slash" d="M4 4 20 20" style="display: none;"/>

                        </svg>
                    </button>
                </div>

                <small class="erro-campo" id="erro-confirmar"></small>

                <button type="submit">Salvar senha</button>

            </form>

            <p class="voltar-login">
                <a href="<?= URL ?>/paginas/login">Voltar para o login</a>
            </p>

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

    <script src="<?= URL ?>/public/js/alterar-senha.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const formulario = document.getElementById("form-redefinir-senha");
            const nova = document.getElementById("nova_senha");
            const confirmar = document.getElementById("confirmar_senha");

            const erroNova = document.getElementById("erro-nova");
            const erroConfirmar = document.getElementById("erro-confirmar");

            function validar() {

                let ok = true;

                erroNova.textContent = "";
                erroConfirmar.textContent = "";

                // Verifica o tamanho da senha.
                if (nova.value.length < 8 || nova.value.length > 30) {

                    erroNova.textContent = "A senha precisa ter entre 8 e 30 caracteres.";
                    ok = false;

                }

                // Verifica se possui pelo menos um número.
                else if (!/[0-9]/.test(nova.value)) {

                    erroNova.textContent = "A senha precisa conter pelo menos um número.";
                    ok = false;

                }

                // Verifica se possui pelo menos um caractere especial.
                else if (!/[^a-zA-Z0-9]/.test(nova.value)) {

                    erroNova.textContent = "A senha precisa conter pelo menos um caractere especial.";
                    ok = false;

                }

                // Verifica se as senhas são iguais.
                if (confirmar.value && confirmar.value !== nova.value) {

                    erroConfirmar.textContent = "As senhas não conferem.";
                    ok = false;

                }

                return ok;
            }

            nova.addEventListener("input", validar);
            confirmar.addEventListener("input", validar);

            formulario.addEventListener("submit", function (evento) {

                if (!validar() || confirmar.value !== nova.value) {

                    if (!erroConfirmar.textContent && confirmar.value !== nova.value) {
                        erroConfirmar.textContent = "As senhas não conferem.";
                    }

                    evento.preventDefault();

                }

            });

        });
    </script>

    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>

</body>
</html>
