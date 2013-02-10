

--
-- Table structure of Search
--


--
-- Table  search reports
--


CREATE TABLE IF NOT EXISTS `".PREFIX."search_reports` (
  `ID` int(12) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `body` text NOT NULL,
  `description` varchar(120) NOT NULL,
  `destination` varchar(150) NULL,
  `keywords` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned,
  `lastchange` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `query` varchar(120) DEFAULT 0,
  `serialize`   text NOT NULL,
  
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset};


--
-- Table join with tables
--

CREATE TABLE IF NOT EXISTS `".PREFIX."search_join` (
  `ID` int(12) NOT NULL AUTO_INCREMENT,
  `main_table` varchar(80) NOT NULL,
  `join_tables` text  NULL,  
  `subcategory` text  NULL,
  
PRIMARY KEY (`ID`)
)ENGINE=MyISAM  DEFAULT CHARSET={$charset};


--
-- Table search White List of shearch
--

CREATE TABLE IF NOT EXISTS `".PREFIX."search_whitelist` (
  `ID` int(12) NOT NULL AUTO_INCREMENT,
  `tables` varchar(80) NOT NULL,
PRIMARY KEY (`ID`)
)ENGINE=MyISAM  DEFAULT CHARSET={$charset};

--
-- Table insert default white list
--

INSERT INTO `".PREFIX."search_whitelist` (`tables`) VALUES('".PREFIX."pages'); 
INSERT INTO `".PREFIX."search_whitelist` (`tables`) VALUES('".PREFIX."news_content'); 