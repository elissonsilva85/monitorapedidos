CREATE TABLE IF NOT EXISTS `sellers` (
  `seller_id` int(6) NOT NULL AUTO_INCREMENT,
  `shop_id` int(6) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL,
  `created_by` varchar(100) NOT NULL,
  PRIMARY KEY (`seller_id`),
  KEY `shop_id` (`shop_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;