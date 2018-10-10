CREATE TABLE IF NOT EXISTS `shops` (
  `shop_id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `descripton` text,
  `link_site` varchar(200) DEFAULT NULL,
  `link_order` varchar(200) DEFAULT NULL,
  `link_seller` varchar(200) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `creation_date` datetime NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `approved_date` datetime DEFAULT NULL,
  `approved_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`shop_id`),
  KEY `created_by` (`created_by`),
  KEY `approved_by` (`approved_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;