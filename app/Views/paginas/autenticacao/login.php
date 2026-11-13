<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

        <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
<link rel="stylesheet" href="<?= URL ?>/public/css/login.css">

</head>

<body>
    <div class="container">
        <div class="lado-direito">
            <img src="<?= URL ?>/public/img/icone_logo_menu.png" alt="Logo">
            <h2>HISTÓRIAS NA PÉROLA</h2>
            <p>Conectando culturas através de línguas.</p>
        </div>

        <div class="formulario">
            <h1>Bem-vindo de volta!</h1>
            <p>Entre para continuar</p>

            <?php Sessao::exibirFlash(); ?>

            <form id="form-login"
                  action="<?= URL ?>/paginas/login"
                  method="POST">

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

                <label for="senha">Senha</label>

                
            <div class="campo-senha">
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    autocomplete="current-password"
                    placeholder="Digite sua senha"
                    required
                >

            <button type="button" id="mostrar-senha" aria-label="Mostrar senha">
                <svg id="icone-olho" viewBox="0 0 24 24"
                    width="20" height="20" fill="none"
                    stroke="currentColor" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path id="olho-contorno"
                        d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/>
                    <circle id="olho-pupila" cx="12" cy="12" r="2.5"/>
                    <path id="olho-risco" d="M4 4 20 20" style="display: none;"/>
                </svg>
            </button>
        </div>


                <small class="erro-campo" id="erro-senha"></small>

                <button type="submit">Entrar</button>
            </form>

            <p class="esqueceu">
                <a href="<?= URL ?>/paginas/esqueceu_senha">
                    Esqueceu senha?
                </a>
            </p>

            <p class="login">
                Ainda não tem uma conta?
                <a href="<?= URL ?>/paginas/cadastro">
                    Cadastre-se
                </a>
            </p>
        </div>
    </div>

    <script src="<?= URL ?>/public/js/filtro-caracteres.js"></script>
    <script src="<?= URL ?>/public/js/login.js"></script>

    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>
</body>
</html>