<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Perfil</title>
    <link rel="stylesheet" href="<?=URL?>/public/css/editar_perfil_user.css">
</head>
<body>

    <?php include APP . '/Views/usuarios/menu_user.php'; ?>

    <main class="content">

        <header class="page-header">
            <div class="title-group">
                <div class="title-row">
                    <span class="title-mark"></span>
                    <h1>Editar Perfil</h1>
                </div>
                <p class="subtitle">
                    Altere seus dados.
                </p>
            </div>
        </header>

        <section class="profile-card">
            <h2>Editar Meus Dados</h2>
            <?php Sessao::exibirFlash(); ?>
            <form method="post" action="<?=URL?>/usuarios/editar_perfil_user" enctype="multipart/form-data">
                <div class="profile-grid">

                    <div class="profile-aside">

                        <div class="avatar-frame">

                            <img id="previewFoto" src="<?= Html::e(Html::fotoPerfil($pessoa->pess_foto_perfil)) ?>" alt="Imagem ilustrativa de uma pessoa, usada como foto de perfil do administrador">
                            <label for="fotoPerfil" class="avatar-edit" title="Alterar foto">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M9 3L7.5 5H5C3.9 5 3 5.9 3 7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V7C21 5.9 20.1 5 19 5H16.5L15 3H9Z"/>
                                    <circle cx="12" cy="12.5" r="3.5"/>
                                </svg>
                            </label>
                            <input type="file" id="fotoPerfil" name="fotoPerfil" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" hidden>
                        </div>

                        <div class="profile-actions">
                            <button type="submit" class="profile-btn profile-btn-primary">
                                Salvar Alterações
                            </button>
                            <a href="<?=URL?>/usuarios/alterar_senha_user" class="profile-btn profile-btn-secondary">
                                Alterar senha
                            </a>
                        </div>
                    </div>

                    <div class="info-grid">

                        <div class="info-item">

                            <span class="info-icon">
                                <img src="<?=URL?>/public/img/icone_nome.png" alt="Ícone verde representando a silhueta de uma pessoa, indicando o nome completo do usuário">
                            </span>

                            <div class="info-content">
                                <p class="info-label">
                                    Nome Completo
                                </p>
                                <input class="info-input" type="text" name="nome" maxlength="100" required value="<?= Html::e($pessoa->pess_nome) ?>">
                            </div>
                        </div>

                        <div class="info-item">

                            <span class="info-icon">

                                <img src="<?=URL?>/public/img/icone_email.png" alt="Ícone verde de um envelope, representando o endereço de e-mail do usuário">
                            </span>

                            <div class="info-content">

                                <p class="info-label">
                                    E-mail
                                </p>

                                <input class="info-input" type="email" name="email" maxlength="200" required value="<?= Html::e($pessoa->emai_nome) ?>">

                            </div>

                        </div>

                    </div>


                </div>


            </form>

        </section>

    </main>

    <?php include APP . '/Views/usuarios/footer_user.php'; ?>
    <script src="<?=URL?>/public/js/foto_perfil.js"></script>

</body>

</html>