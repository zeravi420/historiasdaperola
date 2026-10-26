<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Usuários - Histórias na Pérola</title>

        <link rel="stylesheet" href="<?= URL ?>/public/css/Configuracoes.css">
<link rel="stylesheet" href="<?=URL?>/public/css/editar_usuarios.css">
</head>

<body>

    <?php include APP . '/Views/administrador/menu_adm.php'; ?>

    <main class="conteudo">

        <div class="titulo">
            <h1>Editar Usuários</h1>
        </div>

        <p class="descricao">
            Altere o perfil e o status da pessoa cadastrada no sistema.
        </p>

        <section class="formulario">

            <h2 class="subtitulo-formulario">Usuários</h2>

            <?php Sessao::exibirFlash(); ?>

            <form id="formularioPerfil" method="post" action="<?=URL?>/administrador/editar_usuarios">

                <input type="hidden" name="id" value="<?= (int) $pessoa->pess_id ?>">

                <div class="campo">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" value="<?= Html::e($pessoa->pess_nome) ?>" readonly>
                </div>

                <div class="campo">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" value="<?= Html::e($pessoa->emai_nome) ?>" readonly>
                </div>

                <div class="campo">
                    <label for="perfil">Perfil</label>

                    <select id="perfil" name="perfil" required>
                        <?php foreach ($perfis as $perfil): ?>
                            <option value="<?= (int) $perfil->perf_id ?>" <?= (int) $perfil->perf_id === (int) $pessoa->perf_id ? 'selected' : '' ?>>
                                <?= Html::e($perfil->perf_nome) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo">
                    <label for="status">Status</label>

                    <select id="status" name="status" required>
                        <?php foreach (['Ativo', 'Inativo'] as $status): ?>
                            <option value="<?= $status ?>" <?= $pessoa->pess_status === $status ? 'selected' : '' ?>><?= $status ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="mensagem" class="mensagem" role="status"></div>

                <div class="botoes">

                    <a href="<?=URL?>/administrador/gerenciar_perfis"
                    class="btn-cancelar">
                        Cancelar
                    </a>

                    <button type="submit" class="btn-salvar">
                        Salvar
                    </button>

                </div>

            </form>

        </section>

    </main>

    <?php include APP . '/Views/paginas/footer.php'; ?>
    

    <script src="<?= URL ?>/public/js/Configuracoes.js"></script>
</body>

</html>