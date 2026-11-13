
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova História</title>
        <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
<link rel="stylesheet" href="<?= URL ?>/public/css/nova_historia.css">
</head>

<body>

<?php include APP . '/Views/administrador/menu_adm.php'; ?>

<div class="container">

    <div class="titulo-pagina">
        <h1>Nova História</h1>
        <p>Adicione novas histórias.</p>
    </div>

    <div class="card">

        <?php Sessao::exibirFlash(); ?>

        <form
            action="<?= URL ?>/administrador/salvar_historia"
            method="POST"
            enctype="multipart/form-data"
            id="formHistoria"
        >

            <h2>Adicionar nova história</h2>

            <span>Preencha os campos em cada língua.</span>

            <div class="idiomas">

                <button type="button" class="ativo" data-idioma="pt">
                    <img src="<?= URL ?>/public/img/brasil.png" alt="Bandeira do Brasil">
                    <span>Português</span>
                </button>

                <button type="button" data-idioma="es">
                    <img src="<?= URL ?>/public/img/espanha.png" alt="Bandeira da Espanha">
                    <span>Espanhol</span>
                </button>

                <button type="button" class="idioma-wari" data-idioma="wari">
                    <img src="<?= URL ?>/public/img/icone_logo_menu.png" alt="Logo Wari' Oro Nao">
                    <span>Wari' Oro Nao</span>
                </button>

            </div>

            <div class="linha">

                <div class="campo">
                    <label for="categoria">Categoria</label>

                    <select id="categoria" name="categoria" required>
                        <option value="">Selecione uma categoria</option>
                        <option value="1">Mito</option>
                        <option value="2">Lenda</option>
                    </select>
                </div>

                <div class="campo imagem">
                    <label for="imagem">Imagem</label>

                    <input
                        type="file"
                        id="imagem"
                        name="imagem"
                        accept="image/jpeg,image/png,image/webp"
                        required
                    >
                </div>

            </div>

            <div class="linha">

                <div class="campo">
                    <label id="labelTitulo" for="titulo">
                        Título (Português)
                    </label>

                    <input
                        type="text"
                        id="titulo"
                        placeholder="Digite o título em português..."
                        maxlength="100"
                        required
                    >
                </div>

                <div class="campo">
                    <label id="labelLegenda" for="legenda">
                        Legenda da imagem (Português)
                    </label>

                    <input
                        type="text"
                        id="legenda"
                        placeholder="Digite a legenda da imagem em português..."
                        maxlength="100"
                        required
                    >
                </div>

            </div>

            <div class="campo">
                <label id="labelTexto" for="texto">
                    Texto (Português)
                </label>

                <textarea
                    id="texto"
                    placeholder="Digite o texto em português..."
                    required
                ></textarea>
            </div>

            <input type="hidden" name="titulo_pt" id="titulo_pt">
            <input type="hidden" name="legenda_pt" id="legenda_pt">
            <input type="hidden" name="texto_pt" id="texto_pt">

            <input type="hidden" name="titulo_es" id="titulo_es">
            <input type="hidden" name="legenda_es" id="legenda_es">
            <input type="hidden" name="texto_es" id="texto_es">

            <input type="hidden" name="titulo_wari" id="titulo_wari">
            <input type="hidden" name="legenda_wari" id="legenda_wari">
            <input type="hidden" name="texto_wari" id="texto_wari">

            <div class="acoes">

                <button
                    type="button"
                    class="cancelar"
                    onclick="window.location.href='<?= URL ?>/administrador/gerenciar_historia_adm'"
                >
                    Cancelar
                </button>

                <button type="submit" class="salvar">
                    Salvar história
                </button>

            </div>

        </form>

    </div>

</div>

<?php include APP . '/Views/paginas/footer.php'; ?>

<script src="<?= URL ?>/public/js/novas_historias.js" defer></script>

    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>
</body>
</html>
