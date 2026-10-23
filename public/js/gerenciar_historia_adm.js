function toggleMenu(event, index) {
    event.stopPropagation();

    const menuAtual = document.getElementById('menu-' + index);
    const estavaAberto = menuAtual.classList.contains('ativo');

    document.querySelectorAll('.menu-opcoes').forEach(function(menu) {
        menu.classList.remove('ativo');
    });

    document.querySelectorAll('.btn-opcoes').forEach(function(botao) {
        botao.setAttribute('aria-expanded', 'false');
    });

    if (!estavaAberto) {
        menuAtual.classList.add('ativo');
        event.currentTarget.setAttribute('aria-expanded', 'true');
    }
}

document.addEventListener('click', function() {
    document.querySelectorAll('.menu-opcoes').forEach(function(menu) {
        menu.classList.remove('ativo');
    });

    document.querySelectorAll('.btn-opcoes').forEach(function(botao) {
        botao.setAttribute('aria-expanded', 'false');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const busca = document.getElementById('buscaHistoria');
    const filtro = document.getElementById('filtroCategoria');
    const cartoes = document.querySelectorAll('.card-item');
    const semResultados = document.getElementById('semResultados');

    function normalizar(texto) {
        return (texto || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLocaleLowerCase('pt-BR')
            .trim();
    }

    function filtrarHistorias() {
        const textoBusca = normalizar(busca.value);
        const categoriaSelecionada = filtro.value;
        let quantidadeVisivel = 0;

        cartoes.forEach(function(cartao) {
            const tituloCard = cartao.querySelector('.card-titulo');

            const tituloPt = tituloCard.dataset.tituloPt || '';
            const tituloEs = tituloCard.dataset.tituloEs || '';
            const tituloWari = tituloCard.dataset.tituloWari || '';

            const titulos = [
                { texto: tituloPt, idioma: 'pt' },
                { texto: tituloEs, idioma: 'es' },
                { texto: tituloWari, idioma: 'wari' }
            ];

            let idiomaEncontrado = 'pt';

            if (textoBusca !== '') {
                const correspondencia = titulos.find(function(item) {
                    return normalizar(item.texto).includes(textoBusca);
                });

                const tituloCorrespondente = correspondencia ||
                    titulos.find(function(item) {
                        return normalizar(item.texto).includes(textoBusca);
                    });

                if (tituloCorrespondente) {
                    idiomaEncontrado = tituloCorrespondente.idioma;
                }
            }

            const tituloParaMostrar = {
                pt: tituloPt,
                es: tituloEs,
                wari: tituloWari
            };

            const tituloExibido = tituloParaMostrar[idiomaEncontrado];

            const correspondeTitulo = textoBusca === '' ||
                titulos.some(function(item) {
                    return normalizar(item.texto).includes(textoBusca);
                });

            const categoria = normalizar(cartao.dataset.categoria);
            const correspondeCategoria =
                categoriaSelecionada === '' ||
                categoria === normalizar(categoriaSelecionada);

            const mostrar = correspondeTitulo && correspondeCategoria;

            cartao.hidden = !mostrar;

            if (mostrar) {
                tituloCard.textContent = tituloExibido || tituloPt;
                quantidadeVisivel++;
            }
        });

        if (semResultados) {
            semResultados.hidden = quantidadeVisivel > 0;
        }
    }

    busca.addEventListener('input', filtrarHistorias);
    filtro.addEventListener('change', filtrarHistorias);
});