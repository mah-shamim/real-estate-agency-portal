"dont run this file manually!"

ALTER TABLE `user` 
ADD COLUMN `facebook_link` VARCHAR(150) NULL DEFAULT NULL AFTER `vat_number`,
ADD COLUMN `gplus_link` VARCHAR(150) NULL DEFAULT NULL AFTER `facebook_link`,
ADD COLUMN `youtube_link` VARCHAR(150) NULL DEFAULT NULL AFTER `gplus_link`,
ADD COLUMN `twitter_link` VARCHAR(150) NULL DEFAULT NULL AFTER `youtube_link`,
ADD COLUMN `linkedin_link` VARCHAR(150) NULL DEFAULT NULL AFTER `twitter_link`;

CREATE TABLE IF NOT EXISTS `custom_templates` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `theme` VARCHAR(150) NULL DEFAULT NULL,
  `template_name` VARCHAR(150) NULL DEFAULT NULL,
  `type` VARCHAR(45) NULL DEFAULT NULL COMMENT 'RIGHT|LEFT',
  `widgets_order` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `forms_search` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `theme` VARCHAR(150) NULL DEFAULT NULL,
  `form_name` VARCHAR(150) NULL DEFAULT NULL,
  `type` VARCHAR(45) NULL DEFAULT NULL,
  `fields_order_primary` TEXT NULL DEFAULT NULL COMMENT 'json data',
  `fields_order_secondary` TEXT NULL DEFAULT NULL COMMENT 'json data',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `language` 
ADD COLUMN `is_hidden_submission` TINYINT(1) NULL DEFAULT 0 AFTER `domain`;















