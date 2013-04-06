


--
--  Function for Selecting unreaded messages
--  This MySQL function is not tested well
--  @ver - 01
--
DROP PROCEDURE IF EXISTS UnreadedMessages;

CREATE PROCEDURE UnreadedMessages(IN `id_val` INT UNSIGNED) BEGIN SELECT COUNT(*) FROM ".PREFIX."messages WHERE id_receiver = `id_val` AND readed=1; END;


-- --------------------------------------------------------

--
-- Table structure for table `active_guests`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."active_guests` (
  `ip` varchar(15) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `active_users`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."active_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `banned_users`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."banned_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mail`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."mail` (
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `UserTo` tinytext NOT NULL,
  `UserFrom` tinytext NOT NULL,
  `Subject` mediumtext NOT NULL,
  `Message` longtext NOT NULL,
  `status` text NOT NULL,
  `SentDate` text NOT NULL,
  `mail_id` int(80) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`mail_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."messages` (
  `ID` int(18) AUTO_INCREMENT,
  `id_receiver` int(32) DEFAULT NULL,
  `na_receiver` varchar(160) DEFAULT NULL,
  `id_sender` int(32) DEFAULT NULL,
  `na_sender` varchar(160) DEFAULT NULL,
  `priority` int(1) NOT NULL  DEFAULT '0',
  `readed` BOOLEAN NOT NULL  DEFAULT '1',
  `title` varchar(260),
  `body`   text NOT NULL,
  `timechange` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `erase` INT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset};






