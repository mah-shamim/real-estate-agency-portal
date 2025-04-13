



ALTER TABLE `option` ADD COLUMN `is_locked` TINYINT(1) NULL DEFAULT 0  AFTER `visible` ;
ALTER TABLE `option` ADD COLUMN `is_frontend` TINYINT(1) NULL DEFAULT 1  AFTER `is_locked` ;

ALTER TABLE `language` ADD COLUMN `is_locked` TINYINT(1) NULL DEFAULT 0  AFTER `default` , CHANGE COLUMN `default` `default` BINARY NULL DEFAULT 0  ;
ALTER TABLE `language` ADD COLUMN `is_frontend` TINYINT(1) NULL DEFAULT 1  AFTER `is_locked` , CHANGE COLUMN `default` `default` BINARY NULL DEFAULT 0  ;




