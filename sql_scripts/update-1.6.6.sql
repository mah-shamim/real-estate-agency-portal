"dont run this file manually!"

ALTER TABLE `option` 
ADD COLUMN `is_not_translatable` TINYINT(1) NULL DEFAULT NULL AFTER `is_required`;

ALTER TABLE `enquire` ADD `repository_id` INT(32) NULL DEFAULT NULL AFTER `del_to`;

ALTER TABLE `user` ADD `data_expire_package_sent` DATETIME NULL DEFAULT NULL AFTER `last_edit_ip`;

ALTER TABLE `reports` ADD `repository_id` INT(32) NULL DEFAULT NULL AFTER `allow_contact`;
ALTER TABLE `reports` ADD `surname` VARCHAR(64) NULL DEFAULT NULL AFTER `name`;

-- 1.6.6 END

