-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: infonete
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Facundo','Herrera','admin@infonete.com','827ccb0eea8a706c4c34a16891f84e7b','domicilio',0,0,'face16.jpg','',4,1,1),(2,'Carolina','Montenegro','editor@infonete.com','827ccb0eea8a706c4c34a16891f84e7b','domicilio',0,0,'face23.jpg','',3,1,1),(3,'Nicolas','Arana','redactor@infonete.com','827ccb0eea8a706c4c34a16891f84e7b','domicilio',0,0,'face23.jpg','',2,1,1),(4,'Romina','Godoy','lector@infonete.com','827ccb0eea8a706c4c34a16891f84e7b','domicilio',0,0,'face26.jpg','',1,1,1),(5,'Lautaro','Martinez','lector1@infonete.com','827ccb0eea8a706c4c34a16891f84e7b','domicilio',0,0,'face12.jpg',NULL,1,1,1),(6,'Matias','Gomez','lector2@infonete.com','827ccb0eea8a706c4c34a16891f84e7b','domicilio',0,0,'face13.jpg',NULL,1,1,1),(7,'Sergio','Ramos','lector3@infonete.com','827ccb0eea8a706c4c34a16891f84e7b','domicilio',0,0,'face14.jpg',NULL,1,1,1),(15,'Carlos','Ventura','lector4@infonete.com','827ccb0eea8a706c4c34a16891f84e7b','domicilio',0,0,'face16.jpg',NULL,1,1,1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-28 16:28:19
