"dont run this file manually!"

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `date_visit` datetime DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_confirmed` datetime DEFAULT NULL,
  `date_canceled` datetime DEFAULT NULL,
  `property_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `property` ADD `avarage_rating` INT NULL DEFAULT NULL AFTER `counter_views`;

ALTER TABLE `property` ADD `planimages_repository_id` INT(11) NULL DEFAULT NULL AFTER `repository_id`;

ALTER TABLE `treefield` ADD `font_icon_code` VARCHAR(64) NOT NULL AFTER `notifications_sent`;

#ALTER TABLE `token_api` ADD PRIMARY KEY(`id`);

TRUNCATE TABLE `token_api` ;

ALTER TABLE `token_api` ADD PRIMARY KEY( `id`);

ALTER TABLE `token_api` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `visits` ADD PRIMARY KEY(`id`);

ALTER TABLE `visits` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;

