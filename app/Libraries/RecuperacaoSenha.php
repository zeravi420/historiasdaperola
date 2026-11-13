<?php
// Gera e valida o link de redefinição de senha SEM usar tabela no banco.
//
// Como funciona:
//   token = base64url("idDaPessoa.expiracao") + "." + assinatura
//   assinatura = HMAC-SHA256( id | expiracao | e-mail | hash da senha atual , chave secreta )
//
// - Ninguém consegue montar um link válido sem a chave secreta.
// - O link expira sozinho (validade definida em configuracao_email.php).
// - O hash da senha atual entra na assinatura: assim que a senha é trocada,
//   o hash muda e o link deixa de funcionar (vale uma única vez).

class RecuperacaoSenha
{
    private static function config()
    {
        static $config = null;
        if ($config === null) {
            $config = require APP . '/configuracao_email.php';
        }
        return $config;
    }

    private static function chave()
    {
        $chave = (string) (self::config()['chave_link'] ?? '');
        if (strlen($chave) < 32) {
            throw new RuntimeException('Defina "chave_link" (mínimo 32 caracteres) em app/configuracao_email.php.');
        }
        return $chave;
    }

    private static function base64url($texto)
    {
        return rtrim(strtr(base64_encode($texto), '+/', '-_'), '=');
    }

    private static function desfazerBase64url($texto)
    {
        return base64_decode(strtr($texto, '-_', '+/'), true);
    }

    private static function assinar($pessoaId, $expiracao, $pessoa)
    {
        $dados = $pessoaId . '|' . $expiracao . '|' . mb_strtolower((string) $pessoa->emai_nome, 'UTF-8')
            . '|' . $pessoa->pess_senha;

        return self::base64url(hash_hmac('sha256', $dados, self::chave(), true));
    }

    public static function minutosDeValidade()
    {
        return max(5, (int) (self::config()['validade_link_minutos'] ?? 60));
    }

    // Cria o token para a pessoa (objeto vindo de Pessoa::buscarPorEmail/buscarPorId).
    public static function gerarToken($pessoa)
    {
        $id = (int) $pessoa->pess_id;
        $expiracao = time() + self::minutosDeValidade() * 60;

        return self::base64url($id . '.' . $expiracao) . '.' . self::assinar($id, $expiracao, $pessoa);
    }

    // Devolve a pessoa dona do token, ou false se o link for inválido, expirado ou já usado.
    public static function validarToken($token, $pessoaModel)
    {
        if (!is_string($token) || !preg_match('/^[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/', $token)) {
            return false;
        }

        [$parte, $assinatura] = explode('.', $token, 2);
        $conteudo = self::desfazerBase64url($parte);

        if ($conteudo === false || !preg_match('/^(\d+)\.(\d+)$/', $conteudo, $m)) {
            return false;
        }

        $id = (int) $m[1];
        $expiracao = (int) $m[2];

        if ($id < 1 || $expiracao < time()) {
            return false;
        }

        $pessoa = $pessoaModel->buscarPorId($id);

        if (!$pessoa || $pessoa->pess_status !== 'Ativo' || empty($pessoa->emai_nome)) {
            return false;
        }

        // hash_equals evita ataque de tempo na comparação.
        if (!hash_equals(self::assinar($id, $expiracao, $pessoa), $assinatura)) {
            return false;
        }

        return $pessoa;
    }
}
