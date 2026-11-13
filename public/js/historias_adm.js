/* Interações simples da tela de histórias do administrador. */

(() => {
    'use strict';

    const botoesIdioma = document.querySelectorAll(
        '.botoes-idiomas .btn-idioma'
    );

    botoesIdioma.forEach((botao) => {
        botao.setAttribute(
            'aria-pressed',
            botao.classList.contains('ativo') ? 'true' : 'false'
        );
    });
})();
