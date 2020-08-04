-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 28-Jul-2020 às 15:36
-- Versão do servidor: 8.0.18
-- versão do PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
DROP TABLE IF EXISTS `ag_roteiro`;
CREATE TABLE IF NOT EXISTS `ag_roteiro` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `grupo` int(11) NOT NULL,
  `card_image` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `card_title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `card_value` decimal(10,2) NOT NULL,
  `card_description` varchar(500) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_roteiro_grupo` (`grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
DROP TABLE IF EXISTS `ag_slide`;
CREATE TABLE IF NOT EXISTS `ag_slide` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `roteiro` bigint(20) NOT NULL,
  `image` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `sub_title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `buttom_text` varchar(50) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_slide_roteiro` (`roteiro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
ALTER TABLE `ag_slide`
  ADD CONSTRAINT `fk_slide_roteiro` FOREIGN KEY (`roteiro`) REFERENCES `ag_roteiro` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `ag_roteiro`
  ADD CONSTRAINT `fk_roteiro_grupo` FOREIGN KEY (`grupo`) REFERENCES `ag_grupo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `ag_roteiro` ADD `continent` VARCHAR(20) NOT NULL AFTER `card_description`, ADD INDEX `idx_continente` (`continent`); 
ALTER TABLE `ag_roteiro` ADD `likes` INT NOT NULL AFTER `continent`; 
ALTER TABLE `ag_roteiro` CHANGE `likes` `likes` INT(11) NOT NULL DEFAULT '0'; 
ALTER TABLE `ag_roteiro` DROP `card_value`;
ALTER TABLE `ag_roteiro` ADD `unlikes` INT NOT NULL DEFAULT '0' AFTER `likes`; 
COMMIT;
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
ALTER TABLE `ag_roteiro` ADD `image` VARCHAR(255) NULL AFTER `unlikes`, ADD `title` VARCHAR(255) NULL AFTER `image`, ADD `description` TEXT NULL AFTER `title`; 
COMMIT;


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Banco de dados: `obrademariadf1`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ag_etinerario`
--

DROP TABLE IF EXISTS `ag_etinerario`;
CREATE TABLE IF NOT EXISTS `ag_etinerario` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `roteiro` bigint(20) NOT NULL,
  `ordem` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_etinerario_roteiro` (`roteiro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ag_foto`
--

DROP TABLE IF EXISTS `ag_foto`;
CREATE TABLE IF NOT EXISTS `ag_foto` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `roteiro` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_foto_roteiro` (`roteiro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ag_review`
--

DROP TABLE IF EXISTS `ag_review`;
CREATE TABLE IF NOT EXISTS `ag_review` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `review` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `date` datetime NOT NULL,
  `cliente` int(11) NOT NULL,
  `roteiro` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_review_roteiro` (`roteiro`),
  KEY `fk_review_cliente` (`cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ag_video`
--

DROP TABLE IF EXISTS `ag_video`;
CREATE TABLE IF NOT EXISTS `ag_video` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `roteiro` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_video_roteiro` (`roteiro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Restri��es para despejos de tabelas
--

--
-- Limitadores para a tabela `ag_etinerario`
--
ALTER TABLE `ag_etinerario`
  ADD CONSTRAINT `fk_etinerario_roteiro` FOREIGN KEY (`roteiro`) REFERENCES `ag_roteiro` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `ag_foto`
--
ALTER TABLE `ag_foto`
  ADD CONSTRAINT `fk_foto_roteiro` FOREIGN KEY (`roteiro`) REFERENCES `ag_roteiro` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `ag_review`
--
ALTER TABLE `ag_review`
  ADD CONSTRAINT `fk_review_cliente` FOREIGN KEY (`cliente`) REFERENCES `ag_cliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_review_roteiro` FOREIGN KEY (`roteiro`) REFERENCES `ag_roteiro` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `ag_video`
--
ALTER TABLE `ag_video`
  ADD CONSTRAINT `fk_video_roteiro` FOREIGN KEY (`roteiro`) REFERENCES `ag_roteiro` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

ALTER TABLE `ag_grupo` ADD `local` VARCHAR(150) NULL AFTER `desconto_avista`, ADD `idade_minima` INT NULL AFTER `local`, ADD `max_pessoa` INT NULL AFTER `idade_minima`, ADD `duracao` INT NULL AFTER `max_pessoa`;
ALTER TABLE `ag_review` ADD `name` VARCHAR(150) NOT NULL AFTER `roteiro`, ADD `email` VARCHAR(150) NOT NULL AFTER `name`; 
ALTER TABLE `ag_review` CHANGE `cliente` `cliente` INT(11) NULL; 
ALTER TABLE `ag_roteiro` ADD `countDown` TINYINT NOT NULL DEFAULT '0' AFTER `description`; 
ALTER TABLE `ag_roteiro` CHANGE `continent` `continent` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL; 