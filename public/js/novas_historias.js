document.addEventListener('DOMContentLoaded', function () {
    const botoesIdioma = document.querySelectorAll('.idiomas button');
    const formulario = document.getElementById('formHistoria');

    const campoTitulo = document.getElementById('titulo');
    const campoLegenda = document.getElementById('legenda');
    const campoTexto = document.getElementById('texto');

    const labelTitulo = document.getElementById('labelTitulo');
    const labelLegenda = document.getElementById('labelLegenda');
    const labelTexto = document.getElementById('labelTexto');

    const dados = {
        pt: { titulo: '', legenda: '', texto: '' },
        es: { titulo: '', legenda: '', texto: '' },
        wari: { titulo: '', legenda: '', texto: '' }
    };

    const idiomas = {
        pt: {
            nome: 'Português',
            titulo: 'Digite o título em português...',
            legenda: 'Digite a legenda da imagem em português...',
            texto: 'Digite o texto em português...'
        },
        es: {
            nome: 'Espanhol',
            titulo: 'Escribe el título en español...',
            legenda: 'Escribe la leyenda de la imagen en español...',
            texto: 'Escribe la historia en español...'
        },
        wari: {
            nome: "Wari' Oro Nao",
            titulo: "Digite o título em Wari' Oro Nao...",
            legenda: "Digite a legenda da imagem em Wari' Oro Nao...",
            texto: "Digite a história em Wari' Oro Nao..."
        }
    };

    let idiomaAtual = 'pt';

    function guardarCampos() {
        dados[idiomaAtual].titulo = campoTitulo.value;
        dados[idiomaAtual].legenda = campoLegenda.value;
        dados[idiomaAtual].texto = campoTexto.value;
    }

    function trocarIdioma(novoIdioma) {
        if (!dados[novoIdioma]) {
            return;
        }

        guardarCampos();
        idiomaAtual = novoIdioma;

        campoTitulo.value = dados[novoIdioma].titulo;
        campoLegenda.value = dados[novoIdioma].legenda;
        campoTexto.value = dados[novoIdioma].texto;

        labelTitulo.textContent =
            'Título (' + idiomas[novoIdioma].nome + ')';

        labelLegenda.textContent =
            'Legenda da imagem (' + idiomas[novoIdioma].nome + ')';

        labelTexto.textContent =
            'Texto (' + idiomas[novoIdioma].nome + ')';

        campoTitulo.placeholder = idiomas[novoIdioma].titulo;
        campoLegenda.placeholder = idiomas[novoIdioma].legenda;
        campoTexto.placeholder = idiomas[novoIdioma].texto;

        campoTitulo.required = novoIdioma === 'pt';
        campoLegenda.required = novoIdioma === 'pt';
        campoTexto.required = novoIdioma === 'pt';

        botoesIdioma.forEach(function (botao) {
            botao.classList.toggle(
                'ativo',
                botao.dataset.idioma === novoIdioma
            );
        });
    }

    botoesIdioma.forEach(function (botao) {
        botao.addEventListener('click', function () {
            trocarIdioma(botao.dataset.idioma);
        });
    });

    formulario.addEventListener('submit', function (evento) {
        guardarCampos();

        const portugues = dados.pt;

        if (
            portugues.titulo.trim() === '' ||
            portugues.legenda.trim() === '' ||
            portugues.texto.trim() === ''
        ) {
            evento.preventDefault();

            alert(
                'Preencha o título, a legenda e o texto em português antes de salvar.'
            );

            trocarIdioma('pt');
            return;
        }

        for (const codigo of ['es', 'wari']) {
            const campos = dados[codigo];

            const algumPreenchido =
                campos.titulo.trim() !== '' ||
                campos.legenda.trim() !== '' ||
                campos.texto.trim() !== '';

            const todosPreenchidos =
                campos.titulo.trim() !== '' &&
                campos.legenda.trim() !== '' &&
                campos.texto.trim() !== '';

            if (algumPreenchido && !todosPreenchidos) {
                evento.preventDefault();

                alert(
                    'Se quiser cadastrar a versão em ' +
                    idiomas[codigo].nome +
                    ', preencha o título, a legenda e o texto. ' +
                    'Se ainda não tiver essa versão, deixe os três campos vazios.'
                );

                trocarIdioma(codigo);
                return;
            }
        }

        for (const codigo of ['pt', 'es', 'wari']) {
            document.getElementById('titulo_' + codigo).value =
                dados[codigo].titulo.trim();

            document.getElementById('legenda_' + codigo).value =
                dados[codigo].legenda.trim();

            document.getElementById('texto_' + codigo).value =
                dados[codigo].texto.trim();
        }
    });

    trocarIdioma('pt');
});