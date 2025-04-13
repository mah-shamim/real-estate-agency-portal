INSERT INTO `page` (`id`, `type`, `template`, `template_header`, `template_footer`, `parent_id`, `is_visible`, `is_private`, `order`, `date`, `date_publish`) VALUES (170, 'PAGE', 'page_homepage-filters', 0, 0, 0, '1', 0, 15, '2018-02-27 19:59:38', '2018-02-27 19:59:38');

DELETE FROM `page_lang`
WHERE `page_id` =  170;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 170, 'Homepage', 'Homepage', '<div class=\"row\">\r\n<div class=\"span1\"><img src=\"assets/img/multilangual.png\" /></div>\r\n\r\n<div class=\"span3\">\r\n<h4>Native Multilangual</h4>\r\nNatively multilingual, no need to install any addon. Every element can be translated and you can add as many languages as you want.</div>\r\n\r\n<div class=\"span1\"><img src=\"assets/img/easy_customize.png\" /></div>\r\n\r\n<div class=\"span3\">\r\n<h4>Easy to customize</h4>\r\nApplication is based on CodeIgniter PHP framework, if you know CodeIgniter you can easily customize the system.</div>\r\n\r\n<div class=\"span1\"><img src=\"assets/img/bootstrap.png\" /></div>\r\n\r\n<div class=\"span3\">\r\n<h4>Bootstrap ready</h4>\r\nBackend and Frontend based on Bootstrap and are easy to customize.</div>\r\n</div>\r\n\r\n<div class=\"row\">\r\n<div class=\"span1\"><img src=\"assets/img/drag-drop.png\" /></div>\r\n\r\n<div class=\"span3\">\r\n<h4>User friendly</h4>\r\nWebsite structure is logical. Managing elements such as pages, estates or images is easily done by drag&#39;n&#39;drop!</div>\r\n\r\n<div class=\"span1\"><img src=\"assets/img/template.png\" /></div>\r\n\r\n<div class=\"span3\">\r\n<h4>Template System</h4>\r\nEasy to use CodeIgniter Template Parser Class or alternative PHP syntax available.</div>\r\n\r\n<div class=\"span1\"><img src=\"assets/img/robust.png\" /></div>\r\n\r\n<div class=\"span3\">\r\n<h4>Robust</h4>\r\nBuild as easy to use and robust Metro style Admin user interface</div>\r\n</div>', '', 'Homepage');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 170, 'Homepage', 'Homepage', '<div class=\"row\">\r\n<div class=\"span1\"><img src=\"assets/img/multilangual.png\" /></div>\r\n\r\n<div class=\"span3\">\r\n<h4>Native Multilangual</h4>\r\nNatively multilingual, no need to install any addon. Every element can be translated and you can add as many languages as you want.</div>\r\n\r\n<div class=\"span1\"><img src=\"assets/img/easy_customize.png\" /></div>\r\n\r\n<div class=\"span3\">\r\n<h4>Easy to customize</h4>\r\nApplication is based on CodeIgniter PHP framework, if you know CodeIgniter you can easily customize the system.</div>\r\n\r\n<div class=\"span1\"><img src=\"assets/img/bootstrap.png\" /></div>\r\n\r\n<div class=\"span3\">\r\n<h4>Bootstrap ready</h4>\r\nBackend and Frontend based on Bootstrap and are easy to customize.</div>\r\n</div>\r\n\r\n<div class=\"row\">\r\n<div class=\"span1\"><img src=\"assets/img/drag-drop.png\" /></div>\r\n\r\n<div class=\"span3\">\r\n<h4>User friendly</h4>\r\nWebsite structure is logical. Managing elements such as pages, estates or images is easily done by drag&#39;n&#39;drop!</div>\r\n\r\n<div class=\"span1\"><img src=\"assets/img/template.png\" /></div>\r\n\r\n<div class=\"span3\">\r\n<h4>Template System</h4>\r\nEasy to use CodeIgniter Template Parser Class or alternative PHP syntax available.</div>\r\n\r\n<div class=\"span1\"><img src=\"assets/img/robust.png\" /></div>\r\n\r\n<div class=\"span3\">\r\n<h4>Robust</h4>\r\nBuild as easy to use and robust Metro style Admin user interface</div>\r\n</div>', '', 'Homepage');
INSERT INTO `page` (`id`,`type`, `template`, `template_header`, `template_footer`, `parent_id`, `is_visible`, `is_private`, `order`, `date`, `date_publish`) VALUES (171, 'PAGE', 'page_homepage-search-with-location', 0, '', 0, '1', 0, 15, '2018-02-27 20:02:06', '2018-02-27 20:02:06');
DELETE FROM `page_lang`
WHERE `page_id` =  171;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 171, 'Search with locations', 'Search with locations', '', '', 'Search with locations');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 171, 'Search with locations', 'Search with locations', '', '', 'Search with locations');
INSERT INTO `page` (`id`,`type`, `template`, `template_header`, `template_footer`, `parent_id`, `is_visible`, `is_private`, `order`, `date`, `date_publish`) VALUES (172,'PAGE', 'page_homepage-categories', 0, '', 170, '1', 0, 15, '2018-02-27 20:03:30', '2018-02-27 20:03:30');
DELETE FROM `page_lang`
WHERE `page_id` =  172;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 172, 'Search with categories', 'Search with categories', '', 'Search with categories', 'Search with categories');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 172, 'Search with categories', 'Search with categories', '', 'Search with categories', 'Search with categories');

INSERT INTO `page` (`id`,`type`, `template`, `template_header`, `template_footer`, `parent_id`, `is_visible`, `is_private`, `order`, `date`, `date_publish`) VALUES (173,'PAGE', 'page_homepage-slider-topresults', 0, 'slim', 170, '1', 0, 18, '2018-02-27 20:07:47', '2018-02-27 20:07:47');
DELETE FROM `page_lang`
WHERE `page_id` =  173;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 173, 'Slider top results', 'Slider top results', '', 'Slider top results', 'Slider top results');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 173, 'Slider top results', 'Slider top results', '', 'Slider top results', 'Slider top results');
INSERT INTO `page` (`id`,`type`, `template`, `template_header`, `template_footer`, `parent_id`, `is_visible`, `is_private`, `order`, `date`, `date_publish`) VALUES (174,'PAGE', 'page_homepage', 0, '', 0, '1', 0, 18, '2018-02-27 20:08:23', '2018-02-27 20:08:23');
DELETE FROM `page_lang`
WHERE `page_id` =  174;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 174, 'Map examples', 'Map examples', '', 'Map examples', 'Map examples');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 174, 'Map examples', 'Map examples', '', 'Map examples', 'Map examples');
INSERT INTO `page` (`id`,`type`, `template`, `template_header`, `template_footer`, `parent_id`, `is_visible`, `is_private`, `order`, `date`, `date_publish`) VALUES (175,'PAGE', 'page_homepage-side-list', 0, '', 174, '1', 0, 18, '2018-02-27 20:09:25', '2018-02-27 20:09:25');
DELETE FROM `page_lang`
WHERE `page_id` =  175;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 175, 'Map by Side search', 'Map by Side search', '', 'Map by Side search', 'Map by Side search');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 175, 'Map by Side search', 'Map by Side search', '', 'Map by Side search', 'Map by Side search');
UPDATE `page` SET `type` = 'PAGE', `template` = 'page_homepage-geo_search', `template_header` = 0, `template_footer` = '', `parent_id` = 170, `is_visible` = '1', `is_private` = 0 WHERE `id` =  172;
DELETE FROM `page_lang`
WHERE `page_id` =  172;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 172, 'Search with categories', 'Search with categories', '', 'Search with categories', 'Search with categories');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 172, 'Search with categories', 'Search with categories', '', 'Search with categories', 'Search with categories');
INSERT INTO `page` (`id`,`type`, `template`, `template_header`, `template_footer`, `parent_id`, `is_visible`, `is_private`, `order`, `date`, `date_publish`) VALUES (176, 'PAGE', 'page_customsearch', 0, '', 174, '1', 0, 18, '2018-02-27 20:12:09', '2018-02-27 20:12:09');
DELETE FROM `page_lang`
WHERE `page_id` =  176;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 176, 'Small map', 'Small map', '', 'Small map', 'Small map');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 176, 'Small map', 'Small map', '', 'Small map', 'Small map');
INSERT INTO `page` (`id`,`type`, `template`, `template_header`, `template_footer`, `parent_id`, `is_visible`, `is_private`, `order`, `date`, `date_publish`) VALUES (177, 'PAGE', 'page_homepage', 0, '', 174, '1', 0, 18, '2018-02-27 20:13:48', '2018-02-27 20:13:48');
DELETE FROM `page_lang`
WHERE `page_id` =  177;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 177, 'Map on top', 'Map on top', '', 'Map on top', 'Map on top');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 177, 'Map on top', 'Map on top', '', 'Map on top', 'Map on top');
UPDATE `page` SET `type` = 'PAGE', `template` = 'page_news', `template_header` = 0, `template_footer` = '', `parent_id` = 5, `is_visible` = '1', `is_private` = 0 WHERE `id` =  142;
DELETE FROM `page_lang`
WHERE `page_id` =  142;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 142, 'Blog', 'Blog page', '<p><span style=\"font-weight: bold;\">Nam eget est facilisis, porta mi ac, ultricies enim. Proin nisi diam, eleifend ac eleifend in, dapibus in orci. Vestibulum elementum lectus non nisl venenatis, tempus molestie nisi tempus. Pellentesque facilisis nibh nec purus blandit, id aliquam lorem fermentum. </span></p>', 'Nam eget est facilisis, porta mi ac, ultricies enim. Proin nisi diam, eleifend ac eleifend in, dapibus in orci. Vestibulum elementum lectus non nisl venenatis, tempus molestie nisi tempus. Pellentesque facilisis nibh nec purus blandit, id aliquam lorem fermentum.', 'Blog');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 142, 'Blog', 'Blog stranica', '<p><span style=\"font-weight: bold;\">Nam eget est facilisis, porta mi ac, ultricies enim. Proin nisi diam, eleifend ac eleifend in, dapibus in orci. Vestibulum elementum lectus non nisl venenatis, tempus molestie nisi tempus. Pellentesque facilisis nibh nec purus blandit, id aliquam lorem fermentum. </span></p>', 'Nam eget est facilisis, porta mi ac, ultricies enim. Proin nisi diam, eleifend ac eleifend in, dapibus in orci. Vestibulum elementum lectus non nisl venenatis, tempus molestie nisi tempus. Pellentesque facilisis nibh nec purus blandit, id aliquam lorem fermentum.', 'Blog');
UPDATE `page` SET `parent_id` = 0 WHERE `parent_id` =  '143';
DELETE FROM `page_lang`
WHERE `page_id` =  143;
DELETE FROM `page`
WHERE `id` =  143 LIMIT 1;
UPDATE `page` SET `type` = 'PAGE', `template` = 'page_page', `template_header` = 0, `template_footer` = '', `parent_id` = 5, `is_visible` = '1', `is_private` = 0 WHERE `id` =  146;
DELETE FROM `page_lang`
WHERE `page_id` =  146;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 146, 'External link', 'External link', '<p>http://www.google.com</p>', 'This is just a link example, so you need enter your link to keywords with \'http\'', 'http://www.google.com');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 146, 'Google link', 'Google link', '<p>http://www.google.com</p>', 'This is just a link example, so you need enter your link to keywords with \'http\'', 'http://www.google.com');
DELETE FROM `page_lang`
WHERE `page_id` =  163;
DELETE FROM `page`
WHERE `id` =  163 LIMIT 1;
UPDATE `page` SET `parent_id` = 0 WHERE `parent_id` =  '163';
INSERT INTO `page` (`id`,`type`, `template`, `template_header`, `template_footer`, `parent_id`, `is_visible`, `is_private`, `order`, `date`, `date_publish`) VALUES (178, 'PAGE', 'page_contact', 0, '', 4, '1', 0, 15, '2018-02-27 20:17:36', '2018-02-27 20:17:36');
DELETE FROM `page_lang`
WHERE `page_id` =  178;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 178, 'Contact', 'Contact', '<p><span style=\"font-weight: bold;\">CityGuide</span>Map Kiosk from Croatia<br />\r\nIlica 345<br />\r\nHR-10000 Zagreb<br />\r\n<br />\r\n<span style=\"font-weight: bold;\"><span>Tel:</span></span> +385 (0)1 123 321<br />\r\n<span style=\"font-weight: bold;\"><span>Fax:</span></span> +385 (0)1 123 322<br />\r\n<span style=\"font-weight: bold;\"><span>Mail:</span></span> info@info.info</p>', 'Contact', 'Contact');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 178, 'Contact', 'Contact', '<p><span style=\"font-weight: bold;\">CityGuide</span>Map Kiosk from Croatia<br />\r\nIlica 345<br />\r\nHR-10000 Zagreb<br />\r\n<br />\r\n<span style=\"font-weight: bold;\"><span>Tel:</span></span> +385 (0)1 123 321<br />\r\n<span style=\"font-weight: bold;\"><span>Fax:</span></span> +385 (0)1 123 322<br />\r\n<span style=\"font-weight: bold;\"><span>Mail:</span></span> info@info.info</p>', 'Contact', 'Contact');
INSERT INTO `page` (`id`,`type`, `template`, `template_header`, `template_footer`, `parent_id`, `is_visible`, `is_private`, `order`, `date`, `date_publish`) VALUES (179, 'PAGE', 'page_expert', 0, '', 4, '1', 0, 15, '2018-02-27 20:19:04', '2018-02-27 20:19:04');
DELETE FROM `page_lang`
WHERE `page_id` =  179;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 179, 'FAQ', 'FAQ', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sollicitudin commodo cursus. Nunc varius accumsan ultrices. Quisque hendrerit mi id ullamcorper pretium. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam ac metus vitae nibh aliquet adipiscing quis at nibh. Nullam lacinia magna sed enim ullamcorper rutrum.</p>', 'FAQ', 'FAQ');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 179, 'FAQ', 'FAQ', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sollicitudin commodo cursus. Nunc varius accumsan ultrices. Quisque hendrerit mi id ullamcorper pretium. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam ac metus vitae nibh aliquet adipiscing quis at nibh. Nullam lacinia magna sed enim ullamcorper rutrum.</p>', 'FAQ', 'FAQ');
INSERT INTO `page` (`id`,`type`, `template`, `template_header`, `template_footer`, `parent_id`, `is_visible`, `is_private`, `order`, `date`, `date_publish`) VALUES (180, 'PAGE', 'page_agents', 0, '', 4, '1', 0, 15, '2018-02-27 20:23:44', '2018-02-27 20:23:44');
DELETE FROM `page_lang`
WHERE `page_id` =  180;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 180, 'Agent profile', 'Agent profile', '', 'Agent profile', '/index.php/profile/9/en');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 180, 'Agent profile', 'Agent profile', '', 'Agent profile', '/index.php/profile/9/en');
INSERT INTO `page` (`id`,`type`, `template`, `template_header`, `template_footer`, `parent_id`, `is_visible`, `is_private`, `order`, `date`, `date_publish`) VALUES (181, 'PAGE', 'page_homepage-filters', 0, '', 4, '1', 0, 15, '2018-02-27 20:25:33', '2018-02-27 20:25:33');
DELETE FROM `page_lang`
WHERE `page_id` =  181;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 181, 'Listing preview', 'Listing preview', '', 'Listing preview', 'index.php/property/6/en/coffeehouse');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 181, 'Listing preview', 'Listing preview', '', 'Listing preview', 'index.php/property/6/en/coffeehouse');

UPDATE `page` SET `parent_id` = 0 WHERE `parent_id` =  '6';
DELETE FROM `page_lang`
WHERE `page_id` =  6;
DELETE FROM `page`
WHERE `id` =  6 LIMIT 1;

UPDATE `page` SET `parent_id` = 0 WHERE `parent_id` =  '1';
DELETE FROM `page_lang`
WHERE `page_id` =  1;
DELETE FROM `page`
WHERE `id` =  1 LIMIT 1;

UPDATE `page` SET `parent_id` = 0 WHERE `parent_id` =  '145';
DELETE FROM `page_lang`
WHERE `page_id` =  145;
DELETE FROM `page`
WHERE `id` =  145 LIMIT 1;


INSERT INTO `page` (`id`,`type`, `template`, `template_header`, `template_footer`, `parent_id`, `is_visible`, `is_private`, `order`, `date`, `date_publish`) VALUES (182, 'PAGE', 'page_homepage-geo_search', 0, '', 174, '1', 0, 8, '2018-02-27 20:28:16', '2018-02-27 20:28:16');
DELETE FROM `page_lang`
WHERE `page_id` =  182;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 182, 'Search with geo map', 'Search with geo map', '', 'Search with geo map', 'Search with geo map');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 182, 'Search with geo map', 'Search with geo map', '', 'Search with geo map', 'Search with geo map');


SELECT *
FROM (`language`)
WHERE `language` =  'english'
LIMIT 1;
UPDATE `page` SET `parent_id` = 0, `order` = 1 WHERE `id` =  '170';
UPDATE `page` SET `parent_id` = 170, `order` = 2 WHERE `id` =  '171';
UPDATE `page` SET `parent_id` = 170, `order` = 3 WHERE `id` =  '172';
UPDATE `page` SET `parent_id` = 170, `order` = 4 WHERE `id` =  '173';
UPDATE `page` SET `parent_id` = 0, `order` = 5 WHERE `id` =  '174';
UPDATE `page` SET `parent_id` = 174, `order` = 6 WHERE `id` =  '175';
UPDATE `page` SET `parent_id` = 174, `order` = 7 WHERE `id` =  '182';
UPDATE `page` SET `parent_id` = 174, `order` = 8 WHERE `id` =  '176';
UPDATE `page` SET `parent_id` = 174, `order` = 9 WHERE `id` =  '177';
UPDATE `page` SET `parent_id` = 0, `order` = 10 WHERE `id` =  '5';
UPDATE `page` SET `parent_id` = 5, `order` = 11 WHERE `id` =  '142';
UPDATE `page` SET `parent_id` = 5, `order` = 12 WHERE `id` =  '169';
UPDATE `page` SET `parent_id` = 5, `order` = 13 WHERE `id` =  '147';
UPDATE `page` SET `parent_id` = 5, `order` = 14 WHERE `id` =  '146';
UPDATE `page` SET `parent_id` = 5, `order` = 15 WHERE `id` =  '157';
UPDATE `page` SET `parent_id` = 157, `order` = 16 WHERE `id` =  '158';
UPDATE `page` SET `parent_id` = 157, `order` = 17 WHERE `id` =  '156';
UPDATE `page` SET `parent_id` = 5, `order` = 18 WHERE `id` =  '162';
UPDATE `page` SET `parent_id` = 0, `order` = 19 WHERE `id` =  '4';
UPDATE `page` SET `parent_id` = 4, `order` = 20 WHERE `id` =  '178';
UPDATE `page` SET `parent_id` = 4, `order` = 21 WHERE `id` =  '179';
UPDATE `page` SET `parent_id` = 4, `order` = 22 WHERE `id` =  '180';
UPDATE `page` SET `parent_id` = 4, `order` = 23 WHERE `id` =  '181';


UPDATE `page` SET `type` = 'PAGE', `template` = 'page_agents', `template_header` = 0, `template_footer` = '', `parent_id` = 4, `is_visible` = '1', `is_private` = 0 WHERE `id` =  180;
DELETE FROM `page_lang`
WHERE `page_id` =  180;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 180, 'Agent profile', 'Agent profile', '', 'Agent profile', '%site_domain$s/index.php/profile/9/en');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 180, 'Agent profile', 'Agent profile', '', 'Agent profile', '%site_domain$s/index.php/profile/9/hr');
UPDATE `page` SET `type` = 'PAGE', `template` = 'page_homepage-filters', `template_header` = 0, `template_footer` = '', `parent_id` = 4, `is_visible` = '1', `is_private` = 0 WHERE `id` =  181;
DELETE FROM `page_lang`
WHERE `page_id` =  181;
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 181, 'Listing preview', 'Listing preview', '', 'Listing preview', '%site_domain$s/index.php/property/9/en/coffeehouse');
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 181, 'Listing preview', 'Listing preview', '', 'Listing preview', '%site_domain$s/index.php/property/9/hr/coffeehouse');

UPDATE `page` SET `type` = 'PAGE', `template` = 'page_homepage-categories', `template_header` = 0, `template_footer` = '', `parent_id` = 170, `is_visible` = '1', `is_private` = 0 WHERE `id` =  172
DELETE FROM `page_lang`
WHERE `page_id` =  172
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (1, 172, 'Search with categories', 'Search with categories', '', 'Search with categories', 'Search with categories')
INSERT INTO `page_lang` (`language_id`, `page_id`, `title`, `navigation_title`, `body`, `description`, `keywords`) VALUES (2, 172, 'Search with categories', 'Search with categories', '', 'Search with categories', 'Search with categories')
