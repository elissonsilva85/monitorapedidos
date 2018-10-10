CREATE TABLE IF NOT EXISTS `mail_queue` (
  `mail_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(100) NOT NULL,
  `tracking_id` int(11) NOT NULL,
  `tracking_info` text NOT NULL,
  `mail_copy` varchar(500) DEFAULT NULL,
  `mail_sent` tinyint(1) NOT NULL DEFAULT '0',
  `mail_sent_date` datetime DEFAULT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`mail_id`),
  KEY `tracking_id` (`tracking_id`),
  KEY `user_login` (`user_login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1_general_ci;