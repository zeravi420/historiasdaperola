<?php

class Comentario {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function listarPorHistoria($historiaId) {
        $this->db->query(
            "SELECT come_id, come_texto, come_data_pessoa, pess_id, pess_nome
             FROM vw_comentarios
             WHERE hist_id = :historia
             ORDER BY come_data_pessoa DESC, come_id DESC"
        );
        $this->db->bind(':historia', (int) $historiaId, PDO::PARAM_INT);
        return $this->db->resultados();
    }

    public function buscarPorId($id) {
        $this->db->query(
            "SELECT come_id, come_texto, come_data_pessoa, pess_id, pess_nome, hist_id, hiid_titulo
             FROM vw_comentarios
             WHERE come_id = :id
             LIMIT 1"
        );
        $this->db->bind(':id', (int) $id, PDO::PARAM_INT);
        return $this->db->resultado();
    }

    public function cadastrar($historiaId, $pessoaId, $texto) {
        try {
            $this->db->query(
                "INSERT INTO comentario (come_texto, pess_id, hist_id) VALUES (:texto, :pessoa, :historia)"
            );
            $this->db->bind(':texto', $texto);
            $this->db->bind(':pessoa', (int) $pessoaId, PDO::PARAM_INT);
            $this->db->bind(':historia', (int) $historiaId, PDO::PARAM_INT);
            $this->db->executa();
            return true;
        } catch (Throwable $erro) {
            error_log('Erro ao cadastrar comentário: ' . $erro->getMessage());
            return false;
        }
    }

    // Se $pessoaId for informado, só exclui se o comentário for dessa pessoa.
    public function excluir($id, $pessoaId = null) {
        $sql = "DELETE FROM comentario WHERE come_id = :id";

        if ($pessoaId !== null) {
            $sql .= " AND pess_id = :pessoa";
        }

        $this->db->query($sql);
        $this->db->bind(':id', (int) $id, PDO::PARAM_INT);

        if ($pessoaId !== null) {
            $this->db->bind(':pessoa', (int) $pessoaId, PDO::PARAM_INT);
        }

        $this->db->executa();
        return $this->db->totalResultados() > 0;
    }
}
