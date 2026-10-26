<?php
// Identifica o perfil para exibir o menu correto e definir o destino de Cancelar.
$administrador = Sessao::ehAdministrador();

$paginaInicio = $administrador
    ? 'administrador/inicio_adm'
    : 'usuarios/inicio_user';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <!-- Configuração básica e folhas de estilo da tela de saída. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TELA SAIR</title>

    <link rel="stylesheet" href="<?= URL ?>/public/css/sair.css">
    <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
</head>

<body>

    <?php
    if ($administrador) {
        include APP . '/Views/administrador/menu_adm.php';
    } else {
        include APP . '/Views/usuarios/menu_user.php';
    }
    ?>

    <!-- Área principal da tela de confirmação. -->
    <main class="conteudo">

        <div class="titulo">
            <h1>Sair</h1>
        </div>

        <p class="descricao">
            Deseja realmente encerrar sua sessão?
        </p>

        <!-- Área do cartão de confirmação. -->
        <div class="overlay">

            <div class="modal">

                <div class="icone-saida">
                    <img
                        src="<?= URL ?>/public/img/logo_saida.png"
                        alt="Ícone de saída"
                        class="icon-saida"
                    >
                </div>

                <h2>Deseja realmente sair?</h2>

                <p>
                    Sua sessão será encerrada e você será redirecionado
                    para a página de login.
                </p>

                <!-- Área dos botões Cancelar e Sair. -->
                <div class="botoes">

                    <a
                        href="<?= URL ?>/<?= $paginaInicio ?>"
                        class="cancelar"
                    >
                        Cancelar
                    </a>

                    <a
                        href="<?= URL ?>/paginas/logout"
                        class="Sair"
                    >
                        Sair
                    </a>

                </div>

            </div>

        </div>

    </main>

    <?php include APP . '/Views/paginas/footer.php'; ?>

    <!-- Script das configurações de aparência e acessibilidade. -->
    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>

</body>

</html>