<?php 
// Buffer de saída + sessão iniciados antes de qualquer HTML, para que
// redirecionamentos (header Location) e o login funcionem.
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../app/configuracao.php'; 
include '../app/Autoload.php'; 
?>

<!DOCTYPE html> 
<html lang="pt-br"> 

<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    
    <title><?= APP_NAME ?></title> 

    <link rel="stylesheet" href="<?=URL?>/public/css/footer.css"/>
    <link rel="stylesheet" href="<?=URL?>/public/css/header_adm.css"/>
    <link rel="stylesheet" href="<?=URL?>/public/css/header_user.css"/>
    <link rel="stylesheet" href="<?=URL?>/public/css/perfil_usuario.css"/>
    <link rel="stylesheet" href="<?=URL?>/public/css/perfil_adm.css"/>
    <link rel="stylesheet" href="<?=URL?>/public/css/editar_perfil_user.css"/>
    <link rel="stylesheet" href="<?=URL?>/public/css/alterar_senha.css"/>
    <link rel="stylesheet" href="<?=URL?>/public/css/sair.css"/>

</head> 

<body> 

    <?php 
        $rotas = new Rota(); 
    ?>
      <!-- COMPONENTE DO VLIBRAS -->
    <div vw class="enabled">
        <div vw-access-button class="active"></div>

        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>

    <!-- SCRIPT OFICIAL DO VLIBRAS: CARREGAR UMA ÚNICA VEZ -->
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script> 

</body> 
</html>