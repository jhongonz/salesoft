--
-- DbNinja v3.2.7 for MySQL
--
-- Dump date: 2020-08-16 15:21:41 (UTC)
-- Server version: 10.3.23-MariaDB-0+deb10u1
-- Database: salesoft
--

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

CREATE DATABASE `salesoft` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `salesoft`;

--
-- Structure for table: app_categories
--
CREATE TABLE `app_categories` (
  `cate_id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(255) DEFAULT NULL,
  `cate_state` varchar(3) DEFAULT NULL,
  `cate_created_at` datetime DEFAULT NULL,
  `cate_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`cate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='Categories''s table';


--
-- Structure for table: app_customers
--
CREATE TABLE `app_customers` (
  `cus_id` int(11) NOT NULL AUTO_INCREMENT,
  `cus_document_type` varchar(15) DEFAULT NULL,
  `cus_document_number` varchar(15) DEFAULT NULL,
  `cus_name` varchar(150) DEFAULT NULL,
  `cus_lastname` varchar(150) DEFAULT NULL,
  `cus_birthdate` date DEFAULT NULL,
  `cus_phone` varchar(15) DEFAULT NULL,
  `cus_email` varchar(150) DEFAULT NULL,
  `cus_search` text DEFAULT NULL,
  `cus_gender` varchar(15) DEFAULT NULL,
  `cus_address` varchar(255) DEFAULT NULL,
  `cus_state` varchar(3) DEFAULT NULL,
  `cus_created_at` datetime DEFAULT NULL,
  `cus_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`cus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='customer''s table for save all of registers';


--
-- Structure for table: app_managers
--
CREATE TABLE `app_managers` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_identifier_type` varchar(15) NOT NULL,
  `admin_identifier` varchar(50) NOT NULL,
  `admin_name` varchar(150) NOT NULL,
  `admin_lastname` varchar(150) NOT NULL,
  `admin_address` text DEFAULT NULL,
  `admin_phone` varchar(15) DEFAULT NULL,
  `admin_email` varchar(150) DEFAULT NULL,
  `admin_search` text DEFAULT NULL,
  `admin_state` varchar(3) NOT NULL DEFAULT '1',
  `admin_created_at` datetime DEFAULT NULL,
  `admin_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='manager''s table. This table has relation with user''s table';


--
-- Structure for table: app_products
--
CREATE TABLE `app_products` (
  `pro_id` int(11) NOT NULL AUTO_INCREMENT,
  `pro__cate_id` int(11) DEFAULT NULL,
  `pro_code` int(11) DEFAULT NULL,
  `pro_name` varchar(255) DEFAULT NULL,
  `pro_description` text DEFAULT NULL,
  `pro_price` decimal(20,10) DEFAULT NULL,
  `pro_price_cut` decimal(20,10) DEFAULT NULL,
  `pro_search` text DEFAULT NULL,
  `pro_state` varchar(3) DEFAULT NULL,
  `pro_created_at` datetime DEFAULT NULL,
  `pro_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`pro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='products''s table';


--
-- Structure for table: app_sales
--
CREATE TABLE `app_sales` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `sale__cus_id` int(11) DEFAULT NULL,
  `sale__user_id` int(11) DEFAULT NULL,
  `sale_subtotal` decimal(20,10) DEFAULT NULL,
  `sale_total` decimal(20,10) DEFAULT NULL,
  `sale_state` varchar(3) DEFAULT NULL,
  `sale_created_at` datetime DEFAULT NULL,
  `sale_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sale_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='sale''s table for registers';


--
-- Structure for table: app_sales_detail
--
CREATE TABLE `app_sales_detail` (
  `det_id` int(11) NOT NULL AUTO_INCREMENT,
  `det__sale_id` int(11) DEFAULT NULL,
  `det__pro_id` int(11) DEFAULT NULL,
  `det_lot` int(11) DEFAULT NULL,
  `det_price` decimal(20,10) DEFAULT NULL,
  `det_subtotal` decimal(20,10) DEFAULT NULL,
  `det_state` varchar(3) DEFAULT NULL,
  `det_created_at` datetime DEFAULT NULL,
  `det_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`det_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='detail''s table';


--
-- Structure for table: sk_modules
--
CREATE TABLE `sk_modules` (
  `mod_id` int(11) NOT NULL AUTO_INCREMENT,
  `mod__mod_id` int(11) DEFAULT NULL,
  `mod_name` varchar(150) DEFAULT NULL,
  `mod_link` varchar(50) DEFAULT NULL,
  `mod_icon_menu` varchar(50) DEFAULT NULL,
  `mod_icon_panel` varchar(50) DEFAULT NULL,
  `mod_hidden` tinyint(2) DEFAULT 0,
  `mod_state` tinyint(3) DEFAULT NULL,
  `mod_position` int(11) DEFAULT 1,
  `mod_created_at` datetime DEFAULT NULL,
  `mod_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`mod_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='Tabla de registros para los modulos principales';


--
-- Structure for table: sk_privileges
--
CREATE TABLE `sk_privileges` (
  `pri_id` int(11) NOT NULL AUTO_INCREMENT,
  `pri__pro_id` int(11) DEFAULT NULL,
  `pri__mod_id` int(11) DEFAULT NULL,
  `pri_state` tinyint(3) DEFAULT NULL,
  `pri_created_at` datetime DEFAULT NULL,
  `pri_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`pri_id`)
) ENGINE=InnoDB AUTO_INCREMENT=710 DEFAULT CHARSET=utf8 COMMENT='Tabla de registros para los privilegios de los perfiles';


--
-- Structure for table: sk_profiles
--
CREATE TABLE `sk_profiles` (
  `pro_id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_name` varchar(50) DEFAULT NULL,
  `pro_permission` varchar(5) DEFAULT NULL,
  `pro_state` tinyint(3) DEFAULT NULL,
  `pro_created_at` datetime DEFAULT NULL,
  `pro_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`pro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='Tabla de registro para los perfiles administrativos';


--
-- Structure for table: sk_users
--
CREATE TABLE `sk_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user__pro_id` int(11) DEFAULT NULL,
  `user__registry_id` int(11) NOT NULL,
  `user_login` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `user_state` varchar(3) NOT NULL DEFAULT '1',
  `user_created_at` datetime DEFAULT NULL,
  `user_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='user''s table, this table has relation with system''s administrators';


--
-- Data for table: app_categories
--
LOCK TABLES `app_categories` WRITE;
ALTER TABLE `app_categories` DISABLE KEYS;

INSERT INTO `app_categories` (`cate_id`,`cate_name`,`cate_state`,`cate_created_at`,`cate_updated_at`) VALUES (1,'Viveres','2','2020-07-23 20:21:00','2020-07-23 20:26:55'),(2,'zxczxcasdasasd','-1','2020-07-23 20:33:17','2020-07-24 14:00:23'),(3,'Bebidas','2','2020-07-24 14:02:04','2020-07-24 14:02:04'),(4,'Condimentos','2','2020-07-24 14:02:21','2020-07-24 14:02:21'),(5,'Frutas / Verduras','2','2020-07-24 14:02:39','2020-07-24 14:02:39'),(6,'Carne','2','2020-07-24 14:02:58','2020-07-24 14:02:58'),(7,'Pescado / Mariscos','2','2020-07-24 14:03:09','2020-07-24 14:03:09'),(8,'Lacteos','2','2020-07-24 14:03:29','2020-07-24 14:03:29');

ALTER TABLE `app_categories` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;

--
-- Data for table: app_customers
--
LOCK TABLES `app_customers` WRITE;
ALTER TABLE `app_customers` DISABLE KEYS;

INSERT INTO `app_customers` (`cus_id`,`cus_document_type`,`cus_document_number`,`cus_name`,`cus_lastname`,`cus_birthdate`,`cus_phone`,`cus_email`,`cus_search`,`cus_gender`,`cus_address`,`cus_state`,`cus_created_at`,`cus_updated_at`) VALUES (1,'dni','asdas','Jhonny','Gonzalez','1988-10-16','946761555','jhonnygonzalezf@gmail.com','asdas Jhonny Avenida Jose Pardo\nResidencial 1357, Piso 15, Apartamento 1501 946761555 jhonnygonzalezf@gmail.com','male','Avenida Jose Pardo\nResidencial 1357, Piso 15, Apartamento 1501','2','2020-07-23 17:29:23','2020-07-23 17:43:24');

ALTER TABLE `app_customers` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;

--
-- Data for table: app_managers
--
LOCK TABLES `app_managers` WRITE;
ALTER TABLE `app_managers` DISABLE KEYS;

INSERT INTO `app_managers` (`admin_id`,`admin_identifier_type`,`admin_identifier`,`admin_name`,`admin_lastname`,`admin_address`,`admin_phone`,`admin_email`,`admin_search`,`admin_state`,`admin_created_at`,`admin_updated_at`) VALUES (1,'foreign','18591297','jhonny andres','gonzalez','Lima','111111111','jhonny@local.com','18591297 jhonny andres Lima 111111111 jhonny@local.com','2','2020-07-23 10:28:00','2020-07-24 14:09:38'),(2,'dni','123456789','Pedro','Perez','lima','123456789','pedro@local.com','123456789 Pedro lima 123456789 pedro@local.com','2','2020-07-23 16:07:43','2020-07-23 16:08:43');

ALTER TABLE `app_managers` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;

--
-- Data for table: app_products
--
LOCK TABLES `app_products` WRITE;
ALTER TABLE `app_products` DISABLE KEYS;

INSERT INTO `app_products` (`pro_id`,`pro__cate_id`,`pro_code`,`pro_name`,`pro_description`,`pro_price`,`pro_price_cut`,`pro_search`,`pro_state`,`pro_created_at`,`pro_updated_at`) VALUES (1,2,1010,'Producto de prueba','Producto de prueba',45.5000000000,0.0000000000,'1010 Producto de prueba Producto de prueba','-1','2020-07-23 20:56:00','2020-07-24 14:04:25'),(2,1,123,'sdfsdf','sdfsdf',12.0000000000,0.0000000000,NULL,'-1','2020-07-23 21:27:08','2020-07-23 21:34:03'),(3,1,1001,'Atun Enlatado','atun',5.5000000000,0.0000000000,'1001 Atun Enlatado atun','2','2020-07-24 14:05:58','2020-07-24 14:05:58'),(4,6,1002,'Carne de Res (Kg)','CArne de res',9.8000000000,0.0000000000,'1002 Carne de Res (Kg) CArne de res','2','2020-07-24 14:06:36','2020-07-24 14:06:36'),(5,1,1003,'Huevos de Consumo','huevos',6.5000000000,0.0000000000,'1003 Huevos de Consumo huevos','2','2020-07-24 14:07:05','2020-07-24 14:07:05'),(6,1,1004,'Pollo Beneficiado (Kg)','Pollo beneficiado',4.8000000000,0.0000000000,'1004 Pollo Beneficiado (Kg) Pollo beneficiado','2','2020-07-24 14:07:49','2020-07-24 14:07:49'),(7,1,1005,'Arroz blanco de mesa','Arroz tipo 1',4.7500000000,0.0000000000,'1005 Arroz blanco de mesa Arroz tipo 1','2','2020-07-24 14:08:24','2020-07-24 14:08:24'),(8,1,1006,'Café molido','Café',8.5000000000,0.0000000000,'1006 Café molido Café','2','2020-07-24 14:09:05','2020-07-24 14:09:05');

ALTER TABLE `app_products` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;

--
-- Data for table: app_sales
--
LOCK TABLES `app_sales` WRITE;
ALTER TABLE `app_sales` DISABLE KEYS;

INSERT INTO `app_sales` (`sale_id`,`sale__cus_id`,`sale__user_id`,`sale_subtotal`,`sale_total`,`sale_state`,`sale_created_at`,`sale_updated_at`) VALUES (4,1,1,227.5000000000,268.4500000000,'-1','2020-07-24 13:02:56','2020-07-24 13:54:52'),(5,1,1,45.5000000000,53.6900000000,'2','2020-07-24 13:58:44','2020-07-24 13:58:44'),(6,1,1,546.0000000000,644.2800000000,'2','2020-07-24 14:00:00','2020-07-24 14:00:00'),(7,1,1,75.5000000000,89.0900000000,'2','2020-07-24 17:18:47','2020-07-24 17:18:47');

ALTER TABLE `app_sales` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;

--
-- Data for table: app_sales_detail
--
LOCK TABLES `app_sales_detail` WRITE;
ALTER TABLE `app_sales_detail` DISABLE KEYS;

INSERT INTO `app_sales_detail` (`det_id`,`det__sale_id`,`det__pro_id`,`det_lot`,`det_price`,`det_subtotal`,`det_state`,`det_created_at`,`det_updated_at`) VALUES (1,4,1,5,45.5000000000,227.5000000000,'-1','2020-07-24 13:02:56','2020-07-24 13:54:53'),(2,5,1,1,45.5000000000,45.5000000000,'2','2020-07-24 13:58:44','2020-07-24 13:58:44'),(3,6,1,12,45.5000000000,546.0000000000,'2','2020-07-24 14:00:00','2020-07-24 14:00:00'),(4,7,3,5,5.5000000000,27.5000000000,'2','2020-07-24 17:18:47','2020-07-24 17:18:47'),(5,7,6,10,4.8000000000,48.0000000000,'2','2020-07-24 17:18:47','2020-07-24 17:18:47');

ALTER TABLE `app_sales_detail` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;

--
-- Data for table: sk_modules
--
LOCK TABLES `sk_modules` WRITE;
ALTER TABLE `sk_modules` DISABLE KEYS;

INSERT INTO `sk_modules` (`mod_id`,`mod__mod_id`,`mod_name`,`mod_link`,`mod_icon_menu`,`mod_icon_panel`,`mod_hidden`,`mod_state`,`mod_position`,`mod_created_at`,`mod_updated_at`) VALUES (44,NULL,'Configuraciones',NULL,'fa-gears','icon',0,2,1,'2020-07-23 11:56:00','2020-07-23 11:56:00'),(45,44,'Administradores','manager','fa-users','icon',0,2,1,'2020-07-23 11:57:00','2020-07-23 11:57:00'),(46,NULL,'Base',NULL,'fa-cube','icon',0,2,1,'2020-07-23 16:24:00','2020-07-23 16:24:00'),(47,46,'Clientes','customer','icon','icon',0,2,1,'2020-07-23 16:24:00','2020-07-23 16:24:00'),(48,46,'Categorias','category','icon','icon',0,2,1,'2020-07-23 20:19:00','2020-07-23 20:19:00'),(49,46,'Productos','product','icon','icon',0,2,1,'2020-07-23 20:54:00','2020-07-23 20:54:00'),(50,NULL,'Punto de Venta','salepoint','fa-calculator','icon',0,2,1,'2020-07-24 06:11:00','2020-07-24 06:11:00'),(51,46,'Pedidos','sale','icon','icon',0,2,1,'2020-07-24 13:04:00','2020-07-24 13:04:00');

ALTER TABLE `sk_modules` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;

--
-- Data for table: sk_privileges
--
LOCK TABLES `sk_privileges` WRITE;
ALTER TABLE `sk_privileges` DISABLE KEYS;

INSERT INTO `sk_privileges` (`pri_id`,`pri__pro_id`,`pri__mod_id`,`pri_state`,`pri_created_at`,`pri_updated_at`) VALUES (704,18,45,2,'2020-07-23 12:01:00','2020-07-23 12:01:00'),(705,18,47,2,'2020-07-23 16:24:00','2020-07-23 16:24:00'),(706,18,48,2,'2020-07-23 20:20:00','2020-07-23 20:20:00'),(707,18,49,2,'2020-07-23 20:54:00','2020-07-23 20:54:00'),(708,18,50,2,'2020-07-24 06:11:00','2020-07-24 06:11:00'),(709,18,51,2,'2020-07-24 13:04:00','2020-07-24 13:04:00');

ALTER TABLE `sk_privileges` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;

--
-- Data for table: sk_profiles
--
LOCK TABLES `sk_profiles` WRITE;
ALTER TABLE `sk_profiles` DISABLE KEYS;

INSERT INTO `sk_profiles` (`pro_id`,`pro_name`,`pro_permission`,`pro_state`,`pro_created_at`,`pro_updated_at`) VALUES (18,'Root','rui',2,'2020-07-23 12:00:00','2020-07-23 12:00:00');

ALTER TABLE `sk_profiles` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;

--
-- Data for table: sk_users
--
LOCK TABLES `sk_users` WRITE;
ALTER TABLE `sk_users` DISABLE KEYS;

INSERT INTO `sk_users` (`user_id`,`user__pro_id`,`user__registry_id`,`user_login`,`password`,`remember_token`,`user_state`,`user_created_at`,`user_updated_at`) VALUES (1,18,1,'jhonny','$2y$10$lcQXMJw7ZUZACN2WV5dO/esv7rfMLjTsAiK29Rb4vywDiBmR7cze6',NULL,'2','2020-07-23 10:26:00','2020-07-24 14:09:38'),(2,18,2,'pedro','$2y$10$ONHLFijpAo2/t2dDZvr7Mu/NVL77kqVEukMW/HODyRorWFw3veLTq',NULL,'2','2020-07-23 16:07:43','2020-07-23 16:08:43');

ALTER TABLE `sk_users` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

