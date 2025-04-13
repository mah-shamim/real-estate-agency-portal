
-- Masking feature, $config['agent_masking_enabled'] = TRUE; in cms_config.php
CREATE TABLE IF NOT EXISTS `masking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_submit` datetime DEFAULT NULL,
  `visitor_type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `allow_contact` tinyint(1) DEFAULT '0',
  `agent_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- Update debug table, usable for problems when updating script
CREATE TABLE IF NOT EXISTS `update_debug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.1', 'initial update_debug message');

ALTER TABLE `property` ADD COLUMN `counter_views` INT(11) NULL DEFAULT 0  AFTER `featured_paid_date` ;

CREATE  TABLE IF NOT EXISTS `reviews` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `listing_id` INT(11) NULL DEFAULT NULL ,
  `user_id` INT(11) NULL DEFAULT NULL ,
  `stars` INT(11) NULL DEFAULT NULL ,
  `message` TEXT NULL DEFAULT NULL ,
  `is_visible` TINYINT(1) NULL DEFAULT NULL ,
  `date_publish` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

INSERT INTO `settings` (`field`, `value`) VALUES
('reviews_enabled', '1'),
('reviews_public_visible_enabled', '1');

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.1', 'reviews table added');

-- In next statement can be problem if you already have fields with that id available, you should update your fields with this data...

INSERT IGNORE INTO `option` (`id`, `parent_id`, `order`, `type`, `visible`, `is_locked`, `is_frontend`, `is_hardlocked`) VALUES
(56, 0, 6, 'DROPDOWN', 0, 1, 0, 1),
(55, 1, 19, 'INPUTBOX', 0, 1, 1, 0),
(54, 1, 20, 'DROPDOWN', 0, 1, 1, 1);

INSERT INTO `option_lang` (`option_id`, `language_id`, `option`, `values`, `prefix`, `suffix`) VALUES
(56, 1, 'Pro rating', ',1,2,3,4,5', '', ''),
(55, 1, 'Rent price (incl.)', '', '$', ''),
(54, 1, 'Ownership', 'Agent,Owner,Builder', '', '');

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.1', 'new options insert ignore executed');


