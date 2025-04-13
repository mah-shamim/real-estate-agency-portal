"dont run this file manually!"

ALTER TABLE `user` 
ADD COLUMN `county_affiliate_values` TEXT NULL DEFAULT NULL AFTER `linkedin_link`;


CREATE TABLE IF NOT EXISTS `conversions` (
`id` int(11) NOT NULL,
  `currency_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `conversion_index` decimal(10,2) DEFAULT NULL,
  `currency_symbol` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `conversions` (`id`, `currency_code`, `conversion_index`, `currency_symbol`) VALUES
(1, 'HRK', '1.00', NULL),
(2, 'EUR', '7.50', '€'),
(3, 'USD', '6.50', '$');

ALTER TABLE `user` 
ADD COLUMN `embed_video_code` TEXT NULL DEFAULT NULL AFTER `county_affiliate_values`;

ALTER TABLE `property` 
ADD COLUMN `date_activated` DATETIME NULL DEFAULT NULL COMMENT 'Activated even if not paid for activation (by admin for example)' AFTER `date_modified`;

ALTER TABLE `property` 
ADD COLUMN `sent_to_affiliate` TINYINT(1) NULL DEFAULT 0 AFTER `image_repository`;

ALTER TABLE `enquire` 
ADD COLUMN `last_reply` TEXT NULL DEFAULT NULL AFTER `todate`;

ALTER TABLE `treefield` 
ADD COLUMN `affilate_price` DECIMAL(10,2) NULL DEFAULT NULL AFTER `repository_id`;

CREATE TABLE IF NOT EXISTS `affilate_packages` (
  `id_affilate_packages` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NULL DEFAULT NULL,
  `treefield_id` INT(11) NULL DEFAULT NULL,
  `date_last_payment` DATETIME NULL DEFAULT NULL,
  `date_expire` DATETIME NULL DEFAULT NULL,
  `paid_total` DECIMAL(9,2) NULL DEFAULT NULL,
  `currency` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id_affilate_packages`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `user` 
ADD COLUMN `payment_details` TEXT NULL DEFAULT NULL AFTER `embed_video_code`;

ALTER TABLE `treefield` 
ADD COLUMN `notifications_sent` TINYINT(1) NULL DEFAULT 1 AFTER `affilate_price`;

ALTER TABLE `user` 
ADD COLUMN `research_sms_notifications` TINYINT(1) NULL DEFAULT 0 AFTER `payment_details`;

UPDATE `repository` SET `name`='option_m_val' WHERE `name`='option_m';

ALTER TABLE `option` 
ADD COLUMN `repository_id` INT(11) NULL DEFAULT NULL AFTER `max_length`;

ALTER TABLE `option` 
ADD COLUMN `image_filename` VARCHAR(160) NULL DEFAULT NULL AFTER `repository_id`,
ADD COLUMN `image_gallery` TEXT NULL DEFAULT NULL AFTER `image_filename`;



