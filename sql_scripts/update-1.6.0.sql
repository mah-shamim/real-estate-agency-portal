"dont run this file manually!"

CREATE TABLE IF NOT EXISTS `token_api` (
`id` int(11) NOT NULL,
  `date_last_access` datetime NOT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `other` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `property` ADD `id_trans_text` VARCHAR(60) NULL DEFAULT NULL AFTER `id_transitions`;


