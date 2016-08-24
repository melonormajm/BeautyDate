-- MySQL dump 10.13  Distrib 5.5.27, for Win32 (x86)
--
-- Host: localhost    Database: beautydate
-- ------------------------------------------------------
-- Server version	5.5.27

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `calificacion`
--

DROP TABLE IF EXISTS `calificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calificacion` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `valor` int(10) NOT NULL,
  `reservacionid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FKcalificaci874925` (`reservacionid`),
  CONSTRAINT `FKcalificaci874925` FOREIGN KEY (`reservacionid`) REFERENCES `reservacion` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calificacion`
--

LOCK TABLES `calificacion` WRITE;
/*!40000 ALTER TABLE `calificacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `calificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'corte','Tratamiento de Cabello','corte.png'),(2,'bronceado','bronceado','bronceado.png'),(3,'depilacion','depilacion','depilacion.png'),(4,'masaje','masaje','masaje.png'),(5,'uñas','uñas','unas.png'),(6,'maquillaje','maquillaje','maquillaje.png'),(7,'trartamiento','trartamiento','tratamiento.png'),(8,'peinado','peinado','peinado.png'),(9,'color','color','color.png');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria_salon`
--

DROP TABLE IF EXISTS `categoria_salon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria_salon` (
  `categoriaid` int(10) NOT NULL,
  `salonid` int(10) NOT NULL,
  PRIMARY KEY (`categoriaid`,`salonid`),
  KEY `FKcategoria_860683` (`categoriaid`),
  KEY `FKcategoria_803765` (`salonid`),
  CONSTRAINT `FKcategoria_803765` FOREIGN KEY (`salonid`) REFERENCES `salon` (`id`),
  CONSTRAINT `FKcategoria_860683` FOREIGN KEY (`categoriaid`) REFERENCES `categoria` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria_salon`
--

LOCK TABLES `categoria_salon` WRITE;
/*!40000 ALTER TABLE `categoria_salon` DISABLE KEYS */;
INSERT INTO `categoria_salon` VALUES (1,6);
/*!40000 ALTER TABLE `categoria_salon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_salon_favorito`
--

DROP TABLE IF EXISTS `cliente_salon_favorito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cliente_salon_favorito` (
  `usuarioid` int(10) NOT NULL,
  `salonid` int(10) NOT NULL,
  PRIMARY KEY (`usuarioid`,`salonid`),
  KEY `FKcliente_sa746006` (`usuarioid`),
  KEY `FKcliente_sa979999` (`salonid`),
  CONSTRAINT `FKcliente_sa746006` FOREIGN KEY (`usuarioid`) REFERENCES `user` (`id`),
  CONSTRAINT `FKcliente_sa979999` FOREIGN KEY (`salonid`) REFERENCES `salon` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_salon_favorito`
--

LOCK TABLES `cliente_salon_favorito` WRITE;
/*!40000 ALTER TABLE `cliente_salon_favorito` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente_salon_favorito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emcuesta`
--

DROP TABLE IF EXISTS `emcuesta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emcuesta` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emcuesta`
--

LOCK TABLES `emcuesta` WRITE;
/*!40000 ALTER TABLE `emcuesta` DISABLE KEYS */;
/*!40000 ALTER TABLE `emcuesta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagenes`
--

DROP TABLE IF EXISTS `imagenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imagenes` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `principal` tinyint(1) DEFAULT NULL,
  `salonid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `salonid` (`salonid`),
  CONSTRAINT `imagenes_ibfk_1` FOREIGN KEY (`salonid`) REFERENCES `salon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagenes`
--

LOCK TABLES `imagenes` WRITE;
/*!40000 ALTER TABLE `imagenes` DISABLE KEYS */;
INSERT INTO `imagenes` VALUES (2,'1','111','uploads/dia4.jpg',NULL,6);
/*!40000 ALTER TABLE `imagenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `licencia`
--

DROP TABLE IF EXISTS `licencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `licencia` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fecha_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fecha_fin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `licencia_specid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FKlicencia32471` (`licencia_specid`),
  CONSTRAINT `FKlicencia32471` FOREIGN KEY (`licencia_specid`) REFERENCES `licencia_spec` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licencia`
--

LOCK TABLES `licencia` WRITE;
/*!40000 ALTER TABLE `licencia` DISABLE KEYS */;
INSERT INTO `licencia` VALUES (2,'2015-02-18 03:53:25','2015-04-23 22:53:14',2);
/*!40000 ALTER TABLE `licencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `licencia_spec`
--

DROP TABLE IF EXISTS `licencia_spec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `licencia_spec` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `precio` float NOT NULL,
  `duracion` int(11) NOT NULL,
  `tipo_duracion` char(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licencia_spec`
--

LOCK TABLES `licencia_spec` WRITE;
/*!40000 ALTER TABLE `licencia_spec` DISABLE KEYS */;
INSERT INTO `licencia_spec` VALUES (2,25,3,'meses'),(3,30,11,'meses');
/*!40000 ALTER TABLE `licencia_spec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promocion`
--

DROP TABLE IF EXISTS `promocion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promocion` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fecha_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fecha_fin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `operador` varchar(10) NOT NULL,
  `valor` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promocion`
--

LOCK TABLES `promocion` WRITE;
/*!40000 ALTER TABLE `promocion` DISABLE KEYS */;
/*!40000 ALTER TABLE `promocion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservacion`
--

DROP TABLE IF EXISTS `reservacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservacion` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `estado` char(255) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `aplicacion_cliente` char(25) NOT NULL,
  `usuarioid` int(10) NOT NULL,
  `sillon_servicioid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FKreservacio216496` (`usuarioid`),
  KEY `FKreservacio337514` (`sillon_servicioid`),
  CONSTRAINT `FKreservacio216496` FOREIGN KEY (`usuarioid`) REFERENCES `user` (`id`),
  CONSTRAINT `FKreservacio337514` FOREIGN KEY (`sillon_servicioid`) REFERENCES `sillon_servicio` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservacion`
--

LOCK TABLES `reservacion` WRITE;
/*!40000 ALTER TABLE `reservacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `reservacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salon`
--

DROP TABLE IF EXISTS `salon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salon` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `cantidad_sillas` int(10) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `estado` char(25) NOT NULL,
  `hora_inicio` char(4) NOT NULL,
  `hora_fin` char(4) NOT NULL,
  `usuarioid` int(10) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `descripcion_corta` varchar(255) DEFAULT NULL,
  `licenciaid` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FKsalon575384` (`usuarioid`),
  KEY `FKsalon94122` (`licenciaid`),
  CONSTRAINT `FKsalon94122` FOREIGN KEY (`licenciaid`) REFERENCES `licencia` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salon`
--

LOCK TABLES `salon` WRITE;
/*!40000 ALTER TABLE `salon` DISABLE KEYS */;
INSERT INTO `salon` VALUES (6,'Salon de juanma',12,'1.jpg','','INACTIVO','8:00','12:0',4,'Descr','',NULL);
/*!40000 ALTER TABLE `salon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salon_imagenes`
--

DROP TABLE IF EXISTS `salon_imagenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salon_imagenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `thumbnail` tinyint(1) DEFAULT NULL,
  `salon_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `salon_id` (`salon_id`),
  CONSTRAINT `salon_imagenes_ibfk_1` FOREIGN KEY (`salon_id`) REFERENCES `salon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salon_imagenes`
--

LOCK TABLES `salon_imagenes` WRITE;
/*!40000 ALTER TABLE `salon_imagenes` DISABLE KEYS */;
/*!40000 ALTER TABLE `salon_imagenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio`
--

DROP TABLE IF EXISTS `servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `duracion` int(10) NOT NULL,
  `horario_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `horario_fin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `precio` float NOT NULL,
  `estado` varchar(50) NOT NULL,
  `salonid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FKservicio661480` (`salonid`),
  CONSTRAINT `FKservicio661480` FOREIGN KEY (`salonid`) REFERENCES `salon` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio`
--

LOCK TABLES `servicio` WRITE;
/*!40000 ALTER TABLE `servicio` DISABLE KEYS */;
INSERT INTO `servicio` VALUES (7,'Peinado','Descripcion bla bla',20,'0000-00-00 00:00:00','0000-00-00 00:00:00',20,'ACTIVO',6);
/*!40000 ALTER TABLE `servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sillon`
--

DROP TABLE IF EXISTS `sillon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sillon` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `estado` varchar(50) DEFAULT NULL,
  `salonid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FKsillon777939` (`salonid`),
  CONSTRAINT `FKsillon777939` FOREIGN KEY (`salonid`) REFERENCES `salon` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sillon`
--

LOCK TABLES `sillon` WRITE;
/*!40000 ALTER TABLE `sillon` DISABLE KEYS */;
/*!40000 ALTER TABLE `sillon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sillon_servicio`
--

DROP TABLE IF EXISTS `sillon_servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sillon_servicio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sillonid` int(10) NOT NULL,
  `servicioid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sillon_servicio_unique` (`sillonid`,`servicioid`),
  KEY `FKsillon_ser913426` (`sillonid`),
  KEY `FKsillon_ser448361` (`servicioid`),
  CONSTRAINT `FKsillon_ser448361` FOREIGN KEY (`servicioid`) REFERENCES `servicio` (`id`),
  CONSTRAINT `FKsillon_ser913426` FOREIGN KEY (`sillonid`) REFERENCES `sillon` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sillon_servicio`
--

LOCK TABLES `sillon_servicio` WRITE;
/*!40000 ALTER TABLE `sillon_servicio` DISABLE KEYS */;
/*!40000 ALTER TABLE `sillon_servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_contacto`
--

DROP TABLE IF EXISTS `solicitud_contacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_contacto` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre_cliente` varchar(150) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_contacto`
--

LOCK TABLES `solicitud_contacto` WRITE;
/*!40000 ALTER TABLE `solicitud_contacto` DISABLE KEYS */;
/*!40000 ALTER TABLE `solicitud_contacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(100) NOT NULL,
  `password_reset_token` varchar(150) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `auth_key` varchar(200) DEFAULT NULL,
  `status` int(50) DEFAULT NULL,
  `created_at` int(50) DEFAULT NULL,
  `updated_at` int(50) DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `user_type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'jorge','$2y$13$v35zd60DM6pEsTIyh/ZfX./DgdupsEQWvFY7NvaESS9pGugAV54Sy',NULL,'admin@mail.com','sEkcHIGKFHYl7a7M-W5P8hJQ3MVd9n0p',10,1425349327,1425349327,'','LOCAL'),(2,'abel','$2y$13$PovmJgrnlswahDHqdzYhs.BHTdN/4svkoWX9/F1W8LYiWvAhrum06',NULL,'abel@mail.com','bNVsz0h5W3QTFDigmHaI-agKDzEv04Pv',10,1425505915,1425505915,'','LOCAL'),(3,'juanma','$2y$13$agcr2wneMjnZvvtrOrwnqumt3OJmlX91/WnnRvK9/RPkK1UmCtiM2',NULL,'admin1@mail.com','di_YzvthVyG7WMgdVwoNABqRF1hFxG2e',10,1425508027,1425508027,'','LOCAL'),(4,'jm','$2y$13$uVkGvHC96Xc9Phk3yg7Mh.6/8.Go8oFm3iqJMQ.6OFx3XtUZhkQI.',NULL,'jm@gmail.com','Cg5lWV5HRt3hX-B2XpCk_3MKif9eP1MI',10,1426271484,1426271484,'','LOCAL');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-13 16:07:35
