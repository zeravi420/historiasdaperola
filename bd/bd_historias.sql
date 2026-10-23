-- =====================================================================
-- Banco de dados: bd_historias  —  Histórias na Pérola
-- Compatível com MariaDB 10.4+ (XAMPP) e MySQL 8.
--
-- ATENÇÃO: este script RECRIA o banco do zero (apaga os dados atuais).
-- Se você já tem histórias cadastradas e quer mantê-las, use o arquivo
-- bd/atualizacao_bd_historias.sql em vez deste.
--
-- Alterações em relação à versão anterior:
--   * hist_imagem e pess_foto_perfil passaram de BLOB (64 KB) para
--     MEDIUMBLOB (16 MB) — o sistema aceita imagens de até 5 MB;
--   * e-mail único (usado no login);
--   * uma avaliação por pessoa em cada história (nota de 1 a 5);
--   * ON DELETE CASCADE nas tabelas dependentes, para permitir excluir
--     histórias, pessoas e notificações sem erro de chave estrangeira;
--   * views recriadas sem DEFINER vazio (que impedia a importação);
--   * administrador padrão para o primeiro acesso.
-- =====================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

DROP DATABASE IF EXISTS `bd_historias`;
CREATE DATABASE `bd_historias` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bd_historias`;

-- ---------------------------------------------------------------------
-- Tabelas de apoio
-- ---------------------------------------------------------------------

CREATE TABLE `perfil` (
  `perf_id` int(11) NOT NULL AUTO_INCREMENT,
  `perf_nome` varchar(100) NOT NULL,
  PRIMARY KEY (`perf_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `perfil` (`perf_id`, `perf_nome`) VALUES
(1, 'Administrador'),
(2, 'Usuário');

CREATE TABLE `categoria` (
  `cate_id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_nome` varchar(50) NOT NULL,
  PRIMARY KEY (`cate_id`),
  KEY `ix_categoria_nome` (`cate_nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categoria` (`cate_id`, `cate_nome`) VALUES
(1, 'Mito'),
(2, 'Lenda');

CREATE TABLE `idioma` (
  `idio_id` int(11) NOT NULL AUTO_INCREMENT,
  `idio_nome` varchar(50) NOT NULL,
  PRIMARY KEY (`idio_id`),
  KEY `ix_idioma_nome` (`idio_nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `idioma` (`idio_id`, `idio_nome`) VALUES
(1, 'Português'),
(2, 'Espanhol'),
(3, 'Wari'' Oro Nao');

-- ---------------------------------------------------------------------
-- Pessoas
-- ---------------------------------------------------------------------

CREATE TABLE `pessoa` (
  `pess_id` int(11) NOT NULL AUTO_INCREMENT,
  `pess_nome` varchar(100) NOT NULL,
  `pess_senha` varchar(200) NOT NULL,
  `pess_foto_perfil` mediumblob DEFAULT NULL,
  `pess_status` enum('Ativo','Inativo') NOT NULL DEFAULT 'Ativo',
  `perf_id` int(11) NOT NULL,
  PRIMARY KEY (`pess_id`),
  KEY `ix_pessoa_perfil` (`perf_id`),
  KEY `ix_pessoa_status` (`pess_status`),
  CONSTRAINT `fk_pessoa_perfil` FOREIGN KEY (`perf_id`) REFERENCES `perfil` (`perf_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Administrador padrão: admin@historiasdaperola.com / Admin@123
-- (troque a senha depois do primeiro acesso em Perfil > Alterar senha)
INSERT INTO `pessoa` (`pess_id`, `pess_nome`, `pess_senha`, `pess_foto_perfil`, `pess_status`, `perf_id`) VALUES
(1, 'Administrador', '$2y$10$Qi1A.MDE5gnk6riPtcLBiOvmlPxLRsOQ/9yBUp/Vj.ru0Qw.jFRa.', NULL, 'Ativo', 1),
(2, 'Darliane', '$2y$10$0zp.xUWt00OI.sPVUwIubeZSqCM8FnbRu/XZacsnlSnViu66xIIEm', NULL, 'Ativo', 1);

CREATE TABLE `email` (
  `emai_id` int(11) NOT NULL AUTO_INCREMENT,
  `emai_nome` varchar(200) NOT NULL,
  `pess_id` int(11) NOT NULL,
  PRIMARY KEY (`emai_id`),
  UNIQUE KEY `uq_email_nome` (`emai_nome`),
  KEY `ix_email_pessoa` (`pess_id`),
  CONSTRAINT `fk_email_pessoa` FOREIGN KEY (`pess_id`) REFERENCES `pessoa` (`pess_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `email` (`emai_id`, `emai_nome`, `pess_id`) VALUES
(1, 'admin@historiasdaperola.com', 1),
(2, 'darlianearza27@gmail.com', 2);

-- ---------------------------------------------------------------------
-- Histórias
-- ---------------------------------------------------------------------

CREATE TABLE `historia` (
  `hist_id` int(11) NOT NULL AUTO_INCREMENT,
  `hist_imagem` mediumblob NOT NULL,
  `pess_id` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL,
  PRIMARY KEY (`hist_id`),
  KEY `ix_historia_pessoa` (`pess_id`),
  KEY `ix_historia_categoria` (`cate_id`),
  CONSTRAINT `fk_historia_categoria` FOREIGN KEY (`cate_id`) REFERENCES `categoria` (`cate_id`),
  CONSTRAINT `fk_historia_pessoa` FOREIGN KEY (`pess_id`) REFERENCES `pessoa` (`pess_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `historia_idioma` (
  `hiid_titulo` varchar(100) NOT NULL,
  `hiid_legenda_imagem` varchar(100) NOT NULL,
  `hiid_texto` text NOT NULL,
  `hist_id` int(11) NOT NULL,
  `idio_id` int(11) NOT NULL,
  PRIMARY KEY (`hist_id`, `idio_id`),
  KEY `ix_historia_idioma_titulo` (`hiid_titulo`),
  KEY `ix_historia_idioma_idioma` (`idio_id`),
  CONSTRAINT `fk_historia_idioma_historia` FOREIGN KEY (`hist_id`) REFERENCES `historia` (`hist_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_historia_idioma_idioma` FOREIGN KEY (`idio_id`) REFERENCES `idioma` (`idio_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `comentario` (
  `come_id` int(11) NOT NULL AUTO_INCREMENT,
  `come_texto` text DEFAULT NULL,
  `come_data_pessoa` timestamp NOT NULL DEFAULT current_timestamp(),
  `pess_id` int(11) NOT NULL,
  `hist_id` int(11) NOT NULL,
  PRIMARY KEY (`come_id`),
  KEY `ix_comentario_pessoa` (`pess_id`),
  KEY `ix_comentario_historia` (`hist_id`),
  CONSTRAINT `fk_comentario_historia` FOREIGN KEY (`hist_id`) REFERENCES `historia` (`hist_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_comentario_pessoa` FOREIGN KEY (`pess_id`) REFERENCES `pessoa` (`pess_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `avaliacao` (
  `avai_id` int(11) NOT NULL AUTO_INCREMENT,
  `avai_data_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `avai_nota` int(11) DEFAULT NULL,
  `hist_id` int(11) NOT NULL,
  `pess_id` int(11) NOT NULL,
  PRIMARY KEY (`avai_id`),
  UNIQUE KEY `uq_avaliacao_historia_pessoa` (`hist_id`, `pess_id`),
  KEY `ix_avaliacao_historia` (`hist_id`),
  KEY `ix_avaliacao_pessoa` (`pess_id`),
  CONSTRAINT `fk_avaliacao_historia` FOREIGN KEY (`hist_id`) REFERENCES `historia` (`hist_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_avaliacao_pessoa` FOREIGN KEY (`pess_id`) REFERENCES `pessoa` (`pess_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Notificações
-- ---------------------------------------------------------------------

CREATE TABLE `notificacao` (
  `noti_id` int(11) NOT NULL AUTO_INCREMENT,
  `noti_mensagem` text NOT NULL,
  `noti_data_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `noti_titulo` varchar(100) NOT NULL,
  PRIMARY KEY (`noti_id`),
  KEY `ix_notificacao_titulo` (`noti_titulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `historia_notificacao` (
  `hino_data_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `hist_id` int(11) NOT NULL,
  `noti_id` int(11) NOT NULL,
  PRIMARY KEY (`hist_id`, `noti_id`),
  KEY `ix_historia_notificacao_notificacao` (`noti_id`),
  CONSTRAINT `fk_historia_notificacao_historia` FOREIGN KEY (`hist_id`) REFERENCES `historia` (`hist_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_historia_notificacao_notificacao` FOREIGN KEY (`noti_id`) REFERENCES `notificacao` (`noti_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pessoa_notificacao` (
  `peno_data_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `pess_id` int(11) NOT NULL,
  `noti_id` int(11) NOT NULL,
  `peno_lida` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`pess_id`, `noti_id`),
  KEY `ix_pessoa_notificacao_notificacao` (`noti_id`),
  CONSTRAINT `fk_pessoa_notificacao_notificacao` FOREIGN KEY (`noti_id`) REFERENCES `notificacao` (`noti_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pessoa_notificacao_pessoa` FOREIGN KEY (`pess_id`) REFERENCES `pessoa` (`pess_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Trigger: notificação de avaliações para os administradores
-- ---------------------------------------------------------------------

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

-- ---------------------------------------------------------------------
-- Views
-- ---------------------------------------------------------------------

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
