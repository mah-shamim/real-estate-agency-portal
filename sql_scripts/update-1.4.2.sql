
ALTER TABLE `option` ADD COLUMN `is_hardlocked` TINYINT(1) NULL DEFAULT 0  AFTER `is_frontend` ;

INSERT IGNORE INTO `option` (`id`, `parent_id`, `order`, `type`, `visible`, `is_locked`, `is_frontend`, `is_hardlocked`) VALUES ('6', '0', '5', 'DROPDOWN', '0', '1', '1', '1');

INSERT IGNORE INTO `option_lang` (`id_option_lang`, `option_id`, `language_id`, `option`, `values`, `suffix`) VALUES (NULL, '6', '1', 'Icon', 'empty,commercial,house,land,apartment', '');








