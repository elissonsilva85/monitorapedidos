CREATE TABLE IF NOT EXISTS `company_001` (
  `register_id` int(11) NOT NULL AUTO_INCREMENT,
  `tracking_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `last_update_date` datetime DEFAULT NULL,
  `track_linha` varchar(500) DEFAULT NULL,
  `track_data` varchar(500) DEFAULT NULL,
  `track_local` varchar(500) DEFAULT NULL,
  `track_situacao` varchar(500) DEFAULT NULL,
  `track_hash` varchar(100) NOT NULL,
  PRIMARY KEY (`register_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;