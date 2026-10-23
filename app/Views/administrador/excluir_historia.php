<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Excluir História</title>

    <link rel="stylesheet" href="<?=URL?>/public/css/excluir_historia.css">
</head>

<body>

    <?php include APP . '/Views/administrador/menu_adm.php'; ?>

    <main class="conteudo">

        <div class="titulo">
            <h1>Excluir História</h1>
        </div>

        <p class="descricao">
            Remova suas histórias.
        </p>

        <div class="overlay">

            <div class="modal">

                <h2>
                    Deseja realmente excluir esta história?
                </h2>

                <p>
                    <strong>"<?= Html::e($titulo) ?>"</strong> será apagada permanentemente,
                    junto com as traduções, comentários e avaliações.
                    Exclua se tiver certeza dessa decisão.
                </p>

                <form class="botoes" method="post" action="<?=URL?>/administrador/excluir_historia_adm">

                    <input type="hidden" name="id" value="<?= (int) $id ?>">

                    <button type="button" class="cancelar">
                        <a href="<?=URL?>/administrador/gerenciar_historia_adm">
                            Cancelar
                        </a>
                    </button>

                    <button type="submit" class="excluir">
                        Excluir
                    </button>

                </form>

            </div>

        </div>

    </main>

    <?php include APP . '/Views/administrador/footer_adm.php'; ?>

</body>

</html>