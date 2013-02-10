

--
-- Table structure of news
-- 25 words long for description
-- recommended 12 - 15 words
--


--
-- Table news content
--


CREATE TABLE IF NOT EXISTS `".PREFIX."news_content` (
  `ID` int(12) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `body` text NOT NULL,
  `image` varchar(120) NOT NULL,
  `description` varchar(150) NULL,
  `keywords` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned,
  `lastchange` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `category` varchar(80) DEFAULT 0,
  `serialize`   text NOT NULL,
  
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset};


--
-- Table news category
--

CREATE TABLE IF NOT EXISTS `".PREFIX."news_category` (
  `ID` int(12) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL,
  `description` tinytext  NULL,  
  `subcategory` text      NULL,
  

PRIMARY KEY (`ID`)
)ENGINE=MyISAM  DEFAULT CHARSET={$charset};

