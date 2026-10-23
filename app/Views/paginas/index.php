<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro</title>
    <link rel="stylesheet" href="<?=URL?>/public/css/index.css">
</head>
<body>

<div class="container">

    <div class="formulario">
        <h1>Identifique-se</h1>
        <p>Selecione uma das opções</p>

        <form>

            <label>Sou:</label>

            <a href="<?=URL?>/usuarios/cadastro_user"><button type="button">Usuário</button></a>
            <a href="<?=URL?>/administrador/login_adm"><button type="button">Administrador</button></a>

        </form>


        <p class="login">
            Já tem uma conta? <a href="<?=URL?>/usuarios/login_user">Login</a>
        </p>

    

    </div>

    <div class="lado-direito">

        <img src="<?=URL?>/public/img/icone_logo_menu.png" alt="Logo">

        <h2>HISTÓRIAS NA PÉROLA</h2>

        <p>Conectando culturas através de línguas</p>

    </div>

</div>

</body>
</html>