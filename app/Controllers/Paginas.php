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

    // ---------------------------------------------------------------
    // Recuperação de senha (sem tabela extra: o link é assinado, ver RecuperacaoSenha)
    // ---------------------------------------------------------------

    public function esqueceu_senha()
    {
        if (!$this->ehPost()) {
            $this->view('paginas/autenticacao/esqueceu_senha');
            return;
        }

        $email = mb_strtolower(trim($_POST['email'] ?? ''), 'UTF-8');
        $_SESSION['antigo_email'] = $email;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Sessao::flash('Digite um e-mail válido. Exemplo: nome@email.com', 'erro');
            Url::redirecionar('paginas/esqueceu_senha');
        }

        // Evita disparar vários e-mails seguidos pela mesma sessão.
        $ultimoEnvio = (int) ($_SESSION['recuperacao_ultimo_envio'] ?? 0);
        if (time() - $ultimoEnvio < 60) {
            Sessao::flash('Aguarde 1 minuto antes de pedir um novo link.', 'erro');
            Url::redirecionar('paginas/esqueceu_senha');
        }

        $pessoa = $this->model('Pessoa')->buscarPorEmail($email);

        if ($pessoa && $pessoa->pess_status === 'Ativo') {
            if (!$this->enviarEmailRecuperacao($pessoa)) {
                Sessao::flash('Não foi possível enviar o e-mail agora. Tente novamente em alguns minutos.', 'erro');
                Url::redirecionar('paginas/esqueceu_senha');
            }
        }

        $_SESSION['recuperacao_ultimo_envio'] = time();

        // Mesma resposta para e-mail cadastrado ou não: ninguém descobre quem tem conta.
        unset($_SESSION['antigo_email']);
        Sessao::flash(
            'Se este e-mail estiver cadastrado, você receberá um link para criar uma nova senha. ' .
            'Confira a caixa de entrada e o spam. O link vale por ' .
            RecuperacaoSenha::minutosDeValidade() . ' minutos.'
        );
        Url::redirecionar('paginas/esqueceu_senha');
    }

    private function enviarEmailRecuperacao($pessoa)
    {
        $link = URL . '/paginas/redefinir_senha?token=' . urlencode(RecuperacaoSenha::gerarToken($pessoa));
        $nome = Html::e($pessoa->pess_nome);
        $linkHtml = Html::e($link);
        $minutos = RecuperacaoSenha::minutosDeValidade();

        $html = '
<div style="font-family:Arial,Helvetica,sans-serif;max-width:560px;margin:0 auto;color:#222">
  <h2 style="color:#2f5d34">Histórias na Pérola</h2>
  <p>Olá, ' . $nome . '!</p>
  <p>Recebemos um pedido para redefinir a senha da sua conta. Clique no botão abaixo para criar uma nova senha:</p>
  <p style="text-align:center;margin:28px 0">
    <a href="' . $linkHtml . '" style="background:#2f5d34;color:#fff;text-decoration:none;padding:12px 24px;border-radius:6px;display:inline-block">Criar nova senha</a>
  </p>
  <p style="font-size:14px;color:#555">Se o botão não funcionar, copie e cole este endereço no navegador:<br>
    <a href="' . $linkHtml . '">' . $linkHtml . '</a></p>
  <p style="font-size:14px;color:#555">O link vale por ' . $minutos . ' minutos e só pode ser usado uma vez.
    Se você não pediu a troca de senha, ignore este e-mail: sua senha atual continua a mesma.</p>
</div>';

        $texto = "Olá, {$pessoa->pess_nome}!\n\n" .
            "Para criar uma nova senha no Histórias na Pérola, acesse:\n{$link}\n\n" .
            "O link vale por {$minutos} minutos e só pode ser usado uma vez.\n" .
            "Se você não pediu a troca de senha, ignore este e-mail.";

        return Email::enviar($pessoa->emai_nome, $pessoa->pess_nome, 'Redefinição de senha - Histórias na Pérola', $html, $texto);
    }

    public function redefinir_senha()
    {
        $token = (string) ($this->ehPost() ? ($_POST['token'] ?? '') : ($_GET['token'] ?? ''));
        $pessoaModel = $this->model('Pessoa');
        $pessoa = RecuperacaoSenha::validarToken($token, $pessoaModel);

        if (!$pessoa) {
            Sessao::flash('Este link é inválido, expirou ou já foi usado. Peça um novo link abaixo.', 'erro');
            Url::redirecionar('paginas/esqueceu_senha');
        }

        if (!$this->ehPost()) {
            $this->view('paginas/autenticacao/redefinir_senha', ['token' => $token]);
            return;
        }

        $nova = (string) ($_POST['nova_senha'] ?? '');
        $confirmacao = (string) ($_POST['confirmar_senha'] ?? '');
        $voltar = 'paginas/redefinir_senha?token=' . urlencode($token);

        // Verifica se a senha possui entre 8 e 30 caracteres.
        if (strlen($nova) < 8 || strlen($nova) > 30) {
            Sessao::flash('A nova senha precisa ter entre 8 e 30 caracteres.', 'erro');
            Url::redirecionar($voltar);
        }

        // Verifica se a senha possui pelo menos um número.
        if (!preg_match('/[0-9]/', $nova)) {
            Sessao::flash('A senha precisa conter pelo menos um número.', 'erro');
            Url::redirecionar($voltar);
        }

        // Verifica se a senha possui pelo menos um caractere especial.
        if (!preg_match('/[^a-zA-Z0-9]/', $nova)) {
            Sessao::flash('A senha precisa conter pelo menos um caractere especial.', 'erro');
            Url::redirecionar($voltar);
        }

        if ($nova !== $confirmacao) {
            Sessao::flash('A confirmação não confere com a nova senha.', 'erro');
            Url::redirecionar($voltar);
        }

        // A nova senha não pode ser igual à senha já cadastrada.
        if (password_verify($nova, $pessoa->pess_senha)) {
            Sessao::flash('A nova senha não pode ser igual à senha atual. Escolha uma senha diferente.', 'erro');
            Url::redirecionar($voltar);
        }

        if (!$pessoaModel->atualizarSenha($pessoa->pess_id, $nova)) {
            Sessao::flash('Não foi possível salvar a nova senha. Tente novamente.', 'erro');
            Url::redirecionar($voltar);
        }

        // Ao trocar a senha o hash muda, então este link deixa de valer automaticamente.
        Sessao::sair();
        Sessao::flash('Senha alterada com sucesso! Entre com a sua nova senha.');
        Url::redirecionar('paginas/login');
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