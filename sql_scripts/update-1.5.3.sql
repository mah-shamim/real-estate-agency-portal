"dont run this file manually!"

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.3', '[START] update');

ALTER TABLE `packages` ADD COLUMN `auto_activation` TINYINT(1) NULL DEFAULT 0  AFTER `user_type` ;

ALTER TABLE `page_lang` ADD COLUMN `slug` VARCHAR(100) NULL DEFAULT NULL  AFTER `keywords` ;

CREATE TABLE IF NOT EXISTS `slugs` (
`id` int(11) NOT NULL,
  `model_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT 'page_m',
  `model_id` int(11) DEFAULT NULL,
  `model_lang_code` varchar(15) COLLATE utf8_unicode_ci DEFAULT 'en',
  `model_lang_id` int(11) DEFAULT '1',
  `slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

ALTER TABLE `treefield` ADD COLUMN `template` VARCHAR(100) NULL DEFAULT 'treefield'  AFTER `field_name` ;

ALTER TABLE `treefield_lang` ADD COLUMN `title` VARCHAR(160) NULL DEFAULT NULL  AFTER `value_path` , ADD COLUMN `path_title` VARCHAR(160) NULL DEFAULT NULL  AFTER `title` , ADD COLUMN `address` VARCHAR(160) NULL DEFAULT NULL  AFTER `path_title` , ADD COLUMN `body` TEXT NULL DEFAULT NULL  AFTER `address` , ADD COLUMN `keywords` VARCHAR(160) NULL DEFAULT NULL  AFTER `body` , ADD COLUMN `description` VARCHAR(160) NULL DEFAULT NULL  AFTER `keywords` , ADD COLUMN `slug` VARCHAR(100) NULL DEFAULT NULL  AFTER `description` ;

ALTER TABLE `treefield` ADD COLUMN `repository_id` INT(11) NULL DEFAULT NULL  AFTER `template` ;

ALTER TABLE `treefield_lang` ADD COLUMN `adcode1` TEXT NULL DEFAULT NULL  AFTER `slug` , ADD COLUMN `adcode2` TEXT NULL DEFAULT NULL  AFTER `adcode1` , ADD COLUMN `adcode3` TEXT NULL DEFAULT NULL  AFTER `adcode2` , ADD COLUMN `adcode4` TEXT NULL DEFAULT NULL  AFTER `adcode3` , ADD COLUMN `adcode5` TEXT NULL DEFAULT NULL  AFTER `adcode4` , ADD COLUMN `adcode6` TEXT NULL DEFAULT NULL  AFTER `adcode5` ;

ALTER TABLE `slugs` ADD COLUMN `real_url` TEXT NULL DEFAULT NULL  AFTER `slug` ;

ALTER TABLE `user` CHANGE `type` `type` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'ADMINAGENTUSER';

ALTER TABLE `favorites` ADD COLUMN `date_last_informed` DATETIME NULL DEFAULT NULL  AFTER `property_id` ;

ALTER TABLE `option` ADD COLUMN `is_required` TINYINT(1) NULL DEFAULT 0  AFTER `is_hardlocked` ;

ALTER TABLE `property` ADD COLUMN `lat` DECIMAL(9,6) NULL DEFAULT NULL  AFTER `gps` , ADD COLUMN `lng` DECIMAL(9,6) NULL DEFAULT NULL  AFTER `lat` ;
ALTER TABLE `property` ADD COLUMN `image_filename` VARCHAR(200) NULL DEFAULT NULL  AFTER `counter_views` ;
ALTER TABLE `property` ADD COLUMN `image_repository` TEXT NULL DEFAULT NULL  AFTER `image_filename` ;

CREATE  TABLE IF NOT EXISTS `property_lang` (
  `l_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `property_id` INT(11) NOT NULL ,
  `language_id` INT(11) NOT NULL ,
  `json_object` TEXT NULL DEFAULT NULL ,
  `field_36_int` INT(11) NULL DEFAULT NULL COMMENT 'Sale price' ,
  `field_37_int` INT(11) NULL DEFAULT NULL COMMENT 'Rent price (excl)' ,
  `field_55_int` INT(11) NULL DEFAULT NULL COMMENT 'Rent price (incl)' ,
  `field_19_int` INT(11) NULL DEFAULT NULL ,
  `field_20_int` INT(11) NULL DEFAULT NULL ,
  `field_58_int` INT(11) NULL DEFAULT NULL ,
  `field_57_int` INT(11) NULL DEFAULT NULL ,
  `field_4` VARCHAR(200) NULL DEFAULT NULL ,
  `field_2` VARCHAR(200) NULL DEFAULT NULL ,
  `field_5` VARCHAR(200) NULL DEFAULT NULL ,
  `field_7` VARCHAR(200) NULL DEFAULT NULL ,
  PRIMARY KEY (`l_id`) ,
  INDEX `fk_property_lang_property1` (`property_id` ASC) ,
  INDEX `fk_property_lang_language1` (`language_id` ASC) ,
  CONSTRAINT `fk_property_lang_property1`
    FOREIGN KEY (`property_id` )
    REFERENCES `property` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_property_lang_language1`
    FOREIGN KEY (`language_id` )
    REFERENCES `language` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `user` ADD COLUMN `image_user_filename` VARCHAR(200) NULL DEFAULT NULL  AFTER `phone_verified` , ADD COLUMN `image_agency_filename` VARCHAR(200) NULL DEFAULT NULL  AFTER `image_user_filename` ;

ALTER TABLE `showroom` ADD COLUMN `image_filename` VARCHAR(200) NULL DEFAULT NULL  AFTER `contact_email` ;

ALTER TABLE `page` ADD COLUMN `image_filename` VARCHAR(200) NULL DEFAULT NULL  AFTER `is_private` ;

ALTER TABLE `option` ADD COLUMN `max_length` INT(11) NULL DEFAULT NULL  AFTER `is_required` ;

ALTER TABLE `packages` ADD COLUMN `num_images_limit` INT(11) NULL DEFAULT 1000  AFTER `auto_activation` , ADD COLUMN `num_amenities_limit` INT(11) NULL DEFAULT 1000  AFTER `num_images_limit` ;

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.3', 'Fabian');

CREATE  TABLE IF NOT EXISTS `stats` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `property_id` INT(11) NULL DEFAULT NULL ,
  `time_part_5min` DATETIME NULL DEFAULT NULL ,
  `views` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.3', 'Dhaifallah API');

CREATE  TABLE IF NOT EXISTS `stats_periods` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `property_id` INT(11) NULL DEFAULT NULL ,
  `period` VARCHAR(45) NULL DEFAULT 'WEEK' COMMENT 'WEEK\nDAY\nMONTH' ,
  `views` INT(11) NULL DEFAULT 0 ,
  `date` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE  TABLE IF NOT EXISTS `cacher` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `index_hash` VARCHAR(40) NULL DEFAULT NULL ,
  `index_real` VARCHAR(100) NULL DEFAULT NULL ,
  `value` TEXT NULL DEFAULT NULL ,
  `expire_date` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `index_chars_UNIQUE` (`index_hash` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

INSERT INTO `update_debug` (`version`, `message`) VALUES ('1.5.3', '[END] update');








