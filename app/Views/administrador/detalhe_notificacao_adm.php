<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detalhe Notificação</title>

    <link rel="stylesheet" href="<?=URL?>/public/css/detalhe_notificacao_adm.css">
</head>

<body>

<?php include APP . '/Views/administrador/menu_adm.php'; ?>

<main class="content">

    <header class="page-header">

        <div class="title-group">

            <div class="title-row">
                <span class="title-mark"></span>

                <h1>Detalhes da Notificação</h1>
            </div>

            <p class="subtitle">
                Acompanhe os detalhes da sua notificação.
            </p>

        </div>

    </header>


    <section class="detail-panel">

        <div class="notification-detail-card">

            <div class="message-info">

                <h2><?= Html::e($notificacao->noti_titulo) ?></h2>

                <div class="message-date">
                    <strong>Data:</strong> <?= Html::e(Html::data($notificacao->noti_data_hora, 'd/m/Y H:i')) ?>
                </div>

            </div>


            <div class="message-content">

                <p>
                    <?= nl2br(Html::e($notificacao->noti_mensagem)) ?>
                </p>

            </div>


            <div class="detail-actions">

                <a href="<?=URL?>/administrador/notificacoes_adm" class="btn btn-primary">
                    Voltar às notificações
                </a>

            </div>

        </div>

    </section>

</main>

<?php include APP . '/Views/administrador/footer_adm.php'; ?>

</body>
</html>