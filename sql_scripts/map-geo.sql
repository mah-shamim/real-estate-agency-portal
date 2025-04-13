DELETE FROM `treefield`;

DELETE FROM `treefield_lang`;


INSERT INTO `treefield` (`id`, `field_id`, `root_id`, `parent_id`, `order`, `level`, `field_name`, `template`, `repository_id`, `affilate_price`, `notifications_sent`) VALUES
(36, 64, NULL, 0, 0, 0, NULL, '0', NULL, NULL, 1),
(37, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(38, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(39, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(40, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(41, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(42, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(43, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(44, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(45, 64, NULL, 36, 0, 1, NULL, 'treefield_treefield', NULL, NULL, 1),
(46, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(48, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(49, 64, NULL, 36, 0, 1, NULL, 'treefield_treefield', NULL, NULL, 1),
(50, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(51, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(52, 64, NULL, 36, 0, 1, NULL, 'treefield_treefield', NULL, NULL, 1),
(53, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(54, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(55, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(56, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1),
(57, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1);


INSERT INTO `treefield_lang` (`id`, `treefield_id`, `language_id`, `value`, `value_path`, `title`, `path_title`, `address`, `body`, `keywords`, `description`, `slug`, `adcode1`, `adcode2`, `adcode3`, `adcode4`, `adcode5`, `adcode6`) VALUES
(391, 36, 1, 'Croatia', 'Croatia', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(392, 36, 2, 'Croatia', 'Croatia', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(393, 37, 1, 'Vukovar and Srijem', 'Croatia - Vukovar and Srijem', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(394, 37, 2, 'Vukovar and Srijem', 'Croatia - Vukovar and Srijem', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(395, 38, 1, 'Osijek and Baranja', 'Croatia - Osijek and Baranja', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(396, 38, 2, 'Osijek and Baranja', 'Croatia - Osijek and Baranja', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(397, 39, 1, 'Virovitica and Podravina', 'Croatia - Virovitica and Podravina', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(398, 39, 2, 'Virovitica and Podravina', 'Croatia - Virovitica and Podravina', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(399, 40, 1, 'Koprivnica and Knizevci', 'Croatia - Koprivnica and Knizevci', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(400, 40, 2, 'Koprivnica and Knizevci', 'Croatia - Koprivnica and Knizevci', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(401, 41, 1, 'Medjimurje', 'Croatia - Medjimurje', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(402, 41, 2, 'Medjimurje', 'Croatia - Medjimurje', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(403, 42, 1, 'Varazdin', 'Croatia - Varazdin', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(404, 42, 2, 'Varazdin', 'Croatia - Varazdin', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(405, 43, 1, 'Krapina and Zagorje', 'Croatia - Krapina and Zagorje', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(406, 43, 2, 'Krapina and Zagorje', 'Croatia - Krapina and Zagorje', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(407, 44, 1, 'Zagreb', 'Croatia - Zagreb', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(408, 44, 2, 'Zagreb', 'Croatia - Zagreb', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(411, 46, 1, 'Pozega and Slavonia', 'Croatia - Pozega and Slavonia', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(412, 46, 2, 'Pozega and Slavonia', 'Croatia - Pozega and Slavonia', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(415, 45, 1, 'Bjelovar and Bilogora', 'Croatia - Bjelovar and Bilogora', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(416, 45, 2, 'Bjelovar and Bilogora', 'Croatia - Bjelovar and Bilogora', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(417, 48, 1, 'Slavonski Brod and Posavina', 'Croatia - Slavonski Brod and Posavina', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(418, 48, 2, 'Slavonski Brod and Posavina', 'Croatia - Slavonski Brod and Posavina', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(421, 50, 1, 'Karlovac', 'Croatia - Karlovac', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(422, 50, 2, 'Karlovac', 'Croatia - Karlovac', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(423, 51, 1, 'Rijeka', 'Croatia - Rijeka', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(424, 51, 2, 'Rijeka', 'Croatia - Rijeka', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(429, 53, 1, 'Lika and Senj', 'Croatia - Lika and Senj', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(430, 53, 2, 'Lika and Senj', 'Croatia - Lika and Senj', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(431, 54, 1, 'Zadar', 'Croatia - Zadar', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(432, 54, 2, 'Zadar', 'Croatia - Zadar', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(433, 55, 1, 'Sibenik and Knin', 'Croatia - Sibenik and Knin', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(434, 55, 2, 'Sibenik and Knin', 'Croatia - Sibenik and Knin', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(435, 56, 1, 'Split and Dalmatia', 'Croatia - Split and Dalmatia', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(436, 56, 2, 'Split and Dalmatia', 'Croatia - Split and Dalmatia', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(437, 57, 1, 'Dubrovnik and Neretva', 'Croatia - Dubrovnik and Neretva', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(438, 57, 2, 'Dubrovnik and Neretva', 'Croatia - Dubrovnik and Neretva', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(439, 49, 1, 'Sisak and Moslavina', 'Croatia - Sisak and Moslavina', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(440, 49, 2, 'Sisak and Moslavina', 'Croatia - Sisak and Moslavina', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(441, 52, 1, 'Istria', 'Croatia - Istria', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(442, 52, 2, 'Istria', 'Croatia - Istria', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');


INSERT INTO `treefield` (`id`, `field_id`, `root_id`, `parent_id`, `order`, `level`, `field_name`, `template`, `repository_id`, `affilate_price`, `notifications_sent`) VALUES
(58, 64, NULL, 36, 0, 1, NULL, '0', NULL, NULL, 1);

INSERT INTO `treefield_lang` (`id`, `treefield_id`, `language_id`, `value`, `value_path`, `title`, `path_title`, `address`, `body`, `keywords`, `description`, `slug`, `adcode1`, `adcode2`, `adcode3`, `adcode4`, `adcode5`, `adcode6`) VALUES
(443, 58, 1, 'Surroundings of Zagreb', 'Croatia - Surroundings of Zagreb', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(444, 58, 2, 'Surroundings of Zagreb', 'Croatia - Surroundings of Zagreb', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');

