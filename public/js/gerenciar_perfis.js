
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

    const formulario = document.getElementById("formExcluirPessoa");

    if (formulario) {
        formulario.addEventListener("submit", function (evento) {
            const id = document.getElementById("idPessoaExcluir").value;

            if (!id || !Number.isInteger(Number(id)) || Number(id) <= 0) {
                evento.preventDefault();
                alert("Não foi possível identificar o usuário selecionado.");
            }
        });
    }
});

function abrirModal(botao, nome) {
    const modal = document.getElementById("modalExcluir");
    const campoId = document.getElementById("idPessoaExcluir");
    const nomeUsuario = document.getElementById("nomeUsuarioExcluir");

    if (!modal || !campoId || !nomeUsuario) {
        return;
    }

    campoId.value = botao.dataset.id;
    nomeUsuario.textContent = nome;

    modal.classList.add("mostrar");
}

function fecharModal() {
    const modal = document.getElementById("modalExcluir");

    if (modal) {
        modal.classList.remove("mostrar");
    }
}
