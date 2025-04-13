
ALTER TABLE `page` ADD COLUMN `is_visible` TINYINT(1) NULL DEFAULT 1  AFTER `repository_id` ;

CREATE  TABLE IF NOT EXISTS `showroom` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `parent_id` INT(11) NULL DEFAULT NULL ,
  `order` INT(11) NULL DEFAULT NULL ,
  `type` VARCHAR(45) NOT NULL DEFAULT 'CATEGORY' ,
  `date` DATETIME NULL DEFAULT NULL ,
  `date_publish` DATETIME NULL DEFAULT NULL ,
  `template` VARCHAR(100) NULL DEFAULT NULL ,
  `slug` VARCHAR(160) NULL DEFAULT NULL ,
  `repository_id` INT(11) NULL DEFAULT NULL ,
  `gps` VARCHAR(100) NULL DEFAULT NULL ,
  `address` VARCHAR(200) NULL DEFAULT NULL ,
  `contact_email` VARCHAR(100) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE  TABLE IF NOT EXISTS `showroom_lang` (
  `id_showroom_lang` INT(11) NOT NULL ,
  `showroom_id` INT(11) NOT NULL ,
  `language_id` INT(11) NOT NULL ,
  `company_name` VARCHAR(160) NULL DEFAULT NULL ,
  `window_title` VARCHAR(160) NULL DEFAULT NULL ,
  `body` TEXT NULL DEFAULT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `keywords` VARCHAR(200) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_showroom_lang`) ,
  INDEX `fk_showroom_lang_showroom1` (`showroom_id` ASC) ,
  INDEX `fk_showroom_lang_language1` (`language_id` ASC) ,
  CONSTRAINT `fk_showroom_lang_showroom1`
    FOREIGN KEY (`showroom_id` )
    REFERENCES `showroom` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_showroom_lang_language1`
    FOREIGN KEY (`language_id` )
    REFERENCES `language` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `showroom` ADD COLUMN `date_modified` DATETIME NULL DEFAULT NULL  AFTER `date` ;

ALTER TABLE `showroom_lang` CHANGE COLUMN `company_name` `title` VARCHAR(160) NULL DEFAULT NULL  ;

ALTER TABLE `showroom_lang` CHANGE COLUMN `id_showroom_lang` `id_showroom_lang` INT(11) NOT NULL AUTO_INCREMENT  ;

ALTER TABLE `showroom_lang` CHANGE COLUMN `body` `body` TEXT NOT NULL  , DROP FOREIGN KEY `fk_showroom_lang_showroom1` ;

-- Option video
INSERT IGNORE INTO `option` (`id`,  `parent_id`, `type`, `visible`, `is_locked`, `is_frontend`, `order`) VALUES (9, '0', 'CATEGORY', 0, '1', 0, 100); 
INSERT IGNORE INTO `option_lang` (`language_id`, `option_id`, `values`, `suffix`, `option`) VALUES (1, 9, '', '', 'Multimedia');
INSERT IGNORE INTO `option_lang` (`language_id`, `option_id`, `values`, `suffix`, `option`) VALUES (2, 9, '', '', 'Multimedia');  

INSERT IGNORE INTO `option` (`id`,  `parent_id`, `type`, `visible`, `is_locked`, `is_frontend`, `order`) VALUES (12, '9', 'INPUTBOX', 0, '1', 0, 101); 
INSERT IGNORE INTO `option_lang` (`language_id`, `option_id`, `values`, `suffix`, `option`) VALUES (1, 12, '', '', 'Embed video');
INSERT IGNORE INTO `option_lang` (`language_id`, `option_id`, `values`, `suffix`, `option`) VALUES (2, 12, '', '', 'Integrirani video');  

-- Q & A

ALTER TABLE `user` ADD COLUMN `qa_id` INT(11) NULL DEFAULT NULL  AFTER `activated` ;

CREATE  TABLE IF NOT EXISTS `qa` (
  `id` INT(11) NOT NULL ,
  `parent_id` INT(11) NULL DEFAULT NULL ,
  `order` INT(11) NULL DEFAULT NULL ,
  `date` DATETIME NULL DEFAULT NULL ,
  `date_modified` DATETIME NULL DEFAULT NULL ,
  `date_publish` DATETIME NULL DEFAULT NULL ,
  `user_id` INT(11) NULL DEFAULT NULL ,
  `type` VARCHAR(45) NOT NULL DEFAULT 'CATEGORY' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE  TABLE IF NOT EXISTS `qa_lang` (
  `id_qa_lang` INT(11) NOT NULL ,
  `qa_id` INT(11) NOT NULL ,
  `language_id` INT(11) NOT NULL ,
  `question` TEXT NULL DEFAULT NULL ,
  `answer` TEXT NULL DEFAULT NULL ,
  `keywords` VARCHAR(160) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_qa_lang`) ,
  INDEX `fk_qa_lang_qa1` (`qa_id` ASC) ,
  INDEX `fk_qa_lang_language1` (`language_id` ASC) ,
  CONSTRAINT `fk_qa_lang_qa1`
    FOREIGN KEY (`qa_id` )
    REFERENCES `qa` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_qa_lang_language1`
    FOREIGN KEY (`language_id` )
    REFERENCES `language` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `qa_lang` CHANGE COLUMN `id_qa_lang` `id_qa_lang` INT(11) NOT NULL AUTO_INCREMENT  ;

ALTER TABLE `qa` CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT  ;

ALTER TABLE `qa` ADD COLUMN `is_readed` TINYINT(1) NULL DEFAULT 0  AFTER `type` , ADD COLUMN `answer_user_id` INT(11) NULL DEFAULT NULL  AFTER `is_readed` , CHANGE COLUMN `user_id` `question_user_id` INT(11) NULL DEFAULT NULL  ;





