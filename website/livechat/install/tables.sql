
--
-- Table structure 
-- Dollop application
-- file used for installation
--


--
-- Table sql content
--


CREATE TABLE IF NOT EXISTS `".PREFIX."chat_live` (
  `ID` int(12) NOT NULL AUTO_INCREMENT,

  `chat_message` text NOT NULL,
  `username` varchar(30) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `chat_chanel` int(12) DEFAULT 0,
  `serialize`   text NOT NULL,
  
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset};


--
-- Table 
--

CREATE TABLE IF NOT EXISTS `".PREFIX."chat_chanels` (
  `ID` int(12) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL,
  `description` tinytext  NULL,  
  `available` text      NULL,
  
PRIMARY KEY (`ID`)
)ENGINE=MyISAM  DEFAULT CHARSET={$charset};



