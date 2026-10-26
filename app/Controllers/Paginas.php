<?php
// Controla as páginas públicas, o catálogo e a autenticação compartilhada.


class Paginas extends Controller
{
    use AcoesDaConta;

    public function index()
    {
        $busca = trim($_GET['busca'] ?? '');
        $this->view('paginas/index', [
            'historias' => $this->model('Historia')->listarTodas($busca),
            'busca' => $busca
        ]);
    }

    public function esqueceu_senha()
    {
        $this->view('paginas/autenticacao/esqueceu_senha');
    }

    public function configuracoes()
    {
        $this->view('paginas/Configuracoes');
    }

    // Telas públicas preservadas da versão 1.
    public function login()
    {
        // Processa o envio antes de exibir a tela; credenciais inválidas nunca criam sessão.
        if ($this->ehPost()) {
            $this->processarLogin('paginas/login');
        }

        // Mantém a tela de login acessível mesmo se existir uma sessão antiga.
        // O processamento do POST encerra a sessão anterior antes de validar as credenciais.

        $historiaId = filter_input(INPUT_GET, 'historia', FILTER_VALIDATE_INT);
        if ($historiaId && $historiaId > 0) {
            $_SESSION['historia_apos_login'] = $historiaId;
        }
        $this->view('paginas/autenticacao/login');
    }

    public function cadastro()
    {
        $historiaId = filter_input(INPUT_GET, 'historia', FILTER_VALIDATE_INT);
        if ($historiaId && $historiaId > 0) {
            $_SESSION['historia_apos_login'] = $historiaId;
        }
        $this->view('paginas/autenticacao/cadastro');
    }

    public function sobre_nos()
    {
        $this->view('paginas/sobre_nos');
    }


    public function sair()
    {
        if (!Sessao::estaLogado()) {
            Url::redirecionar('paginas/login');
            return;
        }

        $administrador = Sessao::ehAdministrador();

        $paginaInicio = $administrador
            ? 'administrador/inicio_adm'
            : 'usuarios/inicio_user';

        $this->view('paginas/autenticacao/sair', [
            'paginaInicio' => $paginaInicio
        ]);
    }

    // Encerra a sessão somente quando a pessoa confirma a saída.
    public function logout()
    {
        Sessao::sair();
        Sessao::flash('Você saiu da sua conta.');
        Url::redirecionar('paginas/login');
    }
}
