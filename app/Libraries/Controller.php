<?php
// Métodos comuns aos controllers, incluindo as verificações de acesso.

class Controller
{
    public function model($model)
    {
        require_once APP . '/Models/' . $model . '.php';
        return new $model;
    }



    public function view($view, $dados = [])
    {
        $arquivo = APP . '/Views/' . $view . '.php';

        if (file_exists($arquivo)) {

            if (
                Sessao::estaLogado()
                && in_array(
                    $_SESSION['usuario_perfil'] ?? '',
                    ['Administrador', 'Usuário'],
                    true
                )
            ) {
                $notificacaoModel = $this->model('Notificacao');

                $dados['quantidadeNotificacoesNaoLidas'] =
                    $notificacaoModel->contarNaoLidas(Sessao::id());
            }

            extract($dados);
            require_once $arquivo;
        } else {
            die("O arquivo não existe: " . $view);
        }
    }



    // Verifica se a pessoa está logada e se a conta continua ativa.
    protected function exigirLogin($paginaLogin = 'usuarios/login_user')
    {
        if (!Sessao::estaLogado()) {
            Sessao::flash('Faça login para continuar.', 'erro');
            Url::redirecionar($paginaLogin);
            return;
        }

        $pessoa = $this->model('Pessoa')->buscarPorId(Sessao::id());

        if (!$pessoa || $pessoa->pess_status !== 'Ativo') {
            Sessao::sair();
            Sessao::flash('Sua conta está inativa ou não existe mais. Faça login novamente.', 'erro');
            Url::redirecionar($paginaLogin);
            return;
        }

        // Atualiza os dados da sessão sem gerar outro identificador.
        $_SESSION['usuario_nome'] = $pessoa->pess_nome;
        $_SESSION['usuario_email'] = $pessoa->emai_nome;
        $_SESSION['usuario_perfil'] = $pessoa->perf_nome;
    }

    // Verifica se a pessoa está logada, ativa e possui perfil de administrador.
    protected function exigirAdministrador()
    {
        if (!Sessao::estaLogado()) {
            Sessao::flash('Acesso restrito a administradores. Faça login.', 'erro');
            Url::redirecionar('administrador/login_adm');
            return;
        }

        $pessoa = $this->model('Pessoa')->buscarPorId(Sessao::id());

        if (
            !$pessoa || $pessoa->pess_status !== 'Ativo'
            || $pessoa->perf_nome !== 'Administrador'
        ) {
            Sessao::sair();
            Sessao::flash('Sua conta não possui acesso administrativo ativo.', 'erro');
            Url::redirecionar('administrador/login_adm');
            return;
        }

        // Atualiza os dados da sessão sem gerar outro identificador.
        $_SESSION['usuario_nome'] = $pessoa->pess_nome;
        $_SESSION['usuario_email'] = $pessoa->emai_nome;
        $_SESSION['usuario_perfil'] = $pessoa->perf_nome;
    }


    protected function ehPost()
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    // Lê um id inteiro positivo de GET ou POST.
    protected function idRecebido($nome = 'id', $origem = INPUT_GET)
    {
        $id = filter_input($origem, $nome, FILTER_VALIDATE_INT);
        return ($id && $id > 0) ? $id : 0;
    }
}
