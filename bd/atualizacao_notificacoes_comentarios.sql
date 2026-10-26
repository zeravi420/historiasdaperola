-- Atualização segura para o banco bd_historias existente.
-- Não apaga histórias, usuários, comentários ou avaliações.
-- Execute com o banco bd_historias selecionado no phpMyAdmin.

USE bd_historias;

-- Relaciona cada comentário à notificação criada para ele.
-- Não usa chave estrangeira para que o vínculo sobreviva até o gatilho AFTER DELETE.
CREATE TABLE IF NOT EXISTS comentario_notificacao (
    come_id INT NOT NULL PRIMARY KEY,
    noti_id INT NOT NULL
);

-- Gatilho: notificar administradores sobre novo comentário, incluindo o texto completo.
DELIMITER $$
DROP TRIGGER IF EXISTS tg_comentario_notificacao_pos_ins$$
CREATE TRIGGER tg_comentario_notificacao_pos_ins
AFTER INSERT ON comentario
FOR EACH ROW
BEGIN
    DECLARE vs_perfil VARCHAR(100);
    DECLARE vs_nome VARCHAR(100);
    DECLARE vs_titulo VARCHAR(100);
    DECLARE vn_noti_id INT;

    SELECT MAX(pf.perf_nome), MAX(ps.pess_nome)
    INTO vs_perfil, vs_nome
    FROM pessoa ps
    INNER JOIN perfil pf ON pf.perf_id = ps.perf_id
    WHERE ps.pess_id = NEW.pess_id;

    IF vs_perfil = 'Usuário' THEN
        SELECT hiid_titulo INTO vs_titulo
        FROM historia_idioma
        WHERE hist_id = NEW.hist_id
        ORDER BY (idio_id = 1) DESC, idio_id
        LIMIT 1;

        INSERT INTO notificacao (noti_titulo, noti_mensagem, noti_data_hora)
        VALUES ('Novo comentário', CONCAT('O usuário ', vs_nome, ' comentou na história "', vs_titulo, '". Comentário: ', NEW.come_texto), NOW());
        SET vn_noti_id = LAST_INSERT_ID();

        INSERT INTO historia_notificacao (hist_id, noti_id, hino_data_hora)
        VALUES (NEW.hist_id, vn_noti_id, NOW());

        INSERT INTO comentario_notificacao (come_id, noti_id)
        VALUES (NEW.come_id, vn_noti_id);

        INSERT INTO pessoa_notificacao (pess_id, noti_id, peno_lida)
        SELECT ps.pess_id, vn_noti_id, 0
        FROM pessoa ps
        INNER JOIN perfil pf ON pf.perf_id = ps.perf_id
        WHERE pf.perf_nome = 'Administrador' AND ps.pess_status = 'Ativo';
    END IF;
END$$
DELIMITER ;

-- Gatilho: ao excluir um comentário, remove a notificação desse comentário.
-- Se a exclusão foi feita por um administrador, também avisa o autor.
DELIMITER $$
DROP TRIGGER IF EXISTS tg_comentario_notificacao_pos_del$$
CREATE TRIGGER tg_comentario_notificacao_pos_del
AFTER DELETE ON comentario
FOR EACH ROW
BEGIN
    DECLARE vn_noti_id INT DEFAULT NULL;
    DECLARE vs_perfil VARCHAR(100) DEFAULT NULL;
    DECLARE vs_titulo VARCHAR(100) DEFAULT NULL;

    SELECT MAX(pf.perf_nome) INTO vs_perfil
    FROM pessoa ps
    INNER JOIN perfil pf ON pf.perf_id = ps.perf_id
    WHERE ps.pess_id = @user_pess_id;

    -- Recupera a notificação específica criada para esse comentário.
    SELECT MAX(cn.noti_id) INTO vn_noti_id
    FROM comentario_notificacao cn
    WHERE cn.come_id = OLD.come_id;

    -- Compatibilidade com notificações antigas, criadas antes da tabela de vínculo.
    IF vn_noti_id IS NULL THEN
        SELECT MAX(n.noti_id) INTO vn_noti_id
        FROM notificacao n
        INNER JOIN historia_notificacao hn ON hn.noti_id = n.noti_id
        WHERE hn.hist_id = OLD.hist_id
          AND n.noti_titulo = 'Novo comentário'
          AND RIGHT(n.noti_mensagem, CHAR_LENGTH(CONCAT('Comentário: ', OLD.come_texto))) = CONCAT('Comentário: ', OLD.come_texto);
    END IF;

    -- A notificação do comentário removido não deve continuar na lista dos administradores.
    IF vn_noti_id IS NOT NULL THEN
        DELETE FROM pessoa_notificacao WHERE noti_id = vn_noti_id;
        DELETE FROM historia_notificacao WHERE noti_id = vn_noti_id;
        DELETE FROM comentario_notificacao WHERE come_id = OLD.come_id;
        DELETE FROM notificacao WHERE noti_id = vn_noti_id;
    END IF;

    -- Só avisa o autor quando quem excluiu foi um administrador.
    IF vs_perfil = 'Administrador' THEN
        SELECT hiid_titulo INTO vs_titulo
        FROM historia_idioma
        WHERE hist_id = OLD.hist_id
        ORDER BY (idio_id = 1) DESC, idio_id
        LIMIT 1;

        INSERT INTO notificacao (noti_titulo, noti_mensagem, noti_data_hora)
        VALUES ('Comentário excluído', CONCAT('Seu comentário na história "', vs_titulo, '" foi excluído pelo administrador.'), NOW());
        SET vn_noti_id = LAST_INSERT_ID();

        INSERT INTO historia_notificacao (hist_id, noti_id, hino_data_hora)
        VALUES (OLD.hist_id, vn_noti_id, NOW());

        INSERT INTO pessoa_notificacao (pess_id, noti_id, peno_lida)
        VALUES (OLD.pess_id, vn_noti_id, 0);
    END IF;
END$$
DELIMITER ;
