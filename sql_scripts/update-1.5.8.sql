"dont run this file manually!"

ALTER TABLE `trates` 
ADD COLUMN `rates` TEXT NULL DEFAULT NULL AFTER `dates`;

ALTER TABLE `property` 
ADD COLUMN `status` VARCHAR(45) NULL DEFAULT NULL AFTER `date_notify`,
ADD COLUMN `date_status` VARCHAR(45) NULL DEFAULT NULL AFTER `status`;

ALTER TABLE `property` 
ADD COLUMN `affilate_id` INT(11) NULL DEFAULT NULL AFTER `date_status`;

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_submit` datetime DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `allow_contact` varchar(45) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `file`
ADD  `title` VARCHAR( 255 ) NULL DEFAULT NULL ,
ADD  `link` VARCHAR( 255 ) NULL DEFAULT NULL ;

ALTER TABLE  `language` ADD  `facebook_lang_code` VARCHAR( 32 ) NULL DEFAULT NULL ;

INSERT INTO `option` (`id`,`parent_id`, `order`, `type`, `visible`, `is_locked`, `is_frontend`, `is_hardlocked`) VALUES
(78, 0, 1, 'INPUTBOX', 0, 1, 1, 0);

INSERT INTO `option_lang` (`option_id`, `language_id`, `option`, `values`, `prefix`, `suffix`) VALUES
(78,1,'Keywords','','','');

ALTER TABLE `property` 
ADD COLUMN `date_repost` DATETIME NULL DEFAULT NULL AFTER `affilate_id`,
ADD COLUMN `date_renew` DATETIME NULL DEFAULT NULL AFTER `date_repost`;

ALTER TABLE `property` 
ADD COLUMN `date_alert` DATETIME NULL DEFAULT NULL AFTER `date_renew`;


