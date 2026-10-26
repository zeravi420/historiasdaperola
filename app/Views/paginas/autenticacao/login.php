
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
