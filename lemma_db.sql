/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE IF NOT EXISTS `lemma_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `lemma_db`;

CREATE TABLE IF NOT EXISTS `lemma_associations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` char(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `I_NAME` char(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `COMMENTS` text COLLATE utf8mb4_unicode_ci,
  `FROM_CLASS_ID` int(11) DEFAULT NULL,
  `TO_CLASS_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_lemma_associations_lemma_classes` (`FROM_CLASS_ID`),
  KEY `FK_lemma_associations_lemma_classes_2` (`TO_CLASS_ID`),
  CONSTRAINT `FK_lemma_associations_lemma_classes` FOREIGN KEY (`FROM_CLASS_ID`) REFERENCES `lemma_classes` (`ID`),
  CONSTRAINT `FK_lemma_associations_lemma_classes_2` FOREIGN KEY (`TO_CLASS_ID`) REFERENCES `lemma_classes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40000 ALTER TABLE `lemma_associations` DISABLE KEYS */;
/*!40000 ALTER TABLE `lemma_associations` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `lemma_attributes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` char(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `I_NAME` char(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `COMMENTS` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ATTRIBUTE_TYPE` int(11) NOT NULL,
  `ATTRIBUTE_SIZE` int(11) NOT NULL,
  `OWNER_CLASS_ID` int(11) NOT NULL,
  `IS_INDEXED` int(11) NOT NULL,
  `IS_NOTNULL` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `OWNER_CLASS_ID` (`OWNER_CLASS_ID`),
  CONSTRAINT `axiom_ATTRIBUTES_ibfk_1` FOREIGN KEY (`OWNER_CLASS_ID`) REFERENCES `lemma_classes` (`ID`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `lemma_attributes` DISABLE KEYS */;
REPLACE INTO `lemma_attributes` (`ID`, `NAME`, `I_NAME`, `COMMENTS`, `ATTRIBUTE_TYPE`, `ATTRIBUTE_SIZE`, `OWNER_CLASS_ID`, `IS_INDEXED`, `IS_NOTNULL`) VALUES
	(2, 'IS_TEMP', '0', 'IS THIS CLASS TEMP FLAG', 1, 1, 1, 0, 0);
/*!40000 ALTER TABLE `lemma_attributes` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `lemma_classes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` char(128) NOT NULL DEFAULT '',
  `I_NAME` char(64) NOT NULL DEFAULT '0',
  `COMMENTS` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `lemma_classes` DISABLE KEYS */;
REPLACE INTO `lemma_classes` (`ID`, `NAME`, `I_NAME`, `COMMENTS`) VALUES
	(1, 'TEST_CLASS0', '0_TEST', 'JUST TEMP TEST CLASS');
/*!40000 ALTER TABLE `lemma_classes` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `lemma_elements` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OWNER_CLASS_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `OWNER_CLASS_ID` (`OWNER_CLASS_ID`),
  CONSTRAINT `axiom_ELEMENTS_ibfk_1` FOREIGN KEY (`OWNER_CLASS_ID`) REFERENCES `lemma_classes` (`ID`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `lemma_elements` DISABLE KEYS */;
REPLACE INTO `lemma_elements` (`ID`, `OWNER_CLASS_ID`) VALUES
	(1, 1);
/*!40000 ALTER TABLE `lemma_elements` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `lemma_hyperlinks` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ASSOCIATION_ID` int(11) NOT NULL,
  `FROM_ELEMENT_ID` int(11) NOT NULL,
  `TO_ELEMENT_ID` int(11) NOT NULL,
  `ATTRIBUTE_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_lemma_hyperlinks_lemma_elements` (`FROM_ELEMENT_ID`),
  KEY `FK_lemma_hyperlinks_lemma_elements_2` (`TO_ELEMENT_ID`),
  KEY `FK_lemma_hyperlinks_lemma_attributes` (`ATTRIBUTE_ID`),
  KEY `FK_lemma_hyperlinks_lemma_associations` (`ASSOCIATION_ID`),
  CONSTRAINT `FK_lemma_hyperlinks_lemma_associations` FOREIGN KEY (`ASSOCIATION_ID`) REFERENCES `lemma_associations` (`ID`),
  CONSTRAINT `FK_lemma_hyperlinks_lemma_attributes` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `lemma_attributes` (`ID`),
  CONSTRAINT `FK_lemma_hyperlinks_lemma_elements` FOREIGN KEY (`FROM_ELEMENT_ID`) REFERENCES `lemma_elements` (`ID`),
  CONSTRAINT `FK_lemma_hyperlinks_lemma_elements_2` FOREIGN KEY (`TO_ELEMENT_ID`) REFERENCES `lemma_elements` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40000 ALTER TABLE `lemma_hyperlinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `lemma_hyperlinks` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `lemma_interfaces` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` char(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `I_NAME` char(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `COMMENTS` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40000 ALTER TABLE `lemma_interfaces` DISABLE KEYS */;
/*!40000 ALTER TABLE `lemma_interfaces` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `lemma_links` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ORDER_ID` int(11) NOT NULL,
  `FROM_ELEMENT_ID` int(11) NOT NULL,
  `TO_ELEMENT_ID` int(11) NOT NULL,
  `RELATION_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_lemma_links_lemma_elements` (`FROM_ELEMENT_ID`),
  KEY `FK_lemma_links_lemma_elements_2` (`TO_ELEMENT_ID`),
  KEY `FK_lemma_links_lemma_relations` (`RELATION_ID`),
  CONSTRAINT `FK_lemma_links_lemma_elements` FOREIGN KEY (`FROM_ELEMENT_ID`) REFERENCES `lemma_elements` (`ID`),
  CONSTRAINT `FK_lemma_links_lemma_elements_2` FOREIGN KEY (`TO_ELEMENT_ID`) REFERENCES `lemma_elements` (`ID`),
  CONSTRAINT `FK_lemma_links_lemma_relations` FOREIGN KEY (`RELATION_ID`) REFERENCES `lemma_relations` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40000 ALTER TABLE `lemma_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `lemma_links` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `lemma_macroses` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` char(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `I_NAME` char(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `BODY` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'CODE',
  `OWNER_CLASS_ID` int(11) NOT NULL DEFAULT '0',
  `OWNER_INTERFACE_ID` int(11) NOT NULL DEFAULT '0',
  `COMMENTS` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_lemma_macroses_lemma_classes` (`OWNER_CLASS_ID`),
  KEY `FK_lemma_macroses_lemma_interfaces` (`OWNER_INTERFACE_ID`),
  CONSTRAINT `FK_lemma_macroses_lemma_classes` FOREIGN KEY (`OWNER_CLASS_ID`) REFERENCES `lemma_classes` (`ID`),
  CONSTRAINT `FK_lemma_macroses_lemma_interfaces` FOREIGN KEY (`OWNER_INTERFACE_ID`) REFERENCES `lemma_interfaces` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40000 ALTER TABLE `lemma_macroses` DISABLE KEYS */;
/*!40000 ALTER TABLE `lemma_macroses` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `lemma_relations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` char(128) CHARACTER SET latin1 NOT NULL,
  `I_NAME` char(64) CHARACTER SET latin1 NOT NULL,
  `COMMENTS` text CHARACTER SET latin1,
  `FROM_CLASS_ID` int(11) NOT NULL,
  `TO_CLASS_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_lemma_relations_lemma_classes` (`FROM_CLASS_ID`),
  KEY `FK_lemma_relations_lemma_classes_2` (`TO_CLASS_ID`),
  CONSTRAINT `FK_lemma_relations_lemma_classes` FOREIGN KEY (`FROM_CLASS_ID`) REFERENCES `lemma_classes` (`ID`),
  CONSTRAINT `FK_lemma_relations_lemma_classes_2` FOREIGN KEY (`TO_CLASS_ID`) REFERENCES `lemma_classes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40000 ALTER TABLE `lemma_relations` DISABLE KEYS */;
/*!40000 ALTER TABLE `lemma_relations` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `lemma_templates` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` char(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `I_NAME` char(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `COMMENTS` text COLLATE utf8mb4_unicode_ci,
  `BODY` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'CODE',
  `OWNER_CLASS_ID` int(11) NOT NULL,
  `OWNER_INTERFACE_ID` int(11) NOT NULL,
  `MEDIATYPE_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK__lemma_classes` (`OWNER_CLASS_ID`),
  KEY `FK_lemma_templates_lemma_interfaces` (`OWNER_INTERFACE_ID`),
  CONSTRAINT `FK__lemma_classes` FOREIGN KEY (`OWNER_CLASS_ID`) REFERENCES `lemma_classes` (`ID`),
  CONSTRAINT `FK_lemma_templates_lemma_interfaces` FOREIGN KEY (`OWNER_INTERFACE_ID`) REFERENCES `lemma_interfaces` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40000 ALTER TABLE `lemma_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `lemma_templates` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
