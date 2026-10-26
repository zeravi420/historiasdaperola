<?php
$nome = Sessao::nome() !== '' ? Sessao::nome() : 'Nome';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Início - Histórias na Pérola</title>

        <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
<link rel="stylesheet" href="<?=URL?>/public/css/inicio_user.css">
</head>
<body>

<?php include APP . '/Views/usuarios/menu_user.php'; ?>

<main class="conteudo">

    
    <header class="cabecalho-inicio">
        <div class="titulo"><h1>Início</h1></div>
        <p class="boas-vindas">Bem-vindo, <?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?>!</p>
    </header>

    <?php Sessao::exibirFlash(); ?>

    
    <h2>
        Explore, conheça e valorize histórias em diferentes línguas!
    </h2>


    
    <div class="cards-inicio">

        
        <section class="card card-mito">

            <h3>
                Existe diferença entre lenda e mito?
            </h3>

            <div class="texto-card">

                <p>
                    Sim! Apesar de serem histórias tradicionais transmitidas de geração
                    em geração, mitos e lendas possuem algumas diferenças. Os mitos
                    geralmente estão ligados a deuses, seres sobrenaturais e explicações
                    sobre a origem do mundo, da natureza ou de determinados acontecimentos.
                    Já as lendas costumam misturar fatos reais com elementos fantásticos e
                    estão relacionadas à cultura, aos costumes e às histórias de um povo.
                </p>

                <p>
                    Ambos fazem parte da cultura e da tradição de diferentes comunidades,
                    ajudando a preservar histórias, conhecimentos e valores ao longo do tempo.
                </p>

            </div>

        </section>


        
        <section class="card card-explorar">

            <h3>
                Que tal conhecer uma história?
            </h3>

            <p>
                Descubra mitos e lendas de diferentes culturas e conheça
                histórias que atravessaram gerações.
            </p>

            <a href="<?=URL?>/usuarios/escolher_historia_user" class="btn-historias">
                Explorar histórias
            </a>

        </section>

    </div>

</main>


<?php include APP . '/Views/paginas/footer.php'; ?>

    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>
</body>
</html>