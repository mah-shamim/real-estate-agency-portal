"dont run this file manually!"

ALTER TABLE `treefield` CHANGE `font_icon_code` `font_icon_code` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

ALTER TABLE `ads` CHANGE `description` `description` VARCHAR(160) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

INSERT INTO `settings` (`field`, `value`) VALUES
('limit_markers', '100');

ALTER TABLE `token_api` ADD `json` TEXT NULL ;

-- 1.6.5 END


