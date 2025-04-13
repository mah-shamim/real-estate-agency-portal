"dont run this file manually!"

ALTER TABLE `reservations` ADD COLUMN `saller_id` INT(11) NULL DEFAULT NULL  AFTER `is_confirmed` ;

ALTER TABLE `reservations` ADD COLUMN `booking_fee` INT(11) NULL DEFAULT 0  AFTER `saller_id` ;

ALTER TABLE `payments` ADD COLUMN `saller_id` INT(11) NULL DEFAULT NULL  AFTER `reservation_id` , ADD COLUMN `property_id` INT(11) NULL DEFAULT NULL  AFTER `saller_id` ;

CREATE  TABLE IF NOT EXISTS `withdrawal` (
  `id_withdrawal` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NULL DEFAULT NULL ,
  `amount` DECIMAL(10,2) NULL DEFAULT NULL ,
  `currency` VARCHAR(45) NULL DEFAULT NULL ,
  `completed` TINYINT(1) NULL DEFAULT 0 ,
  `date_requested` DATETIME NULL DEFAULT NULL ,
  `date_completed` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id_withdrawal`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `withdrawal` ADD COLUMN `withdrawal_email` VARCHAR(160) NULL DEFAULT NULL  AFTER `date_completed` ;

CREATE  TABLE IF NOT EXISTS `map_report` (
  `id_mapreport` INT(11) NOT NULL AUTO_INCREMENT ,
  `lat` DECIMAL(9,6) NULL DEFAULT NULL ,
  `lng` DECIMAL(9,6) NULL DEFAULT NULL ,
  `address` VARCHAR(160) NULL DEFAULT NULL ,
  `outcome` VARCHAR(45) NULL DEFAULT NULL COMMENT 'NOT_SOLD,SOLD\n' ,
  `price` INT(11) NULL DEFAULT NULL ,
  `area` INT(11) NULL DEFAULT NULL ,
  `date_removed` DATETIME NULL DEFAULT NULL ,
  `date_submited` VARCHAR(45) NULL DEFAULT NULL ,
  `purpose` VARCHAR(45) NULL DEFAULT NULL ,
  `type` VARCHAR(45) NULL DEFAULT NULL ,
  `property_json` TEXT NULL DEFAULT NULL COMMENT 'complete json details in english or default language' ,
  PRIMARY KEY (`id_mapreport`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `map_report` ADD COLUMN `id_old` INT(11) NULL DEFAULT NULL  AFTER `property_json` , ADD COLUMN `user_id_remover` INT(11) NULL DEFAULT NULL  AFTER `id_old` , ADD COLUMN `user_id_agent` INT(11) NULL DEFAULT NULL  AFTER `user_id_remover` ;

ALTER TABLE `option` auto_increment = 1000;

ALTER TABLE `user` 
ADD COLUMN `company_name` VARCHAR(200) NULL DEFAULT NULL AFTER `image_agency_filename`,
ADD COLUMN `zip_city` VARCHAR(100) NULL DEFAULT NULL AFTER `company_name`,
ADD COLUMN `prename_name` VARCHAR(100) NULL DEFAULT NULL AFTER `zip_city`,
ADD COLUMN `vat_number` VARCHAR(100) NULL DEFAULT NULL AFTER `prename_name`;

CREATE TABLE IF NOT EXISTS `invoice` (
  `id_invoice` INT(11) NOT NULL AUTO_INCREMENT,
  `invoice_num` VARCHAR(100) NULL DEFAULT NULL,
  `date_created` DATETIME NULL DEFAULT NULL,
  `user_id` INT(11) NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `reference_id` INT(11) NULL DEFAULT NULL,
  `week` INT(11) NULL DEFAULT NULL,
  `price` DECIMAL(10,2) NULL DEFAULT NULL,
  `currency_code` VARCHAR(45) NULL DEFAULT NULL,
  `is_confirmed` TINYINT(1) NULL DEFAULT NULL,
  `is_paid` TINYINT(1) NULL DEFAULT NULL,
  `is_activated` TINYINT(1) NULL DEFAULT NULL,
  `data_json` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_invoice`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `packages` 
ADD COLUMN `num_featured_limit` INT(11) NULL DEFAULT 0 AFTER `num_amenities_limit`;

ALTER TABLE `property` 
ADD COLUMN `id_transitions` INT(11) NULL DEFAULT NULL AFTER `id`;

ALTER TABLE `language` 
ADD COLUMN `domain` VARCHAR(100) NULL DEFAULT '' AFTER `currency_default`;

ALTER TABLE `page` 
ADD COLUMN `template_header` VARCHAR(100) NULL DEFAULT NULL AFTER `template`,
ADD COLUMN `template_footer` VARCHAR(100) NULL DEFAULT NULL AFTER `template_header`;

INSERT INTO `settings` (`field`, `value`) VALUES
('payments_enabled', '1');

INSERT INTO `settings` (`field`, `value`) VALUES
('walkscore_enabled', '1');

CREATE TABLE IF NOT EXISTS `dependent_field` (
  `id_dependent_field` INT(11) NOT NULL AUTO_INCREMENT,
  `field_id` INT(11) NULL DEFAULT NULL COMMENT 'Dependent field/option id',
  `selected_index` INT(11) NULL DEFAULT NULL COMMENT 'Selected dropdown index',
  `selected_value` INT(11) NULL DEFAULT NULL COMMENT 'Depended field value (not required)',
  `hidden_fields_list` TEXT NULL DEFAULT NULL COMMENT 'saparated with comma \"1,2,3\"',
  PRIMARY KEY (`id_dependent_field`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;











