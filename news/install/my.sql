-- --------------------------------------------------------
--
-- Table structure for table `news_content`
--

CREATE TABLE IF NOT EXISTS  `".PREFIX."news_content` (
  `ID` int(12) NOT NULL AUTO_INCREMENT,
  `title` varchar(240) NOT NULL,
  `body` text NOT NULL,
  `image` varchar(120) NOT NULL,
  `description` varchar(150) NULL,
  `keywords` varchar(130) NOT NULL,
  `timestamp` int(11) unsigned,
  `lastchange` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `category` varchar(160) DEFAULT 0,
  `serialize`   text NOT NULL,
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset};

-- --------------------------------------------------------
--
-- Table structure for table `news_category`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."news_category` (
  `ID` int(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(160) NOT NULL,
  `description` tinytext,
  `subcategory` text,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset};