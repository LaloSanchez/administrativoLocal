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

DROP TABLE IF EXISTS `tblchatmessages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblchatmessages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `message` text NOT NULL,
  `ip` varchar(15) NOT NULL,
  `date` datetime NOT NULL,
  `chatid` char(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=461 DEFAULT CHARSET=utf8;
--

DROP TABLE IF EXISTS `tblchatonline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblchatonline` (
  `hash` varchar(32) NOT NULL,
  `username` varchar(64) DEFAULT NULL,
  `ip` varchar(15) NOT NULL,
  `last_update` datetime NOT NULL,
  `token` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

