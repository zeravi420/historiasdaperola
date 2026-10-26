<?php

class Historia {
    private $db;
    private $ultimoId = 0;

    public function __construct() {
        $this->db = new Database();
    }

    public function cadastrar($imagem, $categoria, $pessoa, $idiomas) {
        try {
            $this->db->iniciarTransacao();

            $this->db->query(
                "INSERT INTO historia (hist_imagem, pess_id, cate_id)
                 VALUES (:imagem, :pessoa, :categoria)"
            );

            $this->db->bind(':imagem', $imagem, PDO::PARAM_LOB);
            $this->db->bind(':pessoa', $pessoa, PDO::PARAM_INT);
            $this->db->bind(':categoria', $categoria, PDO::PARAM_INT);
            $this->db->executa();

            $histId = $this->db->ultimoIdInserido();
            $this->ultimoId = (int) $histId;

            foreach ($idiomas as $idioma) {
                $this->db->query(
                    "INSERT INTO historia_idioma
                    (hiid_titulo, hiid_legenda_imagem, hiid_texto, hist_id, idio_id)
                    VALUES
                    (:titulo, :legenda, :texto, :historia, :idioma)"
                );

                $this->db->bind(':titulo', $idioma['titulo']);
                $this->db->bind(':legenda', $idioma['legenda']);
                $this->db->bind(':texto', $idioma['texto']);
                $this->db->bind(':historia', (int) $histId, PDO::PARAM_INT);
                $this->db->bind(':idioma', (int) $idioma['id'], PDO::PARAM_INT);

                $this->db->executa();
            }

            $this->db->confirmarTransacao();

            return true;
        } catch (Throwable $erro) {
            $this->db->cancelarTransacao();

            error_log('Erro ao cadastrar história: ' . $erro->getMessage());

            return false;
        }
    }

    // $busca (opcional) procura no título em qualquer idioma.
    public function listarTodas($busca = '') {
        $filtro = '';

        if ($busca !== '') {
            $filtro = "WHERE EXISTS (
                    SELECT 1 FROM historia_idioma hb
                    WHERE hb.hist_id = h.hist_id
                    AND hb.hiid_titulo LIKE :busca
                )";
        }

        $this->db->query(
            "SELECT
                h.hist_id AS id,
                hi.hiid_titulo AS titulo,
                (
                    SELECT GROUP_CONCAT(hi_busca.hiid_titulo SEPARATOR ' ')
                    FROM historia_idioma hi_busca
                    WHERE hi_busca.hist_id = h.hist_id
                ) AS titulos_busca,
                (
                    SELECT hi2.hiid_titulo
                    FROM historia_idioma hi2
                    WHERE hi2.hist_id = h.hist_id
                    AND hi2.idio_id = 2
                    LIMIT 1
                ) AS titulo_es,
                (
                    SELECT hi3.hiid_titulo
                    FROM historia_idioma hi3
                    WHERE hi3.hist_id = h.hist_id
                    AND hi3.idio_id = 3
                    LIMIT 1
                ) AS titulo_wari,
                c.cate_nome AS categoria,
                h.hist_imagem AS imagem
            FROM historia h
            INNER JOIN categoria c
                ON c.cate_id = h.cate_id
            INNER JOIN historia_idioma hi
                ON hi.hist_id = h.hist_id
                AND hi.idio_id = 1
            {$filtro}
            ORDER BY h.hist_id DESC"
        );

        if ($busca !== '') {
            $this->db->bind(':busca', '%' . $busca . '%');
        }

        $historias = $this->db->resultados();

        foreach ($historias as &$historia) {
            if (!empty($historia->imagem)) {
                $imagem = $historia->imagem;
                $informacoes = new finfo(FILEINFO_MIME_TYPE);
                $tipo = $informacoes->buffer($imagem);

                $tiposPermitidos = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];

                if (in_array($tipo, $tiposPermitidos, true)) {
                    $historia->imagem =
                        'data:' . $tipo . ';base64,' .
                        base64_encode($imagem);
                } else {
                    $historia->imagem = '';
                }
            } else {
                $historia->imagem = '';
            }
        }

        unset($historia);

        return $historias;
    }

    
    public function buscarPorId($id) {
        $this->db->query(
            "SELECT
                h.hist_id AS id,
                h.hist_imagem AS imagem,
                h.cate_id AS categoria_id,
                c.cate_nome AS categoria
            FROM historia h
            INNER JOIN categoria c
                ON c.cate_id = h.cate_id
            WHERE h.hist_id = :id
            LIMIT 1"
        );

        $this->db->bind(':id', (int) $id, PDO::PARAM_INT);

        $historia = $this->db->resultado();

        if (!$historia) {
            return false;
        }

        if (!empty($historia->imagem)) {
            $informacoes = new finfo(FILEINFO_MIME_TYPE);
            $tipo = $informacoes->buffer($historia->imagem);

            $tiposPermitidos = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (in_array($tipo, $tiposPermitidos, true)) {
                $historia->imagem_uri =
                    'data:' . $tipo . ';base64,' .
                    base64_encode($historia->imagem);
            } else {
                $historia->imagem_uri = '';
            }
        } else {
            $historia->imagem_uri = '';
        }

        $this->db->query(
            "SELECT
                idio_id AS idioma_id,
                hiid_titulo AS titulo,
                hiid_legenda_imagem AS legenda,
                hiid_texto AS texto
            FROM historia_idioma
            WHERE hist_id = :id
            ORDER BY idio_id"
        );

        $this->db->bind(':id', (int) $id, PDO::PARAM_INT);

        $idiomas = $this->db->resultados();

        $historia->idiomas = [];

        foreach ($idiomas as $idioma) {
            $historia->idiomas[(int) $idioma->idioma_id] = $idioma;
        }

        return $historia;
    }

    public function salvarEdicao($id, $categoria, $imagem, $idiomas) {
        try {
            $this->db->iniciarTransacao();

            if ($imagem !== null) {
                $this->db->query(
                    "UPDATE historia
                    SET cate_id = :categoria,
                        hist_imagem = :imagem
                    WHERE hist_id = :id"
                );

                $this->db->bind(':categoria', (int) $categoria, PDO::PARAM_INT);
                $this->db->bind(':imagem', $imagem, PDO::PARAM_LOB);
                $this->db->bind(':id', (int) $id, PDO::PARAM_INT);
            } else {
                $this->db->query(
                    "UPDATE historia
                    SET cate_id = :categoria
                    WHERE hist_id = :id"
                );

                $this->db->bind(':categoria', (int) $categoria, PDO::PARAM_INT);
                $this->db->bind(':id', (int) $id, PDO::PARAM_INT);
            }

            $this->db->executa();

            foreach ($idiomas as $idioma) {
                $this->db->query(
                    "SELECT hist_id
                    FROM historia_idioma
                    WHERE hist_id = :historia
                    AND idio_id = :idioma
                    LIMIT 1"
                );

                $this->db->bind(':historia', (int) $id, PDO::PARAM_INT);
                $this->db->bind(':idioma', (int) $idioma['id'], PDO::PARAM_INT);

                $existe = $this->db->resultado();

                if ($existe) {
                    $this->db->query(
                        "UPDATE historia_idioma
                        SET hiid_titulo = :titulo,
                            hiid_legenda_imagem = :legenda,
                            hiid_texto = :texto
                        WHERE hist_id = :historia
                        AND idio_id = :idioma"
                    );
                } else {
                    $this->db->query(
                        "INSERT INTO historia_idioma
                        (hiid_titulo, hiid_legenda_imagem, hiid_texto, hist_id, idio_id)
                        VALUES
                        (:titulo, :legenda, :texto, :historia, :idioma)"
                    );
                }

                $this->db->bind(':titulo', $idioma['titulo']);
                $this->db->bind(':legenda', $idioma['legenda']);
                $this->db->bind(':texto', $idioma['texto']);
                $this->db->bind(':historia', (int) $id, PDO::PARAM_INT);
                $this->db->bind(':idioma', (int) $idioma['id'], PDO::PARAM_INT);

                $this->db->executa();
            }

            $this->db->confirmarTransacao();

            return true;
            } catch (Throwable $erro) {
                $this->db->cancelarTransacao();

                error_log('Erro ao editar história: ' . $erro->getMessage());

                return false;
            }
        }

    // Título em português (usado nas notificações).
    public function tituloPorId($id) {
        $this->db->query(
            "SELECT hiid_titulo FROM historia_idioma
             WHERE hist_id = :id
             ORDER BY idio_id
             LIMIT 1"
        );
        $this->db->bind(':id', (int) $id, PDO::PARAM_INT);
        $linha = $this->db->resultado();
        return $linha ? $linha->hiid_titulo : '';
    }

    public function ultimoIdCadastrado() {
        return $this->ultimoId;
    }

    // Exclui a história. Traduções, comentários, avaliações e vínculos de

public function excluir($id)
    {
        $id = (int) $id;

        if ($id < 1) {
            return false;
        }

        try {
            $this->db->iniciarTransacao();

            // Confirma que a história existe antes de iniciar a remoção.
            $this->db->query(
                "SELECT hist_id FROM historia WHERE hist_id = :id LIMIT 1"
            );
            $this->db->bind(':id', $id, PDO::PARAM_INT);
            $historia = $this->db->resultado();

            if (!$historia) {
                $this->db->confirmarTransacao();
                error_log('Exclusão de história cancelada: ID inexistente ' . $id);
                return false;
            }

            // Remove dependências explicitamente para funcionar também em bancos
            // que ainda não tenham ON DELETE CASCADE configurado.
            $this->db->query("DELETE FROM comentario WHERE hist_id = :id");
            $this->db->bind(':id', $id, PDO::PARAM_INT);
            $this->db->executa();

            $this->db->query("DELETE FROM avaliacao WHERE hist_id = :id");
            $this->db->bind(':id', $id, PDO::PARAM_INT);
            $this->db->executa();

            // Reúne as notificações vinculadas à história. Os comentários e as
            // avaliações foram removidos antes para incluir possíveis vínculos
            // criados por triggers durante essas operações.
            $this->db->query(
                "SELECT noti_id FROM historia_notificacao WHERE hist_id = :id"
            );
            $this->db->bind(':id', $id, PDO::PARAM_INT);
            $notificacoes = $this->db->resultados();

            foreach ($notificacoes as $notificacao) {
                $notiId = (int) $notificacao->noti_id;

                $this->db->query(
                    "DELETE FROM historia_notificacao WHERE hist_id = :id AND noti_id = :noti_id"
                );
                $this->db->bind(':id', $id, PDO::PARAM_INT);
                $this->db->bind(':noti_id', $notiId, PDO::PARAM_INT);
                $this->db->executa();

                // Preserva notificações que também estejam ligadas a outra história.
                $this->db->query(
                    "SELECT noti_id FROM historia_notificacao WHERE noti_id = :noti_id LIMIT 1"
                );
                $this->db->bind(':noti_id', $notiId, PDO::PARAM_INT);
                $aindaVinculada = $this->db->resultado();

                if (!$aindaVinculada) {
                    $this->db->query(
                        "DELETE FROM pessoa_notificacao WHERE noti_id = :noti_id"
                    );
                    $this->db->bind(':noti_id', $notiId, PDO::PARAM_INT);
                    $this->db->executa();

                    $this->db->query(
                        "DELETE FROM notificacao WHERE noti_id = :noti_id"
                    );
                    $this->db->bind(':noti_id', $notiId, PDO::PARAM_INT);
                    $this->db->executa();
                }
            }

            $this->db->query("DELETE FROM historia_idioma WHERE hist_id = :id");
            $this->db->bind(':id', $id, PDO::PARAM_INT);
            $this->db->executa();

            $this->db->query("DELETE FROM historia WHERE hist_id = :id");
            $this->db->bind(':id', $id, PDO::PARAM_INT);
            $this->db->executa();
            $excluida = $this->db->totalResultados() > 0;

            if (!$excluida) {
                throw new RuntimeException('A exclusão não removeu nenhuma linha da tabela historia. ID: ' . $id);
            }

            $this->db->confirmarTransacao();
            return true;
        } catch (Throwable $erro) {
            $this->db->cancelarTransacao();
            error_log('Erro ao excluir história ID ' . $id . ': ' . $erro->getMessage());
            return false;
        }
    }

}