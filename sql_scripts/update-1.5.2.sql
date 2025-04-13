
INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.2', '[START] update');

DELETE FROM `settings` WHERE `id` = 872;

INSERT INTO `settings` (`id`, `field`, `value`) VALUES
(872, 'useful_links', 'Admin->Settings->Useful links');

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.2', 'Private listings');

ALTER TABLE `packages` ADD COLUMN `show_private_listings` TINYINT(1) NULL DEFAULT 1  AFTER `currency_code` ;

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.2', 'Energy efficient');

ALTER TABLE `option_lang` CHANGE COLUMN `prefix` `prefix` VARCHAR(25) NULL DEFAULT NULL  , CHANGE COLUMN `suffix` `suffix` VARCHAR(25) NULL DEFAULT NULL  ;

DELETE FROM `property_value` WHERE `option_id` = 59;
DELETE FROM `property_value` WHERE `option_id` = 60;
DELETE FROM `property_value` WHERE `option_id` = 64;

DELETE FROM `option_lang` WHERE `option_id` = 59;
DELETE FROM `option_lang` WHERE `option_id` = 60;

DELETE FROM `option` WHERE `id` = 59;
DELETE FROM `option` WHERE `id` = 60;

INSERT INTO `option` (`id`, `parent_id`, `order`, `type`, `visible`, `is_locked`, `is_frontend`, `is_hardlocked`) VALUES
(60, 1, 25, 'INPUTBOX', 0, 1, 1, 0),
(59, 1, 24, 'INPUTBOX', 0, 1, 1, 0);

INSERT INTO `option_lang` (`option_id`, `language_id`, `option`, `values`, `prefix`, `suffix`) VALUES
(60, 1, 'Gas emissions', '', '', 'kg CO2 / m2, year'),
(59, 1, 'Energy efficient', '', '', 'kWh EP / m2, year');

/*
INSERT INTO `option_lang` (`option_id`, `language_id`, `option`, `values`, `prefix`, `suffix`) VALUES
(60, 1, 'Gas emissions', '', '', 'kg CO2 / m2, year'),
(60, 2, 'Emisije plinova', '', '', 'kg CO2 / m2, year'),
(59, 1, 'Energy efficient', '', '', 'kWh EP / m2, year'),
(59, 2, 'Energetska efikasnost', '', '', 'kWh EP / m2, year');
*/

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.2', 'Saved search');

CREATE TABLE IF NOT EXISTS `saved_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `lang_code` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activated` tinyint(1) DEFAULT '1',
  `date_created` datetime DEFAULT NULL,
  `date_last_informed` datetime DEFAULT NULL,
  `parameters` text COLLATE utf8_unicode_ci COMMENT 'parameters saved in JSON format',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.2', 'Package based on user type');

ALTER TABLE `packages` ADD COLUMN `user_type` VARCHAR(45) NULL DEFAULT '' COMMENT 'USER|AGENT'  AFTER `show_private_listings` ;

-- TreeField

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.2', 'TreeField tables');

CREATE TABLE IF NOT EXISTS `treelevel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `root_id` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `level_name` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `treefield_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `treefield_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `value_path` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `treefield` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) DEFAULT NULL,
  `root_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `field_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- Insert treefield content

DELETE FROM `option_lang` WHERE `option_id` = 64;
DELETE FROM `option` WHERE `id` = 64;

INSERT INTO `option` (`id`, `parent_id`, `order`, `type`, `visible`, `is_locked`, `is_frontend`, `is_hardlocked`) VALUES
(64, 1, 13, 'TREE', 0, 1, 1, 0);

INSERT INTO `option_lang` (`option_id`, `language_id`, `option`, `values`, `prefix`, `suffix`) VALUES
(64, 1, 'Neighbourhood', '', '', '');

/*
INSERT INTO `option_lang` (`option_id`, `language_id`, `option`, `values`, `prefix`, `suffix`) VALUES
(64, 1, 'Neighbourhood', '', '', ''),
(64, 2, 'Neighbourhood', '', '', '');
*/

DELETE FROM `treefield_lang` WHERE `id` < 110;
DELETE FROM `treefield` WHERE `id` < 40;

INSERT INTO `treefield` (`id`, `field_id`, `root_id`, `parent_id`, `order`, `level`, `field_name`) VALUES
(13, 64, NULL, 0, 0, 0, NULL),
(14, 64, NULL, 0, 0, 0, NULL),
(15, 64, NULL, 0, 0, 0, NULL),
(16, 64, NULL, 0, 0, 0, NULL),
(17, 64, NULL, 0, 0, 0, NULL),
(18, 64, NULL, 13, 0, 1, NULL),
(19, 64, NULL, 13, 0, 1, NULL),
(20, 64, NULL, 13, 0, 1, NULL),
(21, 64, NULL, 13, 0, 1, NULL),
(22, 64, NULL, 14, 0, 1, NULL),
(23, 64, NULL, 15, 0, 1, NULL),
(24, 64, NULL, 16, 0, 1, NULL),
(25, 64, NULL, 23, 0, 2, NULL),
(26, 64, NULL, 23, 0, 2, NULL),
(27, 64, NULL, 23, 0, 2, NULL),
(28, 64, NULL, 18, 0, 2, NULL),
(29, 64, NULL, 18, 0, 2, NULL),
(30, 64, NULL, 18, 0, 2, NULL),
(31, 64, NULL, 18, 0, 2, NULL),
(32, 64, NULL, 18, 0, 2, NULL),
(33, 64, NULL, 28, 0, 3, NULL),
(34, 64, NULL, 28, 0, 3, NULL),
(35, 64, NULL, 28, 0, 3, NULL);

INSERT INTO `treefield_lang` (`id`, `treefield_id`, `language_id`, `value`, `value_path`) VALUES
(31, 14, 1, 'Manitoba', 'Manitoba'),
(32, 14, 2, 'Manitoba', 'Manitoba'),
(33, 15, 1, 'McMaster University', 'McMaster University'),
(34, 15, 2, 'McMaster University', 'McMaster University'),
(35, 16, 1, 'Waterloo University', 'Waterloo University'),
(36, 16, 2, 'Waterloo University', 'Waterloo University'),
(37, 17, 1, 'Wilfrid Laurier University', 'Wilfrid Laurier University'),
(38, 17, 2, 'Wilfrid Laurier University', 'Wilfrid Laurier University'),
(39, 18, 1, 'Toronto', 'Toronto'),
(40, 18, 2, 'Toronto', 'Toronto'),
(41, 19, 1, 'Etobicoke', 'Etobicoke'),
(42, 19, 2, 'Etobicoke', 'Etobicoke'),
(43, 20, 1, 'North York', 'North York'),
(44, 20, 2, 'North York', 'North York'),
(45, 21, 1, 'Scarborough', 'Scarborough'),
(46, 21, 2, 'Scarborough', 'Scarborough'),
(47, 22, 1, 'Winnipeg', 'Winnipeg'),
(48, 22, 2, 'Winnipeg', 'Winnipeg'),
(49, 23, 1, 'Hamilton', 'Hamilton'),
(50, 23, 2, 'Hamilton', 'Hamilton'),
(51, 24, 1, 'Waterloo', 'Waterloo'),
(52, 24, 2, 'Waterloo', 'Waterloo'),
(53, 25, 1, 'Westdale', 'McMaster University - Westdale'),
(54, 25, 2, 'Westdale', 'McMaster University - Westdale'),
(55, 26, 1, 'West of Campus', 'McMaster University - West of Campus'),
(56, 26, 2, 'West of Campus', 'McMaster University - West of Campus'),
(57, 27, 1, 'South of Campus', 'McMaster University - South of Campus'),
(58, 27, 2, 'South of Campus', 'McMaster University - South of Campus'),
(59, 28, 1, 'Downtown', 'Ontario - Downtown'),
(60, 28, 2, 'Downtown', 'Ontario - Downtown'),
(61, 29, 1, 'West Toronto', 'Ontario - West Toronto'),
(62, 29, 2, 'West Toronto', 'Ontario - West Toronto'),
(63, 30, 1, 'East Toronto', 'Ontario - East Toronto'),
(64, 30, 2, 'East Toronto', 'Ontario - East Toronto'),
(65, 31, 1, 'Midtown Toronto', 'Ontario - Midtown Toronto'),
(66, 31, 2, 'Midtown Toronto', 'Ontario - Midtown Toronto'),
(67, 32, 1, 'Uptown  Toronto', 'Ontario - Uptown  Toronto'),
(68, 32, 2, 'Uptown  Toronto', 'Ontario - Uptown  Toronto'),
(81, 34, 1, 'City place', 'Ontario - Toronto - City place'),
(82, 34, 2, 'City place', 'Ontario - Toronto - City place'),
(93, 35, 1, 'Cork town', 'Ontario - Toronto - Cork town'),
(94, 35, 2, 'Cork town', 'Ontario - Toronto - Cork town'),
(99, 13, 1, 'Ontario', 'Ontario'),
(100, 13, 2, 'Ontario HR', 'Ontario HR');

-- Favorites

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.2', 'Favorites tables');

CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_saved` datetime DEFAULT NULL,
  `lang_code` varchar(45) COLLATE utf8_unicode_ci DEFAULT 'en',
  `user_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- Phone and mail verification

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.2', 'Phone amd mail verification');

ALTER TABLE `user` ADD COLUMN `mail_verified` TINYINT(1) NULL DEFAULT 0  AFTER `facebook_id` , ADD COLUMN `phone_verified` TINYINT(1) NULL DEFAULT 0  AFTER `mail_verified` ;

-- Payment rovider authorize.net

ALTER TABLE `payments` ADD COLUMN `listing_id` INT(11) NULL DEFAULT NULL  AFTER `payer_email` , ADD COLUMN `payment_gateway` VARCHAR(45) NULL DEFAULT NULL  AFTER `listing_id` ;

ALTER TABLE `payments` ADD COLUMN `user_id` INT(11) NULL DEFAULT NULL  AFTER `payment_gateway` , ADD COLUMN `package_id` INT(11) NULL DEFAULT NULL  AFTER `user_id` , ADD COLUMN `reservation_id` INT(11) NULL DEFAULT NULL  AFTER `package_id` ;

-- Fix for default is reserved word in mysql

ALTER TABLE `language` CHANGE COLUMN `default` `is_default` TINYINT(1) NULL DEFAULT 0  ;

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.2', '[END] update');









