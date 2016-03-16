CREATE DATABASE IF NOT EXISTS journal;
USE journal;

CREATE TABLE if not exists user
(
user_id int NOT NULL AUTO_INCREMENT,
email varchar(255) NOT NULL,
name varchar(255),
PRIMARY KEY (user_id)
);

-- -----------------------------------------------------
-- Table `journal`.`entry` Created by Olu Egunjobi
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `journal`.`entry` (
  `journal_id` INT NOT NULL AUTO_INCREMENT,
  user_id int NOT NULL,
  `past_thought` LONGTEXT NULL,
  `ponder_question` LONGTEXT NULL,
  `question` LONGTEXT NULL,
  `prompting` LONGTEXT NULL,
  `sharing` LONGTEXT NULL,
  PRIMARY KEY (`journal_id`))
ENGINE = InnoDB;

-- The following statement will delete and entry if you think it is corrupt
-- delete from entry where entry_id = 25;

-- The following will escape double quotes
-- UPDATE entry SET question = REPLACE(question, '"', '\\"') where entry_id = 26;
-- The following will escape single quotes
-- UPDATE entry SET question = REPLACE(question, "'", "\\'") where entry_id = 26;

