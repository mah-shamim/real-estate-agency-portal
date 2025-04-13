"dont run this file manually!"


ALTER TABLE `option` 
ADD COLUMN `is_quickvisible` TINYINT(1) NULL DEFAULT NULL AFTER `is_required`;

ALTER TABLE `repository` 
ADD COLUMN `is_activated` TINYINT(1) NULL DEFAULT 1 AFTER `name`;

ALTER TABLE `repository` 
ADD COLUMN `date_submit` DATETIME NULL DEFAULT NULL AFTER `is_activated`,
ADD COLUMN `date_modified` DATETIME NULL DEFAULT NULL AFTER `date_submit`;

INSERT INTO `forms_search` (`id`, `theme`, `form_name`, `type`, `fields_order_primary`, `fields_order_secondary`) VALUES
(4, 'bootstrap4', 'Standard search', 'MAIN', '{ "PRIMARY": { "DROPDOWN":{"direction":"NONE", "style":"", "class":"col-lg-2", "id":"4", "type":"DROPDOWN"} ,"SMART_SEARCH":{"direction":"NONE", "style":"", "class":"col-lg-3", "id":"NONE", "type":"SMART_SEARCH"} ,"DROPDOWN_2":{"direction":"NONE", "style":"", "class":"col-lg-3", "id":"2", "type":"DROPDOWN"} ,"DROPDOWN_3":{"direction":"NONE", "style":"", "class":"col-lg-3", "id":"3", "type":"DROPDOWN"} }, "SECONDARY": { "INPUTBOX_19":{"direction":"NONE", "style":"", "class":"", "id":"19", "type":"INPUTBOX"} ,"INPUTBOX_20":{"direction":"NONE", "style":"", "class":"", "id":"20", "type":"INPUTBOX"} ,"INPUTBOX_36":{"direction":"NONE", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"INPUTBOX_59":{"direction":"NONE", "style":"", "class":"", "id":"59", "type":"INPUTBOX"} ,"CHECKBOX_24":{"direction":"NONE", "style":"", "class":"", "id":"24", "type":"CHECKBOX"} ,"CHECKBOX_28":{"direction":"NONE", "style":"", "class":"", "id":"28", "type":"CHECKBOX"} ,"CHECKBOX_31":{"direction":"NONE", "style":"", "class":"", "id":"31", "type":"CHECKBOX"} ,"CHECKBOX_30":{"direction":"NONE", "style":" ", "class":"", "id":"30", "type":"CHECKBOX"} ,"CHECKBOX_33":{"direction":"NONE", "style":"", "class":"", "id":"33", "type":"CHECKBOX"} ,"CHECKBOX_25":{"direction":"NONE", "style":"", "class":"", "id":"25", "type":"CHECKBOX"} ,"CHECKBOX_29":{"direction":"NONE", "style":"", "class":"", "id":"29", "type":"CHECKBOX"} ,"CHECKBOX_32":{"direction":"NONE", "style":"", "class":"", "id":"32", "type":"CHECKBOX"} ,"CHECKBOX_23":{"direction":"NONE", "style":"", "class":"", "id":"23", "type":"CHECKBOX"} ,"CHECKBOX_11":{"direction":"NONE", "style":"", "class":"", "id":"11", "type":"CHECKBOX"} } }', '0');

CREATE TABLE IF NOT EXISTS `user_attend_listing` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NULL DEFAULT NULL,
  `listing_id` INT(11) NULL DEFAULT NULL,
  `date` DATETIME NULL DEFAULT NULL,
  `ip` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `property` ADD `is_visible` TINYINT NOT NULL DEFAULT '1' AFTER `is_activated`;

CREATE TABLE IF NOT EXISTS `agency_agent` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `agency_id` INT(11) NULL DEFAULT NULL,
  `agent_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `user` 
ADD COLUMN `agency_id` INT(11) NULL DEFAULT NULL AFTER `research_mail_notifications`;

ALTER TABLE `user` 
CHANGE COLUMN `facebook_id` `facebook_id` VARCHAR(160) NULL DEFAULT NULL ;