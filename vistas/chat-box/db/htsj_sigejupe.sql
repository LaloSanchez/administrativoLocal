-- MySQL dump 10.15  Distrib 10.0.21-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: htsj_sigejupe
-- ------------------------------------------------------
-- Server version	10.0.21-MariaDB-log

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
-- Table structure for table `tblchatcerrados`
--

DROP TABLE IF EXISTS `tblchatcerrados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblchatcerrados` (
  `idtblchatcerrados` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(32) NOT NULL,
  `fechacierre` datetime NOT NULL,
  PRIMARY KEY (`idtblchatcerrados`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblchatcerrados`
--

LOCK TABLES `tblchatcerrados` WRITE;
/*!40000 ALTER TABLE `tblchatcerrados` DISABLE KEYS */;
INSERT INTO `tblchatcerrados` VALUES (1,'bc891d8f242a2a4246d1a953c1aeea18','2016-01-13 09:20:28');
/*!40000 ALTER TABLE `tblchatcerrados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblchatmessages`
--

DROP TABLE IF EXISTS `tblchatmessages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblchatmessages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `iduser` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `ip` varchar(15) NOT NULL,
  `date` datetime NOT NULL,
  `chatid` char(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=477 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblchatmessages`
--

LOCK TABLES `tblchatmessages` WRITE;
/*!40000 ALTER TABLE `tblchatmessages` DISABLE KEYS */;
INSERT INTO `tblchatmessages` VALUES (464,'SFB',1057,'dddd','10.22.165.166','2016-01-28 16:48:21','9612b4c730e99e543ba5a67e50693702'),(465,'SFB',1057,'dddd','10.22.165.166','2016-01-28 16:48:28','9612b4c730e99e543ba5a67e50693702'),(466,'SFB',1057,'ddddd','10.22.165.166','2016-01-28 16:48:34','8bb439e4e440d21c3799c2fc4c7d1a10'),(467,'SFB',1057,'ddd','10.22.165.166','2016-01-28 17:05:27','9612b4c730e99e543ba5a67e50693702'),(468,'SFB',1057,'wwwwww','10.22.165.166','2016-01-28 17:12:04','9612b4c730e99e543ba5a67e50693702'),(469,'SFB',1057,'eeee','10.22.165.166','2016-01-28 17:12:07','9612b4c730e99e543ba5a67e50693702'),(470,'SFB',1057,'222222','10.22.165.166','2016-01-28 17:12:19','9612b4c730e99e543ba5a67e50693702'),(471,'MAFER',1056,'jkjkj','10.22.165.166','2016-01-28 18:04:24','9612b4c730e99e543ba5a67e50693702'),(472,'MAFER',1056,'ATENCION: Subí archivo [documento.doc] a este chat.','0.0.0.0','2016-01-28 18:16:11','9612b4c730e99e543ba5a67e50693702'),(473,'MAFER',1056,'ATENCION: Subí archivo [PFC-doc.pdf] a este chat.','0.0.0.0','2016-01-28 18:16:36','9612b4c730e99e543ba5a67e50693702'),(474,'IRMA ISABEL VARGAS QUEZADA',1056,'hola','10.22.165.166','2016-01-28 18:37:38','9612b4c730e99e543ba5a67e50693702'),(475,'IRMA ISABEL VARGAS QUEZADA',1056,'dddd','10.22.165.166','2016-01-28 18:55:08','9612b4c730e99e543ba5a67e50693702'),(476,'IRMA ISABEL VARGAS QUEZADA',1056,'dddd','10.22.165.166','2016-01-29 09:31:34','9612b4c730e99e543ba5a67e50693702');
/*!40000 ALTER TABLE `tblchatmessages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblchatonline`
--

DROP TABLE IF EXISTS `tblchatonline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblchatonline` (
  `hash` varchar(32) NOT NULL,
  `username` varchar(64) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `ip` varchar(15) NOT NULL,
  `last_update` datetime NOT NULL,
  `token` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblchatonline`
--

LOCK TABLES `tblchatonline` WRITE;
/*!40000 ALTER TABLE `tblchatonline` DISABLE KEYS */;
INSERT INTO `tblchatonline` VALUES ('17f48bd725ca4280473b68e7e639e575','MOY',NULL,'10.22.165.166','2016-01-27 18:58:05','9612b4c730e99e543ba5a67e50693702'),('70aeaa0511ec542b8dd66bd411f6d672','MOY',NULL,'::1','2016-01-14 13:22:00',NULL),('7af1fd859774829edb922c36c94a996b','MOY',NULL,'127.0.0.1','2016-01-13 09:21:06',NULL),('8ade79df4f9b80a09763811b6e10afc3','IRMA ISABEL VARGAS QUEZADA',80,'10.22.165.166','2016-01-29 15:24:43','8bb439e4e440d21c3799c2fc4c7d1a10'),('af2fcaa0b51600a61cf733390b1c5e88','MOY',NULL,'::1','2016-01-19 15:54:16',NULL);
/*!40000 ALTER TABLE `tblchatonline` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-01-29 15:24:44
