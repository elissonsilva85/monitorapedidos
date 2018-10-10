CREATE TABLE IF NOT EXISTS `users` (
  `login` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `language` varchar(10) NOT NULL,
  `send_mail` tinyint(1) NOT NULL DEFAULT '1',
  `mail_grouping` tinyint(1) NOT NULL DEFAULT '0',
  `mail_format` varchar(50) NOT NULL DEFAULT 'HTML',
  `last_access` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `confirmation` varchar(100) DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;