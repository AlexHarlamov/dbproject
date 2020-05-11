-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `class_0_editor_entity`;
CREATE TABLE `class_0_editor_entity` (
  `OWNER_ELEMENT_ID` int NOT NULL,
  `NAME` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `OWNER_ELEMENT_ID` (`OWNER_ELEMENT_ID`),
  CONSTRAINT `class_0_EDITOR_ENTITY_ibfk_1` FOREIGN KEY (`OWNER_ELEMENT_ID`) REFERENCES `lemma_elements` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `class_0_editor_entity` (`OWNER_ELEMENT_ID`, `NAME`) VALUES
(16,	'SINGLE_CLASS_ELEMENT');

DROP TABLE IF EXISTS `class_0_faculty`;
CREATE TABLE `class_0_faculty` (
  `OWNER_ELEMENT_ID` int NOT NULL,
  `NAME` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `OWNER_ELEMENT_ID` (`OWNER_ELEMENT_ID`),
  CONSTRAINT `class_0_FACULTY_ibfk_1` FOREIGN KEY (`OWNER_ELEMENT_ID`) REFERENCES `lemma_elements` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `class_0_faculty` (`OWNER_ELEMENT_ID`, `NAME`) VALUES
(3,	'FIT'),
(4,	'FF'),
(5,	'MathM'),
(6,	'GGF'),
(8,	'LAN');

DROP TABLE IF EXISTS `class_0_group`;
CREATE TABLE `class_0_group` (
  `OWNER_ELEMENT_ID` int NOT NULL,
  `NUMBER` int NOT NULL,
  `WARDEN` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `OWNER_ELEMENT_ID` (`OWNER_ELEMENT_ID`),
  CONSTRAINT `class_0_GROUP_ibfk_1` FOREIGN KEY (`OWNER_ELEMENT_ID`) REFERENCES `lemma_elements` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `class_0_group` (`OWNER_ELEMENT_ID`, `NUMBER`, `WARDEN`) VALUES
(10,	17201,	'Bob'),
(11,	17202,	'Rob'),
(12,	17203,	'Mob'),
(13,	17204,	'Noooop'),
(14,	17205,	'John'),
(15,	17206,	'Dara');

DROP TABLE IF EXISTS `class_0_site_structure`;
CREATE TABLE `class_0_site_structure` (
  `OWNER_ELEMENT_ID` int NOT NULL,
  `COMMENT` text COLLATE utf8mb4_unicode_ci,
  KEY `OWNER_ELEMENT_ID` (`OWNER_ELEMENT_ID`),
  CONSTRAINT `class_0_site_structure_ibfk_1` FOREIGN KEY (`OWNER_ELEMENT_ID`) REFERENCES `lemma_elements` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `class_0_site_structure` (`OWNER_ELEMENT_ID`, `COMMENT`) VALUES
(18,	'SINGLE_CLASS_ELEMENT');

DROP TABLE IF EXISTS `lemma_associations`;
CREATE TABLE `lemma_associations` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NAME` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `I_NAME` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `COMMENTS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `FROM_CLASS_ID` int DEFAULT NULL,
  `TO_CLASS_ID` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_lemma_associations_lemma_classes` (`FROM_CLASS_ID`),
  KEY `FK_lemma_associations_lemma_classes_2` (`TO_CLASS_ID`),
  CONSTRAINT `FK_lemma_associations_lemma_classes` FOREIGN KEY (`FROM_CLASS_ID`) REFERENCES `lemma_classes` (`ID`),
  CONSTRAINT `FK_lemma_associations_lemma_classes_2` FOREIGN KEY (`TO_CLASS_ID`) REFERENCES `lemma_classes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `lemma_attributes`;
CREATE TABLE `lemma_attributes` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NAME` char(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `I_NAME` char(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `COMMENTS` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ATTRIBUTE_TYPE` int NOT NULL,
  `ATTRIBUTE_SIZE` int NOT NULL,
  `OWNER_CLASS_ID` int NOT NULL,
  `IS_INDEXED` int NOT NULL,
  `IS_NOTNULL` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `OWNER_CLASS_ID` (`OWNER_CLASS_ID`),
  CONSTRAINT `axiom_ATTRIBUTES_ibfk_1` FOREIGN KEY (`OWNER_CLASS_ID`) REFERENCES `lemma_classes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `lemma_attributes` (`ID`, `NAME`, `I_NAME`, `COMMENTS`, `ATTRIBUTE_TYPE`, `ATTRIBUTE_SIZE`, `OWNER_CLASS_ID`, `IS_INDEXED`, `IS_NOTNULL`) VALUES
(1,	'NAME',	'0_NAME',	'NAME OF FACULTY',	0,	3,	2,	1,	1);

DROP TABLE IF EXISTS `lemma_classes`;
CREATE TABLE `lemma_classes` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NAME` char(128) NOT NULL DEFAULT '',
  `I_NAME` char(64) NOT NULL DEFAULT '0',
  `COMMENTS` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `lemma_classes` (`ID`, `NAME`, `I_NAME`, `COMMENTS`) VALUES
(2,	'FACULTY',	'0_FACULTY',	'COMMENT_FACULTY'),
(3,	'GROUP',	'0_GROUP',	'COMMENT_ST'),
(4,	'EDITOR_ENTITY',	'0_EDITOR_ENTITY',	''),
(5,	'SITE_STRUCTURE',	'0_SITE_STRUCTURE',	'Templates of this class form the main look of the site');

DROP TABLE IF EXISTS `lemma_elements`;
CREATE TABLE `lemma_elements` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `OWNER_CLASS_ID` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `OWNER_CLASS_ID` (`OWNER_CLASS_ID`),
  CONSTRAINT `axiom_ELEMENTS_ibfk_1` FOREIGN KEY (`OWNER_CLASS_ID`) REFERENCES `lemma_classes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `lemma_elements` (`ID`, `OWNER_CLASS_ID`) VALUES
(3,	2),
(4,	2),
(5,	2),
(6,	2),
(8,	2),
(9,	3),
(10,	3),
(11,	3),
(12,	3),
(13,	3),
(14,	3),
(15,	3),
(16,	4),
(18,	5);

DROP TABLE IF EXISTS `lemma_hyperlinks`;
CREATE TABLE `lemma_hyperlinks` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `ASSOCIATION_ID` int NOT NULL,
  `FROM_ELEMENT_ID` int NOT NULL,
  `TO_ELEMENT_ID` int NOT NULL,
  `ATTRIBUTE_ID` int NOT NULL,
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
  `ID` int NOT NULL AUTO_INCREMENT,
  `NAME` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `I_NAME` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `COMMENTS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `lemma_links`;
CREATE TABLE `lemma_links` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `ORDER_ID` int DEFAULT NULL,
  `FROM_ELEMENT_ID` int NOT NULL,
  `TO_ELEMENT_ID` int NOT NULL,
  `RELATION_ID` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_lemma_links_lemma_elements` (`FROM_ELEMENT_ID`),
  KEY `FK_lemma_links_lemma_elements_2` (`TO_ELEMENT_ID`),
  KEY `FK_lemma_links_lemma_relations` (`RELATION_ID`),
  CONSTRAINT `FK_lemma_links_lemma_elements` FOREIGN KEY (`FROM_ELEMENT_ID`) REFERENCES `lemma_elements` (`ID`),
  CONSTRAINT `FK_lemma_links_lemma_elements_2` FOREIGN KEY (`TO_ELEMENT_ID`) REFERENCES `lemma_elements` (`ID`),
  CONSTRAINT `FK_lemma_links_lemma_relations` FOREIGN KEY (`RELATION_ID`) REFERENCES `lemma_relations` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lemma_links` (`ID`, `ORDER_ID`, `FROM_ELEMENT_ID`, `TO_ELEMENT_ID`, `RELATION_ID`) VALUES
(1,	NULL,	10,	3,	1),
(2,	NULL,	11,	4,	1),
(3,	NULL,	12,	5,	1),
(4,	NULL,	13,	6,	1),
(5,	NULL,	14,	3,	1),
(6,	NULL,	15,	3,	1);

DROP TABLE IF EXISTS `lemma_macroses`;
CREATE TABLE `lemma_macroses` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NAME` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `I_NAME` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `BODY` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'CODE',
  `OWNER_CLASS_ID` int NOT NULL DEFAULT '0',
  `OWNER_INTERFACE_ID` int NOT NULL DEFAULT '0',
  `COMMENTS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_lemma_macroses_lemma_classes` (`OWNER_CLASS_ID`),
  KEY `FK_lemma_macroses_lemma_interfaces` (`OWNER_INTERFACE_ID`),
  CONSTRAINT `FK_lemma_macroses_lemma_classes` FOREIGN KEY (`OWNER_CLASS_ID`) REFERENCES `lemma_classes` (`ID`),
  CONSTRAINT `FK_lemma_macroses_lemma_interfaces` FOREIGN KEY (`OWNER_INTERFACE_ID`) REFERENCES `lemma_interfaces` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `lemma_relations`;
CREATE TABLE `lemma_relations` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NAME` char(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `I_NAME` char(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `COMMENTS` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `FROM_CLASS_ID` int NOT NULL,
  `TO_CLASS_ID` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_lemma_relations_lemma_classes` (`FROM_CLASS_ID`),
  KEY `FK_lemma_relations_lemma_classes_2` (`TO_CLASS_ID`),
  CONSTRAINT `FK_lemma_relations_lemma_classes` FOREIGN KEY (`FROM_CLASS_ID`) REFERENCES `lemma_classes` (`ID`),
  CONSTRAINT `FK_lemma_relations_lemma_classes_2` FOREIGN KEY (`TO_CLASS_ID`) REFERENCES `lemma_classes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lemma_relations` (`ID`, `NAME`, `I_NAME`, `COMMENTS`, `FROM_CLASS_ID`, `TO_CLASS_ID`) VALUES
(1,	'PART_OF',	'0_PART_OF',	NULL,	3,	2);

DROP TABLE IF EXISTS `lemma_templates`;
CREATE TABLE `lemma_templates` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NAME` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `I_NAME` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `COMMENTS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `BODY` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'CODE',
  `OWNER_CLASS_ID` int NOT NULL,
  `OWNER_INTERFACE_ID` int DEFAULT NULL,
  `MEDIATYPE_ID` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK__lemma_classes` (`OWNER_CLASS_ID`),
  KEY `FK_lemma_templates_lemma_interfaces` (`OWNER_INTERFACE_ID`),
  CONSTRAINT `FK__lemma_classes` FOREIGN KEY (`OWNER_CLASS_ID`) REFERENCES `lemma_classes` (`ID`),
  CONSTRAINT `FK_lemma_templates_lemma_interfaces` FOREIGN KEY (`OWNER_INTERFACE_ID`) REFERENCES `lemma_interfaces` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lemma_templates` (`ID`, `NAME`, `I_NAME`, `COMMENTS`, `BODY`, `OWNER_CLASS_ID`, `OWNER_INTERFACE_ID`, `MEDIATYPE_ID`) VALUES
(2,	'VIEW',	'0_VIEW',	NULL,	'<p>&nbsp;</p> <p><font size=\"5\">Faculty:</font></p> <p><font size=\"5\"><span lang=\"ru\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &#1048;&#1084;&#1103; : </span><span style=\"background-color: #00FFFF\"\">@NAME()</span></font></p>',	2,	NULL,	NULL),
(3,	'VIEW',	'0_VIEW',	NULL,	'<p>&nbsp;</p> <p><font size=\"5\"><span lang=\"en-us\">Group</span><span lang=\"ru\">:  </span>\r\n</font></p> <p><font size=\"5\"><span lang=\"ru\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  </span>\r\n<span lang=\"en-us\">Number</span><span lang=\"ru\"> : </span><span style=\"background-color: #00FFFF\"\">@NUMBER()</span></font></p>\r\n<p><font size=\"5\"><span lang=\"en-us\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; \r\nWarden</span><span lang=\"ru\"> : </span><span style=\"background-color: #00FFFF\"\">@WARDEN</span></font></p>',	3,	NULL,	NULL),
(4,	'ANOTHER',	'0_ANOTHER',	NULL,	'<p>&nbsp;</p> <p><font size=\"5\"><span lang=\"en-us\">Group</span><span lang=\"ru\">:  </span>\r\n</font></p>\r\n<table border=\"1\" width=\"100%\">\r\n	<tr>\r\n		<td><font size=\"5\"><span lang=\"en-us\">Number</span></font></td>\r\n		<td><font size=\"5\">@NUMBER()</font></td>\r\n	</tr>\r\n	<tr>\r\n		<td><font size=\"5\"><span lang=\"en-us\">Warden</span></font></td>\r\n		<td><font size=\"5\">@WARDEN()</font></td>\r\n	</tr>\r\n</table>\r\n',	3,	NULL,	NULL),
(5,	'EDITOR_WT_P0',	'0_EDITOR_WT_P0',	NULL,	'#attach_library(\"hello\");\r\n<div class=\"container\">\r\n    <div class=\"row d-flex justify-content-center\">\r\n        <h1>\r\n            Editor panel\r\n        </h1>\r\n    </div>\r\n    <div class=\"container\">\r\n<h4>\r\n        PLease, select what are we going create to :\r\n</h4>\r\n    </div>\r\n    <div class=\"btn-group\" role=\"group\">\r\n        <button type=\"button\" class=\"btn btn-secondary\">Class</button>\r\n        <button type=\"button\" class=\"btn btn-secondary\">Object</button>\r\n        <button type=\"button\" class=\"btn btn-secondary load-template-edit-form\">Template</button>\r\n    </div>\r\n    <div class=\"working-area\">\r\n        Please, select subject to look at instruments\r\n    </div>\r\n</div>',	4,	NULL,	NULL),
(6,	'EDITOR_CT',	'0_EDITOR_CT',	NULL,	'<div class=\"container\">\r\n    <div class=\"row\">\r\n        <div class=\"col\">\r\n            <div class=\"row\">\r\n                Edit area:\r\n            </div>\r\n            <div class=\"row\">\r\n                <div class=\"col\">\r\n                    <textarea class=\"template-sample\">\r\n\r\n                    </textarea>\r\n                    <button type=\"button\" class=\"btn btn-primary accept-preview\">Preview</button>\r\n                </div>\r\n            </div>\r\n        </div>\r\n        <div class=\"col\">\r\n            <div class=\"row\">\r\n                Preview:\r\n            </div>\r\n            <div class=\"row\">\r\n                <div class=\"col template-preview\">\r\n\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>',	4,	NULL,	NULL),
(7,	'SITE_HEADER',	'0_SITE_HEADER',	'need Bootstrap',	'<header>\r\n    <nav class=\"navbar navbar-expand-lg navbar-light bg-primary\">\r\n        <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarTogglerDemo01\" aria-controls=\"navbarTogglerDemo01\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">\r\n            <span class=\"navbar-toggler-icon\"></span>\r\n        </button>\r\n        <div class=\"collapse navbar-collapse\" id=\"navbarTogglerDemo01\">\r\n            <a class=\"navbar-brand mb-0 h1\" href=$home>Лемма</a>\r\n            <ul class=\"navbar-nav mr-auto mt-2 mt-lg-0\">\r\n                <li class=\"nav-item\">\r\n                    <a class=\"nav-link text-white\" href=$user_interface>Пользовательский интерфейс</a>\r\n                </li>\r\n                <li class=\"nav-item\">\r\n                    <a class=\"nav-link text-white\" href=$null_interface>Нулевой интерфейс</a>\r\n                </li>\r\n            </ul>\r\n            <form class=\"form-inline my-2 my-lg-0\">\r\n                <input class=\"form-control mr-sm-2\" type=\"search\" placeholder=\"Search\" aria-label=\"Search\">\r\n                <button class=\"btn btn-outline-white my-2 my-sm-0\" type=\"submit\">Поиск</button>\r\n            </form>\r\n        </div>\r\n    </nav>\r\n</header>',	5,	NULL,	NULL),
(8,	'SITE_BODY',	'0_SITE_BODY',	NULL,	'<body style=\"height: 100%;\">\r\n<div class=\"container border-left border-right\" style=\"height: 100%;\">\r\n    <div class=\"p-3 mb-2 bg-light text-black\">Инструментарий</div>\r\n    <ul class=\"nav nav-tabs\" id=\"myTab\" role=\"tablist\">\r\n        <li class=\"nav-item\">\r\n            <p class=\"nav-link dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Просмотр</p>\r\n            <div class=\"dropdown-menu\">\r\n                <p class=\"dropdown-item\">Классы</p>\r\n            </div>\r\n        </li>\r\n        <li class=\"nav-item\">\r\n            <p class=\"nav-link dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Создание</p>\r\n            <div class=\"dropdown-menu\">\r\n                <p class=\"dropdown-item load-EDITOR_FULL_CLASS\">Класс</p>\r\n                <p class=\"dropdown-item\">Отношение</p>\r\n                <div class=\"dropdown-divider\"></div>\r\n                <p class=\"dropdown-item\" >Элемент</p>\r\n                <p class=\"dropdown-item\" >Связь</p>\r\n            </div>\r\n        </li>\r\n    </ul>\r\n    <div style=\"height: 100%; \" class=\"bg-white text-black working-area\">$content</div>\r\n\r\n</div>\r\n\r\n</body>',	5,	NULL,	NULL),
(9,	'SITE',	'0_SITE',	NULL,	'#attach_library(\"fullClassEditor\");\r\n@element(18,10)\r\n@element(18,7)\r\n@element(18,8)',	5,	NULL,	NULL),
(10,	'SITE_HEAD',	'0_SITE_HEAD',	NULL,	'<head>\r\n                <title>\r\n                    $title\r\n                 </title>\r\n            <link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css\" integrity=\"sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh\" crossorigin=\"anonymous\">\r\n            <script src=\"https://code.jquery.com/jquery-3.4.1.slim.min.js\" integrity=\"sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n\" crossorigin=\"anonymous\"></script>\r\n            <script src=\"https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js\" integrity=\"sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo\" crossorigin=\"anonymous\"></script>\r\n            <script src=\"https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js\" integrity=\"sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6\" crossorigin=\"anonymous\"></script>\r\n            $scripts_injection\r\n            </head>',	5,	NULL,	NULL),
(12,	'EDITOR_ATRIBUTE_F',	'0_EDITOR_ATRIBUTE_F',	NULL,	'<div class=\"form-row\">\r\n      \r\n    <div class=\"form-group col-md-2\">\r\n      <label for=\"AttributeName0\">Название</label>\r\n      <input type=\"text\" class=\"form-control\" id=\"AttributeName0\" name=\"AttributeName0\">\r\n    </div>\r\n      \r\n    <div class=\"form-group col-md-4\">\r\n      <label for=\"AttributeComment0\">Комментарий</label>\r\n      <textarea class=\"form-control\" rows=\"1\" id=\"AttributeComment0\" placeholder=\"\" name=\"AttributeComment0\"></textarea>\r\n  </div>\r\n    \r\n    <div class=\"form-group col-md-2\">\r\n      <label for=\"AttributeType0\">Тип</label>\r\n      <select id=\"AttributeType0\" class=\"form-control\" name=\"AttributeType0\">\r\n        <option selected>1</option>\r\n        <option>2</option>\r\n      </select>\r\n         </div> \r\n    \r\n    <div class=\"form-group col-md-2\">\r\n      <label for=\"AttributeSize0\">Размер</label>\r\n      <input type=\"number\" class=\"form-control\" id=\"AttributeSize0\" name=\"AttributeSize0\">\r\n    </div>\r\n        \r\n    <div class=\"form-group col-md-1\">\r\n      <label for=\"AttributeIndexed0\">Is indexed</label>\r\n      <input type=\"number\" class=\"form-control\" id=\"AttributeIndexed0\" name=\"AttributeIndexed0\">\r\n    </div>\r\n        \r\n <div class=\"form-group col-md-1\">\r\n      <label for=\"AttributeNull0\">Is null</label>\r\n      <select id=\"AttributeNull0\" class=\"form-control\" name=\"AttributeNull0\">\r\n        <option selected>1</option>\r\n        <option>0</option>\r\n      </select>\r\n    </div>\r\n</div>',	4,	NULL,	NULL),
(13,	'EDITOR_TEMPLATE_F',	'0_EDITOR_TEMPLATE_F',	NULL,	'<div class=\"form-row\">      \r\n    <div class=\"form-group col-md-2\">\r\n      <label for=\"TemplateName0\">Название</label>\r\n      <input type=\"text\" class=\"form-control\" id=\"TemplateName0\" name=\"TemplateName0\">\r\n    </div>\r\n      \r\n    <div class=\"form-group col-md-10\">\r\n      <label for=\"TemplateComment0\">Комментарий</label>\r\n      <textarea class=\"form-control\" rows=\"1\" id=\"TemplateComment0\" placeholder=\"\" name=\"TemplateComment0\"></textarea>\r\n  </div>   \r\n</div>\r\n\r\n<div class=\"form-row\">\r\n    \r\n<div class=\"form-group col-md-6\">\r\n    <label for=\"TemplateBody0\">Тело</label>\r\n    <textarea class=\"form-control template-sample\" rows=\"10\" id=\"TemplateBody0\" placeholder=\"\" name=\"TemplateBody0\"></textarea>\r\n </div> \r\n      \r\n<div class=\"form-group col-md-6 \">\r\n    <button type=\"button\" class=\"btn btn-outline-secondary accept-preview\">Превью</button>\r\n    <div class=\"col template-preview mt-3\" style=\"border:1px solid #FF7630\">Что-то</div>\r\n</div>      \r\n</div>',	4,	NULL,	NULL),
(14,	'EDITOR_CLASS_F',	'0_EDITOR_CLASS_F',	NULL,	'<div class=\"form-row\">\r\n      \r\n    <div class=\"form-group col-md-2\">\r\n        <label for=\"ClassName\">Название</label>\r\n        <input type=\"text\" class=\"form-control\" id=\"ClassName\" name=\"ClassName\" placeholder=\"\">\r\n    </div>\r\n\r\n    <div class=\"form-group col-md-10\">\r\n        <label for=\"ClassComment\">Комментарий</label>\r\n        <textarea class=\"form-control\" rows=\"1\" id=\"ClassComment\" name=\"ClassComment\" placeholder=\"\"></textarea>\r\n    </div>   \r\n    \r\n</div>',	4,	NULL,	NULL),
(16,	'EDITOR_FULL_CLASS',	'0_EDITOR_FULL_CLASS',	NULL,	'<form name=\"class-editor\" action=\"../POST/?ACTION=0\" method=\"post\">\r\n<p class=\"display-4 \" style=\"color: #FF7630\">Класс</p>\r\n<div class=\"class\">@element(16,14)</div>\r\n<p class=\"display-4\" style=\"color: #FF7630\">Атрибуты Класса</p>\r\n<div class=\"attributes\">@element(16,12)</div>\r\n<button type=\"button\" class=\"btn btn-outline-secondary\">Add</button>\r\n<p class=\"display-4\" style=\"color: #FF7630\">Шаблон Класса</p>\r\n<div class=\"template\">@element(16,13)</div>\r\n\r\n<button type=\"submit\" class=\"btn btn-primary\" name=\"editor-full-class-submit\">Submit</button>\r\n      \r\n</form>',	4,	NULL,	NULL);

-- 2020-05-11 18:09:44
