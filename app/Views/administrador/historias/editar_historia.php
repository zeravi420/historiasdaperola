
<?php
$idiomas = $historia->idiomas ?? [];

$portugues = $idiomas[1] ?? null;
$espanhol = $idiomas[2] ?? null;
$wari = $idiomas[3] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar História</title>

    <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
    <link rel="stylesheet" href="<?= URL ?>/public/css/editar_historia.css">
</head>

<body>

    <?php include APP . '/Views/administrador/menu_adm.php'; ?>

    <main class="pagina">

        <!-- CABEÇALHO DA PÁGINA -->
        <section class="cabecalho-pagina">
            <h1>Editar História</h1>
            <p>Modifique suas histórias.</p>
        </section>

        <!-- CARD DE EDIÇÃO -->
        <section class="modal">

            <div class="modal-header">
                <h2>Editar História</h2>

                <a
                    href="<?= URL ?>/administrador/gerenciar_historia_adm"
                    class="fechar"
                    aria-label="Fechar"
                >×</a>
            </div>

            <form
                id="formEditarHistoria"
                action="<?= URL ?>/administrador/atualizar_historia"
                method="POST"
                enctype="multipart/form-data"
            >

                <!-- ID DA HISTÓRIA -->
                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $historia->id ?>"
                >

                <!-- PRÉ-VISUALIZAÇÃO DA IMAGEM -->
                <div class="imagem-container">
                    <img
                        id="imagemPreview"
                        class="imagem-preview"
                        src="<?= htmlspecialchars(
                            $historia->imagem_uri ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        alt="Imagem atual da história"
                        style="object-fit: cover;"
                    >
                </div>

                <!-- IDIOMA E SUBSTITUIR IMAGEM -->
                <div class="linha-lingua-imagem">

                    <div class="lingua">
                        <select id="lingua" name="lingua">
                            <option value="pt">Português</option>
                            <option value="es">Espanhol</option>
                            <option value="wari">Wari' Oro Nao</option>
                        </select>
                    </div>

                    <button
                        type="button"
                        class="btn-substituir"
                        id="btnSubstituir"
                    >
                        Substituir imagem
                    </button>

                    <input
                        type="file"
                        id="imagem"
                        name="imagem"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        hidden
                    >

                </div>

                <!-- CAMPOS OCULTOS: PORTUGUÊS -->
                <input
                    type="hidden"
                    id="titulo_pt"
                    name="titulo_pt"
                    value="<?= htmlspecialchars(
                        $portugues->titulo ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <input
                    type="hidden"
                    id="legenda_pt"
                    name="legenda_pt"
                    value="<?= htmlspecialchars(
                        $portugues->legenda ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <input
                    type="hidden"
                    id="texto_pt"
                    name="texto_pt"
                    value="<?= htmlspecialchars(
                        $portugues->texto ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <!-- CAMPOS OCULTOS: ESPANHOL -->
                <input
                    type="hidden"
                    id="titulo_es"
                    name="titulo_es"
                    value="<?= htmlspecialchars(
                        $espanhol->titulo ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <input
                    type="hidden"
                    id="legenda_es"
                    name="legenda_es"
                    value="<?= htmlspecialchars(
                        $espanhol->legenda ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <input
                    type="hidden"
                    id="texto_es"
                    name="texto_es"
                    value="<?= htmlspecialchars(
                        $espanhol->texto ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <!-- CAMPOS OCULTOS: WARI' ORO NAO -->
                <input
                    type="hidden"
                    id="titulo_wari"
                    name="titulo_wari"
                    value="<?= htmlspecialchars(
                        $wari->titulo ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <input
                    type="hidden"
                    id="legenda_wari"
                    name="legenda_wari"
                    value="<?= htmlspecialchars(
                        $wari->legenda ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <input
                    type="hidden"
                    id="texto_wari"
                    name="texto_wari"
                    value="<?= htmlspecialchars(
                        $wari->texto ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <!-- CATEGORIA E TÍTULO LADO A LADO -->
                <div class="linha-campos">

                    <!-- CATEGORIA -->
                    <div class="campo">
                        <label for="categoria_exibida">
                            Categoria
                        </label>

                        <input
                            type="hidden"
                            name="categoria"
                            value="<?= (int) $historia->categoria_id ?>"
                        >

                        <input
                            type="text"
                            id="categoria_exibida"
                            value="<?= htmlspecialchars(
                                $historia->categoria,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            readonly
                        >
                    </div>

                    <!-- TÍTULO -->
                    <div class="campo">
                        <label id="labelTitulo" for="titulo">
                            Título (Português)
                        </label>

                        <input
                            type="text"
                            id="titulo"
                            placeholder="Título da história..."
                            required
                        >
                    </div>

                </div>

                <!-- LEGENDA SOZINHA -->
                <div class="campo campo-legenda">

                    <label id="labelLegenda" for="legenda">
                        Legenda da imagem (Português)
                    </label>

                    <input
                        type="text"
                        id="legenda"
                        placeholder="Legenda da imagem..."
                        required
                    >

                </div>

                <!-- TEXTO SOZINHO -->
                <div class="campo texto-campo">

                    <label id="labelTexto" for="texto">
                        Texto (Português)
                    </label>

                    <textarea
                        id="texto"
                        placeholder="Texto da história..."
                        required
                    ></textarea>

                </div>

                <!-- BOTÕES -->
                <div class="botoes">

                    <button
                        type="button"
                        class="btn-cancelar"
                        onclick="window.location.href='<?= URL ?>/administrador/gerenciar_historia_adm'"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn-salvar"
                    >
                        Salvar
                    </button>

                </div>

            </form>

        </section>

    </main>

    <!-- RODAPÉ -->
    <?php include APP . '/Views/paginas/footer.php'; ?>

    <!-- JAVASCRIPT DA EDIÇÃO -->
    <script src="<?= URL ?>/public/js/editar_historia.js"></script>

    <!-- CONFIGURAÇÕES GERAIS -->
    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>

</body>
</html>
