<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro</title>

        <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
<link rel="stylesheet" href="<?= URL ?>/public/css/cadastro.css">

</head>

<body>

<div class="container">

    <div class="lado-direito">
        <img src="<?= URL ?>/public/img/icone_logo_menu.png" alt="Logo">
        <h2>HISTÓRIAS NA PÉROLA</h2>
        <p>Conectando culturas através de línguas.</p>
    </div>

    <div class="formulario">
        <h1>Crie sua conta!</h1>
        <p>Cadastre-se para continuar</p>

        <?php Sessao::exibirFlash(); ?>

        <form id="form-cadastro"
              action="<?= URL ?>/usuarios/cadastro_user"
              method="POST">

            <label for="nome">Nome completo</label>
            <input
                type="text"
                id="nome"
                name="nome"
                placeholder="Digite seu nome"
                autocomplete="name"
                required
            >
            <small class="erro-campo" id="erro-nome"></small>

            <label for="email">E-mail</label>
            <input
                type="text"
                id="email"
                name="email"
                maxlength="100"
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
                    minlength="8"
                    maxlength="30"
                    placeholder="Digite sua senha"
                    autocomplete="new-password"
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


            <button type="submit">Cadastrar</button>
        </form>

        <p class="login">
            Já tem uma conta?
            <a href="<?= URL ?>/paginas/login">Entrar</a>
        </p>
    </div>

</div>

<script src="<?= URL ?>/public/js/filtro-caracteres.js"></script>
<script src="<?= URL ?>/public/js/cadastro.js"></script>


    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>
</body>
</html>