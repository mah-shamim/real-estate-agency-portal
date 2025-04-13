
ALTER TABLE `language` ADD COLUMN `currency_default` VARCHAR(10) NULL DEFAULT 'USD'  AFTER `is_rtl` , CHANGE COLUMN `default` `default` BINARY NULL DEFAULT 0  ;

CREATE  TABLE IF NOT EXISTS `rates` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `property_id` INT(11) NOT NULL ,
  `date_from` DATETIME NOT NULL ,
  `date_to` DATETIME NOT NULL ,
  `min_stay` INT(11) NULL DEFAULT 1 ,
  `changeover_day` INT(11) NULL DEFAULT 6 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE  TABLE IF NOT EXISTS `rates_lang` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `rates_id` INT(11) NOT NULL ,
  `language_id` INT(11) NOT NULL ,
  `rate_nightly` DECIMAL(10,2) NULL DEFAULT NULL ,
  `rate_weekly` DECIMAL(10,2) NULL DEFAULT NULL ,
  `rate_monthly` DECIMAL(10,2) NULL DEFAULT NULL ,
  `currency_code` VARCHAR(10) NULL DEFAULT 'USD' COMMENT 'USD, EUR, HRK...' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_raes_lang_rates1` (`rates_id` ASC) ,
  INDEX `fk_raes_lang_language1` (`language_id` ASC) ,
  CONSTRAINT `fk_raes_lang_rates1`
    FOREIGN KEY (`rates_id` )
    REFERENCES `rates` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_raes_lang_language1`
    FOREIGN KEY (`language_id` )
    REFERENCES `language` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE  TABLE IF NOT EXISTS `reservations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `property_id` INT(11) NOT NULL ,
  `date_from` DATETIME NOT NULL ,
  `date_to` DATETIME NOT NULL ,
  `total_price` DECIMAL(10,2) NULL DEFAULT NULL ,
  `currency_code` VARCHAR(10) NULL DEFAULT 'USD' ,
  `date_paid_advice` DATETIME NULL DEFAULT NULL ,
  `date_paid_total` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_reservations_user1` (`user_id` ASC) ,
  CONSTRAINT `fk_reservations_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `reservations` DROP COLUMN `date_paid_advice` , ADD COLUMN `date_paid_advance` DATETIME NULL DEFAULT NULL  AFTER `currency_code` , ADD COLUMN `total_paid` DECIMAL(10,2) NULL DEFAULT NULL  AFTER `date_paid_total` ;

ALTER TABLE `reservations` ADD COLUMN `is_confirmed` TINYINT(1) NULL DEFAULT 0  AFTER `total_paid` ;

CREATE  TABLE IF NOT EXISTS `payments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `date_paid` DATETIME NULL DEFAULT NULL ,
  `invoice_num` VARCHAR(45) NULL DEFAULT NULL ,
  `data_post` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `payments` ADD COLUMN `payer_id` VARCHAR(100) NULL DEFAULT NULL  AFTER `data_post` , ADD COLUMN `txn_id` VARCHAR(100) NULL DEFAULT NULL  AFTER `payer_id` , ADD COLUMN `paid` VARCHAR(45) NULL DEFAULT NULL  AFTER `txn_id` , ADD COLUMN `currency_code` VARCHAR(45) NULL DEFAULT NULL  AFTER `paid` , ADD COLUMN `payer_email` VARCHAR(100) NULL DEFAULT NULL  AFTER `currency_code` ;

-- 5.6.2014.

ALTER TABLE `property` ADD COLUMN `date_modified` DATETIME NULL DEFAULT NULL  AFTER `date` ;

ALTER TABLE `user` ADD COLUMN `package_id` INT(11) NULL DEFAULT 0  AFTER `qa_id` , ADD COLUMN `package_last_payment` DATETIME NULL DEFAULT NULL  AFTER `package_id` ;

CREATE  TABLE IF NOT EXISTS `packages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `date_created` DATETIME NULL DEFAULT NULL ,
  `date_modified` DATETIME NULL DEFAULT NULL ,
  `package_name` VARCHAR(60) NULL DEFAULT NULL COMMENT 'FREE\nBASIC\nPREMIUM\n' ,
  `package_price` DECIMAL(10,2) NULL DEFAULT NULL ,
  `package_days` INT(11) NULL DEFAULT 0 ,
  `num_listing_limit` INT(11) NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `packages` ADD COLUMN `currency_code` VARCHAR(45) NULL DEFAULT 'USD'  AFTER `num_listing_limit` ;

-- 8.6.2014.

ALTER TABLE `property_value` ADD COLUMN `value_num` INT(11) NULL DEFAULT NULL  AFTER `value` , DROP FOREIGN KEY `fk_property_value_language1` ;

-- 30.6.2014.

ALTER TABLE `page` ADD COLUMN `is_private` TINYINT(1) NULL DEFAULT 0  AFTER `is_visible` ;

-- 04.7.2014.
-- Added
-- settings paypal_email sandi.winter@gmail.com
-- settings listing_expiry_days 0

INSERT INTO `settings` (`field`, `value`) VALUES
('paypal_email', 'sandi.winter@gmail.com'),
('listing_expiry_days', '0');


-- 16.7.2014.

INSERT INTO `settings` (`field`, `value`) VALUES
('activation_price', '1'),
('default_currency', 'USD');

ALTER TABLE `property` ADD COLUMN `activation_paid_date` DATETIME NULL DEFAULT NULL  AFTER `is_activated` ;



