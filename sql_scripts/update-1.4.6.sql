
ALTER TABLE `language` ADD COLUMN `is_rtl` TINYINT(1) NULL DEFAULT 0  AFTER `is_frontend` , CHANGE COLUMN `default` `default` BINARY NULL DEFAULT 0  ;


