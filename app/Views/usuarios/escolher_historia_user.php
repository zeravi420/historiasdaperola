<?php
$subtitulo = "Visualize e leia novas histórias.";

$historias = $historias ?? [];
$busca = $busca ?? '';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Histórias - Histórias na Pérola</title>

    <link rel="stylesheet" href="<?=URL?>/public/css/escolher_historia_user.css">
</head>

<body>

<?php include APP . '/Views/usuarios/menu_user.php'; ?>

<main class="conteudo">

    <h1>Histórias</h1>

    <p class="subtitulo">
        <?php echo htmlspecialchars($subtitulo); ?>
    </p>

    <?php Sessao::exibirFlash(); ?>

    <div class="busca-container">

        <form action="<?=URL?>/usuarios/escolher_historia_user" method="GET" class="busca-box">

            
            <span class="icone-busca" aria-hidden="true">
                <img src="<?=URL?>/public/img/LUPA2.png" alt="Ícone de busca">
            </span>

            <input 
                type="text" 
                name="busca" 
                value="<?= Html::e($busca) ?>"
                aria-label="Buscar história pelo título"
                placeholder="Digite nome de uma lenda ou de um mito.."
            >

        </form>

    </div>


    
    <section class="grid-historias">

        <?php if (empty($historias)): ?>

            <p class="sem-historias">
                <?= $busca !== ''
                    ? 'Nenhuma história encontrada para "' . Html::e($busca) . '".'
                    : 'Nenhuma história cadastrada ainda.' ?>
            </p>

        <?php endif; ?>

        <?php foreach ($historias as $item): ?>

            <div class="card-item">

                
                <div class="card-imagem">

                    <?php if (!empty($item->imagem)): ?>

                        <img 
                            src="<?= Html::e($item->imagem) ?>" 
                            alt="<?= Html::e($item->titulo) ?>"
                        >

                    <?php endif; ?>

                </div>


                
                <div class="card-info">

                    <div class="card-texto">

                        <div class="card-titulo">
                            <?= Html::e($item->titulo) ?>
                        </div>

                        <div class="card-categoria">
                            <?= Html::e($item->categoria) ?>
                        </div>

                    </div>


                    
                    <a href="<?=URL?>/usuarios/historias_user?id=<?= (int) $item->id ?>" class="btn-ler">
                        Ler história
                    </a>

                </div>

            </div>

        <?php endforeach; ?>

    </section>

</main>

<?php include APP . '/Views/usuarios/footer_user.php'; ?>

</body>
</html>