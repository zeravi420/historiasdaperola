<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TELA SAIR</title>

    <link rel="stylesheet" href="<?=URL?>/public/css/sair_adm.css">
</head>

<body>

    <?php include APP . '/Views/administrador/menu_adm.php'; ?>

    <main class="conteudo">

        <div class="titulo">
            <h1>Sair</h1>
        </div>

        <p class="descricao">
            Deseja realmente encerrar sua sessão?
        </p>

        <div class="overlay">

            <div class="modal">

                <div class="icone-saida">
                    <img 
                        src="<?=URL?>/public/img/logo_saida.png"
                        alt="A imagem apresenta um ícone circular com fundo azul claro. No centro, há um símbolo de porta ou retângulo aberto à esquerda, 
                        com uma seta apontando para a direita , sugerindo a ação de sair ou encerrar sessão. "
                        class="icon-saida"
                    >
                </div>

                <h2>Deseja realmente sair?</h2>

                <p>
                    Sua sessão será encerrada e você será redirecionado<br>
                    para a página de login.
                </p>

                <div class="botoes">

                    <button type="button" class="cancelar">
                        <a href="<?=URL?>/administrador/inicio_adm">
                            Cancelar    
                        </a>
                    </button>

                    <button type="button" class="Sair">
                        <a href="<?=URL?>/administrador/logout">
                            Sair
                        </a>
                    </button>

                </div>

            </div>

        </div>

    </main>

    <?php include APP . '/Views/administrador/footer_adm.php'; ?>

</body>

</html>