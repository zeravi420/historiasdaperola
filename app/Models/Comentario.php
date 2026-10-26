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
    // $atorId identifica quem executou a exclusão para o gatilho SQL.
    public function excluir($id, $pessoaId = null, $atorId = null) {
        // Para exclusão própria, o autor também é quem executa a ação.
        // Para exclusão administrativa, o controller informa $atorId explicitamente.
        if ($atorId === null && $pessoaId !== null) {
            $atorId = $pessoaId;
        }

        // Define a variável na mesma conexão usada pelo DELETE e pelo gatilho.
        $this->db->query("SET @user_pess_id = :ator");
        $this->db->bind(':ator', $atorId === null ? null : (int) $atorId, $atorId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $this->db->executa();

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
