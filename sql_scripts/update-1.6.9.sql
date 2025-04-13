"dont run this file manually!"

UPDATE `forms_search` SET `fields_order_primary` = '{ \"PRIMARY\": { \"SMART_SEARCH\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\"\", \"id\":\"NONE\", \"type\":\"SMART_SEARCH\"} ,\"TREE_79\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\"\", \"id\":\"79\", \"type\":\"TREE\"} ,\"TREE_64\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\" \", \"id\":\"64\", \"type\":\"TREE\"} ,\"C_PURPOSE\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\"\", \"id\":\"NONE\", \"type\":\"C_PURPOSE\"} ,\"C_PRICE_RANGE\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\"\", \"id\":\"NONE\", \"type\":\"C_PRICE_RANGE\"} ,\"DROPDOWN_MULTIPLE_2\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\"\", \"id\":\"2\", \"type\":\"DROPDOWN_MULTIPLE\"} ,\"DROPDOWN_3\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\"\", \"id\":\"3\", \"type\":\"DROPDOWN\"} ,\"INPUTBOX_20_FROM\":{\"direction\":\"FROM\", \"style\":\"\", \"class\":\"\", \"id\":\"20\", \"type\":\"INPUTBOX\"} ,\"INPUTBOX_20_TO\":{\"direction\":\"TO\", \"style\":\"\", \"class\":\"\", \"id\":\"20\", \"type\":\"INPUTBOX\"} ,\"CHECKBOX_31\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\"\", \"id\":\"31\", \"type\":\"CHECKBOX\"} ,\"CHECKBOX_25\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\"\", \"id\":\"25\", \"type\":\"CHECKBOX\"} ,\"CHECKBOX_22\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\"\", \"id\":\"22\", \"type\":\"CHECKBOX\"} ,\"CHECKBOX_29\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\"\", \"id\":\"29\", \"type\":\"CHECKBOX\"} ,\"CHECKBOX_23\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\"\", \"id\":\"23\", \"type\":\"CHECKBOX\"} ,\"CHECKBOX_27\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\"\", \"id\":\"27\", \"type\":\"CHECKBOX\"} ,\"CHECKBOX_11\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\"\", \"id\":\"11\", \"type\":\"CHECKBOX\"} ,\"CHECKBOX_24\":{\"direction\":\"NONE\", \"style\":\"\", \"class\":\"\", \"id\":\"24\", \"type\":\"CHECKBOX\"} }, \"SECONDARY\": { } }' WHERE `forms_search`.`id` = 7;

ALTER TABLE `promocode` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);

CREATE TABLE `historyads` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `listing_id` mediumint(8) UNSIGNED DEFAULT NULL,
  `user_id` mediumint(8) UNSIGNED DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `ip_address` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `historyads`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `historyads` CHANGE `id` `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `property` CHANGE `last_edit_ip` `last_edit_ip` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `treefield` ADD `code` VARCHAR(6) NULL DEFAULT NULL AFTER `font_icon_code`;

ALTER TABLE `user` CHANGE `last_edit_ip` `last_edit_ip` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `treefield_lang` ADD INDEX(`treefield_id`);
ALTER TABLE `treefield_lang` ADD INDEX(`language_id`);


