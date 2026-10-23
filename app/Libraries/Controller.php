<?php
class Controller{
    public function model($model){
        require_once APP.'/Models/'.$model.'.php';
        return new $model;
    }


    public function view($view, $dados = []) {
        $arquivo = APP . '/Views/' . $view . '.php';

        if (file_exists($arquivo)) {
            extract($dados);
            require_once $arquivo;
        } else {
            die("O arquivo não existe: " . $view);
        }
    }

    // Só deixa continuar se houver alguém logado.
    protected function exigirLogin($paginaLogin = 'usuarios/login_user') {
        if (!Sessao::estaLogado()) {
            Sessao::flash('Faça login para continuar.', 'erro');
            Url::redirecionar($paginaLogin);
        }
    }

    // Só deixa continuar se a pessoa logada for administrador.
    protected function exigirAdministrador() {
        if (!Sessao::ehAdministrador()) {
            Sessao::flash('Acesso restrito a administradores. Faça login.', 'erro');
            Url::redirecionar('administrador/login_adm');
        }
    }

    protected function ehPost() {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    // Lê um id inteiro positivo de GET ou POST.
    protected function idRecebido($nome = 'id', $origem = INPUT_GET) {
        $id = filter_input($origem, $nome, FILTER_VALIDATE_INT);
        return ($id && $id > 0) ? $id : 0;
    }
}
