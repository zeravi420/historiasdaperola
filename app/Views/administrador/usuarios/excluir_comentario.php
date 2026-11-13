<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Excluir Comentário</title>

        <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
<link rel="stylesheet" href="<?=URL?>/public/css/excluir_comentario.css">
</head>

<body>

    <?php include APP . '/Views/administrador/menu_adm.php'; ?>

    <main class="conteudo">

        <div class="titulo">
            <h1>Excluir Comentário</h1>
        </div>

        <p class="descricao">
            Deseja realmente excluir este comentário?
        </p>

        <div class="overlay">

            <div class="modal">

                <h2>
                    Deseja realmente excluir este comentário?
                </h2>

                <p>
                    Comentário de <strong><?= Html::e($comentario->pess_nome) ?></strong>
                    em "<?= Html::e($comentario->hiid_titulo) ?>":<br>
                    <em>"<?= Html::e(mb_strimwidth($comentario->come_texto, 0, 200, '...', 'UTF-8')) ?>"</em>
                </p>

                <p>
                    O comentário será apagado permanentemente,
                    exclua se tiver certeza dessa decisão.
                </p>

                <form class="botoes" method="post" action="<?=URL?>/administrador/excluir_comentario">

                    <input type="hidden" name="id" value="<?= (int) $comentario->come_id ?>">

                    <button type="button" class="cancelar">
                        <a href="<?=URL?>/administrador/historias_adm?id=<?= (int) $comentario->hist_id ?>#comentarios">
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

    <?php include APP . '/Views/paginas/footer.php'; ?>

    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>
</body>

</html>