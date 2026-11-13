<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Configurações</title>

    <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
</head>

<body>

    <?php
        if (Sessao::ehAdministrador()) {
            include APP . '/Views/administrador/menu_adm.php';
        } elseif (Sessao::estaLogado()) {
            include APP . '/Views/usuarios/menu_user.php';
        }
    ?>


    <main class="container-configuracoes">

        <!-- CABEÇALHO -->
        <section class="cabecalho-pagina">
            <h1>Configurações</h1>
            <p>Personalize a visualização da página de acordo com suas necessidades.</p>
        </section>

        <!-- PAINEL DE ACESSIBILIDADE -->
        <section class="painel-acessibilidade">

            <!-- TEMA -->
            <div class="grupo-configuracao">
                <h2>Tema</h2>
                <p>Escolha entre o tema claro e o tema escuro.</p>

                <div class="botoes-configuracao">
                    <button type="button" data-acessibilidade-tema="claro">
                        ☀️ Claro
                    </button>

                    <button type="button" data-acessibilidade-tema="escuro">
                        🌙 Escuro
                    </button>
                </div>
            </div>

            <!-- TAMANHO DA FONTE -->
            <div class="grupo-configuracao">
                <h2>Tamanho da fonte</h2>
                <p>Ajuste o tamanho dos textos da página.</p>

                <div class="botoes-configuracao">
                    <button type="button" data-acessibilidade-fonte="normal">
                        Normal
                    </button>

                    <button type="button" data-acessibilidade-fonte="grande">
                        Grande
                    </button>

                    <button type="button" data-acessibilidade-fonte="muito-grande">
                        Muito grande
                    </button>
                </div>
            </div>

            <!-- CONTRASTE -->
            <div class="grupo-configuracao">
                <h2>Contraste</h2>
                <p>
                    Ative o alto contraste para facilitar a visualização
                    dos elementos.
                </p>

                <div class="botoes-configuracao">
                    <button
                        type="button"
                        data-acessibilidade-contraste
                        aria-pressed="false">
                        🌓 Ativar alto contraste
                    </button>
                </div>
            </div>

            <!-- RESTAURAR CONFIGURAÇÕES -->
            <div class="grupo-configuracao restaurar">
                <button
                    type="button"
                    class="botao-restaurar"
                    data-acessibilidade-restaurar>
                    ↩ Restaurar configurações padrão
                </button>
            </div>

            <!-- STATUS -->
            <div
                class="acessibilidade-status"
                id="status"
                role="status"
                aria-live="polite">
                Configurações carregadas automaticamente.
            </div>

        </section>
    </main>

    <!-- JAVASCRIPT DAS CONFIGURAÇÕES -->
    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>

    <!-- RODAPÉ -->
    <?php include APP . '/Views/paginas/footer.php'; ?>

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