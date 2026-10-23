<?php

class Avaliacao {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Grava a nota (1 a 5). Se a pessoa já avaliou, atualiza a nota.
    // Na primeira avaliação o trigger tg_avaliacao_notificacao_pos_ins
    // avisa os administradores.
    public function salvar($historiaId, $pessoaId, $nota) {
        try {
            $this->db->query(
                "SELECT avai_id FROM avaliacao WHERE hist_id = :historia AND pess_id = :pessoa LIMIT 1"
            );
            $this->db->bind(':historia', (int) $historiaId, PDO::PARAM_INT);
            $this->db->bind(':pessoa', (int) $pessoaId, PDO::PARAM_INT);
            $existente = $this->db->resultado();

            if ($existente) {
                $this->db->query(
                    "UPDATE avaliacao SET avai_nota = :nota, avai_data_hora = NOW() WHERE avai_id = :id"
                );
                $this->db->bind(':id', (int) $existente->avai_id, PDO::PARAM_INT);
            } else {
                $this->db->query(
                    "INSERT INTO avaliacao (avai_nota, hist_id, pess_id) VALUES (:nota, :historia, :pessoa)"
                );
                $this->db->bind(':historia', (int) $historiaId, PDO::PARAM_INT);
                $this->db->bind(':pessoa', (int) $pessoaId, PDO::PARAM_INT);
            }

            $this->db->bind(':nota', (int) $nota, PDO::PARAM_INT);
            $this->db->executa();
            return true;
        } catch (Throwable $erro) {
            error_log('Erro ao salvar avaliação: ' . $erro->getMessage());
            return false;
        }
    }

    // Média e total de avaliações da história.
    public function resumo($historiaId) {
        $this->db->query(
            "SELECT COUNT(*) AS total, COALESCE(AVG(avai_nota), 0) AS media
             FROM avaliacao WHERE hist_id = :historia"
        );
        $this->db->bind(':historia', (int) $historiaId, PDO::PARAM_INT);
        return $this->db->resultado();
    }

    public function notaDaPessoa($historiaId, $pessoaId) {
        $this->db->query(
            "SELECT avai_nota FROM avaliacao WHERE hist_id = :historia AND pess_id = :pessoa LIMIT 1"
        );
        $this->db->bind(':historia', (int) $historiaId, PDO::PARAM_INT);
        $this->db->bind(':pessoa', (int) $pessoaId, PDO::PARAM_INT);
        $linha = $this->db->resultado();
        return $linha ? (int) $linha->avai_nota : 0;
    }
}
