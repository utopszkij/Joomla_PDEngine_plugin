CREATE TABLE IF NOT EXISTS #__pvoks_acrediteds (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'meghatalmazó',
  `acredited_id` int(11) DEFAULT NULL COMMENT 'meghatalamzott',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `terminate` date DEFAULT NULL COMMENT 'érvényesség vége',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS #__pvoks_categories (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT 'parent category.id',
  `category_type` int(11) NOT NULL COMMENT 'pvok_config.id',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'short name',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'alias for SEO URL',
  `introtext` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `fulltext` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `state` tinyint(1) NOT NULL COMMENT '0 - unpublished, 1 -suggestion 2- opened 3- close',
  `questvalid` varchar(255) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'question-suggestion valid limit szám vagy képlet',
  `created` datetime NOT NULL COMMENT 'time of creation',
  `created_by` int(11) NOT NULL COMMENT 'creator user.id',
  `modified` datetime DEFAULT NULL COMMENT 'time of last modify',
  `modified_by` int(11) DEFAULT NULL COMMENT 'last modify user.id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS #__pvoks_configs (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `config_type` varchar(32) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'question_type | global',
  `title` varchar(128) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'config title',
  `json` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'config json code',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `state` tinyint(1) DEFAULT NULL COMMENT '1- published, 0 - unpublished',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS #__pvoks_logs (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime DEFAULT NULL,
  `object_type` varchar(32) DEFAULT NULL COMMENT 'category|question|option|member',
  `object_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(32) DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS #__pvoks_members (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `state` tinyint(1) DEFAULT NULL COMMENT '0 - unpublished, 1- suggestion, 2- member, 4- acredited',
  `admin` tinyint(4) DEFAULT NULL COMMENT '1 - igen',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS #__pvoks_options (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `question_id` int(11) NOT NULL DEFAULT '0' COMMENT 'parent question.id',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'short name',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'alias for SEO URL',
  `introtext` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `fulltext` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `state` int(1) NOT NULL COMMENT '0 - unpublished, 1 - suggestion, 2-  option',
  `ordering` int(11) NOT NULL COMMENT 'list ordering',
  `created` datetime NOT NULL COMMENT 'time of creation',
  `created_by` int(11) NOT NULL COMMENT 'creator user.id',
  `modified` datetime DEFAULT NULL COMMENT 'time of last modify',
  `modified_by` int(11) DEFAULT NULL COMMENT 'last modify user.id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS #__pvoks_questions (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pvoks_server_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'pvoks server question ID',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT 'parent category.id',
  `question_type` int(11) NOT NULL COMMENT 'pvoks_config.id',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'short name',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'alias for SEO URL',
  `introtext` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `fulltext` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `state` int(2) NOT NULL COMMENT '0 - unpublished 1 - suggestion 0,1,2.. - stepNo ',
  `secret` int(1) NOT NULL DEFAULT '0' COMMENT '0 - nem zárolt, 1 - szavazás folyik, 2 - szavazás lezárva',
  `publicvote` int(1) NOT NULL DEFAULT '1' COMMENT 'Nyilt szavazás',
  `accredite_enabled` int(1) NOT NULL COMMENT 'accredite is enabled?',
  `target_category_id` int(11) NOT NULL,
  `termins` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'json tömb index:step, érték: határidő dátum vagy képlet',
  `optvalid` varchar(255) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'option-sufgestion valid szám vagy képlet',
  `debatevalid` varchar(255) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'vita lezáráshoz szükséges támogatottság szám vagy képlet',
  `votevalid` varchar(255) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'szavazás érvényességi küszöb szám vagy képlet',
  `created` datetime NOT NULL COMMENT 'time of creation',
  `created_by` int(11) NOT NULL COMMENT 'creator user.id',
  `modified` datetime DEFAULT NULL COMMENT 'time of last modify',
  `modified_by` int(11) NOT NULL COMMENT 'last modify user.id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS #__pvoks_supports (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `object_type` varchar(32) NOT NULL COMMENT 'category|question1|question2|option|member',
  `object_id` int(11) NOT NULL COMMENT 'pvoks_question.id',
  `user_id` int(11) NOT NULL COMMENT 'user.id',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS #__pvoks_voters (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pvoks_server_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT 'pvoks server ürlap ID',
  `created` datetime DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS #__pvoks_votes (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `question_id` int(11) NOT NULL COMMENT 'pvoks_question.id',
  `option_id` int(11) NOT NULL COMMENT 'pvoks_option.id',
  `position` int(11) NOT NULL,
  `anonym_voter` int(11) NOT NULL COMMENT 'voter created anonym ID',
  `voter_id` int(11) NOT NULL COMMENT 'valid voter_id (if published voks)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
DELIMITER |
DROP TRIGGER IF EXISTS `acredited_insert_log` $$
CREATE TRIGGER `acredited_insert_log` AFTER INSERT ON #__pvoks_acrediteds FOR EACH ROW BEGIN
		SET @S = CONCAT(NEW.id,";", 
		NEW.category_id,";",  
		NEW.user_id,";",  
		NEW.acredited_id,";",  
		NEW.created, ";", 
		NEW.modified,";",  
		NEW.terminate,";"); 
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"acredited",NEW.id, NEW.user_id, "INSERT",@S);
    END |
DELIMITER ;

DELIMITER |
DROP TRIGGER IF EXISTS `acredited_update_log` $$
CREATE TRIGGER `acredited_update_log` AFTER UPDATE ON #__pvoks_acrediteds FOR EACH ROW BEGIN
		SET @S = CONCAT(NEW.id,";", 
		NEW.category_id,";",  
		NEW.user_id,";",  
		NEW.acredited_id,";",  
		NEW.created, ";", 
		NEW.modified,";",  
		NEW.terminate,";"); 
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"acredited",NEW.id, NEW.user_id, "UPDATE",@S);
    END |
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `acredited_delete_log` $$
CREATE TRIGGER `acredited_delete_log` AFTER DELETE ON #__pvoks_acrediteds FOR EACH ROW BEGIN
		SET @S = CONCAT(OLD.id,";", 
		OLD.category_id,";",  
		OLD.user_id,";",  
		OLD.acredited_id,";",  
		OLD.created, ";", 
		OLD.modified,";",  
		OLD.terminate,";"); 
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"acredited",OLD.id, OLD.user_id, "DELETE",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `category_insert_log` $$
CREATE TRIGGER `category_insert_log` AFTER INSERT ON #__pvoks_categories FOR EACH ROW BEGIN
    	SET @S = CONCAT(NEW.id,";", 
	    NEW.parent_id,";", 
	    NEW.category_type,";", 
	    NEW.title,";", 
    	NEW.alias,";", 
    	NEW.introtext,";", 
    	NEW.fulltext,";", 
    	NEW.state,";", 
	    NEW.questvalid,";", 
	    NEW.created,";", 
    	NEW.created_by,";", 
    	NEW.modified,";", 
    	NEW.modified_by);
	  INSERT INTO #__pvoks_logs VALUES (0,NOW(),"category",NEW.id, NEW.created_by, "INSERT",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `category_update_log` $$
CREATE  TRIGGER `category_update_log` AFTER UPDATE ON #__pvoks_categories FOR EACH ROW BEGIN
    	SET @S = CONCAT(NEW.id,";", 
	    NEW.parent_id,";", 
	    NEW.category_type,";", 
	    NEW.title,";", 
    	NEW.alias,";", 
    	NEW.introtext,";", 
    	NEW.fulltext,";", 
    	NEW.state,";", 
	    NEW.questvalid,";", 
	    NEW.created,";", 
    	NEW.created_by,";", 
    	NEW.modified,";", 
    	NEW.modified_by);
	INSERT INTO #__pvoks_logs VALUES (0,NOW(),"category",NEW.id, NEW.created_by, "UPDATE",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `category_delete_log` $$
CREATE TRIGGER `category_delete_log` AFTER DELETE ON #__pvoks_categories FOR EACH ROW BEGIN
    	SET @S = CONCAT(OLD.id,";", 
	    OLD.parent_id,";", 
	    OLD.category_type,";", 
	    OLD.title,";", 
    	OLD.alias,";", 
    	OLD.introtext,";", 
    	OLD.fulltext,";", 
    	OLD.state,";", 
	    OLD.questvalid,";", 
	    OLD.created,";", 
    	OLD.created_by,";", 
    	OLD.modified,";", 
    	OLD.modified_by);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"category",OLD.id, OLD.created_by, "DELETE",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `config_insert_log` $$
CREATE TRIGGER `config_insert_log` AFTER INSERT ON #__pvoks_configs FOR EACH ROW BEGIN
 	  SET @S = CONCAT(NEW.id,";", 
	  NEW.config_type,";",  
	  NEW.title,";",  
	  NEW.json,";",  
	  NEW.created,";",  
	  NEW.created_by,";",  
	  NEW.modified,";",  
	  NEW.modified_by,";",  
	  NEW.state);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"config",NEW.id, NEW.created_by, "INSERT",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `config_update_log` $$
CREATE TRIGGER `config_update_log` AFTER UPDATE ON #__pvoks_configs FOR EACH ROW BEGIN
 	  SET @S = CONCAT(NEW.id,";", 
	  NEW.config_type,";",  
	  NEW.title,";",  
	  NEW.json,";",  
	  NEW.created,";",  
	  NEW.created_by,";",  
	  NEW.modified,";",  
	  NEW.modified_by,";",  
	  NEW.state);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"config",NEW.id, NEW.created_by, "UPDATE",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `config_delete_log` $$
CREATE TRIGGER `config_delete_log` AFTER DELETE ON #__pvoks_configs FOR EACH ROW BEGIN
 	  SET @S = CONCAT(OLD.id,";", 
	  OLD.config_type,";",  
	  OLD.title,";",  
	  OLD.json,";",  
	  OLD.created,";",  
	  OLD.created_by,";",  
	  OLD.modified,";",  
	  OLD.modified_by,";",  
	  OLD.state);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"config",OLD.id, OLD.created_by, "DELETE",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `member_insert_log` $$
CREATE TRIGGER `member_insert_log` AFTER INSERT ON #__pvoks_members FOR EACH ROW BEGIN
	  SET @S = CONCAT(NEW.id,";", 
	  NEW.category_id,";",  
	  NEW.user_id,";",  
	  NEW.state,";",  
	  NEW.admin,";",  
	  NEW.created,";",  
	  NEW.created_by,";",  
	  NEW.modified,";",  
	  NEW.modified_by);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"member",NEW.id, NEW.created_by, "INSERT",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `member_update_log` $$
CREATE TRIGGER `member_update_log` AFTER UPDATE ON #__pvoks_members FOR EACH ROW BEGIN
	  SET @S = CONCAT(NEW.id,";", 
	  NEW.category_id,";",  
	  NEW.user_id,";",  
	  NEW.state,";",  
	  NEW.admin,";",  
	  NEW.created,";",  
	  NEW.created_by,";",  
	  NEW.modified,";",  
	  NEW.modified_by);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"member",NEW.id, NEW.created_by, "UPDATE",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `member_delete_log` $$
CREATE  TRIGGER `member_delete_log` AFTER DELETE ON #__pvoks_members FOR EACH ROW BEGIN
	  SET @S = CONCAT(OLD.id,";", 
	  OLD.category_id,";",  
	  OLD.user_id,";",  
	  OLD.state,";",  
	  OLD.admin,";",  
	  OLD.created,";",  
	  OLD.created_by,";",  
	  OLD.modified,";",  
	  OLD.modified_by);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"member",OLD.id, OLD.created_by, "DELETE",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS  `option_insert_log` $$
CREATE TRIGGER `option_insert_log` AFTER INSERT ON #__pvoks_options FOR EACH ROW BEGIN
	    SET @S = CONCAT(NEW.id,";",  
        NEW.question_id,";",  
        NEW.title,";", 
        NEW.alias,";",  
        NEW.introtext,";",  
        NEW.fulltext,";",  
        NEW.state,";",  
        NEW.ordering,";",  
        NEW.created,";",  
        NEW.created_by,";",  
        NEW.modified,";",  
        NEW.modified_by);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"option",NEW.id, NEW.created_by, "INSERT",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `option_update_log` $$
CREATE TRIGGER `option_update_log` AFTER UPDATE ON #__pvoks_options FOR EACH ROW BEGIN
	    SET @S = CONCAT(NEW.id,";",  
        NEW.question_id,";",  
        NEW.title,";", 
        NEW.alias,";",  
        NEW.introtext,";",  
        NEW.fulltext,";",  
        NEW.state,";",  
        NEW.ordering,";",  
        NEW.created,";",  
        NEW.created_by,";",  
        NEW.modified,";",  
        NEW.modified_by);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"option",NEW.id, NEW.created_by, "UPDATE",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `option_delete_log` $$
CREATE TRIGGER `option_delete_log` AFTER DELETE ON #__pvoks_options FOR EACH ROW BEGIN
	    SET @S = CONCAT(OLD.id,";",  
        OLD.question_id,";",  
        OLD.title,";", 
        OLD.alias,";",  
        OLD.introtext,";",  
        OLD.fulltext,";",  
        OLD.state,";",  
        OLD.ordering,";",  
        OLD.created,";",  
        OLD.created_by,";",  
        OLD.modified,";",  
        OLD.modified_by);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"option",OLD.id, OLD.created_by, "DELETE",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `question_insert_log` $$
CREATE TRIGGER `question_insert_log` AFTER INSERT ON #__pvoks_questions FOR EACH ROW BEGIN
		SET @S = CONCAT(NEW.id,";", 
		NEW.category_id,";", 
		NEW.question_type,";", 
		NEW.title,";", 
		NEW.alias,";", 
		NEW.introtext,";", 
		NEW.fulltext,";", 
		NEW.state,";", 
		NEW.secret,";", 
		NEW.publicvote,";", 
		NEW.accredite_enabled,";", 
		NEW.target_category_id,";", 
		NEW.termins,";", 
		NEW.optvalid,";", 
		NEW.debatevalid,";", 
		NEW.votevalid,";", 
		NEW.created,";", 
		NEW.created_by,";", 
		NEW.modified,";", 
		NEW.modified_by);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"question",NEW.id, NEW.created_by, "INSERT",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `question_update_log` $$
CREATE TRIGGER `question_update_log` AFTER UPDATE ON #__pvoks_questions FOR EACH ROW BEGIN
		SET @S = CONCAT(NEW.id,";", 
		NEW.category_id,";", 
		NEW.question_type,";", 
		NEW.title,";", 
		NEW.alias,";", 
		NEW.introtext,";", 
		NEW.fulltext,";", 
		NEW.state,";", 
		NEW.secret,";", 
		NEW.publicvote,";", 
		NEW.accredite_enabled,";", 
		NEW.target_category_id,";", 
		NEW.termins,";", 
		NEW.optvalid,";", 
		NEW.debatevalid,";", 
		NEW.votevalid,";", 
		NEW.created,";", 
		NEW.created_by,";", 
		NEW.modified,";", 
		NEW.modified_by);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"question",NEW.id, NEW.created_by, "UPDATE",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS  `question_delete_log` /$$
CREATE TRIGGER `question_delete_log` AFTER DELETE ON #__pvoks_questions FOR EACH ROW BEGIN
		SET @S = CONCAT(OLD.id,";", 
		OLD.category_id,";", 
		OLD.question_type,";", 
		OLD.title,";", 
		OLD.alias,";", 
		OLD.introtext,";", 
		OLD.fulltext,";", 
		OLD.state,";", 
		OLD.secret,";", 
		OLD.publicvote,";", 
		OLD.accredite_enabled,";", 
		OLD.target_category_id,";", 
		OLD.termins,";", 
		OLD.optvalid,";", 
		OLD.debatevalid,";", 
		OLD.votevalid,";", 
		OLD.created,";", 
		OLD.created_by,";", 
		OLD.modified,";", 
		OLD.modified_by);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"question",OLD.id, OLD.created_by, "DELETE",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `support_insert_log` $$
CREATE TRIGGER `support_insert_log` AFTER INSERT ON #__pvoks_supports FOR EACH ROW BEGIN
	 SET @S = CONCAT(NEW.id,";", 
	 NEW.object_type,";",  
	 NEW.object_id,";",  
	 NEW.user_id,";",  
	 NEW.created);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"support",NEW.id, NEW.user_id, "INSERT",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `support_update_log` $$
CREATE TRIGGER `support_update_log` AFTER UPDATE ON #__pvoks_supports FOR EACH ROW BEGIN
	 SET @S = CONCAT(NEW.id,";", 
	 NEW.object_type,";",  
	 NEW.object_id,";",  
	 NEW.user_id,";",  
	 NEW.created);
     INSERT INTO #__pvoks_logs VALUES (0,NOW(),"support",NEW.id, NEW.user_id, "UPDATE",@S);
END $$
DELIMITER ;

DELIMITER $$
 DROP TRIGGER IF EXISTS `support_delete_log` $$
 CREATE TRIGGER `support_delete_log` AFTER DELETE ON #__pvoks_supports FOR EACH ROW BEGIN
	 SET @S = CONCAT(OLD.id,";", 
	 OLD.object_type,";",  
	 OLD.object_id,";",  
	 OLD.user_id,";",  
	 OLD.created);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"support",OLD.id, OLD.user_id, "DELETE",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `voter_insert_log` $$
CREATE  TRIGGER `voter_insert_log` AFTER INSERT ON #__pvoks_voters FOR EACH ROW BEGIN
 	  SET @S = CONCAT(NEW.id,";", 
	  NEW.question_id,";",  
	  NEW.user_id,";",  
	  NEW.created);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"voter",NEW.id, NEW.user_id, "INSERT",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `voter_update_log` $$
CREATE TRIGGER `voter_update_log` AFTER UPDATE ON #__pvoks_voters FOR EACH ROW BEGIN
 	  SET @S = CONCAT(NEW.id,";", 
	  NEW.question_id,";",  
	  NEW.user_id,";",  
	  NEW.created);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"voter",NEW.id, NEW.user_id, "UPDATE",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `voter_delete_log` $$
CREATE TRIGGER `voter_delete_log` AFTER DELETE ON #__pvoks_voters FOR EACH ROW BEGIN
 	  SET @S = CONCAT(OLD.id,";", 
	  OLD.question_id,";",  
	  OLD.user_id,";",  
	  OLD.created);
      INSERT INTO #__pvoks_logs VALUES (0,NOW(),"voter",OLD.id, OLD.user_id, "DELETE",@S);
    END $$
DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS vote_insert_log` $$
CREATE TRIGGER `vote_insert_log` AFTER INSERT ON #__pvoks_votes FOR EACH ROW BEGIN
    SET @S = CONCAT(NEW.id,";", 
    NEW.question_id,";",  
    NEW.otion_id,";",  
    NEW.position,";",  
    NEW.anonym_voter,";",  
    NEW.voter_id);
      INSERT INTO #__pvoks_logs VALUES (0,0,"vote",NEW.id, NEW.anonym_voter, "INSERT",@S);
    END $$
DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS  `vote_update_log` $$
CREATE TRIGGER `vote_update_log` AFTER UPDATE ON #__pvoks_votes FOR EACH ROW BEGIN
    SET @S = CONCAT(NEW.id,";", 
    NEW.question_id,";",  
    NEW.otion_id,";",  
    NEW.position,";",  
    NEW.anonym_voter,";",  
    NEW.voter_id);
      INSERT INTO #__pvoks_logs VALUES (0,0,"vote",NEW.id, NEW.anonym_voter, "UPDATE",@S);
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `vote_delete_log` $$
CREATE TRIGGER `vote_delete_log` AFTER DELETE ON #__pvoks_votes FOR EACH ROW BEGIN
    SET @S = CONCAT(OLD.id,";", 
    OLD.question_id,";",  
    OLD.otion_id,";",  
    OLD.position,";",  
    OLD.anonym_voter,";",  
    OLD.voter_id);
     INSERT INTO #__pvoks_logs VALUES (0,0,"vote",OLD.id, OLD.anonym_voter, "DELETE",@S);
    END $$
DELIMITER ;
*/
insert  into #__pvoks_configs (`id`,`config_type`,`title`,`json`,`created`,`created_by`,`modified`,`modified_by`,`state`) values (1,'global','Globális konfiguráció','{\"extraLngFile\":\"\",\r\n\"plugin\":\"\",\r\n\"steps\":[\r\n]\r\n}','2016-12-31 11:53:05',954,'2017-01-17 10:03:59',954,1);
insert  into #__pvoks_configs (`id`,`config_type`,`title`,`json`,`created`,`created_by`,`modified`,`modified_by`,`state`) values (2,'category','Default kategória konfiguráció','{\"extraLngFile\":\"\",\r\n\"plugin\":\"debian\",\r\n\"steps\":[\r\n  {\"title\":\"Kategória javaslat\",\r\n    \"groups\":[\r\n      {\"group\":\"guest\",\"actions\":[\"view-categories\",\"view-questions\",\"view-options\",\"view-category-sugestions\",\"view-question-sugestions\",\"view-option-sugestions\",\"view-creator-nick\",\"view-comment\",\"view-vokscount\",\"view-result\",\"view-myvoks\",\"view-vokses\",\"comment-add\"]\r\n      },\r\n      {\"group\":\"registered\",\"actions\":[\"view-categories\",\"view-questions\",\"view-members\",\"view-options\",\"view-category-sugestions\",\"view-question-sugestions\",\"view-option-sugestions\",\"view-creator-nick\",\"view-comment\",\"view-vokscount\",\"view-result\",\"view-myvoks\",\"view-vokses\",\"add-category-suggestion\",\"support-category-suggestion\",\"comment-add\"]\r\n      },\r\n      {\"group\":\"category-admin\",\"actions\":[\"view-categories\",\"view-questions\",\"view-members\",\"view-options\",\"view-category-sugestions\",\"view-question-sugestions\",\"view-option-sugestions\",\"view-creator-nick\",\"view-comment\",\"view-vokscount\",\"view-result\",\"view-myvoks\",\"view-vokses\",\"add-category-suggestion\",\"delete-category-suggestion\",\"support-category-suggestion\",\"comment-add\",\"set-step\"]\r\n      },\r\n      {\"group\":\"admin\",\"actions\":[\"view-categories\",\"view-questions\",\"view-members\",\"view-options\",\"view-category-sugestions\",\"view-question-sugestions\",\"view-option-sugestions\",\"view-creator-nick\",\"view-creator-name\",\"view-comment\",\"view-vokscount\",\"view-result\",\"view-myvoks\",\"view-vokses\",\"add-category-suggestion\",\"delete-category-suggestion\",\"support-category-suggestion\",\"comment-add\",\"set-step\"]\r\n      }\r\n    ]\r\n  },\r\n  {\"title\":\"Aktív kategória\",\r\n    \"groups\":[\r\n      {\"group\":\"guest\",\"actions\":[\"view-categories\",\"view-questions\",\"view-members\",\"view-options\",\"view-category-sugestions\",\"view-question-sugestions\",\"view-option-sugestions\",\"view-creator-nick\",\"view-comment\",\"view-vokscount\",\"view-result\",\"view-myvoks\",\"view-vokses\",\"comment-add\"]\r\n      },\r\n      {\"group\":\"registered\",\"actions\":[\"view-questions\",\"add-category-suggestion\",\"add-question-suggestion\",\"add-member-suggestion\",\"add-acredited\",\"edit-acredited\",\"delete-acredited\",\"support-category-suggestion\",\"support-question-suggestion\",\"support-member-suggestion\"]\r\n      },\r\n      {\"group\":\"category-admin\",\"actions\":[\"add-category\",\"add-question\",\"add-member\",\"edit-category-suggestion\",\"edit-question-suggestion\",\"edit-member-suggestion\",\"edit-category\",\"edit-question\",\"edit-member\",\"delete-category-suggestion\",\"delete-question-suggestion\",\"delete-member-suggestion\",\"delete-category\",\"delete-question\",\"delete-member\",\"merge-category-suggestion\",\"merge-question-suggestion\",\"merge-category\",\"merge-question\",\"comment-edit\",\"comment-delete\",\"set-step\"]\r\n      },\r\n      {\"group\":\"admin\",\"actions\":[\"add-category\",\"add-question\",\"add-member\",\"edit-category-suggestion\",\"edit-question-suggestion\",\"edit-member-suggestion\",\"edit-category\",\"edit-question\",\"edit-member\",\"delete-category-suggestion\",\"delete-question-suggestion\",\"delete-member-suggestion\",\"delete-category\",\"delete-question\",\"delete-member\",\"merge-category-suggestion\",\"merge-question-suggestion\",\"merge-category\",\"merge-question\",\"comment-edit\",\"comment-delete\",\"set-step\"]\r\n      }\r\n    ]\r\n  },\r\n  {\"title\":\"Lezárt kategória\",\r\n    \"groups\":[\r\n      {\"group\":\"guest\",\"actions\":[\"view-categories\",\"view-questions\",\"view-members\",\"view-options\",\"view-category-sugestions\",\"view-question-sugestions\",\"view-member-sugestions\",\"view-option-sugestions\",\"view-creator-nick\",\"view-comment\",\"view-vokscount\",\"view-result\",\"view-myvoks\",\"view-vokses\"]\r\n      },\r\n      {\"group\":\"admin\",\"actions\":[\"set-step\"]\r\n      },\r\n      {\"group\":\"admin\",\"actions\":[\"set-step\"]\r\n      }\r\n    ]\r\n  }\r\n]\r\n}','2016-12-31 12:29:40',954,'2017-01-18 08:50:25',954,1);
insert  into #__pvoks_configs (`id`,`config_type`,`title`,`json`,`created`,`created_by`,`modified`,`modified_by`,`state`) values (3,'question','Default kérdés (szavazás) konfiguráció','{\"extraLngFile\":\"\",\r\n\"plugin\":\"debian\",\r\n\"steps\":[\r\n  {\"title\":\"Javaslat\",\r\n    \"groups\":[\r\n      {\"group\":\"guest\",\"actions\":[\"view-categories\",\"view-questions\",\"view-members\",\"view-options\",\"view-category-sugestions\",\"view-question-sugestions\",\"view-option-sugestions\",\"view-creator-nick\",\"view-comment\",\"comment-add\"]\r\n      },\r\n      {\"group\":\"registered\",\"actions\":[\"add-acredited\",\"edit-acredited\",\"delete-acredited\",\"support-question-suggestion\"]\r\n      },\r\n      {\"group\":\"category-admin\",\"actions\":[\"comment-edit\",\"comment-delete\",\"set-step\"]\r\n      },\r\n      {\"group\":\"admin\",\"actions\":[\"comment-edit\",\"comment-delete\",\"set-step\"]\r\n      }\r\n    ]\r\n  },\r\n  {\"title\":\"vita\",\r\n    \"groups\":[\r\n      {\"group\":\"guest\",\"actions\":[\"view-categories\",\"view-questions\",\"view-members\",\"view-options\",\"view-category-sugestions\",\"view-question-sugestions\",\"view-option-sugestions\",\"view-creator-nick\",\"view-comment\",\"view-vokscount\",\"view-result\",\"comment-add\"]\r\n      },\r\n      {\"group\":\"registered\",\"actions\":[\"add-option-suggestion\",\"add-acredited\",\"edit-acredited\",\"delete-acredited\",\"support-option-suggestion\",\"support-vote-start\"]\r\n      },\r\n      {\"group\":\"category-admin\",\"actions\":[\"add-option\",\"edit-option-suggestion\",\"edit-option\",\"delete-option-suggestion\",\"delete-option\",\"merge-option-suggestion\",\"merge-option\",\"comment-edit\",\"comment-delete\",\"set-step\"]\r\n      },\r\n      {\"group\":\"admin\",\"actions\":[\"add-option\",\"edit-option-suggestion\",\"edit-option\",\"delete-option-suggestion\",\"delete-option\",\"merge-option-suggestion\",\"merge-option\",\"comment-edit\",\"comment-delete\",\"set-step\"]\r\n      }\r\n    ]\r\n  },\r\n  {\"title\":\"szavazás\",\r\n    \"groups\":[\r\n      {\"group\":\"guest\",\"actions\":[\"view-categories\",\"view-questions\",\"view-members\",\"view-options\",\"view-category-sugestions\",\"view-question-sugestions\",\"view-option-sugestions\",\"view-creator-nick\",\"view-comment\",\"view-vokscount\",\"view-result\",\"view-myvoks\",\"view-vokses\",\"comment-add\"]\r\n      },\r\n      {\"group\":\"registered\",\"actions\":[\"view-vokscount\",\"view-result\",\"view-myvoks\",\"view-vokses\",\"add-acredited\",\"edit-acredited\",\"delete-acredited\",\"voks-add\",\"voks-delete\"]\r\n      },\r\n      {\"group\":\"category-admin\",\"actions\":[\"set-step\"]\r\n      },\r\n      {\"group\":\"admin\",\"actions\":[\"set-step\"]\r\n      }\r\n    ]\r\n  },\r\n  {\"title\":\"lezárt\",\r\n    \"groups\":[\r\n      {\"group\":\"guest\",\"actions\":[\"view-categories\",\"view-questions\",\"view-members\",\"view-options\",\"view-category-sugestions\",\"view-question-sugestions\",\"view-option-sugestions\",\"view-creator-nick\",\"view-creator-name\",\"view-comment\",\"view-vokscount\",\"view-result\",\"view-vokses\"]\r\n      },\r\n      {\"group\":\"registered\",\"actions\":[\"view-myvoks\"]\r\n      },\r\n      {\"group\":\"category-admin\",\"actions\":[\"set-step\"]\r\n      },\r\n      {\"group\":\"admin\",\"actions\":[\"set-step\"]\r\n      }\r\n    ]\r\n  }\r\n]\r\n}','2017-01-17 10:32:10',954,'2017-01-18 09:08:28',954,1);

