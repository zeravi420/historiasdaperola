
document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('formEditarHistoria');
    const seletorIdioma = document.getElementById('lingua');

    const campoTitulo = document.getElementById('titulo');
    const campoLegenda = document.getElementById('legenda');
    const campoTexto = document.getElementById('texto');

    const labelTitulo = document.getElementById('labelTitulo');
    const labelLegenda = document.getElementById('labelLegenda');
    const labelTexto = document.getElementById('labelTexto');

    const btnSubstituir = document.getElementById('btnSubstituir');
    const campoImagem = document.getElementById('imagem');
    const imagemPreview = document.getElementById('imagemPreview');

    const idiomas = {
        pt: {
            nome: 'Português',
            titulo: 'Título da história...',
            legenda: 'Legenda da imagem...',
            texto: 'Texto da história...'
        },
        es: {
            nome: 'Espanhol',
            titulo: 'Título de la historia...',
            legenda: 'Leyenda de la imagen...',
            texto: 'Texto de la historia...'
        },
        wari: {
            nome: "Wari' Oro Nao",
            titulo: "Título em Wari' Oro Nao...",
            legenda: "Legenda da imagem em Wari' Oro Nao...",
            texto: "Texto da história em Wari' Oro Nao..."
        }
    };

    const codigos = ['pt', 'es', 'wari'];
    const dados = {};
    let idiomaAtual = null;

    codigos.forEach(function (codigo) {
        dados[codigo] = {
            titulo: document.getElementById('titulo_' + codigo).value,
            legenda: document.getElementById('legenda_' + codigo).value,
            texto: document.getElementById('texto_' + codigo).value
        };
    });

    function guardarCampos() {
        if (idiomaAtual === null) {
            return;
        }

        dados[idiomaAtual].titulo = campoTitulo.value;
        dados[idiomaAtual].legenda = campoLegenda.value;
        dados[idiomaAtual].texto = campoTexto.value;
    }

    function trocarIdioma(novoIdioma) {
        if (!idiomas[novoIdioma]) {
            return;
        }

        guardarCampos();
        idiomaAtual = novoIdioma;

        campoTitulo.value = dados[novoIdioma].titulo;
        campoLegenda.value = dados[novoIdioma].legenda;
        campoTexto.value = dados[novoIdioma].texto;

        labelTitulo.textContent = 'Título (' + idiomas[novoIdioma].nome + ')';
        labelLegenda.textContent = 'Legenda da imagem (' + idiomas[novoIdioma].nome + ')';
        labelTexto.textContent = 'Texto (' + idiomas[novoIdioma].nome + ')';

        campoTitulo.placeholder = idiomas[novoIdioma].titulo;
        campoLegenda.placeholder = idiomas[novoIdioma].legenda;
        campoTexto.placeholder = idiomas[novoIdioma].texto;

        campoTitulo.required = novoIdioma === 'pt';
        campoLegenda.required = novoIdioma === 'pt';
        campoTexto.required = novoIdioma === 'pt';

        seletorIdioma.value = novoIdioma;
    }

    seletorIdioma.addEventListener('change', function () {
        trocarIdioma(seletorIdioma.value);
    });

    btnSubstituir.addEventListener('click', function () {
        campoImagem.click();
    });

    campoImagem.addEventListener('change', function () {
        const arquivo = campoImagem.files[0];

        if (arquivo) {
            imagemPreview.src = URL.createObjectURL(arquivo);
        }
    });

    formulario.addEventListener('submit', function (evento) {
        guardarCampos();

        for (const codigo of codigos) {
            const campos = dados[codigo];

            const algumPreenchido =
                campos.titulo.trim() !== '' ||
                campos.legenda.trim() !== '' ||
                campos.texto.trim() !== '';

            const todosPreenchidos =
                campos.titulo.trim() !== '' &&
                campos.legenda.trim() !== '' &&
                campos.texto.trim() !== '';

            if (codigo === 'pt' && !todosPreenchidos) {
                evento.preventDefault();
                alert('Preencha o título, a legenda e o texto em português.');
                trocarIdioma('pt');
                return;
            }

            if (codigo !== 'pt' && algumPreenchido && !todosPreenchidos) {
                evento.preventDefault();
                alert('Preencha todos os campos de ' + idiomas[codigo].nome + ' ou deixe os três vazios.');
                trocarIdioma(codigo);
                return;
            }
        }

        codigos.forEach(function (codigo) {
            document.getElementById('titulo_' + codigo).value = dados[codigo].titulo.trim();
            document.getElementById('legenda_' + codigo).value = dados[codigo].legenda.trim();
            document.getElementById('texto_' + codigo).value = dados[codigo].texto.trim();
        });
    });

    trocarIdioma('pt');
});
