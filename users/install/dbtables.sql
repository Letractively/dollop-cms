



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

--
--  Fixing table for missing erase cel
--  This MySQL function is not tested well
--  @ver - 01
--
DELIMITER $$

DROP PROCEDURE IF EXISTS addFieldIfNotExists 
$$# MySQL returned an empty result set (i.e. zero rows).


DROP FUNCTION IF EXISTS isFieldExisting 
$$# MySQL returned an empty result set (i.e. zero rows).


CREATE FUNCTION isFieldExisting (table_name_IN VARCHAR(100), field_name_IN VARCHAR(100)) 
RETURNS INT
RETURN (
    SELECT COUNT(COLUMN_NAME) 
    FROM INFORMATION_SCHEMA.columns 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = table_name_IN 
    AND COLUMN_NAME = field_name_IN
)
$$# MySQL returned an empty result set (i.e. zero rows).


CREATE PROCEDURE addFieldIfNotExists (
    IN table_name_IN VARCHAR(100)
    , IN field_name_IN VARCHAR(100)
    , IN field_definition_IN VARCHAR(100)
)
BEGIN

    -- http://javajon.blogspot.com/2012/10/mysql-alter-table-add-column-if-not.html

    SET @isFieldThere = isFieldExisting(table_name_IN, field_name_IN);
    IF (@isFieldThere = 0) THEN

        SET @ddl = CONCAT('ALTER TABLE ', table_name_IN);
        SET @ddl = CONCAT(@ddl, ' ', 'ADD COLUMN') ;
        SET @ddl = CONCAT(@ddl, ' ', field_name_IN);
        SET @ddl = CONCAT(@ddl, ' ', field_definition_IN);

        PREPARE stmt FROM @ddl;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

    END IF;

END;
$$# MySQL returned an empty result set (i.e. zero rows).

CALL addFieldIfNotExists ('".PREFIX."messages', 'erase', 'INT(1) NOT NULL DEFAULT 0');

