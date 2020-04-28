-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `lemma_associations`;
CREATE TABLE `lemma_associations` (
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


DROP TABLE IF EXISTS `lemma_attributes`;
CREATE TABLE `lemma_attributes` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `lemma_attributes` (`ID`, `NAME`, `I_NAME`, `COMMENTS`, `ATTRIBUTE_TYPE`, `ATTRIBUTE_SIZE`, `OWNER_CLASS_ID`, `IS_INDEXED`, `IS_NOTNULL`) VALUES
(1,	'NAME',	'0_NAME',	'NAME OF FACULTY',	0,	3,	2,	1,	1);

DROP TABLE IF EXISTS `lemma_classes`;
CREATE TABLE `lemma_classes` (
                                 `ID` int(11) NOT NULL AUTO_INCREMENT,
                                 `NAME` char(128) NOT NULL DEFAULT '',
                                 `I_NAME` char(64) NOT NULL DEFAULT '0',
                                 `COMMENTS` text NOT NULL,
                                 PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `lemma_classes` (`ID`, `NAME`, `I_NAME`, `COMMENTS`) VALUES
(2,	'FACULTY',	'0_FACULTY',	'COMMENT_FACULTY'),
(3,	'GROUP',	'0_GROUP',	'COMMENT_GROUP');

DROP TABLE IF EXISTS `lemma_elements`;
CREATE TABLE `lemma_elements` (
                                  `ID` int(11) NOT NULL AUTO_INCREMENT,
                                  `OWNER_CLASS_ID` int(11) NOT NULL,
                                  PRIMARY KEY (`ID`),
                                  KEY `OWNER_CLASS_ID` (`OWNER_CLASS_ID`),
                                  CONSTRAINT `axiom_ELEMENTS_ibfk_1` FOREIGN KEY (`OWNER_CLASS_ID`) REFERENCES `lemma_classes` (`ID`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `lemma_elements` (`ID`, `OWNER_CLASS_ID`) VALUES
(3,	2),
(4,	2),
(5,	2),
(6,	2),
(8,	2);

DROP TABLE IF EXISTS `lemma_hyperlinks`;
CREATE TABLE `lemma_hyperlinks` (
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


DROP TABLE IF EXISTS `lemma_interfaces`;
CREATE TABLE `lemma_interfaces` (
                                    `ID` int(11) NOT NULL AUTO_INCREMENT,
                                    `NAME` char(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
                                    `I_NAME` char(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
                                    `COMMENTS` text COLLATE utf8mb4_unicode_ci,
                                    PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `lemma_links`;
CREATE TABLE `lemma_links` (
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


DROP TABLE IF EXISTS `lemma_macroses`;
CREATE TABLE `lemma_macroses` (
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


DROP TABLE IF EXISTS `lemma_relations`;
CREATE TABLE `lemma_relations` (
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


DROP TABLE IF EXISTS `lemma_templates`;
CREATE TABLE `lemma_templates` (
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


-- 2020-04-24 00:49:01
