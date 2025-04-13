"dont run this file manually!"

ALTER TABLE `enquire` 
    ADD `from_id` INT NULL DEFAULT NULL AFTER `agent_id`, 
    ADD `to_id` INT NULL DEFAULT NULL AFTER `from_id`;

ALTER TABLE `enquire` 
    ADD `del_from` INT NOT NULL DEFAULT '0' AFTER `to_id`,
    ADD `del_to` INT NOT NULL DEFAULT '0' AFTER `del_from`;


