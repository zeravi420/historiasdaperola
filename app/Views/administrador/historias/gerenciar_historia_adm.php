
<?php
$subtitulo = "Adicione novas histórias.";

$historias = $historias ?? [];
$mensagem = $mensagem ?? '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Histórias - Histórias na Pérola</title>
        <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
<link rel="stylesheet" href="<?= URL ?>/public/css/gerenciar_historia_adm.css">
</head>
<body>

<?php include APP . '/Views/administrador/menu_adm.php'; ?>

<main class="conteudo">

    <h1>Gerenciar Histórias</h1>

    <p class="subtitulo">
        <?= htmlspecialchars($subtitulo, ENT_QUOTES, 'UTF-8'); ?>
    </p>

    <?php Sessao::exibirFlash(); ?>

    <?php if ($mensagem !== ''): ?>
        <div class="mensagem-sucesso" role="status">
            <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <div class="ferramentas-container">

        <div class="busca-box">
            <span class="icone-busca" aria-hidden="true">
                <img src="<?= URL ?>/public/img/LUPA2.png" alt="">
            </span>

            <input
                type="text"
                id="buscaHistoria"
                placeholder="Buscar História..."
                aria-label="Buscar história pelo título"
            >
        </div>

        <select
            class="select-categoria"
            id="filtroCategoria"
            aria-label="Filtrar por categoria"
        >
            <option value="">Categoria</option>
            <option value="mito">Mito</option>
            <option value="lenda">Lenda</option>
        </select>

        <a
            href="<?= URL ?>/administrador/nova_historia"
            class="btn-nova-historia"
        >
            + Nova História
        </a>

    </div>

    <section class="grid-historias" id="gridHistorias">

        <?php if (empty($historias)): ?>

            <p class="sem-historias" id="semHistorias">
                Nenhuma história cadastrada ainda.
            </p>

        <?php else: ?>

            <?php foreach ($historias as $index => $item): ?>

                <?php
                $id = (int) $item->id;
                $titulo = $item->titulo ?? '';
                $categoria = $item->categoria ?? '';
                $imagem = $item->imagem ?? '';
                ?>

                <div
                    class="card-item"
                    data-titulo="<?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?>"
                    data-categoria="<?= htmlspecialchars(mb_strtolower($categoria, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>"
                >

                    <div class="card-imagem-wrapper">

                        <?php if ($imagem !== ''): ?>
                            <img
                                src="<?= htmlspecialchars($imagem, ENT_QUOTES, 'UTF-8'); ?>"
                                alt="<?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?>"
                            >
                        <?php endif; ?>

                        <button
                            class="btn-opcoes"
                            type="button"
                            onclick="toggleMenu(event, <?= $index; ?>)"
                            aria-label="Opções da história"
                            aria-expanded="false"
                        >
                            ⋮
                        </button>

                        <div class="menu-opcoes" id="menu-<?= $index; ?>">

                            <a href="<?= URL ?>/administrador/editar_historia_adm?id=<?= $id; ?>">
                                Editar História
                            </a>

                            <a
                                href="<?= URL ?>/administrador/excluir_historia_adm?id=<?= $id; ?>"
                                class="btn-apagar"
                            >
                                Excluir História
                            </a>

                        </div>

                    </div>

                    <div class="card-info">

                        <div class="card-textos">
                            <div
                                class="card-titulo"
                                data-titulo-pt="<?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?>"
                                data-titulo-es="<?= htmlspecialchars($item->titulo_es ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                data-titulo-wari="<?= htmlspecialchars($item->titulo_wari ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                <?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?>
                            </div>

                            <div class="card-categoria">
                                <?= htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        </div>

                        <a
                            href="<?= URL ?>/administrador/historias_adm?id=<?= $id; ?>"
                            class="btn-ler"
                        >
                            Ver história
                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

            <p class="sem-resultados" id="semResultados" hidden>
                Nenhuma história corresponde à sua busca.
            </p>

        <?php endif; ?>

    </section>

</main>

<?php include APP . '/Views/paginas/footer.php'; ?>

<script src="<?= URL ?>/public/js/gerenciar_historia_adm.js"></script>


    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>
</body>
</html>
