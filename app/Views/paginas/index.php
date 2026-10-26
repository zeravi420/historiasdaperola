<?php
$subtitulo = "Explore mitos e lendas e conheça as histórias da região.";
$historias = $historias ?? [];
$busca = $busca ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Histórias - Histórias na Pérola</title>
    <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
    <link rel="stylesheet" href="<?= URL ?>/public/css/escolher_historia_user.css">
    <style>
        .cabecalho-publico { position: relative; }
        .cabecalho-publico .logo { margin-left: 24px; }
        .sem-historias { text-align:center; padding:24px; }
    </style>
</head>
<body>
<?php include APP . '/Views/paginas/cabecalho_publico.php'; ?>
<main class="conteudo">
    <h1>Histórias</h1>
    <p class="subtitulo"><?= Html::e($subtitulo) ?></p>
    <div class="busca-container">
        <form action="<?= URL ?>/paginas/index" method="GET" class="busca-box">
            <span class="icone-busca" aria-hidden="true"><img src="<?= URL ?>/public/img/LUPA2.png" alt=""></span>
            <input type="text" name="busca" value="<?= Html::e($busca) ?>" aria-label="Buscar história pelo título" placeholder="Digite o nome de uma lenda ou de um mito...">
        </form>
    </div>
    <section class="grid-historias">
        <?php if (empty($historias)): ?>
            <p class="sem-historias"><?= $busca !== '' ? 'Nenhuma história encontrada para “' . Html::e($busca) . '”.' : 'Nenhuma história cadastrada ainda.' ?></p>
        <?php endif; ?>
        <?php foreach ($historias as $item): ?>
            <article class="card-item">
                <div class="card-imagem">
                    <?php if (!empty($item->imagem)): ?><img src="<?= Html::e($item->imagem) ?>" alt="<?= Html::e($item->titulo) ?>"><?php endif; ?>
                </div>
                <div class="card-info">
                    <div class="card-texto">
                        <div class="card-titulo"><?= Html::e($item->titulo) ?></div>
                        <div class="card-categoria"><?= Html::e($item->categoria) ?></div>
                    </div>
                    <a href="<?= URL ?>/paginas/login?historia=<?= (int) $item->id ?>" class="btn-ler">Ler história</a>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</main>
<script src="<?= URL ?>/public/js/Configuracoes.js"></script>
</body>
</html>
