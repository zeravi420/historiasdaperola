<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detalhe Notificação</title>

        <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
<link rel="stylesheet" href="<?=URL?>/public/css/detalhe_notificacao_user.css">
</head>

<body>

<?php include APP . '/Views/usuarios/menu_user.php'; ?>

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

                <a href="<?= URL ?>/usuarios/notificacoes_user" class="btn btn-secondary">
                    Voltar às notificações
                </a>

                <?php if (!empty($historiaId)): ?>
                    <a href="<?= URL ?>/usuarios/historias_user?id=<?= (int) $historiaId ?><?= ($notificacao->noti_titulo === 'Novo comentário' ? '#comentarios' : '') ?>" class="btn btn-primary">
                        Ver história
                    </a>
                <?php endif; ?>

            </div>

        </div>

    </section>

</main>

<?php include APP . '/Views/paginas/footer.php'; ?>

    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>
</body>
</html>