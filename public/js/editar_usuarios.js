document.getElementById("formularioPerfil").addEventListener("submit", function(evento) {
    evento.preventDefault();

    sessionStorage.setItem(
        "mensagemPerfil",
        "Alterações salvas com sucesso!"
    );

    window.location.href = this.querySelector(".btn-cancelar").href;
});