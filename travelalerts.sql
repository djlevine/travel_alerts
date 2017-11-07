DROP TABLE IF EXISTS `lirr`;
CREATE TABLE `lirr` (
  `ID` int(4) NOT NULL AUTO_INCREMENT,
  `Title` varchar(35) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `PubDate` varchar(35) DEFAULT NULL,
  `Agency` varchar(23) DEFAULT NULL,
  `Abrv` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `mnr`;
CREATE TABLE `mnr` (
  `ID` int(4) NOT NULL AUTO_INCREMENT,
  `Title` varchar(35) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `PubDate` varchar(35) DEFAULT NULL,
  `Agency` varchar(12) DEFAULT NULL,
  `Abrv` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `njtlr`;
CREATE TABLE `njtlr` (
  `ID` int(4) NOT NULL AUTO_INCREMENT,
  `Title` varchar(35) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `PubDate` varchar(35) DEFAULT NULL,
  `Agency` varchar(15) DEFAULT NULL,
  `Abrv` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `njtr`;
CREATE TABLE `njtr` (
  `ID` int(4) NOT NULL AUTO_INCREMENT,
  `Title` varchar(35) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `PubDate` varchar(35) DEFAULT NULL,
  `Agency` varchar(10) DEFAULT NULL,
  `Abrv` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `path`;
CREATE TABLE `path` (
  `ID` int(4) NOT NULL AUTO_INCREMENT,
  `Title` varchar(35) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `PubDate` varchar(35) DEFAULT NULL,
  `Agency` varchar(5) DEFAULT NULL,
  `Abrv` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `updated`;
CREATE TABLE `updated` (
  `ID` int(11) NOT NULL,
  `njtr` int(35) DEFAULT NULL,
  `njtlr` int(35) DEFAULT NULL,
  `path` int(35) DEFAULT NULL,
  `btt` int(35) DEFAULT NULL,
  `mnr` int(35) DEFAULT NULL,
  `lirr` int(35) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO updated (ID) VALUES ('0');

