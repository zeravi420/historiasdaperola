<?php
// Consultas e operações relacionadas às pessoas e seus perfis.


class Pessoa {
    const PERFIL_ADMINISTRADOR = 1;
    const PERFIL_USUARIO = 2;

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    private function selectBase() {
        return "SELECT
                    p.pess_id,
                    p.pess_nome,
                    p.pess_senha,
                    p.pess_foto_perfil,
                    p.pess_status,
                    p.perf_id,
                    pf.perf_nome,
                    e.emai_nome
                FROM pessoa p
                INNER JOIN perfil pf ON pf.perf_id = p.perf_id
                LEFT JOIN email e ON e.pess_id = p.pess_id";
    }

    public function buscarPorEmail($email) {
        $this->db->query($this->selectBase() . " WHERE e.emai_nome = :email LIMIT 1");
        $this->db->bind(':email', $email);
        return $this->db->resultado();
    }

    public function buscarPorId($id) {
        $this->db->query($this->selectBase() . " WHERE p.pess_id = :id LIMIT 1");
        $this->db->bind(':id', (int) $id, PDO::PARAM_INT);
        return $this->db->resultado();
    }

    public function emailExiste($email, $ignorarPessoaId = 0) {
        $this->db->query(
            "SELECT emai_id FROM email WHERE emai_nome = :email AND pess_id <> :pessoa LIMIT 1"
        );
        $this->db->bind(':email', $email);
        $this->db->bind(':pessoa', (int) $ignorarPessoaId, PDO::PARAM_INT);
        return (bool) $this->db->resultado();
    }

    // Cria a pessoa e o e-mail dela. Retorna o id criado ou false.
    public function cadastrar($nome, $email, $senha, $perfil = self::PERFIL_USUARIO) {
        try {
            $this->db->iniciarTransacao();

            $this->db->query(
                "INSERT INTO pessoa (pess_nome, pess_senha, pess_status, perf_id)
                 VALUES (:nome, :senha, 'Ativo', :perfil)"
            );
            $this->db->bind(':nome', $nome);
            $this->db->bind(':senha', password_hash($senha, PASSWORD_DEFAULT));
            $this->db->bind(':perfil', (int) $perfil, PDO::PARAM_INT);
            $this->db->executa();

            $pessoaId = (int) $this->db->ultimoIdInserido();

            $this->db->query("INSERT INTO email (emai_nome, pess_id) VALUES (:email, :pessoa)");
            $this->db->bind(':email', $email);
            $this->db->bind(':pessoa', $pessoaId, PDO::PARAM_INT);
            $this->db->executa();

            $this->db->confirmarTransacao();

            return $pessoaId;
        } catch (Throwable $erro) {
            $this->db->cancelarTransacao();
            error_log('Erro ao cadastrar pessoa: ' . $erro->getMessage());
            return false;
        }
    }

    // Atualiza nome, e-mail e (opcionalmente) a foto de perfil.
    public function atualizarDados($id, $nome, $email, $foto = null) {
        try {
            $this->db->iniciarTransacao();

            if ($foto !== null) {
                $this->db->query(
                    "UPDATE pessoa SET pess_nome = :nome, pess_foto_perfil = :foto WHERE pess_id = :id"
                );
                $this->db->bind(':foto', $foto, PDO::PARAM_LOB);
            } else {
                $this->db->query("UPDATE pessoa SET pess_nome = :nome WHERE pess_id = :id");
            }
            $this->db->bind(':nome', $nome);
            $this->db->bind(':id', (int) $id, PDO::PARAM_INT);
            $this->db->executa();

            $this->db->query("SELECT emai_id FROM email WHERE pess_id = :id LIMIT 1");
            $this->db->bind(':id', (int) $id, PDO::PARAM_INT);

            if ($this->db->resultado()) {
                $this->db->query("UPDATE email SET emai_nome = :email WHERE pess_id = :id");
            } else {
                $this->db->query("INSERT INTO email (emai_nome, pess_id) VALUES (:email, :id)");
            }
            $this->db->bind(':email', $email);
            $this->db->bind(':id', (int) $id, PDO::PARAM_INT);
            $this->db->executa();

            $this->db->confirmarTransacao();
            return true;
        } catch (Throwable $erro) {
            $this->db->cancelarTransacao();
            error_log('Erro ao atualizar dados da pessoa: ' . $erro->getMessage());
            return false;
        }
    }

    public function atualizarSenha($id, $novaSenha) {
        $this->db->query("UPDATE pessoa SET pess_senha = :senha WHERE pess_id = :id");
        $this->db->bind(':senha', password_hash($novaSenha, PASSWORD_DEFAULT));
        $this->db->bind(':id', (int) $id, PDO::PARAM_INT);
        return $this->db->executa();
    }

    // Lista para a tela "Gerenciar Perfis".
    public function listarTodas() {
        // Consulta as tabelas diretamente para não depender de uma view desatualizada.
        $this->db->query(
            "SELECT p.pess_id, p.pess_nome, e.emai_nome,
                    p.perf_id, pf.perf_nome, p.pess_status
             FROM pessoa p
             INNER JOIN perfil pf ON pf.perf_id = p.perf_id
             LEFT JOIN email e ON e.pess_id = p.pess_id
             ORDER BY p.perf_id, p.pess_nome"
        );
        return $this->db->resultados();
    }

    public function listarPerfis() {
        $this->db->query("SELECT perf_id, perf_nome FROM perfil ORDER BY perf_id");
        return $this->db->resultados();
    }

    public function atualizarPerfilEStatus($id, $perfilId, $status) {
        $this->db->query(
            "UPDATE pessoa SET perf_id = :perfil, pess_status = :status WHERE pess_id = :id"
        );
        $this->db->bind(':perfil', (int) $perfilId, PDO::PARAM_INT);
        $this->db->bind(':status', $status);
        $this->db->bind(':id', (int) $id, PDO::PARAM_INT);
        return $this->db->executa();
    }

    public function totalHistoriasCadastradas($id) {
        $this->db->query("SELECT COUNT(*) AS total FROM historia WHERE pess_id = :id");
        $this->db->bind(':id', (int) $id, PDO::PARAM_INT);
        return (int) $this->db->resultado()->total;
    }

    public function totalAdministradoresAtivos() {
        $this->db->query(
            "SELECT COUNT(*) AS total FROM pessoa
             WHERE perf_id = :perfil AND pess_status = 'Ativo'"
        );
        $this->db->bind(':perfil', self::PERFIL_ADMINISTRADOR, PDO::PARAM_INT);
        return (int) $this->db->resultado()->total;
    }

    // Exclui a pessoa e seus registros dependentes em uma única transação.
    // O controlador já impede a exclusão de pessoas que cadastraram histórias
    // e da própria conta do administrador.
    public function excluir($id) {
        $id = (int) $id;

        if ($id < 1) {
            return false;
        }

        try {
            $this->db->iniciarTransacao();

            // Remove os vínculos da pessoa antes da conta, inclusive em bancos
            // que ainda não tenham todas as chaves estrangeiras com CASCADE.
            $this->db->query("DELETE FROM pessoa_notificacao WHERE pess_id = :id");
            $this->db->bind(':id', $id, PDO::PARAM_INT);
            $this->db->executa();

            $this->db->query("DELETE FROM comentario WHERE pess_id = :id");
            $this->db->bind(':id', $id, PDO::PARAM_INT);
            $this->db->executa();

            $this->db->query("DELETE FROM avaliacao WHERE pess_id = :id");
            $this->db->bind(':id', $id, PDO::PARAM_INT);
            $this->db->executa();

            $this->db->query("DELETE FROM email WHERE pess_id = :id");
            $this->db->bind(':id', $id, PDO::PARAM_INT);
            $this->db->executa();

            $this->db->query("DELETE FROM pessoa WHERE pess_id = :id");
            $this->db->bind(':id', $id, PDO::PARAM_INT);
            $this->db->executa();
            $excluiu = $this->db->totalResultados() > 0;

            if (!$excluiu) {
                $this->db->cancelarTransacao();
                return false;
            }

            $this->db->confirmarTransacao();
            return true;
        } catch (Throwable $erro) {
            $this->db->cancelarTransacao();
            error_log('Erro ao excluir pessoa ID ' . $id . ': ' . $erro->getMessage());
            return false;
        }
    }
}
