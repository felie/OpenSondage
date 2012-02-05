
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- poll
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `poll`;

CREATE TABLE `poll`
(
	`name` VARCHAR(255) NOT NULL COMMENT 'Name of the poll',
	`private_uid` VARCHAR(255) NOT NULL COMMENT 'The private uid of the poll (use in the admin url)',
	`public_uid` VARCHAR(255) NOT NULL COMMENT 'The public uid of the poll (use in the public url)',
	`username` VARCHAR(255) NOT NULL COMMENT 'The name of the creator of the poll',
	`mail` VARCHAR(255) NOT NULL COMMENT 'Email of the creator of the poll',
	`type` TINYINT DEFAULT 1 NOT NULL COMMENT 'Type of poll : simple poll or meeting',
	`maybe_authorized` TINYINT(1) DEFAULT 0 COMMENT 'Allow the user to answer maybe',
	`nb_point_maybe` REAL DEFAULT 0.5 COMMENT 'Number of point to the maybe response',
	`description` TEXT COMMENT 'Description of the poll',
	`mail_modified` TINYINT(1) DEFAULT 0 COMMENT 'Send an email to the creator when a user add a response',
	`allow_modified` TINYINT(1) DEFAULT 0 COMMENT 'Allow user to modified their response',
	`end_at` DATE NOT NULL COMMENT 'The date where the poll can be deleted',
	`login_required` TINYINT(1) DEFAULT 0 COMMENT 'Indicate if access of the poll, login is required',
	`passwd` VARCHAR(255) COMMENT 'The password to use for access of the poll',
	`salt` VARCHAR(255) COMMENT 'The salt use with the password',
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	`slug` VARCHAR(255),
	PRIMARY KEY (`id`),
	UNIQUE INDEX `poll_U_1` (`private_uid`),
	UNIQUE INDEX `poll_U_2` (`public_uid`),
	UNIQUE INDEX `poll_slug` (`slug`(255))
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- question
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `question`;

CREATE TABLE `question`
(
	`poll_id` INTEGER NOT NULL COMMENT 'Relation between poll and question',
	`name` VARCHAR(255) NOT NULL COMMENT 'The title of the question',
	`proposed_date` DATE COMMENT 'The date proposed in the poll',
	`result` REAL DEFAULT 0 NOT NULL COMMENT 'The result for this question',
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`sortable_rank` INTEGER,
	PRIMARY KEY (`id`),
	INDEX `question_FI_1` (`poll_id`),
	CONSTRAINT `question_FK_1`
		FOREIGN KEY (`poll_id`)
		REFERENCES `poll` (`id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
	`poll_id` INTEGER NOT NULL COMMENT 'Relation between poll and user',
	`name` VARCHAR(255) NOT NULL COMMENT 'Name of the user who answers the poll',
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`sortable_rank` INTEGER,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `user_FI_1` (`poll_id`),
	CONSTRAINT `user_FK_1`
		FOREIGN KEY (`poll_id`)
		REFERENCES `poll` (`id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- user_has_question
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user_has_question`;

CREATE TABLE `user_has_question`
(
	`user_id` INTEGER NOT NULL COMMENT 'Relation between user and user_has_question',
	`question_id` INTEGER NOT NULL COMMENT 'Relation between question and user_has_question',
	`yes` TINYINT(1) COMMENT 'The response of the answer is yes',
	`no` TINYINT(1) COMMENT 'The response of the answer is no',
	`maybe` TINYINT(1) COMMENT 'The response of the answer is maybe',
	PRIMARY KEY (`user_id`,`question_id`),
	INDEX `user_has_question_FI_2` (`question_id`),
	CONSTRAINT `user_has_question_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `user` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `user_has_question_FK_2`
		FOREIGN KEY (`question_id`)
		REFERENCES `question` (`id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- comment
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `comment`;

CREATE TABLE `comment`
(
	`poll_id` INTEGER NOT NULL COMMENT 'Relation between poll and comment',
	`name` VARCHAR(255) NOT NULL COMMENT 'Name of the user who comment the poll',
	`description` TEXT COMMENT 'Comment',
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `comment_FI_1` (`poll_id`),
	CONSTRAINT `comment_FK_1`
		FOREIGN KEY (`poll_id`)
		REFERENCES `poll` (`id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
