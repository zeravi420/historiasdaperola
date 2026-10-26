<?php
// Reúne ações compartilhadas de autenticação, perfil, senha e notificações.

/**
 * Ações iguais para usuário e administrador: login, perfil, senha e
 * notificações. Cada controller informa a "área" (pasta das views e
 * prefixo das rotas) e os nomes das views.
 */
trait AcoesDaConta {

    // ---------------------------------------------------------------
    // Login
    // ---------------------------------------------------------------

    protected function processarLogin($paginaLogin, $somenteAdministrador = false) {
        if (!$this->ehPost()) {
            Url::redirecionar($paginaLogin);
        }

        // Uma tentativa nova de login não pode aproveitar uma sessão anterior.
        // Preserva somente a história escolhida no catálogo, se houver.
        $historiaPendente = $_SESSION['historia_apos_login'] ?? null;
        Sessao::sair();
        if ($historiaPendente) {
            $_SESSION['historia_apos_login'] = (int) $historiaPendente;
        }

        $email = mb_strtolower(trim($_POST['email'] ?? ''), 'UTF-8');
        $senha = (string) ($_POST['senha'] ?? '');
        $_SESSION['antigo_email'] = $email;

        if ($email === '' || $senha === '') {
            Sessao::flash('Informe e-mail e senha.', 'erro');
            Url::redirecionar($paginaLogin);
        }

        $pessoa = $this->model('Pessoa')->buscarPorEmail($email);

        if (!$pessoa || !password_verify($senha, $pessoa->pess_senha)) {
            Sessao::flash('E-mail ou senha incorretos.', 'erro');
            Url::redirecionar($paginaLogin);
        }

        if ($pessoa->pess_status !== 'Ativo') {
            Sessao::flash('Sua conta está inativa. Procure um administrador.', 'erro');
            Url::redirecionar($paginaLogin);
        }

        if ($somenteAdministrador && $pessoa->perf_nome !== 'Administrador') {
            Sessao::flash('Este acesso é exclusivo para administradores.', 'erro');
            Url::redirecionar($paginaLogin);
        }

        unset($_SESSION['antigo_email']);
        Sessao::entrar($pessoa);

        // Se o login começou no catálogo, o usuário continua para a história escolhida.
        if ($pessoa->perf_nome !== 'Administrador' && !empty($_SESSION['historia_apos_login'])) {
            $historiaId = (int) $_SESSION['historia_apos_login'];
            unset($_SESSION['historia_apos_login']);
            Url::redirecionar('usuarios/historias_user?id=' . $historiaId);
        }

        Url::redirecionar(
            $pessoa->perf_nome === 'Administrador' ? 'administrador/inicio_adm' : 'usuarios/inicio_user'
        );
    }

    // ---------------------------------------------------------------
    // Perfil
    // ---------------------------------------------------------------

    protected function pessoaLogada() {
        $pessoa = $this->model('Pessoa')->buscarPorId(Sessao::id());

        // Conta excluída ou desativada enquanto estava logada.
        if (!$pessoa || $pessoa->pess_status !== 'Ativo') {
            Sessao::sair();
            Sessao::flash('Sua sessão foi encerrada. Faça login novamente.', 'erro');
            Url::redirecionar('paginas/login');
        }

        return $pessoa;
    }

    protected function mostrarPerfil($view) {
        $this->view($view, ['pessoa' => $this->pessoaLogada()]);
    }

    protected function processarEdicaoPerfil($view, $rotaPerfil, $rotaEdicao) {
        $pessoa = $this->pessoaLogada();

        if (!$this->ehPost()) {
            $this->view($view, ['pessoa' => $pessoa]);
            return;
        }

        $nome = trim($_POST['nome'] ?? '');
        $email = mb_strtolower(trim($_POST['email'] ?? ''), 'UTF-8');

        if (mb_strlen($nome, 'UTF-8') < 2 || mb_strlen($nome, 'UTF-8') > 100) {
            Sessao::flash('O nome deve ter entre 2 e 100 caracteres.', 'erro');
            Url::redirecionar($rotaEdicao);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email, 'UTF-8') > 200) {
            Sessao::flash('Digite um e-mail válido.', 'erro');
            Url::redirecionar($rotaEdicao);
        }

        $pessoaModel = $this->model('Pessoa');

        if ($pessoaModel->emailExiste($email, $pessoa->pess_id)) {
            Sessao::flash('Este e-mail já está sendo usado por outra conta.', 'erro');
            Url::redirecionar($rotaEdicao);
        }

        [$foto, $erroFoto] = Html::lerImagemEnviada('fotoPerfil', 2);

        if ($erroFoto) {
            Sessao::flash($erroFoto, 'erro');
            Url::redirecionar($rotaEdicao);
        }

        if (!$pessoaModel->atualizarDados($pessoa->pess_id, $nome, $email, $foto)) {
            Sessao::flash('Não foi possível salvar as alterações. Tente novamente.', 'erro');
            Url::redirecionar($rotaEdicao);
        }

        $_SESSION['usuario_nome'] = $nome;
        $_SESSION['usuario_email'] = $email;

        Sessao::flash('Dados atualizados com sucesso!');
        Url::redirecionar($rotaPerfil);
    }

    protected function processarAlteracaoSenha($view, $rotaPerfil, $rotaSenha) {
        $pessoa = $this->pessoaLogada();

        if (!$this->ehPost()) {
            $this->view($view);
            return;
        }

        $atual = (string) ($_POST['senha_atual'] ?? '');
        $nova = (string) ($_POST['nova_senha'] ?? '');
        $confirmacao = (string) ($_POST['confirmar_senha'] ?? '');

        if (!password_verify($atual, $pessoa->pess_senha)) {
            Sessao::flash('A senha atual está incorreta.', 'erro');
            Url::redirecionar($rotaSenha);
        }

        if (strlen($nova) < 8 || strlen($nova) > 30) {
            Sessao::flash('A nova senha precisa ter entre 8 e 30 caracteres.', 'erro');
            Url::redirecionar($rotaSenha);
        }

        if ($nova !== $confirmacao) {
            Sessao::flash('A confirmação não confere com a nova senha.', 'erro');
            Url::redirecionar($rotaSenha);
        }

        $this->model('Pessoa')->atualizarSenha($pessoa->pess_id, $nova);

        Sessao::flash('Senha alterada com sucesso!');
        Url::redirecionar($rotaPerfil);
    }

    // ---------------------------------------------------------------
    // Notificações
    // ---------------------------------------------------------------

    protected function listarNotificacoes($view) {
        $filtro = $_GET['filtro'] ?? 'todas';
        if (!in_array($filtro, ['todas', 'nao_lidas', 'lidas'], true)) {
            $filtro = 'todas';
        }

        $busca = trim($_GET['busca'] ?? '');
        $data = $_GET['data'] ?? '';
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
            $data = '';
        }

        $notificacoes = $this->model('Notificacao')->listar(Sessao::id(), $filtro, $busca, $data);

        $this->view($view, [
            'notificacoes' => $notificacoes,
            'filtro' => $filtro,
            'busca' => $busca,
            'data' => $data
        ]);
    }

    protected function detalharNotificacao($view, $rotaLista) {
        $id = $this->idRecebido();
        $model = $this->model('Notificacao');
        $notificacao = $id ? $model->buscar(Sessao::id(), $id) : false;

        if (!$notificacao) {
            Sessao::flash('Notificação não encontrada.', 'erro');
            Url::redirecionar($rotaLista);
        }

        // Abrir a notificação já marca como lida.
        $model->marcarComoLida(Sessao::id(), $id);

        $this->view($view, [
            'notificacao' => $notificacao,
            'historiaId' => (int) ($notificacao->hist_id ?? 0)
        ]);
    }

    protected function marcarNotificacaoLida($rotaLista) {
        $id = $this->idRecebido();

        if ($id) {
            $this->model('Notificacao')->marcarComoLida(Sessao::id(), $id);
            Sessao::flash('Notificação marcada como lida.');
        }

        Url::redirecionar($rotaLista);
    }

    protected function excluirNotificacao($view, $rotaLista) {
        $model = $this->model('Notificacao');

        if ($this->ehPost()) {
            $id = $this->idRecebido('id', INPUT_POST);

            if ($id && $model->remover(Sessao::id(), $id)) {
                Sessao::flash('Notificação excluída.');
            } else {
                Sessao::flash('Não foi possível excluir a notificação.', 'erro');
            }

            Url::redirecionar($rotaLista);
        }

        $id = $this->idRecebido();
        $notificacao = $id ? $model->buscar(Sessao::id(), $id) : false;

        if (!$notificacao) {
            Sessao::flash('Notificação não encontrada.', 'erro');
            Url::redirecionar($rotaLista);
        }

        $this->view($view, [
            'notificacao' => $notificacao,
            'historiaId' => (int) ($notificacao->hist_id ?? 0)
        ]);
    }

    // ---------------------------------------------------------------
    // Leitura de história (dados comuns às telas de usuário e admin)
    // ---------------------------------------------------------------

    protected function dadosLeituraHistoria($rotaVoltar) {
        $id = $this->idRecebido();
        $historia = $id ? $this->model('Historia')->buscarPorId($id) : false;

        if (!$historia) {
            Sessao::flash('História não encontrada.', 'erro');
            Url::redirecionar($rotaVoltar);
        }

        $codigos = ['pt' => 1, 'es' => 2, 'wari' => 3];
        $idiomaAtual = $_GET['lang'] ?? 'pt';

        if (!isset($codigos[$idiomaAtual]) || !isset($historia->idiomas[$codigos[$idiomaAtual]])) {
            $idiomaAtual = 'pt';
        }

        // Garante um texto mesmo se faltar o português (dados antigos).
        $versao = $historia->idiomas[$codigos[$idiomaAtual]] ?? reset($historia->idiomas);

        $disponiveis = [];
        foreach ($codigos as $codigo => $idiomaId) {
            if (isset($historia->idiomas[$idiomaId])) {
                $disponiveis[] = $codigo;
            }
        }

        $avaliacaoModel = $this->model('Avaliacao');

        return [
            'historia' => $historia,
            'titulo_lenda' => $versao->titulo ?? '',
            'legenda_imagem' => $versao->legenda ?? '',
            'texto_lenda' => $versao->texto ?? '',
            'idioma_atual' => $idiomaAtual,
            'idiomas_disponiveis' => $disponiveis,
            'comentarios' => $this->model('Comentario')->listarPorHistoria($historia->id),
            'avaliacao' => $avaliacaoModel->resumo($historia->id),
            'minha_nota' => $avaliacaoModel->notaDaPessoa($historia->id, Sessao::id())
        ];
    }
}
