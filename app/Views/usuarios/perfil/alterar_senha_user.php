<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Alterar Senha</title>

        <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
<link rel="stylesheet" href="<?=URL?>/public/css/alterar_senha_user.css">
</head>
<body>
    <?php include APP . '/Views/usuarios/menu_user.php'; ?>

    <main class="content">
        <header class="page-header">
            <div class="title-group">
                <div class="title-row">
                    <span class="title-mark"></span>
                    <h1>Alterar Senha</h1>
                </div>

                <p class="subtitle">Modifique sua senha.</p>
            </div>
        </header>
        <section class="password-card">
            <div class="card-heading">
                <h2>Meus Dados - Alterar Senha</h2>
                <div class="section-line"></div>
            </div>
            <?php Sessao::exibirFlash(); ?>
            <form class="password-form" method="post" action="<?=URL?>/usuarios/alterar_senha_user">
                <div class="password-group">
                    <label for="current-password">Digite a senha atual:</label>
                    <div class="password-input-wrapper">
                        <input id="current-password" name="senha_atual" class="password-input" type="password" autocomplete="current-password" required>
                        <button type="button" class="password-toggle" data-target="current-password" title="Mostrar/Ocultar senha">
                            <img src="<?=URL?>/public/img/icone_olhoo.png" alt="Mostrar senha" class="eye-icon">
                        </button>
                    </div>
                </div>
                <div class="password-group">
                    <label for="new-password">Digite a nova senha:</label>
                    <div class="password-input-wrapper">
                        <input id="new-password" name="nova_senha" class="password-input" type="password" autocomplete="new-password" minlength="8" maxlength="30" required>
                        <button type="button" class="password-toggle" data-target="new-password" title="Mostrar/Ocultar senha">
                            <img src="<?=URL?>/public/img/icone_olhoo.png" alt="Mostrar senha" class="eye-icon">
                        </button>
                    </div>
                </div>
                <div class="password-group">
                    <label for="confirm-password">Repita a nova senha:</label>
                    <div class="password-input-wrapper">
                        <input id="confirm-password" name="confirmar_senha" class="password-input" type="password" autocomplete="new-password" minlength="8" maxlength="30" required>

                        <button type="button" class="password-toggle" data-target="confirm-password" title="Mostrar/Ocultar senha">
                            <img src="<?=URL?>/public/img/icone_olhoo.png" alt="Mostrar senha" class="eye-icon">
                        </button>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="password-btn password-btn-primary">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </section>
    </main>
    <?php include APP . '/Views/paginas/footer.php'; ?>
    <script src="<?=URL?>/public/js/alterar-senha.js"></script>
    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>
</body>
</html>