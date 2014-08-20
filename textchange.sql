-- MySQL dump 10.13  Distrib 5.5.34, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: textchange
-- ------------------------------------------------------
-- Server version	5.5.34-0ubuntu0.13.10.1

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
-- Table structure for table `activations`
--

DROP TABLE IF EXISTS `activations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activations` (
  `ActivationID` int(11) NOT NULL AUTO_INCREMENT,
  `Token` varchar(64) NOT NULL,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`ActivationID`),
  UNIQUE KEY `Token` (`Token`),
  UNIQUE KEY `UserID` (`UserID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ads` (
  `AdID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` int(10) unsigned NOT NULL,
  `DomainID` int(10) unsigned NOT NULL,
  `ISBN` bigint(20) unsigned NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Authors` varchar(255) NOT NULL,
  `Publisher` varchar(255) NOT NULL,
  `PubYear` year(4) NOT NULL,
  `Language` enum('french','english','other') NOT NULL,
  `Information` text NOT NULL,
  `Picture` varchar(32) NOT NULL,
  `Price` decimal(8,2) NOT NULL,
  `Creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`AdID`),
  FULLTEXT KEY `Title` (`Title`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ads_reports`
--

DROP TABLE IF EXISTS `ads_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ads_reports` (
  `AdReportID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `AdID` int(10) unsigned NOT NULL,
  `ByUserID` int(10) unsigned NOT NULL,
  `Reason` text NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IsNew` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`AdReportID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alerts`
--

DROP TABLE IF EXISTS `alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alerts` (
  `UserID` int(10) unsigned NOT NULL,
  `ISBN` bigint(20) unsigned NOT NULL,
  `BookTitle` varchar(255) NOT NULL,
  PRIMARY KEY (`UserID`,`ISBN`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `changemail`
--

DROP TABLE IF EXISTS `changemail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `changemail` (
  `Token` varchar(64) NOT NULL,
  `UserID` int(11) NOT NULL,
  `NewEmail` varchar(256) NOT NULL,
  PRIMARY KEY (`Token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `domains`
--

DROP TABLE IF EXISTS `domains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domains` (
  `DomainID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FrenchName` varchar(255) NOT NULL,
  `EnglishName` varchar(255) NOT NULL,
  PRIMARY KEY (`DomainID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `MessageID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `OtherUserID` int(11) NOT NULL,
  `Way` enum('in','out') NOT NULL,
  `DateSent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Message` text NOT NULL,
  `IsNew` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`MessageID`),
  KEY `UserID` (`UserID`,`OtherUserID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `profiles_reports`
--

DROP TABLE IF EXISTS `profiles_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profiles_reports` (
  `ProfileReportID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` int(10) unsigned NOT NULL,
  `ByUserID` int(10) unsigned NOT NULL,
  `Reason` text NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IsNew` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ProfileReportID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `schools`
--

DROP TABLE IF EXISTS `schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schools` (
  `SchoolID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FrenchName` varchar(255) NOT NULL,
  `EnglishName` varchar(255) NOT NULL,
  `Town` varchar(255) NOT NULL,
  `Province` enum('PE','NB','NS','NL','QC','ON','MB','SK','AB','BC','YK','NT','NV') NOT NULL,
  `Picture` varchar(255) NOT NULL,
  `EmailSuffix` varchar(255) NOT NULL COMMENT 'Suffixe du courriel, sans le "@"',
  PRIMARY KEY (`SchoolID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriptions` (
  `SubscriptionID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `BookID` int(11) NOT NULL,
  PRIMARY KEY (`SubscriptionID`),
  UNIQUE KEY `User2Book` (`UserID`,`BookID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `UserID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Email` varchar(255) NOT NULL,
  `Username` varchar(16) NOT NULL,
  `PasswordPreSalt` varchar(128) NOT NULL,
  `PasswordSalted` varchar(128) NOT NULL,
  `PasswordPostSalt` varchar(128) NOT NULL,
  `RealName` varchar(32) NOT NULL,
  `PhoneNumber` varchar(10) NOT NULL,
  `SchoolID` int(11) NOT NULL,
  `Information` mediumtext NOT NULL,
  `Picture` varchar(64) NOT NULL,
  `Language` enum('french','english') NOT NULL DEFAULT 'french',
  `Rights` tinyint(1) NOT NULL DEFAULT '0',
  `Banned` tinyint(1) NOT NULL DEFAULT '0',
  `Active` tinyint(1) NOT NULL DEFAULT '0',
  `DisplayEmail` tinyint(1) NOT NULL DEFAULT '0',
  `NotifyPM` tinyint(1) NOT NULL DEFAULT '0',
  `UnregContact` tinyint(1) NOT NULL DEFAULT '1',
  `SignupDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `Username` (`Username`),
  FULLTEXT KEY `Search` (`Username`,`RealName`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-08-19 21:54:21
