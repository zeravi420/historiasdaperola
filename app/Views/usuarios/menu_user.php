
<link rel="stylesheet" href="<?=URL?>/public/css/menu_user.css">

<header class="header">

    <button class="menu-toggle" id="menuToggle" type="button" aria-label="Abrir menu">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="logo">
        <img src="<?=URL?>/public/img/icone_logo_menu.png" alt="Logo">
        <p>HISTÓRIAS NA PERÓLA</p>
    </div>

    <nav id="mainNav">
        <ul class="menu">

            <li>
                <a href="<?=URL?>/usuarios/inicio_user" class="menu-link">
                    <img src="<?=URL?>/public/img/icone_inicio.png" alt="Início" class="menu-icon inicio-icon">
                    <span>Início</span>
                </a>
            </li>

            <li>
                <a href="<?=URL?>/usuarios/perfil_user" class="menu-link">
                    <img src="<?=URL?>/public/img/icone_perfil.png" alt="Perfil" class="menu-icon perfil-icon">
                    <span>Perfil</span>
                </a>
            </li>

            <li>
                <a href="<?=URL?>/usuarios/escolher_historia_user" class="menu-link">
                    <img src="<?=URL?>/public/img/icone_historias.png" alt="Histórias" class="menu-icon histórias-icon">
                    <span>Histórias</span>
                </a>
            </li>

            <li>
                <a href="<?=URL?>/usuarios/notificacoes_user" class="menu-link">
                    <img src="<?=URL?>/public/img/icone_notificacoes.png" alt="Notificações" class="menu-icon notificações-icon">
                    <span>Notificações</span>
                </a>
            </li>

            <li>
                <a href="<?=URL?>/usuarios/sobre_nos_user" class="menu-link">
                    <img src="<?=URL?>/public/img/icone_sobreNos.png"
                         alt="Sobre nós"
                         class="menu-icon sobre-nós-icon">
                    <span>Sobre Nós</span>
                </a>
            </li>

            <li>
                <a href="<?=URL?>/paginas/configuracoes" class="menu-link">
                    <img src="<?=URL?>/public/img/configuracao_icone.png" alt="Configurações" class="menu-icon configuracao-icon">
                    <span>Configurações</span>
                </a>
            </li>

            <li>
                <a href="<?=URL?>/usuarios/sair_user" class="menu-link">
                    <img src="<?=URL?>/public/img/icone_sair.png" alt="Sair" class="menu-icon sair-icon">
                    <span>Sair</span>
                </a>
            </li>

        </ul>
    </nav>

    <script src="<?=URL?>/public/js/menu.js"></script>
</header>