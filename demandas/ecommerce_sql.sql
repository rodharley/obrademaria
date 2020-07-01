SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

ALTER TABLE `ag_cliente` CHANGE `estadoCivil` `estadoCivil` INT(11) NULL; 
ALTER TABLE `ag_cliente` CHANGE `bairro` `bairro` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `telefoneResidencial` `telefoneResidencial` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `telefoneComercial` `telefoneComercial` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `celular` `celular` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `fax` `fax` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `rg` `rg` INT(20) NULL; 
ALTER TABLE `ag_cliente` CHANGE `orgaoEmissorRg` `orgaoEmissorRg` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `passaporte` `passaporte` VARCHAR(30) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `dataEmissaoPassaporte` `dataEmissaoPassaporte` DATE NULL; 
ALTER TABLE `ag_cliente` CHANGE `dataValidadePassaporte` `dataValidadePassaporte` DATE NULL; 
ALTER TABLE `ag_cliente` CHANGE `orgaoExpedidorPassaporte` `orgaoExpedidorPassaporte` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `nomeCracha` `nomeCracha` VARCHAR(40) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `tamanhoCamisa` `tamanhoCamisa` VARCHAR(3) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `problemasSaude` `problemasSaude` TEXT CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `restricaoAlimentar` `restricaoAlimentar` TEXT CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `txtpaisNascimento` `txtpaisNascimento` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `txtcidadeNascimento` `txtcidadeNascimento` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `txtestadoNascimento` `txtestadoNascimento` VARCHAR(40) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `txtpaisEndereco` `txtpaisEndereco` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `txtcidadeEndereco` `txtcidadeEndereco` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `txtestadoEndereco` `txtestadoEndereco` VARCHAR(40) CHARACTER SET latin1 COLLATE latin1_general_ci NULL; 
ALTER TABLE `ag_cliente` CHANGE `dataNascimento` `dataNascimento` DATE NULL; 
ALTER TABLE `ag_cliente` ADD `site` TINYINT(1) NOT NULL DEFAULT '0' AFTER `image_passaporte`; 
INSERT INTO `ag_tipopagamento` (`id`, `descricao`) VALUES (NULL, 'GerenciaNet'), (NULL, 'PayPal'); 
INSERT INTO `ag_estadocivil` (`id`, `descricao`) VALUES (NULL, 'Não Especificado'); 
DROP TABLE IF EXISTS `ag_gerencianet`;
CREATE TABLE IF NOT EXISTS `ag_gerencianet` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `charge_id` bigint(20) DEFAULT NULL,
  `status` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `message` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `idParticipante` int(11) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `payment_url` varchar(500) COLLATE latin1_general_ci DEFAULT NULL,
  `payment_method` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `token` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `fk_participante_gerencianet` (`idParticipante`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
--25/06/2020
ALTER TABLE `ag_gerencianet`
  ADD CONSTRAINT `fk_participante_gerencianet` FOREIGN KEY (`idParticipante`) REFERENCES `ag_participante` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
  ALTER TABLE `ag_gerencianet` DROP FOREIGN KEY `fk_participante_gerencianet`; ALTER TABLE `ag_gerencianet` ADD CONSTRAINT `fk_participante_gerencianet` FOREIGN KEY (`idParticipante`) REFERENCES `ag_participante`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; 
  ALTER TABLE `ag_gerencianet` ADD `idAcompanhante1` INT NULL AFTER `token`, ADD `idAcompanhante2` INT NULL AFTER `idAcompanhante1`, ADD `idAcompanhante3` INT NULL AFTER `idAcompanhante2`, ADD `idAcompanhante4` INT NULL AFTER `idAcompanhante3`; 
  INSERT INTO `ag_agendamento` (`id`, `descricao`, `data`, `destinatarios`) VALUES (NULL, 'Cotacao do Dia', '2020-06-25', '1.00'); 
  ALTER TABLE `ag_gerencianet` ADD `cotacao` DECIMAL(10,2) NOT NULL AFTER `idAcompanhante4`; 
COMMIT;
--01/07/2020
ALTER TABLE `ag_grupo` ADD `cotacao_a_vista` DECIMAL(10,2) NOT NULL DEFAULT '1.00' AFTER `modeloFicha`, ADD `cotacao_entrada` DECIMAL(10,2) NOT NULL DEFAULT '1.00' AFTER `cotacao_a_vista`, ADD `cotacao_parcelado` DECIMAL(10,2) NOT NULL DEFAULT '1.00' AFTER `cotacao_entrada`; 
ALTER TABLE `ag_grupo` ADD `imagem_destaque` VARCHAR(200) NULL AFTER `cotacao_parcelado`; 
