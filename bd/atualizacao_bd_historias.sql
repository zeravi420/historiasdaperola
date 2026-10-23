-- =====================================================================
-- ATUALIZAÇÃO do banco bd_historias (mantém os dados já cadastrados)
-- Use este arquivo se o banco já existe no seu XAMPP com histórias.
-- Para começar do zero, use bd/bd_historias.sql.
-- Requer MariaDB (XAMPP). Pode ser executado mais de uma vez.
-- =====================================================================

SET NAMES utf8mb4;
USE `bd_historias`;

-- Imagens de até 16 MB (antes: 64 KB)
ALTER TABLE `historia` MODIFY `hist_imagem` mediumblob NOT NULL;
ALTER TABLE `pessoa`   MODIFY `pess_foto_perfil` mediumblob DEFAULT NULL;

-- E-mail único (login)
ALTER TABLE `email` ADD UNIQUE KEY IF NOT EXISTS `uq_email_nome` (`emai_nome`);

-- Uma avaliação por pessoa em cada história (remove duplicadas, se houver)
DELETE a1 FROM `avaliacao` a1
INNER JOIN `avaliacao` a2
    ON a1.hist_id = a2.hist_id AND a1.pess_id = a2.pess_id AND a1.avai_id < a2.avai_id;
ALTER TABLE `avaliacao` ADD UNIQUE KEY IF NOT EXISTS `uq_avaliacao_historia_pessoa` (`hist_id`, `pess_id`);

-- Chaves estrangeiras com ON DELETE CASCADE
ALTER TABLE `email` DROP FOREIGN KEY IF EXISTS `fk_email_pessoa`;
ALTER TABLE `email` ADD CONSTRAINT `fk_email_pessoa` FOREIGN KEY (`pess_id`) REFERENCES `pessoa` (`pess_id`) ON DELETE CASCADE;

ALTER TABLE `historia_idioma` DROP FOREIGN KEY IF EXISTS `fk_historia_idioma_historia`;
ALTER TABLE `historia_idioma` ADD CONSTRAINT `fk_historia_idioma_historia` FOREIGN KEY (`hist_id`) REFERENCES `historia` (`hist_id`) ON DELETE CASCADE;

ALTER TABLE `comentario` DROP FOREIGN KEY IF EXISTS `fk_comentario_historia`;
ALTER TABLE `comentario` DROP FOREIGN KEY IF EXISTS `fk_comentario_pessoa`;
ALTER TABLE `comentario` ADD CONSTRAINT `fk_comentario_historia` FOREIGN KEY (`hist_id`) REFERENCES `historia` (`hist_id`) ON DELETE CASCADE;
ALTER TABLE `comentario` ADD CONSTRAINT `fk_comentario_pessoa` FOREIGN KEY (`pess_id`) REFERENCES `pessoa` (`pess_id`) ON DELETE CASCADE;

ALTER TABLE `avaliacao` DROP FOREIGN KEY IF EXISTS `fk_avaliacao_historia`;
ALTER TABLE `avaliacao` DROP FOREIGN KEY IF EXISTS `fk_avaliacao_pessoa`;
ALTER TABLE `avaliacao` ADD CONSTRAINT `fk_avaliacao_historia` FOREIGN KEY (`hist_id`) REFERENCES `historia` (`hist_id`) ON DELETE CASCADE;
ALTER TABLE `avaliacao` ADD CONSTRAINT `fk_avaliacao_pessoa` FOREIGN KEY (`pess_id`) REFERENCES `pessoa` (`pess_id`) ON DELETE CASCADE;

ALTER TABLE `historia_notificacao` DROP FOREIGN KEY IF EXISTS `fk_historia_notificacao_historia`;
ALTER TABLE `historia_notificacao` DROP FOREIGN KEY IF EXISTS `fk_historia_notificacao_notificacao`;
ALTER TABLE `historia_notificacao` ADD CONSTRAINT `fk_historia_notificacao_historia` FOREIGN KEY (`hist_id`) REFERENCES `historia` (`hist_id`) ON DELETE CASCADE;
ALTER TABLE `historia_notificacao` ADD CONSTRAINT `fk_historia_notificacao_notificacao` FOREIGN KEY (`noti_id`) REFERENCES `notificacao` (`noti_id`) ON DELETE CASCADE;

ALTER TABLE `pessoa_notificacao` DROP FOREIGN KEY IF EXISTS `fk_pessoa_notificacao_notificacao`;
ALTER TABLE `pessoa_notificacao` DROP FOREIGN KEY IF EXISTS `fk_pessoa_notificacao_pessoa`;
ALTER TABLE `pessoa_notificacao` ADD CONSTRAINT `fk_pessoa_notificacao_notificacao` FOREIGN KEY (`noti_id`) REFERENCES `notificacao` (`noti_id`) ON DELETE CASCADE;
ALTER TABLE `pessoa_notificacao` ADD CONSTRAINT `fk_pessoa_notificacao_pessoa` FOREIGN KEY (`pess_id`) REFERENCES `pessoa` (`pess_id`) ON DELETE CASCADE;

-- Administrador padrão (admin@historiasdaperola.com / Admin@123), só se ainda não existir
SET @admin_existe = (SELECT COUNT(*) FROM `email` WHERE `emai_nome` = 'admin@historiasdaperola.com');

INSERT INTO `pessoa` (`pess_nome`, `pess_senha`, `pess_status`, `perf_id`)
SELECT 'Administrador', '$2y$10$Qi1A.MDE5gnk6riPtcLBiOvmlPxLRsOQ/9yBUp/Vj.ru0Qw.jFRa.', 'Ativo', 1
FROM DUAL WHERE @admin_existe = 0;

SET @admin_id = LAST_INSERT_ID();

INSERT INTO `email` (`emai_nome`, `pess_id`)
SELECT 'admin@historiasdaperola.com', @admin_id
FROM DUAL WHERE @admin_existe = 0;

-- Trigger de avaliações
DROP TRIGGER IF EXISTS `tg_avaliacao_notificacao_pos_ins`;

DELIMITER $$
CREATE TRIGGER `tg_avaliacao_notificacao_pos_ins` AFTER INSERT ON `avaliacao` FOR EACH ROW BEGIN
    DECLARE vn_noti_id INT DEFAULT NULL;
    DECLARE vn_total INT DEFAULT 0;
    DECLARE vs_perfil VARCHAR(100);
    DECLARE vs_titulo VARCHAR(255);

/*
DATA: 11/10/2026
AUTORA: Darliane Arza de Oliveira
OBJETIVO: Manter uma notificação de avaliações por história e atualizar a quantidade de avaliações recebidas.
*/

    SELECT MAX(pf.perf_nome)
    INTO vs_perfil
    FROM pessoa ps
    INNER JOIN perfil pf ON pf.perf_id = ps.perf_id
    WHERE ps.pess_id = NEW.pess_id;

    IF vs_perfil = 'Usuário' THEN

        SELECT COUNT(*)
        INTO vn_total
        FROM avaliacao
        WHERE hist_id = NEW.hist_id;

        SELECT hiid_titulo
        INTO vs_titulo
        FROM historia_idioma
        WHERE hist_id = NEW.hist_id
        ORDER BY idio_id
        LIMIT 1;

        SELECT MAX(n.noti_id)
        INTO vn_noti_id
        FROM notificacao n
        INNER JOIN historia_notificacao hn ON hn.noti_id = n.noti_id
        WHERE hn.hist_id = NEW.hist_id
        AND n.noti_titulo = 'Novas avaliações';

        IF vn_noti_id IS NULL THEN

            INSERT INTO notificacao (noti_titulo, noti_mensagem, noti_data_hora)
            VALUES ('Novas avaliações', CONCAT('A história ', vs_titulo, ' recebeu novas avaliações. Total de avaliações: ', vn_total, '.'), NOW());
            SET vn_noti_id = LAST_INSERT_ID();

            INSERT INTO historia_notificacao (hist_id, noti_id, hino_data_hora)
            VALUES (NEW.hist_id, vn_noti_id, NOW());

            INSERT INTO pessoa_notificacao (pess_id, noti_id, peno_lida)
            SELECT ps.pess_id, vn_noti_id, 0
            FROM pessoa ps
            INNER JOIN perfil pf ON pf.perf_id = ps.perf_id
            WHERE pf.perf_nome = 'Administrador'
            AND ps.pess_status = 'Ativo';
        ELSE
            UPDATE notificacao
            SET noti_mensagem = CONCAT('A história ', vs_titulo, ' recebeu novas avaliações. Total de avaliações: ', vn_total, '.'),
                noti_data_hora = NOW()
            WHERE noti_id = vn_noti_id;

            -- volta a marcar como não lida (e recria para quem tinha excluído)
            INSERT INTO pessoa_notificacao (pess_id, noti_id, peno_lida)
            SELECT ps.pess_id, vn_noti_id, 0
            FROM pessoa ps
            INNER JOIN perfil pf ON pf.perf_id = ps.perf_id
            WHERE pf.perf_nome = 'Administrador'
            AND ps.pess_status = 'Ativo'
            ON DUPLICATE KEY UPDATE peno_lida = 0, peno_data_hora = NOW();
        END IF;
    END IF;

END
$$
DELIMITER ;


-- Views (remove versões antigas, inclusive tabelas 'stand-in' de importações que falharam)
DROP VIEW IF EXISTS `vw_usuarios`;
DROP TABLE IF EXISTS `vw_usuarios`;
DROP VIEW IF EXISTS `vw_historias`;
DROP TABLE IF EXISTS `vw_historias`;
DROP VIEW IF EXISTS `vw_comentarios`;
DROP TABLE IF EXISTS `vw_comentarios`;
DROP VIEW IF EXISTS `vw_avaliacoes`;
DROP TABLE IF EXISTS `vw_avaliacoes`;
DROP VIEW IF EXISTS `vw_notificacoes`;
DROP TABLE IF EXISTS `vw_notificacoes`;

CREATE OR REPLACE VIEW `vw_usuarios` AS
SELECT p.pess_id, p.pess_nome, e.emai_nome, pf.perf_id, pf.perf_nome, p.pess_status
FROM pessoa p
INNER JOIN perfil pf ON pf.perf_id = p.perf_id
LEFT JOIN email e ON e.pess_id = p.pess_id;

CREATE OR REPLACE VIEW `vw_historias` AS
SELECT h.hist_id, hi.hiid_titulo, hi.hiid_legenda_imagem, hi.hiid_texto,
       i.idio_id, i.idio_nome, c.cate_nome, h.pess_id
FROM historia h
INNER JOIN historia_idioma hi ON hi.hist_id = h.hist_id
INNER JOIN idioma i ON i.idio_id = hi.idio_id
INNER JOIN categoria c ON c.cate_id = h.cate_id;

CREATE OR REPLACE VIEW `vw_comentarios` AS
SELECT c.come_id, c.come_texto, c.come_data_pessoa, p.pess_id, p.pess_nome,
       h.hist_id, hi.hiid_titulo
FROM comentario c
INNER JOIN pessoa p ON p.pess_id = c.pess_id
INNER JOIN historia h ON h.hist_id = c.hist_id
LEFT JOIN historia_idioma hi ON hi.hist_id = h.hist_id AND hi.idio_id = 1;

CREATE OR REPLACE VIEW `vw_avaliacoes` AS
SELECT a.avai_id, a.avai_nota, a.avai_data_hora, p.pess_id, p.pess_nome,
       h.hist_id, hi.hiid_titulo
FROM avaliacao a
INNER JOIN pessoa p ON p.pess_id = a.pess_id
INNER JOIN historia h ON h.hist_id = a.hist_id
LEFT JOIN historia_idioma hi ON hi.hist_id = h.hist_id AND hi.idio_id = 1;

CREATE OR REPLACE VIEW `vw_notificacoes` AS
SELECT pn.pess_id, n.noti_id, n.noti_titulo, n.noti_mensagem, n.noti_data_hora,
       pn.peno_data_hora, pn.peno_lida
FROM pessoa_notificacao pn
INNER JOIN notificacao n ON n.noti_id = pn.noti_id;
