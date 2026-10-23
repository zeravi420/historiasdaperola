(function () {
    "use strict";
    /* CHAVE DO LOCALSTORAGE */
    const STORAGE_KEY = "acessibilidade";
    /* CONFIGURAÇÕES PADRÃO */
    const padrao = {
        tema: "claro",
        fonte: "normal",
        contraste: false
    };
    /* CARREGAR CONFIGURAÇÕES */
    function carregarConfiguracoes() {
        try {
            const salvo = localStorage.getItem(STORAGE_KEY);
            if (salvo) {
                return {
                    ...padrao,
                    ...JSON.parse(salvo)
                };
            }
        }
        catch (erro) {
            console.warn("Não foi possível carregar as configurações.", erro);
        }
        return {
            ...padrao
        };
    }

    let configuracoes = carregarConfiguracoes();
    /* SALVAR CONFIGURAÇÕES */
    function salvarConfiguracoes() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(configuracoes));
        }
        catch (erro) {
            console.warn("Não foi possível salvar as configurações.", erro);
        }
    }

    /* APLICAR CONFIGURAÇÕES */
    function aplicarConfiguracoes() {
        const html = document.documentElement;
        /* TEMA */
        html.classList.toggle("acessibilidade-escuro", configuracoes.tema === "escuro");
        /* TAMANHO DA FONTE */
        html.classList.remove("acessibilidade-fonte-normal", "acessibilidade-fonte-grande", "acessibilidade-fonte-muito-grande");
        html.classList.add(`acessibilidade-fonte-${configuracoes.fonte}`);
        /* ALTO CONTRASTE */
        html.classList.toggle("acessibilidade-alto-contraste", configuracoes.contraste === true);
        atualizarInterface();
        atualizarStatus();
    }

    /* ATUALIZAR INTERFACE */
    function atualizarInterface() {
        /* BOTÕES DE TEMA */
        document.querySelectorAll("[data-acessibilidade-tema]").forEach(function (botao) {
                botao.classList.toggle("ativo", configuracoes.tema === botao.dataset.acessibilidadeTema);
            });

        /*  BOTÕES DE FONTE */
        document
            .querySelectorAll("[data-acessibilidade-fonte]").forEach(function (botao) {
                botao.classList.toggle("ativo", configuracoes.fonte === botao.dataset.acessibilidadeFonte);
            });

        /* BOTÃO DE CONTRASTE */
        document.querySelectorAll("[data-acessibilidade-contraste]").forEach(function (botao) {
                botao.classList.toggle("ativo", configuracoes.contraste === true);

                botao.setAttribute("aria-pressed", configuracoes.contraste? "true": "false");
            });
    }

    /*  ATUALIZAR STATUS */
    function atualizarStatus() {
        const status = document.getElementById("status");
        if (!status) {
            return;
        }

        const tema = configuracoes.tema === "claro"? "Claro": "Escuro";

        const fontes = {
            normal: "Normal",
            grande: "Grande",
            "muito-grande": "Muito grande"
        };

        const fonte = fontes[configuracoes.fonte];

        const contraste = configuracoes.contraste? "Ativado": "Desativado";

        status.textContent = `Tema: ${tema} | ` + `Fonte: ${fonte} | ` + `Alto contraste: ${contraste}`;
    }

    /* DEFINIR TEMA */
    function definirTema(tema) {
        if (tema !== "claro" && tema !== "escuro") {
            return;
        }

        configuracoes.tema = tema;

        salvarConfiguracoes();

        aplicarConfiguracoes();
    }

    /* DEFINIR TAMANHO DA FONTE */
    function definirFonte(tamanho) {
        const permitidos = ["normal", "grande", "muito-grande"
    ];
        if (!permitidos.includes(tamanho)) {
            return;
        }

        configuracoes.fonte = tamanho;

        salvarConfiguracoes();

        aplicarConfiguracoes();
    }

    /* ALTERNAR ALTO CONTRASTE */
    function alternarContraste() {
        configuracoes.contraste = !configuracoes.contraste;

        salvarConfiguracoes();

        aplicarConfiguracoes();
    }

    /* RESTAURAR CONFIGURAÇÕES */
    function restaurarPadrao() {
        configuracoes = {
            ...padrao
        };

        salvarConfiguracoes();

        aplicarConfiguracoes();
    }

    /* EVENTOS DOS BOTÕES */
    document.addEventListener("click", function (evento) {
            /* TEMA */
            const botaoTema = evento.target.closest("[data-acessibilidade-tema]");
            if (botaoTema) {
                definirTema(botaoTema.dataset.acessibilidadeTema);
                return;
            }

            /* FONTE */
            const botaoFonte = evento.target.closest("[data-acessibilidade-fonte]");
            if (botaoFonte) {
                definirFonte(botaoFonte.dataset.acessibilidadeFonte);
                return;
            }
            /* CONTRASTE */
            const botaoContraste = evento.target.closest("[data-acessibilidade-contraste]");
            if (botaoContraste) {
                alternarContraste();
                return;
            }
            /* RESTAURAR */
            const botaoRestaurar = evento.target.closest("[data-acessibilidade-restaurar]");
            if (botaoRestaurar) {
                restaurarPadrao();
            }
        }
    );

    /* INICIALIZAÇÃO */
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", aplicarConfiguracoes);
    }
    else {
        aplicarConfiguracoes();
    }

    /* API PÚBLICA */
    window.Acessibilidade = {
        obterConfiguracoes: function () {
            return {
                ...configuracoes
            };
        },
        definirTema,
        definirFonte,
        alternarContraste,
        restaurarPadrao,
        aplicar: aplicarConfiguracoes
    };
}
)
();
