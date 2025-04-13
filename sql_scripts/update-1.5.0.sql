
ALTER TABLE `option_lang` ADD COLUMN `prefix` VARCHAR(15) NULL DEFAULT NULL  AFTER `values` ;

ALTER TABLE `property` ADD COLUMN `featured_paid_date` DATETIME NULL DEFAULT NULL  AFTER `activation_paid_date` ;

ALTER TABLE `user` ADD COLUMN `facebook_id` VARCHAR(45) NULL DEFAULT NULL  AFTER `package_last_payment` ;

CREATE TABLE IF NOT EXISTS `backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_created` datetime DEFAULT NULL,
  `sql_file` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip_file` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `script_version` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

INSERT INTO `settings` (`field`, `value`) VALUES
('adsense728_90', '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>\r\n<!-- Real estate top -->\r\n<ins class="adsbygoogle"\r\n     style="display:inline-block;width:728px;height:90px"\r\n     data-ad-client="ca-pub-6994200887384132"\r\n     data-ad-slot="8707197571"></ins>\r\n<script>\r\n(adsbygoogle = window.adsbygoogle || []).push({});\r\n</script>'),
('adsense160_600', '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>\r\n<!-- Real estate right -->\r\n<ins class="adsbygoogle"\r\n     style="display:inline-block;width:160px;height:600px"\r\n     data-ad-client="ca-pub-6994200887384132"\r\n     data-ad-slot="7090863570"></ins>\r\n<script>\r\n(adsbygoogle = window.adsbygoogle || []).push({});\r\n</script>'),
('withdrawal_details', 'IBAN: HR43 2340009 3207462177<br/>\r\nSWIFT: PBZGHR2X');




