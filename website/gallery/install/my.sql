

--
-- Table structure of Search
--


--
-- Table  search reports
--


CREATE TABLE IF NOT EXISTS `".PREFIX."gallery_content` (
  `ID` int(12) NOT NULL AUTO_INCREMENT,
  
                name varchar(20) NOT NULL,
                category varchar(20) NOT NULL,
                value_type varchar(10) NOT NULL,
                value_text text DEFAULT NULL,
                title varchar(190) NOT NULL,
                body text DEFAULT NULL,
                value_date datetime DEFAULT NULL,
                value_number decimal(20,8) DEFAULT NULL,
                value_bool char(1) DEFAULT 0,

  
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset};

CREATE TABLE IF NOT EXISTS `".PREFIX."gallery_category` (
  `ID` int(12) NOT NULL AUTO_INCREMENT,
  
                name varchar(20) NOT NULL,
                value_type varchar(10) NOT NULL,
                value_text text DEFAULT NULL,
                value_bool char(1) DEFAULT NULL,

  
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset};
 