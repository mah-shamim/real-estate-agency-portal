
# Fixed problem from 1.4.2
UPDATE `option` SET `order` = `id` WHERE `order` = 0;

# Insert new setting values
INSERT INTO `settings` (`id`, `field`, `value`) VALUES (NULL, 'noreply', 'webform@my-website.com');
INSERT INTO `settings` (`id`, `field`, `value`) VALUES (NULL, 'zoom', '8');








