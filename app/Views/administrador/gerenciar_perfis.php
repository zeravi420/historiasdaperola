<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gerenciar Perfis - Histórias na Pérola</title>

    <link rel="stylesheet" href="<?=URL?>/public/css/gerenciar_perfis.css">
</head>

<body>

    <?php include APP . '/Views/administrador/menu_adm.php'; ?>

    <main class="conteudo">

        <div class="titulo">
            <h1>Gerenciar Perfis</h1>
        </div>

        <p class="descricao">
            Consulte e gerencie os perfis cadastrados no sistema.
        </p>

        <?php Sessao::exibirFlash(); ?>

        <div id="mensagem" class="mensagem" role="status"></div>

        <section class="area-tabela">

            <div class="cabecalho-tabela">
                <h2>Perfis cadastrados</h2>
            </div>

            <div class="tabela-responsiva">

                <table>

                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Perfil</th>
                            <th>Situação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody id="listaPessoas">

                        <?php if (empty($pessoas)): ?>
                            <tr>
                                <td colspan="5">Nenhuma pessoa cadastrada.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($pessoas as $p): ?>
                            <?php
                                $ehAdmin = (int) $p->perf_id === Pessoa::PERFIL_ADMINISTRADOR;
                                $ativo = $p->pess_status === 'Ativo';
                                $ehVoce = (int) $p->pess_id === Sessao::id();
                            ?>
                        <tr>
                            <td data-label="Nome">
                                <?= Html::e($p->pess_nome) ?><?= $ehVoce ? ' (você)' : '' ?>
                            </td>

                            <td data-label="E-mail">
                                <?= Html::e($p->emai_nome ?? '—') ?>
                            </td>

                            <td data-label="Perfil">
                                <span class="perfil <?= $ehAdmin ? 'administrador' : 'usuario' ?>">
                                    <?= Html::e($p->perf_nome) ?>
                                </span>
                            </td>

                            <td data-label="Situação">
                                <span class="situacao <?= $ativo ? 'ativo' : 'inativo' ?>">
                                    <?= Html::e($p->pess_status) ?>
                                </span>
                            </td>

                            <td data-label="Ações">
                                <div class="acoes">

                                    <a href="<?=URL?>/administrador/editar_usuarios?id=<?= (int) $p->pess_id ?>"
                                       class="btn-editar">
                                        Editar
                                    </a>

                                    <?php if (!$ehVoce): ?>
                                    <button
                                        type="button"
                                        class="btn-excluir"
                                        data-id="<?= (int) $p->pess_id ?>"
                                        data-nome="<?= Html::e($p->pess_nome) ?>"
                                        onclick="abrirModal(this, this.dataset.nome)">
                                        Excluir
                                    </button>
                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

    <div id="modalExcluir" class="modal-excluir" role="dialog" aria-modal="true" aria-labelledby="tituloExcluir">

        <div class="caixa-excluir">

            <span class="icone-alerta">!</span>

            <h2 id="tituloExcluir">Excluir usuário?</h2>

            <p>
                Tem certeza de que deseja excluir
                <strong id="nomeUsuarioExcluir"></strong>?
                Essa ação não poderá ser desfeita.
            </p>

            <p>Comentários, avaliações e notificações dessa pessoa também serão apagados.</p>

            <form class="botoes-excluir" id="formExcluirPessoa" method="post" action="<?=URL?>/administrador/excluir_pessoa">

                <input type="hidden" name="id" id="idPessoaExcluir" value="">

                <button type="button" class="btn-cancelar" onclick="fecharModal()">
                    Cancelar
                </button>

                <button type="submit" class="btn-confirmar-exclusao">
                    Excluir
                </button>

            </form>

        </div>

    </div>

    <div id="mensagemExclusao" class="mensagem-exclusao" role="status"></div>

    <?php include APP . '/Views/administrador/footer_adm.php'; ?>

    <script src="<?=URL?>/public/js/gerenciar_perfis.js"></script>

</body>

</html>