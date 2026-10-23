<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Excluir Notificação</title>

    <link rel="stylesheet" href="<?=URL?>/public/css/excluir_notificacao_adm.css">
</head>

<body>

    <?php include APP . '/Views/administrador/menu_adm.php'; ?>

    <main class="conteudo">

        <div class="titulo">
            <h1>Excluir Notificação</h1>
        </div>

        <p class="descricao">
            Deseja realmente excluir esta notificação?
        </p>

        <div class="overlay">

            <div class="modal">

                <h2>
                    Deseja realmente excluir esta notificação?
                </h2>

                <p>
                    <strong><?= Html::e($notificacao->noti_titulo) ?></strong><br>
                    A notificação será apagada permanentemente,
                    exclua se tiver certeza dessa decisão.
                </p>

                <form class="botoes" method="post" action="<?=URL?>/administrador/excluir_notificacao_adm">

                    <input type="hidden" name="id" value="<?= (int) $notificacao->noti_id ?>">

                    <button type="button" class="cancelar">
                        <a href="<?=URL?>/administrador/notificacoes_adm">
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