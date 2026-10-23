let linhaSelecionada = null;

document.addEventListener("DOMContentLoaded", function () {

    const mensagem = document.getElementById("mensagem");
    const texto = sessionStorage.getItem("mensagemPerfil");

    if (texto && mensagem) {
        mensagem.textContent = texto;
        mensagem.classList.add("mostrar");

        sessionStorage.removeItem("mensagemPerfil");

        setTimeout(function () {
            mensagem.classList.remove("mostrar");
        }, 4000);
    }

    const modal = document.getElementById("modalExcluir");

    if (modal) {
        modal.addEventListener("click", function (evento) {
            if (evento.target === modal) {
                fecharModal();
            }
        });
    }

    document.addEventListener("keydown", function (evento) {
        if (evento.key === "Escape") {
            fecharModal();
        }
    });
});

function abrirModal(botao, nome) {
    linhaSelecionada = botao.closest("tr");

    document.getElementById("nomeUsuarioExcluir").textContent = nome;
    document.getElementById("idPessoaExcluir").value = botao.dataset.id || "";

    document.getElementById("modalExcluir").classList.add("mostrar");
}

function fecharModal() {
    const modal = document.getElementById("modalExcluir");

    if (modal) {
        modal.classList.remove("mostrar");
    }

    linhaSelecionada = null;
}

// A exclusão agora é feita pelo formulário #formExcluirPessoa (POST para o servidor).
function confirmarExclusao() {
    const formulario = document.getElementById("formExcluirPessoa");

    if (formulario && document.getElementById("idPessoaExcluir").value) {
        formulario.submit();
    }
}