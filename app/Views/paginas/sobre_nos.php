<?php
// Exibe o menu correspondente ao perfil conectado.
if (Sessao::estaLogado() && Sessao::ehAdministrador()) {
    include APP . '/Views/administrador/menu_adm.php';
} elseif (Sessao::estaLogado()) {
    include APP . '/Views/usuarios/menu_user.php';
} else {
    include APP . '/Views/paginas/cabecalho_publico.php';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sobre Nós</title>

        <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
<link rel="stylesheet" href="<?=URL?>/public/css/sobre_nos.css">
</head>

<body>


<main class="conteudo">

    

    <section class="cabecalho-pagina">

        <h1>Sobre Nós</h1>

        <p>
            Conheça nossa equipe e a história por trás do projeto.
        </p>

    </section>



    

    <section class="motivo">

        <h2>Motivo para o desenvolvimento do sistema</h2>

        <p>
            O desenvolvimento deste site surgiu da necessidade de preservar,
            valorizar e divulgar o patrimônio cultural imaterial da região
            de Guajará-Mirim, em Rondônia (RO). A região é marcada por uma
            expressiva diversidade étnica e cultural, resultado da intensa
            interação social característica de uma região de fronteira.
        </p>

        <p>
            Nosso projeto busca contribuir para a valorização dessas histórias,
            memórias e manifestações culturais, tornando esse conhecimento
            mais acessível à comunidade. O acervo reúne narrativas disponíveis
            em diferentes línguas, incluindo português brasileiro, espanhol
            latino (Bolívia) e Wari' Oro Nao.
        </p>

    </section>



    

    <section class="secao-equipe">

        <div class="titulo-equipe">

            <h2>Nossa equipe</h2>

            <p>
                Conheça as pessoas que participaram do desenvolvimento deste projeto.
            </p>

        </div>


        <div class="equipe">


            

            <div class="integrante">

                <div class="foto-integrante">

                    <img
                        src="<?=URL?>/public/img/darli.png"
                        alt="Darliane Arza de Oliveira é uma menina de 18 anos, tem a pele morena clara, cabelo preto e ondulado, repartido ao meio. Seus olhos também são escuros, assim como suas sobrancelhas, que são finas e retas.">

                </div>

                <div class="informacoes-integrante">

                    <h3>
                        Darliane Arza
                        <br>
                        de Oliveira
                    </h3>

                    <p class="funcao">
                        Desing e Desenvolvedora Front-end, Banco de dados e Back-end
                    </p>

                </div>

            </div>



            

            <div class="integrante">

                <div class="foto-integrante">

                    <img
                        src="<?=URL?>/public/img/jose.png"
                        alt="Jose Javiar Paz é um menino de 17 anos, pele negra, cabelo castanho escuro e liso, cortado baixo. Suas sobrancelhas são escuras, semiarredondadas e grossas, já seus olhos são de um castanho bem profundo.">

                </div>

                <div class="informacoes-integrante">

                    <h3>
                        Jose Javiar
                        <br>
                        Paz Vaca
                    </h3>

                    <p class="funcao">
                        Desenvolvedor Front-end e Back-end
                    </p>

                </div>

            </div>


            

            <div class="integrante">

                <div class="foto-integrante">

                    <img
                        src="<?=URL?>/public/img/kal.png"
                        alt="Kalondra Avaroma Vieira é uma menina de 17 anos, tem a pele branca, o cabelo castanho com mechas loiras, levemente ondulado e repartido ao meio com uma franja lateral. Suas sobrancelhas são castanhas e arredondadas e seus olhos são castanhos.">
                </div>

                <div class="informacoes-integrante">

                    <h3>
                        Kalondra
                        <br>
                        Avaroma Vieira
                    </h3>

                    <p class="funcao">
                        Documentação e Desenvolvedora Front-end
                    </p>

                </div>

            </div>



            

            <div class="integrante">

                <div class="foto-integrante">

                    <img
                        src="<?=URL?>/public/img/lais.png"
                        alt="Laís Carvalho de Lima é uma menina de 17 anos, de pele branca, cabelo longo e cachos escuros, preso em um penteado meio solto. Suas sobrancelhas são retas com a ponta curvada e seus olhos alongados.">

                </div>

                <div class="informacoes-integrante">

                    <h3>
                        Laís Carvalho
                        <br>
                        de Lima
                    </h3>

                    <p class="funcao">
                        Desenvolvedora Front-end e Acessibilidade
                    </p>

                </div>

            </div>



            

            <div class="integrante">

                <div class="foto-integrante">

                    <img
                        src="<?=URL?>/public/img/levi.png"
                        alt="Levi dos Santos Oliveira é um menino de 17 anos de pele clara, cabelo cacheado escuro e volumoso. Seus olhos são castanho escuro e usa óculos retangulares. Suas sobrancelhas são finas e escuras.">

                </div>

                <div class="informacoes-integrante">

                    <h3>
                        Levi dos Santos
                        <br>
                        de Oliveira
                    </h3>

                    <p class="funcao">
                        Desenvolvedor Front-end e Back-end
                    </p>

                </div>

            </div>



            

            <div class="integrante">

                <div class="foto-integrante">

                    <img
                        src="<?=URL?>/public/img/quelly.png"
                        alt="Raquelly Tuye Hortiz é uma menina de 17 anos, é parda, tem o cabelo preto com mechas frontais vermelhas e olhos castanhos cor de mel. Suas sobrancelhas são retas e castanhas.">

                </div>

                <div class="informacoes-integrante">

                    <h3>
                        Raquelly Tuye
                        <br>
                        Hortiz
                    </h3>

                    <p class="funcao">
                        Desenvolvedora Front-end e Acessibilidade
                    </p>

                </div>

            </div>


        </div>

    </section>

</main>


<?php include APP . '/Views/paginas/footer.php'; ?>


    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>
</body>
</html>