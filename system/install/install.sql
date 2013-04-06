
--
-- Table structure for table `".PREFIX."banned_users`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."banned_users` (
  `user_id`     int(11) NOT NULL,
  `timestamp`   int(11) unsigned NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset};



-- --------------------------------------------------------

--
-- Table structure for table `".PREFIX."flash`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."flash` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `theme` text,
  `body` text,
  `links` text,
  `texture` text,
  `pages` text,
  `preference2` text,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset}  ;

-- --------------------------------------------------------

--
-- Table structure for table `".PREFIX."links`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."links` (
  `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
  `GR` mediumint(9) NOT NULL DEFAULT '0',
  `url` text,
  `title` text,
  `position` mediumint(9) DEFAULT NULL,
  `target` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset}  ;

-- --------------------------------------------------------

--
-- Table structure for table `".PREFIX."mail`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."mail` (
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `UserTo` mediumint(12 NOT NULL,
  `UserFrom` mediumint(12) NOT NULL,
  `Subject` mediumtext NOT NULL,
  `Message` longtext NOT NULL,
  `status` text NOT NULL,
  `readed` tinyint(1) NOT NULL DEFAULT '0',
  `lastchange` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mail_id` int(80) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`mail_id`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset} ;

-- --------------------------------------------------------

--
-- Table structure for table `".PREFIX."menus`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."menus` (
  `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
  `section` int(3) NOT NULL,
  `position` int(3) NOT NULL,
  `title` varchar(260) NOT NULL,
  `body` text NOT NULL,
  `configuration` text NOT NULL,
  `jscripts` text NOT NULL,
  `phpscript` text NOT NULL,
  `option` text NOT NULL,
  `statute` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `statute` (`statute`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset};

-- --------------------------------------------------------

--
-- Table structure for table `".PREFIX."pages`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."pages` (
  `ID` mediumint(15) NOT NULL AUTO_INCREMENT,
  `title` varchar(260) NOT NULL,
  `body` text,
  `phpcripts` text NOT NULL,
  `jscripts` text NOT NULL,
  `admin` varchar(250) NOT NULL DEFAULT '',
  `dates` date DEFAULT NULL,
  `class_view` varchar(50) DEFAULT NULL,
  `comment` text,
  `com_user` mediumint(15) DEFAULT NULL,
  `preference` text,
  `effects` varchar(25) NOT NULL DEFAULT '0;0;0;0',
  `description` text,
  `keywords` tinytext NOT NULL,
  `metatags` mediumtext NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset}  ;

-- --------------------------------------------------------

--
-- Table structure for table `".PREFIX."page_setup`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."page_setup` (
  `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
  `url` varchar(260) DEFAULT NULL,
  `title` varchar(60) DEFAULT NULL,
  `body` text,
  `target` varchar(60) DEFAULT NULL,
  `priority` varchar(60) DEFAULT NULL,
  `setup` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset}  ;

-- --------------------------------------------------------

--
-- Table structure for table `".PREFIX."preferences`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."preferences` (
  `ID` mediumint(15) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(250) NOT NULL DEFAULT '',
  `site_description` text,
  `site_mail` varchar(180) NOT NULL,
  `host` varchar(250) NOT NULL DEFAULT '',
  `datestart` date DEFAULT NULL,
  `charset` varchar(50) DEFAULT NULL,
  `site_meta` text,
  `site_disclaimer` text,
  `site_keywords` text,
  `copyright` varchar(250) DEFAULT NULL,
  `ico` varchar(250) DEFAULT NULL,
  `start_page` varchar(50) DEFAULT NULL,
  `txt_area` varchar(50) DEFAULT NULL,
  `theme` varchar(150) DEFAULT NULL,
  `lan` varchar(15) DEFAULT NULL,
  `IP` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset}  ;

-- --------------------------------------------------------

--
-- Table structure for table `".PREFIX."theme`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."theme` (
  `ID` mediumint(15) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL DEFAULT '',
  `autor` varchar(250) NOT NULL DEFAULT '',
  `dates` date DEFAULT NULL,
  `class_view` varchar(50) DEFAULT NULL,
  `comment` text,
  `com_user` mediumint(15) DEFAULT NULL,
  `template` text,
  `preference` text,
  `preference2` text,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset} ;

-- --------------------------------------------------------

--
-- Table structure for table `".PREFIX."users`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."users` (
  `username` varchar(160) CHARACTER SET utf8 NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `userid` mediumint(32) NOT NULL AUTO_INCREMENT,
  `userlevel` tinyint(1) unsigned NOT NULL,
  `usermail` varchar(120) CHARACTER SET utf8 DEFAULT NULL,
  `fullname` varchar(180) CHARACTER SET utf8 DEFAULT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  `timezone` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `lastchange` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `valid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `signature` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `language` varchar(12) CHARACTER SET utf8 DEFAULT NULL,
  `picture` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `hash` varchar(42) CHARACTER SET utf8 NOT NULL,
  `hash_generated` int(11) NOT NULL,
  `auth_provider` varchar(42) NOT NULL,
  `auth_uid` int(42) NOT NULL,
  `auth_token` varchar(65) NOT NULL,
  `token_secret` varchar(125) NOT NULL,
  `lang` varchar(3) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `profile_image_url` varchar(150) CHARACTER SET utf8 DEFAULT NULL,

  PRIMARY KEY (`userid`),UNIQUE (`username`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset};

-- --------------------------------------------------------

--
-- Table structure for table `".PREFIX."users_fields`
--

CREATE TABLE IF NOT EXISTS `".PREFIX."users_fields` (
  `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
  `fld_name` varchar(60) NOT NULL DEFAULT '',
  `fld_title` varchar(120) NOT NULL,
  `fld_descr` varchar(250) NOT NULL DEFAULT '',
  `fld_require` varchar(24) DEFAULT NULL,
  `fld_type` varchar(22) DEFAULT NULL,
  `row_type` varchar(60) NOT NULL COMMENT 'users db alert type of row',
  `fld_value` text,
  `fld_attr` varchar(200) NOT NULL DEFAULT '',
  `fld_rowscols` varchar(10) NOT NULL COMMENT 'separator is "|"',
  `fld_order` int(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `section_name` (`fld_name`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset}  ;

-- --------------------------------------------------------





/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO `".PREFIX."preferences`
(`ID` ,`site_name` ,`site_description` ,`host` ,`datestart` ,`charset`, `site_meta` ,`site_disclaimer` ,`site_keywords` ,`copyright` ,`ico` ,`start_page` ,`txt_area` ,`theme` ,`lan` ,`IP`)
VALUES (
'1' , 'dollop', '<a href=\"http://fire1.eu/\" target=\"_blank\">dollop CMS</a>', '',  NOW(), 'utf-8', '', '', NULL , 'fire', '/design/website/favicon.ico', '/page?view=1', 'tiny_mce', 'dollop-4',
'EN','');

INSERT INTO `".PREFIX."theme` (`ID`, `title`, `autor`, `dates`, `class_view`, `comment`, `com_user`, `template`, `preference`, `preference2`) VALUES
(1, '', '', NULL, 'white', NULL, NULL, '');


INSERT INTO  `".PREFIX."links` (`ID` ,`url` ,`title` ,`position` ,`target`)
VALUES ('1' , 'page?view=1', 'home', '1', '_self');

INSERT INTO  `".PREFIX."links` (`ID` ,`url` ,`title` ,`position` ,`target`)
VALUES ('2' , 'users/main', 'users', '2', '_self');

INSERT INTO `".PREFIX."pages` (`ID`, `title`, `body`, `admin`, `dates`, `class_view`, `comment`, `com_user`, `preference`, `effects`) VALUES
(1, 'welcome to Dollop CMS','<h2>Successfully Installed Dollop 4.</h2>
<pre>   Welcome to your new dollop 4 website!
   This page content is example of   activity and you may erase the page from control panel.</pre>
<p>Please follow these steps to complete installation procedure and start using your new website:</p>
<p> Complete your website configuration <br />
  Once logged in website, visit the <strong>administration panel</strong>,  where you can customize,install and configure all packages. <br />
</p>
<p> Install additional functionality<br />
  Next visit <strong>administration panel</strong> sections <strong>\"settings\"</strong> and <strong>\"website\"</strong> to check and change  default information on the website.<br />
  <br />
</p>
<p> Customize your  website design<br />
  To change the look of the website, visit the <strong>theme section</strong>. You may choose from one of the included in themes of basic package of dollop.<br />
.</p>
<p> Start posting new content<br />
  Finally you can start creating content for your website. You may erase or replace this message from (home button) <strong>\"website\"</strong> -> <strong>pages</strong>.</p>

  <p>
  <br />
  Basic <strong> Taskbar</strong> Infirmation<br <br />

  <strong>Home Button</strong> -  website content.<br />
  <strong>Wrench Hammer Button</strong> - website settings.<br />
  <strong>Spider Button</strong> - shows what happens inside the script.<br />
  </p>
  <p>&nbsp;</p>
<p>
For more information, please refer to the <strong>dollop website</strong> in the fire1.eu website:<br />
<a href=\"http://fire1.eu/\">fire1.eu</a>
</p>
<h2>&nbsp;</h2>', '', NULL, NULL, NULL, NULL, NULL, '0;0;0;0');


-- --------------------------------------------------------
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