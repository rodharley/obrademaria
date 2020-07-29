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