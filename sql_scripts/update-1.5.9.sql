"dont run this file manually!"

ALTER TABLE `property` 
ADD COLUMN `date_notify_expired` DATETIME NULL DEFAULT NULL AFTER `date_alert`;

CREATE TABLE IF NOT EXISTS `removed_listings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `date_removed` DATETIME NULL DEFAULT NULL,
  `address` TEXT NULL DEFAULT NULL,
  `lat` DECIMAL(9,6) NULL DEFAULT NULL,
  `lng` DECIMAL(9,6) NULL DEFAULT NULL,
  `submission_date` DATETIME NULL DEFAULT NULL,
  `expire_date` DATETIME NULL DEFAULT NULL,
  `price_0` INT(11) NULL DEFAULT NULL,
  `price_1` INT(11) NULL DEFAULT NULL,
  `price_2` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE  `treefield` ADD  `image_filename` VARCHAR( 200 ) NULL DEFAULT NULL AFTER  `repository_id` ;

INSERT INTO `forms_search` (`id`, `theme`, `form_name`, `type`, `fields_order_primary`, `fields_order_secondary`) 
VALUES (NULL, 'realocation', 'Horizontal search', 'MAIN', '{  "PRIMARY": {  "C_PURPOSE":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"C_PURPOSE"} ,"DROPDOWN_3":{"direction":"NONE", "style":"", "class":"col-md-3", "id":"3", "type":"DROPDOWN"} ,"DROPDOWN_2":{"direction":"NONE", "style":"", "class":"col-md-3", "id":"2", "type":"DROPDOWN"} ,"INPUTBOX_20":{"direction":"NONE", "style":"", "class":"col-md-3", "id":"20", "type":"INPUTBOX"} ,"INPUTBOX_58":{"direction":"NONE", "style":"", "class":"col-md-3", "id":"58", "type":"INPUTBOX"} ,"BREAKLINE":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"BREAKLINE"} ,"INPUTBOX_19":{"direction":"NONE", "style":"", "class":"col-md-3", "id":"19", "type":"INPUTBOX"} }, "SECONDARY": { } }', NULL);

CREATE TABLE IF NOT EXISTS `weather_cacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT NULL,
  `weather_api` varchar(32) DEFAULT NULL,
  `value` text,
  `expire_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `user` 
ADD COLUMN `custom_fields` TEXT NULL DEFAULT NULL AFTER `payment_details`;

ALTER TABLE `option_lang` ADD `hint` VARCHAR(100) NULL DEFAULT NULL AFTER `suffix`;

ALTER TABLE `user` ADD `last_edit_ip` VARCHAR(32) NULL DEFAULT NULL AFTER `research_mail_notifications`;

ALTER TABLE `property` ADD `last_edit_ip` VARCHAR(32) NULL DEFAULT NULL AFTER `date_alert`;

