


--
-- Table
--


CREATE TABLE IF NOT EXISTS `".PREFIX."comments_content` (
  `ID` int(12) NOT NULL AUTO_INCREMENT,
  `uri_request` varchar(240) NOT NULL,
  `user_na` varchar(80) NOT NULL,
  `user_id` varchar(12) NOT NULL,
  `user_pi` varchar(12) NOT NULL,
  `body` text NOT NULL,
  `dates` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `serialize`   text NOT NULL,
  
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset};


--
-- Table 
--

CREATE TABLE IF NOT EXISTS `".PREFIX."comments_badpoint` (
    `ID` int(12) NOT NULL AUTO_INCREMENT,
    `title` varchar(80) NOT NULL,
    `points` varchar(20)  NULL,  
    `user_na` varchar(80) NOT NULL,
    `user_id` varchar(12) NOT NULL,
    `lastchange` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

PRIMARY KEY (`ID`)
)ENGINE=MyISAM  DEFAULT CHARSET={$charset};






