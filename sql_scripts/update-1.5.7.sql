"dont run this file manually!"

ALTER TABLE `user` 
ADD COLUMN `research_mail_notifications` TINYINT(1) NULL DEFAULT 1 AFTER `research_sms_notifications`;

ALTER TABLE `saved_search` 
ADD COLUMN `delivery_frequency_h` INT(11) NULL DEFAULT 24 AFTER `parameters`;

ALTER TABLE `property` 
ADD COLUMN `date_notify` DATETIME NULL DEFAULT NULL AFTER `sent_to_affiliate`;

ALTER TABLE `file` 
ADD COLUMN `alt` VARCHAR(100) NULL DEFAULT NULL AFTER `repository_id`,
ADD COLUMN `description` TEXT NULL DEFAULT NULL AFTER `alt`;

ALTER TABLE `property_lang` CHANGE `field_56_int` `field_56_int` INT( 11 ) NULL ;

ALTER TABLE `property_lang` ADD `field_59_int` INT NULL AFTER `field_56_int`;


CREATE TABLE IF NOT EXISTS `trates` (
`id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `date_from` datetime DEFAULT NULL,
  `date_to` datetime DEFAULT NULL,
  `min_stay` int(11) DEFAULT '1',
  `changeover_day` int(11) DEFAULT '6',
  `table_row_index` int(11) DEFAULT NULL,
  `dates` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `trates`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `trates`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `reviews` 
ADD COLUMN `user_mail` VARCHAR(45) NULL DEFAULT NULL AFTER `date_publish`;

ALTER TABLE `enquire` 
ADD COLUMN `agent_id` INT(11) NULL DEFAULT NULL AFTER `last_reply`;




