<?php
// Dados enviados por Administrador::historias_adm()
$titulo_lenda = $titulo_lenda ?? '';
$legenda_imagem = $legenda_imagem ?? '';
$texto_lenda = $texto_lenda ?? '';
$comentarios = $comentarios ?? [];
$idioma_atual = $idioma_atual ?? 'pt';
$idiomas_disponiveis = $idiomas_disponiveis ?? ['pt'];
$totalAvaliacoes = (int) ($avaliacao->total ?? 0);
$mediaAvaliacoes = (float) ($avaliacao->media ?? 0);
$historiaId = (int) $historia->id;

$esc = static fn($valor) =>
    htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');

$botoesIdioma = [
    'pt' => ['nome' => 'Português', 'img' => 'brasil.png', 'classe' => 'img-espanha'],
    'es' => ['nome' => 'Espanhol', 'img' => 'espanha.png', 'classe' => 'img-espanha'],
    'wari' => ['nome' => "Wari' Oro Nao", 'img' => 'icone_logo_menu.png', 'classe' => 'img-wari'],
];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $esc($titulo_lenda) ?> - Histórias na Pérola
    </title>

        <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
<link
        rel="stylesheet"
        href="<?= URL ?>/public/css/historias_adm.css"
    >

    <style>
        .resumo-avaliacao { margin: 22px 0 0; color: #62665d; font-size: 14px; }
        .resumo-avaliacao strong { color: #c99829; }
        .acoes-comentario { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
        .btn-responder-comentario {
            padding: 6px 12px; border: 1px solid #465722; border-radius: 6px;
            background: #fff; color: #465722; font-size: 12px; font-weight: 700; text-decoration: none;
        }
        .btn-responder-comentario:hover { background: #f2f5ea; }
    </style>
</head>

<body>

<?php require APP . '/Views/administrador/menu_adm.php'; ?>

<main class="conteudo">

    <h1>História</h1>

    <p class="subtitulo">
        Mito ou lenda escolhida.
    </p>

    <?php Sessao::exibirFlash(); ?>

    <section class="modal-historia">

        <a
            href="<?= URL ?>/administrador/gerenciar_historia_adm"
            class="btn-fechar"
            title="Voltar"
            aria-label="Voltar ao gerenciamento de histórias"
        >
            <span class="icon-close" aria-hidden="true">×</span>
        </a>

        <h2 class="titulo-lenda">
            <?= $esc($titulo_lenda) ?>
        </h2>

        <div class="box-imagem">
            <?php if (!empty($historia->imagem_uri)): ?>
                <img src="<?= $esc($historia->imagem_uri) ?>" alt="<?= $esc($legenda_imagem) ?>">
            <?php else: ?>
                Imagem da história
            <?php endif; ?>
        </div>

        <p class="legenda">
            <?= $esc($legenda_imagem) ?>
        </p>

        <div class="texto-lenda">
            <?= nl2br($esc(strip_tags((string)$texto_lenda))) ?>
        </div>

        <p class="resumo-avaliacao">
            <?php if ($totalAvaliacoes > 0): ?>
                <strong>★</strong>
                Média <?= number_format($mediaAvaliacoes, 1, ',', '') ?> de 5
                (<?= $totalAvaliacoes ?> <?= $totalAvaliacoes === 1 ? 'avaliação' : 'avaliações' ?>)
            <?php else: ?>
                Esta história ainda não foi avaliada.
            <?php endif; ?>
        </p>

        <?php if (count($idiomas_disponiveis) > 1): ?>
        <div class="secao-idiomas">

            <div class="titulo-idiomas">

                <img
                    src="<?= URL ?>/public/img/icone_globo.png"
                    alt=""
                    aria-hidden="true"
                >

                <span>
                    Ler esta história em outras línguas
                </span>

            </div>

            <p class="subtitulo-idiomas">
                Escolha o idioma para ver a versão completa da história.
            </p>

            <div class="botoes-idiomas">

                <?php foreach ($botoesIdioma as $codigo => $botao): ?>
                    <?php if (!in_array($codigo, $idiomas_disponiveis, true)) continue; ?>
                    <a
                        href="<?= URL ?>/administrador/historias_adm?id=<?= $historiaId ?>&amp;lang=<?= $codigo ?>"
                        class="btn-idioma <?= $idioma_atual === $codigo ? 'ativo' : '' ?>"
                        <?= $idioma_atual === $codigo ? 'aria-current="true"' : '' ?>
                    >
                        <img
                            src="<?= URL ?>/public/img/<?= $botao['img'] ?>"
                            alt=""
                            class="<?= $botao['classe'] ?>"
                            aria-hidden="true"
                        >

                        <span><?= $esc($botao['nome']) ?></span>
                    </a>
                <?php endforeach; ?>

            </div>

        </div>
        <?php endif; ?>

        <section
            class="lista-comentarios"
            id="comentarios"
            aria-labelledby="titulo-lista-comentarios"
        >

            <h3
                class="titulo-lista-comentarios"
                id="titulo-lista-comentarios"
            >
                Comentários (<?= count($comentarios) ?>)
            </h3>

            <?php if (empty($comentarios)): ?>

                <p class="sem-comentarios">
                    Ainda não há comentários nesta história.
                </p>

            <?php else: ?>

                <?php foreach ($comentarios as $c): ?>

                    <article class="comentario-item">

                        <div
                            class="avatar-comentario"
                            aria-hidden="true"
                        >
                            <?= $esc(mb_strtoupper(mb_substr((string)$c->pess_nome, 0, 1, 'UTF-8'), 'UTF-8')) ?>
                        </div>

                        <div class="corpo-comentario">

                            <div class="cabecalho-comentario">

                                <span class="nome-comentario">
                                    <?= $esc($c->pess_nome) ?>
                                </span>

                                <time class="data-comentario" datetime="<?= $esc($c->come_data_pessoa) ?>">
                                    <?= $esc(Html::data($c->come_data_pessoa, 'd/m/Y H:i')) ?>
                                </time>

                            </div>

                            <p class="texto-comentario">
                                <?= nl2br($esc($c->come_texto)) ?>
                            </p>

                            <div class="acoes-comentario">

                                <?php if ((int) $c->pess_id !== Sessao::id()): ?>
                                    <a
                                        class="btn-responder-comentario"
                                        href="<?= URL ?>/administrador/responder_comentario?id=<?= (int) $c->come_id ?>"
                                    >
                                        Responder
                                    </a>
                                <?php endif; ?>

                                <form
                                    action="<?= URL ?>/administrador/excluir_comentario"
                                    method="POST"
                                    class="form-excluir-comentario"
                                    onsubmit="return confirmarExclusaoComentario(this)"
                                >

                                    <input
                                        type="hidden"
                                        name="excluir_comentario"
                                        value="<?= (int)$c->come_id ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-excluir-comentario"
                                    >
                                        Excluir comentário
                                    </button>

                                </form>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            <?php endif; ?>

        </section>

        <div class="acoes-historia">

            <a
                class="btn-secundario"
                href="<?= URL ?>/administrador/gerenciar_historia_adm"
            >
                Voltar ao gerenciamento
            </a>

            <a
                class="btn-primario"
                href="<?= URL ?>/administrador/editar_historia_adm?id=<?= $historiaId ?>"
            >
                Editar história
            </a>

        </div>

    </section>

</main>

<?php include APP . '/Views/paginas/footer.php'; ?>

<script
    src="<?= URL ?>/public/js/historias_adm.js"
    defer
></script>

<script
    src="<?= URL ?>/public/js/historias_comentarios.js"
    defer
></script>

    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>
</body>
</html>
