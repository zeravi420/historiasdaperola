<?php
// Dados enviados por Usuarios::historias_user()
$titulo_lenda = $titulo_lenda ?? '';
$legenda_imagem = $legenda_imagem ?? '';
$texto_lenda = $texto_lenda ?? '';
$comentarios = $comentarios ?? [];
$idioma_atual = $idioma_atual ?? 'pt';
$idiomas_disponiveis = $idiomas_disponiveis ?? ['pt'];
$minha_nota = (int) ($minha_nota ?? 0);
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
        href="<?= URL ?>/public/css/historias_user.css"
    >

    <style>
        .secao-avaliacao { margin: 28px 0 8px; padding-top: 20px; border-top: 1px solid #e3e4dd; }
        .secao-avaliacao .titulo-comentario { display: block; margin-bottom: 6px; }
        .resumo-avaliacao { margin: 0 0 10px; color: #62665d; font-size: 14px; }
        .estrelas { position: relative; display: inline-flex; flex-direction: row-reverse; gap: 4px; border: 0; padding: 0; margin: 0 12px 0 0; vertical-align: middle; }
        .estrelas input { position: absolute; opacity: 0; width: 1px; height: 1px; }
        .estrelas label { font-size: 32px; line-height: 1; color: #cfd1c8; cursor: pointer; }
        .estrelas input:checked ~ label,
        .estrelas label:hover,
        .estrelas label:hover ~ label { color: #c99829; }
        .estrelas input:focus-visible + label { outline: 3px solid #2467c7; outline-offset: 2px; }
    </style>
</head>

<body>

<?php require APP . '/Views/usuarios/menu_user.php'; ?>

<main class="conteudo">

    <h1>História</h1>

    <p class="subtitulo">
        Mito ou lenda escolhida.
    </p>

    <?php Sessao::exibirFlash(); ?>

    <section class="modal-historia">

        <a
            href="<?= URL ?>/usuarios/escolher_historia_user"
            class="btn-fechar"
            title="Voltar"
            aria-label="Voltar para as histórias"
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
                        href="<?= URL ?>/usuarios/historias_user?id=<?= $historiaId ?>&amp;lang=<?= $codigo ?>"
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

        <form
            action="<?= URL ?>/usuarios/avaliar"
            method="POST"
            class="secao-avaliacao"
            id="avaliacao"
        >
            <input type="hidden" name="historia" value="<?= $historiaId ?>">

            <span class="titulo-comentario">Avalie esta história</span>

            <p class="resumo-avaliacao">
                <?php if ($totalAvaliacoes > 0): ?>
                    Média: <?= number_format($mediaAvaliacoes, 1, ',', '') ?> de 5
                    (<?= $totalAvaliacoes ?> <?= $totalAvaliacoes === 1 ? 'avaliação' : 'avaliações' ?>)
                <?php else: ?>
                    Ainda não há avaliações. Seja o primeiro a avaliar!
                <?php endif; ?>
            </p>

            <fieldset class="estrelas" aria-label="Sua nota">
                <?php for ($n = 5; $n >= 1; $n--): ?>
                    <input
                        type="radio"
                        id="nota-<?= $n ?>"
                        name="nota"
                        value="<?= $n ?>"
                        <?= $minha_nota === $n ? 'checked' : '' ?>
                        required
                    >
                    <label for="nota-<?= $n ?>" title="<?= $n ?> de 5">★</label>
                <?php endfor; ?>
            </fieldset>

            <button type="submit" class="btn-enviar">
                <?= $minha_nota > 0 ? 'Alterar avaliação' : 'Avaliar' ?>
            </button>
        </form>

        <form
            action="<?= URL ?>/usuarios/comentar"
            method="POST"
            class="secao-comentario"
        >
            <input type="hidden" name="historia" value="<?= $historiaId ?>">

            <label
                for="comentario"
                class="titulo-comentario"
            >
                O que você achou desta história?
            </label>

            <textarea
                id="comentario"
                name="comentario"
                class="area-texto-comentario"
                maxlength="1000"
                placeholder="Digite aqui seu comentário..."
                required
            ></textarea>

            <button
                type="submit"
                class="btn-enviar"
            >
                Enviar
            </button>

            <p class="aviso-comentario">
                Máximo de 1000 caracteres. Seu nome aparecerá junto ao comentário.
            </p>

        </form>

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
                    Ainda não há comentários.
                    Seja o primeiro a comentar!
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

                            <?php if ((int) $c->pess_id === Sessao::id()): ?>
                            <form
                                action="<?= URL ?>/usuarios/excluir_comentario"
                                method="POST"
                                class="form-excluir-comentario"
                                onsubmit="return confirmarExclusaoComentario(this)"
                            >

                                <input type="hidden" name="historia" value="<?= $historiaId ?>">

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
                            <?php endif; ?>

                        </div>

                    </article>

                <?php endforeach; ?>

            <?php endif; ?>

        </section>

    </section>

</main>

<?php include APP . '/Views/paginas/footer.php'; ?>

<script
    src="<?= URL ?>/public/js/historias_comentarios.js"
    defer
></script>

    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>
</body>
</html>
