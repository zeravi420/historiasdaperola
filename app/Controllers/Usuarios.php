<?php

class Usuarios extends Controller
{
    use AcoesDaConta;

    // Páginas que podem ser abertas sem login.
    private $paginasPublicas = ['login_user', 'login', 'cadastro_user', 'logout'];

    public function __construct()
    {
        $metodo = explode('/', trim((string) ($_GET['url'] ?? ''), '/'))[1] ?? '';

        if (!in_array($metodo, $this->paginasPublicas, true)) {
            $this->exigirLogin('paginas/login');
        }
    }

    public function index()
    {
        Url::redirecionar('usuarios/inicio_user');
    }

    // ---------------------------------------------------------------
    // Acesso
    // ---------------------------------------------------------------

    public function login_user()
    {
        if (Sessao::estaLogado()) {
            Url::redirecionar(Sessao::ehAdministrador() ? 'administrador/inicio_adm' : 'usuarios/inicio_user');
        }
        Url::redirecionar('paginas/login');
    }

    public function login()
    {
        Url::redirecionar('paginas/login');
    }

    public function cadastro_user()
    {
        if (!$this->ehPost()) {
            Url::redirecionar('paginas/cadastro');
            return;
        }

        $nome = trim($_POST['nome'] ?? '');
        $email = mb_strtolower(trim($_POST['email'] ?? ''), 'UTF-8');
        $senha = (string) ($_POST['senha'] ?? '');

        $_SESSION['antigo_nome'] = $nome;
        $_SESSION['antigo_email'] = $email;

        if (
            mb_strlen($nome, 'UTF-8') < 2 || mb_strlen($nome, 'UTF-8') > 100
            || !preg_match("/^[\p{L}\s'-]+$/u", $nome)
        ) {
            Sessao::flash('Digite um nome com pelo menos 2 caracteres, usando apenas letras.', 'erro');
            Url::redirecionar('paginas/cadastro');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email, 'UTF-8') > 200) {
            Sessao::flash('Digite um e-mail válido. Exemplo: nome@email.com', 'erro');
            Url::redirecionar('paginas/cadastro');
        }

        if (strlen($senha) < 8 || strlen($senha) > 30) {
            Sessao::flash('A senha precisa ter entre 8 e 30 caracteres.', 'erro');
            Url::redirecionar('paginas/cadastro');
        }

        $pessoaModel = $this->model('Pessoa');

        if ($pessoaModel->emailExiste($email)) {
            Sessao::flash('Já existe uma conta com este e-mail. Faça login.', 'erro');
            Url::redirecionar('paginas/cadastro');
        }

        $id = $pessoaModel->cadastrar($nome, $email, $senha, Pessoa::PERFIL_USUARIO);

        if (!$id) {
            Sessao::flash('Não foi possível concluir o cadastro. Tente novamente.', 'erro');
            Url::redirecionar('paginas/cadastro');
        }

        unset($_SESSION['antigo_nome'], $_SESSION['antigo_email']);

        // Já entra na conta recém-criada.
        Sessao::entrar($pessoaModel->buscarPorId($id));
        Sessao::flash('Conta criada com sucesso! Seja bem-vindo(a).');
        if (!empty($_SESSION['historia_apos_login'])) {
            $historiaId = (int) $_SESSION['historia_apos_login'];
            unset($_SESSION['historia_apos_login']);
            Url::redirecionar('usuarios/historias_user?id=' . $historiaId);
        }
        Url::redirecionar('usuarios/inicio_user');
    }

    public function logout()
    {
        Sessao::sair();
        Sessao::flash('Você saiu da sua conta.');
        Url::redirecionar('paginas/login');
    }

    // ---------------------------------------------------------------
    // Perfil
    // ---------------------------------------------------------------

    public function perfil_user()
    {
        $this->mostrarPerfil('usuarios/perfil/perfil_user');
    }

    public function editar_perfil_user()
    {
        $this->processarEdicaoPerfil('usuarios/perfil/editar_perfil_user', 'usuarios/perfil/perfil_user', 'usuarios/perfil/editar_perfil_user');
    }

    public function alterar_senha_user()
    {
        $this->processarAlteracaoSenha('usuarios/perfil/alterar_senha_user', 'usuarios/perfil/perfil_user', 'usuarios/perfil/alterar_senha_user');
    }

    // ---------------------------------------------------------------
    // Histórias
    // ---------------------------------------------------------------

    public function inicio_user()
    {
        $this->view('usuarios/inicio_user');
    }

    public function escolher_historia_user()
    {
        $busca = trim($_GET['busca'] ?? '');

        $this->view('usuarios/historias/escolher_historia_user', [
            'historias' => $this->model('Historia')->listarTodas($busca),
            'busca' => $busca
        ]);
    }

    public function historias_user()
    {
        $this->view('usuarios/historias/historias_user', $this->dadosLeituraHistoria('usuarios/historias/escolher_historia_user'));
    }

    public function comentar()
    {
        $historiaId = $this->idRecebido('historia', INPUT_POST);
        $texto = trim($_POST['comentario'] ?? '');
        $voltar = 'usuarios/historias_user?id=' . $historiaId . '#comentarios';

        if (!$this->ehPost() || !$historiaId) {
            Url::redirecionar('usuarios/historias/escolher_historia_user');
        }

        if ($texto === '' || mb_strlen($texto, 'UTF-8') > 1000) {
            Sessao::flash('Escreva um comentário de até 1000 caracteres.', 'erro');
            Url::redirecionar($voltar);
        }

        $historiaModel = $this->model('Historia');
        $titulo = $historiaModel->tituloPorId($historiaId);

        if ($titulo === '' || !$this->model('Comentario')->cadastrar($historiaId, Sessao::id(), $texto)) {
            Sessao::flash('Não foi possível enviar o comentário.', 'erro');
            Url::redirecionar($voltar);
        }

        Sessao::flash('Comentário enviado!');
        Url::redirecionar($voltar);
    }

    public function excluir_comentario()
    {
        $comentarioId = $this->idRecebido('excluir_comentario', INPUT_POST);
        $historiaId = $this->idRecebido('historia', INPUT_POST);

        // Cada usuário só pode excluir os próprios comentários.
        if ($this->ehPost() && $comentarioId && $this->model('Comentario')->excluir($comentarioId, Sessao::id())) {
            Sessao::flash('Comentário excluído.');
        } else {
            Sessao::flash('Não foi possível excluir o comentário.', 'erro');
        }

        Url::redirecionar('usuarios/historias_user?id=' . $historiaId . '#comentarios');
    }

    public function avaliar()
    {
        $historiaId = $this->idRecebido('historia', INPUT_POST);
        $nota = filter_input(INPUT_POST, 'nota', FILTER_VALIDATE_INT);

        if (!$this->ehPost() || !$historiaId) {
            Url::redirecionar('usuarios/escolher_historia_user');
        }

        if (!$nota || $nota < 1 || $nota > 5) {
            Sessao::flash('Escolha uma nota de 1 a 5 estrelas.', 'erro');
        } elseif ($this->model('Avaliacao')->salvar($historiaId, Sessao::id(), $nota)) {
            Sessao::flash('Obrigado pela sua avaliação!');
        } else {
            Sessao::flash('Não foi possível registrar a avaliação.', 'erro');
        }

        Url::redirecionar('usuarios/historias_user?id=' . $historiaId . '#avaliacao');
    }

    // ---------------------------------------------------------------
    // Notificações
    // ---------------------------------------------------------------

    public function notificacoes_user()
    {
        $this->listarNotificacoes('usuarios/notificacao/notificacoes_user');
    }

    public function detalhes_notificacao_user()
    {
        $this->detalharNotificacao('usuarios/notificacao/detalhes_notificacao_user', 'usuarios/notificacao/notificacoes_user');
    }

    public function marcar_lida()
    {
        $this->marcarNotificacaoLida('usuarios/notificacao/notificacoes_user');
    }

    public function excluir_notificacao_user()
    {
        $this->excluirNotificacao('usuarios/notificacao/excluir_notificacao_user', 'usuarios/notificacao/notificacoes_user');
    }

    // ---------------------------------------------------------------
    // Páginas simples
    // ---------------------------------------------------------------

    public function menu_user()
    {
        $this->view('usuarios/menu_user');
    }

    public function sobre_nos_user()
    {
        Url::redirecionar('paginas/sobre_nos');
    }

    // Exibe a tela de confirmação compartilhada; ainda não encerra a sessão.
    public function sair_user()
    {
        $this->view('paginas/autenticacao/sair');
    }

    public function footer_user()
    {
        Url::redirecionar('usuarios/inicio_user');
    }
}
