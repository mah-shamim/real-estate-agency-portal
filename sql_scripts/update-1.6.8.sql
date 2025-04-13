"dont run this file manually!"

CREATE TABLE IF NOT EXISTS `claim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_submit` datetime DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `model` varchar(45) DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `allow_contact` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `claim` ADD `repository_id` INT(32) NULL DEFAULT NULL AFTER `allow_contact`;
ALTER TABLE `claim` ADD `surname` VARCHAR(64) NULL DEFAULT NULL AFTER `name`;

CREATE TABLE `promocode` (
  `id` int(11) NOT NULL,
  `code_name` varchar(64) DEFAULT '',
  `value` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `usage` varchar(128) DEFAULT '',
  `packages` varchar(128) DEFAULT '',
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `used` int(11) DEFAULT NULL,
  `promocode` varchar(128) DEFAULT '',
  `date_created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `user` ADD `promocode_added` VARCHAR(128) NOT NULL DEFAULT '' AFTER `is_password_locked`, 
ADD `promocode_activated` VARCHAR(128) NOT NULL DEFAULT '' AFTER `promocode_added`;
ALTER TABLE `promocode` CHANGE `value` `value` FLOAT(11) NULL DEFAULT NULL;

UPDATE `option_lang` SET `option` = 'Location' WHERE `option_lang`.`option_id` = 64;
