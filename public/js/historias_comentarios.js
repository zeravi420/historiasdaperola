/* Confirmação antes de enviar o pedido de exclusão. */

function confirmarExclusaoComentario(formulario) {
    const confirmado = window.confirm(
        'Tem certeza de que deseja excluir este comentário? ' +
        'Essa ação não poderá ser desfeita.'
    );

    if (!confirmado) {
        return false;
    }

    return true;
}