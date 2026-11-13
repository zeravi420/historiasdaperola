
(function () {
    "use strict";

    const CHAVE = "acessibilidade";

    const padrao = {
        tema: "claro",
        fonte: "normal",
        contraste: false
    };

    function carregar() {
        try {
            const dados = JSON.parse(localStorage.getItem(CHAVE));

            if (!dados || typeof dados !== "object") {
                return { ...padrao };
            }

            return {
                tema: ["claro", "escuro"].includes(dados.tema)
                    ? dados.tema : padrao.tema,

                fonte: ["normal", "grande", "muito-grande"].includes(dados.fonte)
                    ? dados.fonte : padrao.fonte,

                contraste: dados.contraste === true
            };
        } catch (erro) {
            return { ...padrao };
        }
    }

    let config = carregar();

    function salvar() {
        try {
            localStorage.setItem(CHAVE, JSON.stringify(config));
        } catch (erro) {
            console.error("Não foi possível salvar as configurações.", erro);
        }
    }

    function aplicar() {
        const html = document.documentElement;

        html.classList.toggle(
            "acessibilidade-escuro",
            config.tema === "escuro"
        );

        html.classList.toggle(
            "acessibilidade-alto-contraste",
            config.contraste
        );

        html.classList.remove(
            "acessibilidade-fonte-normal",
            "acessibilidade-fonte-grande",
            "acessibilidade-fonte-muito-grande"
        );

        html.classList.add("acessibilidade-fonte-" + config.fonte);

        document.querySelectorAll("[data-acessibilidade-tema]").forEach(function (botao) {
            botao.setAttribute(
                "aria-pressed",
                String(botao.dataset.acessibilidadeTema === config.tema)
            );
        });

        document.querySelectorAll("[data-acessibilidade-fonte]").forEach(function (botao) {
            botao.setAttribute(
                "aria-pressed",
                String(botao.dataset.acessibilidadeFonte === config.fonte)
            );
        });

        document.querySelectorAll("[data-acessibilidade-contraste]").forEach(function (botao) {
            botao.setAttribute("aria-pressed", String(config.contraste));
        });
    }

    function definirTema(tema) {
        if (!["claro", "escuro"].includes(tema)) return;

        config.tema = tema;
        salvar();
        aplicar();
    }

    function definirFonte(fonte) {
        if (!["normal", "grande", "muito-grande"].includes(fonte)) return;

        config.fonte = fonte;
        salvar();
        aplicar();
    }

    function alternarContraste() {
        config.contraste = !config.contraste;
        salvar();
        aplicar();
    }

    function restaurar() {
        config = { ...padrao };
        salvar();
        aplicar();
    }

    document.addEventListener("click", function (evento) {
        const alvo = evento.target;

        if (!(alvo instanceof Element)) return;

        const tema = alvo.closest("[data-acessibilidade-tema]");
        const fonte = alvo.closest("[data-acessibilidade-fonte]");
        const contraste = alvo.closest("[data-acessibilidade-contraste]");
        const restaurarBotao = alvo.closest("[data-acessibilidade-restaurar]");

        if (tema) {
            definirTema(tema.dataset.acessibilidadeTema);
            return;
        }

        if (fonte) {
            definirFonte(fonte.dataset.acessibilidadeFonte);
            return;
        }

        if (contraste) {
            alternarContraste();
            return;
        }

        if (restaurarBotao) {
            restaurar();
            return;
        }

        // Compatibilidade com botões que utilizam IDs.
        const id = alvo.closest("button, a, input")?.id;

        const botoes = {
            "tema-claro": () => definirTema("claro"),
            "tema-escuro": () => definirTema("escuro"),
            "fonte-normal": () => definirFonte("normal"),
            "fonte-grande": () => definirFonte("grande"),
            "fonte-muito-grande": () => definirFonte("muito-grande"),
            "alto-contraste": alternarContraste,
            "restaurar-acessibilidade": restaurar
        };

        if (id && botoes[id]) {
            evento.preventDefault();
            botoes[id]();
        }
    });

    window.Acessibilidade = {
        definirTema: definirTema,
        definirFonte: definirFonte,
        alternarContraste: alternarContraste,
        restaurarConfiguracoes: restaurar,
        aplicarConfiguracoes: aplicar
    };

    aplicar();
})();
