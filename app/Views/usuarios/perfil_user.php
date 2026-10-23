<!DOCTYPE html> 
<html lang="pt-BR"> 

<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    
<title>Meu Perfil</title> 
 
    <link rel="stylesheet" href="<?=URL?>/public/css/perfil_usuario.css"> 
</head> 

<body> 

    <?php include APP . '/Views/usuarios/menu_user.php'; ?>

    <main class="content"> 

        <header class="page-header"> 
            <div class="title-group"> 
                <div class="title-row"> 
                    <span class="title-mark"></span> 
                    <h1>Meu Perfil</h1> 
                </div> 
 
                <p class="subtitle">Visualize e gerencie suas informações pessoais.</p> 
            </div> 
        </header> 

        <?php Sessao::exibirFlash(); ?>

        <section class="profile-card"> 

            <h2>Meus Dados</h2> 

            <div class="profile-grid"> 

                <div class="profile-aside"> 

                    <div class="avatar-frame"> 
                        <img src="<?= Html::e(Html::fotoPerfil($pessoa->pess_foto_perfil)) ?>" alt="Imagem ilustrativa de uma pessoa, usada como foto de perfil do usuário">
                    </div> 

                    <div class="profile-actions"> 

                        <a href="<?=URL?>/usuarios/editar_perfil_user" class="profile-btn profile-btn-primary"> 
                            Editar perfil 
                        </a> 

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
                            <p class="info-label">Nome Completo</p> 
                            <p class="info-value"><?= Html::e($pessoa->pess_nome) ?></p> 
                        </div> 
                    </div> 

                    <div class="info-item"> 
                        <span class="info-icon"> 
                            <img src="<?=URL?>/public/img/icone_email.png" alt="Ícone verde de um envelope, representando o endereço de e-mail do usuário">
                        </span> 

                        <div class="info-content"> 
                            <p class="info-label">E-mail</p> 
                            <p class="info-value"><?= Html::e($pessoa->emai_nome) ?></p> 
                        </div> 
                    </div> 

                </div> 

            </div> 

        </section> 

    </main> 

    <?php include APP . '/Views/usuarios/footer_user.php'; ?>

</body> 

</html>