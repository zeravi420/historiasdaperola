<?php

class Notificacao {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Cria a notificação e a liga à história (opcional). Retorna o id.
    private function criar($titulo, $mensagem, $historiaId = null) {
        $this->db->query(
            "INSERT INTO notificacao (noti_titulo, noti_mensagem) VALUES (:titulo, :mensagem)"
        );
        $this->db->bind(':titulo', mb_substr($titulo, 0, 100, 'UTF-8'));
        $this->db->bind(':mensagem', $mensagem);
        $this->db->executa();

        $notificacaoId = (int) $this->db->ultimoIdInserido();

        if ($historiaId) {
            $this->db->query(
                "INSERT INTO historia_notificacao (hist_id, noti_id) VALUES (:historia, :noti)"
            );
            $this->db->bind(':historia', (int) $historiaId, PDO::PARAM_INT);
            $this->db->bind(':noti', $notificacaoId, PDO::PARAM_INT);
            $this->db->executa();
        }

        return $notificacaoId;
    }

    // Envia para todas as pessoas ativas de um perfil (1 = Administrador, 2 = Usuário).
    public function enviarParaPerfil($perfilId, $titulo, $mensagem, $historiaId = null, $excetoPessoaId = 0) {
        try {
            $notificacaoId = $this->criar($titulo, $mensagem, $historiaId);

            $this->db->query(
                "INSERT INTO pessoa_notificacao (pess_id, noti_id, peno_lida)
                 SELECT pess_id, :noti, 0
                 FROM pessoa
                 WHERE perf_id = :perfil
                 AND pess_status = 'Ativo'
                 AND pess_id <> :exceto"
            );
            $this->db->bind(':noti', $notificacaoId, PDO::PARAM_INT);
            $this->db->bind(':perfil', (int) $perfilId, PDO::PARAM_INT);
            $this->db->bind(':exceto', (int) $excetoPessoaId, PDO::PARAM_INT);
            $this->db->executa();

            return true;
        } catch (Throwable $erro) {
            error_log('Erro ao enviar notificação: ' . $erro->getMessage());
            return false;
        }
    }

    // Envia para uma pessoa específica.
    public function enviarParaPessoa($pessoaId, $titulo, $mensagem, $historiaId = null) {
        try {
            $notificacaoId = $this->criar($titulo, $mensagem, $historiaId);

            $this->db->query(
                "INSERT INTO pessoa_notificacao (pess_id, noti_id, peno_lida) VALUES (:pessoa, :noti, 0)"
            );
            $this->db->bind(':pessoa', (int) $pessoaId, PDO::PARAM_INT);
            $this->db->bind(':noti', $notificacaoId, PDO::PARAM_INT);
            $this->db->executa();

            return true;
        } catch (Throwable $erro) {
            error_log('Erro ao enviar notificação: ' . $erro->getMessage());
            return false;
        }
    }

    // Lista as notificações da pessoa com filtros opcionais.
    // $filtro: todas | nao_lidas | lidas ; $data: AAAA-MM-DD
    public function listar($pessoaId, $filtro = 'todas', $busca = '', $data = '') {
        $sql = "SELECT noti_id, noti_titulo, noti_mensagem, noti_data_hora, peno_lida
                FROM vw_notificacoes
                WHERE pess_id = :pessoa";

        if ($filtro === 'nao_lidas') {
            $sql .= " AND peno_lida = 0";
        } elseif ($filtro === 'lidas') {
            $sql .= " AND peno_lida = 1";
        }

        if ($busca !== '') {
            $sql .= " AND (noti_titulo LIKE :busca1 OR noti_mensagem LIKE :busca2)";
        }

        if ($data !== '') {
            $sql .= " AND DATE(noti_data_hora) = :data";
        }

        $sql .= " ORDER BY noti_data_hora DESC, noti_id DESC";

        $this->db->query($sql);
        $this->db->bind(':pessoa', (int) $pessoaId, PDO::PARAM_INT);

        if ($busca !== '') {
            $this->db->bind(':busca1', '%' . $busca . '%');
            $this->db->bind(':busca2', '%' . $busca . '%');
        }

        if ($data !== '') {
            $this->db->bind(':data', $data);
        }

        return $this->db->resultados();
    }

    public function buscar($pessoaId, $notificacaoId) {
        $this->db->query(
            "SELECT noti_id, noti_titulo, noti_mensagem, noti_data_hora, peno_lida
             FROM vw_notificacoes
             WHERE pess_id = :pessoa AND noti_id = :noti
             LIMIT 1"
        );
        $this->db->bind(':pessoa', (int) $pessoaId, PDO::PARAM_INT);
        $this->db->bind(':noti', (int) $notificacaoId, PDO::PARAM_INT);
        return $this->db->resultado();
    }

    public function marcarComoLida($pessoaId, $notificacaoId) {
        $this->db->query(
            "UPDATE pessoa_notificacao SET peno_lida = 1 WHERE pess_id = :pessoa AND noti_id = :noti"
        );
        $this->db->bind(':pessoa', (int) $pessoaId, PDO::PARAM_INT);
        $this->db->bind(':noti', (int) $notificacaoId, PDO::PARAM_INT);
        return $this->db->executa();
    }

    // Remove a notificação da caixa da pessoa. Se ninguém mais a tiver,
    // apaga a notificação do banco.
    public function remover($pessoaId, $notificacaoId) {
        try {
            $this->db->query(
                "DELETE FROM pessoa_notificacao WHERE pess_id = :pessoa AND noti_id = :noti"
            );
            $this->db->bind(':pessoa', (int) $pessoaId, PDO::PARAM_INT);
            $this->db->bind(':noti', (int) $notificacaoId, PDO::PARAM_INT);
            $this->db->executa();

            $removeu = $this->db->totalResultados() > 0;

            $this->db->query(
                "DELETE FROM notificacao
                 WHERE noti_id = :noti
                 AND NOT EXISTS (SELECT 1 FROM pessoa_notificacao WHERE noti_id = :noti2)"
            );
            $this->db->bind(':noti', (int) $notificacaoId, PDO::PARAM_INT);
            $this->db->bind(':noti2', (int) $notificacaoId, PDO::PARAM_INT);
            $this->db->executa();

            return $removeu;
        } catch (Throwable $erro) {
            error_log('Erro ao remover notificação: ' . $erro->getMessage());
            return false;
        }
    }
}
