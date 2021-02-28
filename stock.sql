/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.5.5-10.4.14-MariaDB : Database - stock
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`stock` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `stock`;

/*Table structure for table `categoria` */

DROP TABLE IF EXISTS `categoria`;

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `categoria` */

insert  into `categoria`(`id`,`name`,`created_at`,`updated_at`) values (1,'Material de limpeza','2021-02-28 11:38:03','0000-00-00 00:00:00'),(3,'Consumíveis de escritório','2021-02-28 11:45:52',NULL),(4,'Consumíveis de informática','2021-02-28 11:46:13',NULL);

/*Table structure for table `departamento` */

DROP TABLE IF EXISTS `departamento`;

CREATE TABLE `departamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `departamento` */

insert  into `departamento`(`id`,`name`,`created_at`,`updated_at`) values (1,'RH','2021-02-28 09:44:48','0000-00-00 00:00:00'),(2,'DAF','2021-02-28 11:40:49',NULL),(4,'Nova','2021-02-28 22:40:51',NULL);

/*Table structure for table `itens_pedido` */

DROP TABLE IF EXISTS `itens_pedido`;

CREATE TABLE `itens_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `status` enum('pendente','confirmada') NOT NULL DEFAULT 'pendente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido` (`pedido_id`),
  KEY `produto` (`produto_id`),
  CONSTRAINT `pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produto` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `itens_pedido` */

insert  into `itens_pedido`(`id`,`pedido_id`,`produto_id`,`quantidade`,`status`,`created_at`,`updated_at`) values (7,6,1,2,'confirmada','2021-02-28 22:42:47',NULL),(8,6,4,1,'confirmada','2021-02-28 22:42:47',NULL),(9,7,5,10,'pendente','2021-02-28 22:42:26',NULL);

/*Table structure for table `pedidos` */

DROP TABLE IF EXISTS `pedidos`;

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `status` enum('pendente','confirmada') NOT NULL DEFAULT 'pendente',
  PRIMARY KEY (`id`),
  KEY `usuario` (`user_id`),
  CONSTRAINT `usuario` FOREIGN KEY (`user_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `pedidos` */

insert  into `pedidos`(`id`,`user_id`,`created_at`,`updated_at`,`status`) values (6,4,'2021-02-28 22:29:57','2021-02-28 22:42:47','confirmada'),(7,1,'2021-02-28 22:42:26',NULL,'pendente');

/*Table structure for table `produto` */

DROP TABLE IF EXISTS `produto`;

CREATE TABLE `produto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `qty` int(255) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categoria` (`categoria_id`),
  CONSTRAINT `categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `produto` */

insert  into `produto`(`id`,`name`,`qty`,`categoria_id`,`created_at`,`updated_at`,`descricao`) values (1,'Toner',98,4,'2021-02-28 22:42:47',NULL,NULL),(2,'Caneta',0,3,'2021-02-28 22:09:45',NULL,'Eversharp'),(4,'Computador',95,4,'2021-02-28 22:42:47',NULL,'HP Envy'),(5,'Nome do produto 2',400,3,'2021-02-28 22:41:50',NULL,'Exemplo                        ');

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access_level` enum('func','admin','fiel') NOT NULL DEFAULT 'func',
  `departamento` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `departamento` (`departamento`),
  CONSTRAINT `departamento` FOREIGN KEY (`departamento`) REFERENCES `departamento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `usuario` */

insert  into `usuario`(`id`,`first_name`,`last_name`,`email`,`password`,`access_level`,`departamento`,`created_at`,`updated_at`) values (1,'Canisio','Arsenio','admin@example.com','$2y$10$RP1NZYsOx2WW.gWM8mJvw.LblFZxDx2/HyBUJMqqt85ueMNrodfSe','admin',1,'2021-02-28 10:38:46','2021-02-28 11:27:54'),(4,'Funcionario','Teste','func@example.com','$2y$10$UUjo3NrmDiJpTarRA4RURuY08LiatUCiRqcvEsIvO8/5hzyaknx1S','func',2,'2021-02-28 22:25:39',NULL),(5,'Benjamin','Franklin','b.fran@gmail.com','$2y$10$CtMPxi.w9l0.//dZmHHAIuRHgicoqUqYzhlp9L3JQZWedRQcaCo9.','func',1,'2021-02-28 22:40:08',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
