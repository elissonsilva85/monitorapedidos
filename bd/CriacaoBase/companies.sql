CREATE TABLE IF NOT EXISTS `companies` (
  `company_id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `link_site` varchar(200) DEFAULT NULL,
  `tracking_class` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `approved_date` datetime DEFAULT NULL,
  `approved_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`company_id`),
  KEY `created_by` (`created_by`),
  KEY `approved_by` (`approved_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;