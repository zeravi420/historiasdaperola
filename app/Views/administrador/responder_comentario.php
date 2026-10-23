<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Responder Comentário</title>

    <link rel="stylesheet" href="<?=URL?>/public/css/responder_comentario.css">
</head>

<body>

    <?php include APP . '/Views/administrador/menu_adm.php'; ?>

    <main class="content">

        
        <div class="page-title">

            <div class="title-line"></div>

            <div>
                <h1>Responder comentário</h1>

                <p>
                    Responda ao comentário enviado pelo usuário.
                </p>
            </div>

        </div>


        
        <?php Sessao::exibirFlash(); ?>

        <form class="reply-card" method="post" action="<?=URL?>/administrador/responder_comentario">

            <input type="hidden" name="id" value="<?= (int) $comentario->come_id ?>">

            
            <div class="top-information">

                <div class="info-section">
                    <span class="label">Usuário</span>

                    <span class="value">
                        <?= Html::e($comentario->pess_nome) ?>
                    </span>
                </div>


                <div class="info-section">
                    <span class="label">História</span>

                    <span class="value">
                        <?= Html::e($comentario->hiid_titulo) ?>
                    </span>
                </div>

            </div>


            
            <div class="info-section comment-section">

                <span class="label">Comentário</span>

                <div class="comment-box">
                    <?= nl2br(Html::e($comentario->come_texto)) ?>
                </div>

            </div>


            
            <div class="comment-date">
                <?= Html::e(Html::data($comentario->come_data_pessoa, 'd/m/Y \\à\\s H:i:s')) ?>
            </div>


            
            <div class="response-section">

                <label for="resposta">
                    Sua resposta
                </label>

                <textarea
                    id="resposta"
                    name="resposta"
                    maxlength="2000"
                    placeholder="Digite sua resposta... Ela chegará ao usuário como notificação."
                    required></textarea>

            </div>


            
            <div class="form-buttons">

                <a
                    href="<?=URL?>/administrador/historias_adm?id=<?= (int) $comentario->hist_id ?>#comentarios"
                    class="cancel-button">
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="reply-button">
                    Enviar
                </button>

            </div>

        </form>

    </main>
    
<?php include APP . '/Views/administrador/footer_adm.php'; ?>

</body>

</html>