<?php
// Define os links conforme o tipo de sessão atual.
$administrador = Sessao::estaLogado() && Sessao::ehAdministrador();
$usuarioLogado = Sessao::estaLogado() && !$administrador;

if ($administrador) {
    $linkInicio = 'administrador/inicio_adm';
    $linkSobre = 'paginas/sobre_nos';
    $linkPerfis = 'administrador/gerenciar_perfis';
} elseif ($usuarioLogado) {
    $linkInicio = 'usuarios/inicio_user';
    $linkSobre = 'paginas/sobre_nos';
    $linkPerfis = 'usuarios/perfil_user';
} else {
    $linkInicio = 'paginas/index';
    $linkSobre = 'paginas/sobre_nos';
    $linkPerfis = 'paginas/login';
}
?>
<footer class="footer">
    <div class="footer-links">
        <ul>
            <li><a href="<?= URL ?>/<?= $linkInicio ?>">Início</a></li>
            <li><a href="<?= URL ?>/<?= $linkSobre ?>">Sobre Nós</a></li>
            <li><a href="<?= URL ?>/paginas/configuracoes">Configurações</a></li>
            <?php if ($administrador): ?>
                <li><a href="<?= URL ?>/<?= $linkPerfis ?>">Gerenciar perfis</a></li>
            <?php elseif ($usuarioLogado): ?>
                <li><a href="<?= URL ?>/<?= $linkPerfis ?>">Meu perfil</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="footer-center">
        <img src="<?= URL ?>/public/img/footer_logo.png" alt="Logo IFRO" class="footer-logo">
    </div>
    <div class="footer-contact">
        <p>Contatos:</p>
        <p>Telefone: (99) 99999-9999</p>
        <p>E-mail: algumacoisa@ifro.edu.br</p>
    </div>
</footer>
