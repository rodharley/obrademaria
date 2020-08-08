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
ALTER TABLE `ag_gerencianet`
  ADD CONSTRAINT `fk_participante_gerencianet` FOREIGN KEY (`idParticipante`) REFERENCES `ag_participante` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
  ALTER TABLE `ag_gerencianet` DROP FOREIGN KEY `fk_participante_gerencianet`; ALTER TABLE `ag_gerencianet` ADD CONSTRAINT `fk_participante_gerencianet` FOREIGN KEY (`idParticipante`) REFERENCES `ag_participante`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; 
  ALTER TABLE `ag_gerencianet` ADD `idAcompanhante1` INT NULL AFTER `token`, ADD `idAcompanhante2` INT NULL AFTER `idAcompanhante1`, ADD `idAcompanhante3` INT NULL AFTER `idAcompanhante2`, ADD `idAcompanhante4` INT NULL AFTER `idAcompanhante3`; 
  INSERT INTO `ag_agendamento` (`id`, `descricao`, `data`, `destinatarios`) VALUES (NULL, 'Cotacao do Dia', '2020-06-25', '1.00'); 
  ALTER TABLE `ag_gerencianet` ADD `cotacao` DECIMAL(10,2) NOT NULL AFTER `idAcompanhante4`; 
ALTER TABLE `ag_grupo` ADD `cotacao_a_vista` DECIMAL(10,2) NOT NULL DEFAULT '1.00' AFTER `modeloFicha`, ADD `cotacao_entrada` DECIMAL(10,2) NOT NULL DEFAULT '1.00' AFTER `cotacao_a_vista`, ADD `cotacao_parcelado` DECIMAL(10,2) NOT NULL DEFAULT '1.00' AFTER `cotacao_entrada`; 
ALTER TABLE `ag_grupo` ADD `imagem_destaque` VARCHAR(200) NULL AFTER `cotacao_parcelado`; 
ALTER TABLE `ag_pagamento` ADD `site` TINYINT(1) NOT NULL DEFAULT '0' AFTER `valorParcela`; 
ALTER TABLE `ag_participante` ADD `site` TINYINT(1) NOT NULL DEFAULT '0' AFTER `idcn`; 
ALTER TABLE `ag_pagamento` ADD `pago` TINYINT(1) NOT NULL DEFAULT '1' AFTER `site`; 
CREATE TABLE IF NOT EXISTS `ag_venda_site` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_participante` int(11) NOT NULL,
  `id_acompanhante1` int(11) DEFAULT NULL,
  `id_acompanhante2` int(11) DEFAULT NULL,
  `id_acompanhante3` int(11) DEFAULT NULL,
  `id_acompanhante4` int(11) DEFAULT NULL,
  `create_at` datetime NOT NULL,
  `cotacao` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
ALTER TABLE `ag_venda_site` ADD CONSTRAINT `fk_participante_venda_site` FOREIGN KEY (`id_participante`) REFERENCES `ag_participante`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; 
ALTER TABLE `ag_gerencianet` CHANGE `idParticipante` `id_participante` INT(11) NOT NULL; 
ALTER TABLE `ag_gerencianet` DROP `cotacao`, DROP `idAcompanhante1`, DROP `idAcompanhante2`, DROP `idAcompanhante3`, DROP `idAcompanhante4`; 
ALTER TABLE `ag_gerencianet` ADD `id_venda_site` BIGINT NOT NULL AFTER `token`; 
TRUNCATE `ag_gerencianet`;
ALTER TABLE `ag_gerencianet` ADD CONSTRAINT `fk_venda_site_gerencianet` FOREIGN KEY (`id_venda_site`) REFERENCES `ag_venda_site`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; 
ALTER TABLE `ag_venda_site` ADD `opcional` TINYINT(1) NOT NULL DEFAULT '0' AFTER `cotacao`, ADD `quantidade` TINYINT(1) NOT NULL DEFAULT '1' AFTER `opcional`, ADD `forma_pagamento` VARCHAR(50) NOT NULL AFTER `quantidade`, ADD `tipo_pagamento1` VARCHAR(50) NOT NULL AFTER `forma_pagamento`, ADD `tipo_pagamento2` VARCHAR(50) NULL AFTER `tipo_pagamento1`; 
ALTER TABLE `ag_venda_site` ADD `total` DECIMAL(10,2) NOT NULL AFTER `tipo_pagamento2`; 
INSERT INTO `ag_menu` (`id`, `idMenuPai`, `descricao`, `url`) VALUES ('', '2', 'Compras Web', 'web.filtro.php'); 
ALTER TABLE `ag_grupo` ADD `desconto_avista` INT(3) NOT NULL DEFAULT '0' AFTER `imagem_destaque`; 
ALTER TABLE `ag_venda_site` ADD `desconto` INT(3) NOT NULL DEFAULT '0' AFTER `total`; 
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
DROP TABLE IF EXISTS `ag_checkout_cielo`;
CREATE TABLE IF NOT EXISTS `ag_checkout_cielo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `checkout_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `profile` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `version` int(4) DEFAULT NULL,
  `venda` bigint(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_date` datetime NOT NULL,
  `checkout_cielo_order_number` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `payment_method_type` tinyint(4) DEFAULT NULL,
  `payment_method_brand` int(11) DEFAULT NULL,
  `payment_method_bank` int(11) DEFAULT NULL,
  `payment_maskedcreditcard` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `payment_installments` int(11) DEFAULT NULL,
  `payment_status` int(11) DEFAULT NULL,
  `tid` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `test_transaction` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cielo_venda` (`venda`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
ALTER TABLE `ag_checkout_cielo`
  ADD CONSTRAINT `fk_cielo_venda` FOREIGN KEY (`venda`) REFERENCES `ag_venda_site` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `ag_grupo` ADD `bit_boleto` TINYINT(1) NULL DEFAULT '0' AFTER `duracao`, ADD `bit_cartao` TINYINT(1) NULL DEFAULT '0' AFTER `bit_boleto`, ADD `bit_cheque` TINYINT(1) NULL DEFAULT '0' AFTER `bit_cartao`, ADD `bit_customizado` TINYINT(1) NULL DEFAULT '0' AFTER `bit_cheque`, ADD `parcela_boleto` TINYINT(2) NULL DEFAULT '12' AFTER `bit_customizado`, ADD `parcela_cartao` TINYINT(2) NULL DEFAULT '12' AFTER `parcela_boleto`, ADD `parcela_cheque` TINYINT(2) NULL DEFAULT '12' AFTER `parcela_cartao`, ADD `nome_customizado` VARCHAR(150) NULL AFTER `parcela_cheque`, ADD `text_customizado` VARCHAR(1000) NULL AFTER `nome_customizado`; 

COMMIT;
