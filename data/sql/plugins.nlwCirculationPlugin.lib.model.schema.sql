
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- request_type
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `request_type`;


CREATE TABLE `request_type`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`type` VARCHAR(255),
	`serial_number` INTEGER default 0 NOT NULL,
	PRIMARY KEY (`id`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- request_status
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `request_status`;


CREATE TABLE `request_status`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`status` VARCHAR(255),
	`order` INTEGER,
	`serial_number` INTEGER default 0 NOT NULL,
	PRIMARY KEY (`id`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- request
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `request`;


CREATE TABLE `request`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`object_id` INTEGER  NOT NULL,
	`request_type_id` INTEGER,
	`physical_object_id` INTEGER,
	`patron_barcode` VARCHAR(255),
	`requester_barcode` VARCHAR(255),
	`collection_date` DATE,
	`expiry_date` DATE,
	`patron_notes` TEXT,
	`patron_type` VARCHAR(255),
	`patron_name` VARCHAR(255),
	`item_title` VARCHAR(255),
	`item_date` VARCHAR(250),
	`item_creator` VARCHAR(255),
	`collection_title` VARCHAR(255),
	`staff_notes` TEXT,
	`status` INTEGER,
	`created_at` DATETIME  NOT NULL,
	`updated_at` DATETIME  NOT NULL,
	`serial_number` INTEGER default 0 NOT NULL,
	PRIMARY KEY (`id`,`object_id`),
	INDEX `request_FI_1` (`object_id`),
	CONSTRAINT `request_FK_1`
		FOREIGN KEY (`object_id`)
		REFERENCES `object` (`id`)
		ON DELETE CASCADE,
	INDEX `request_FI_2` (`request_type_id`),
	CONSTRAINT `request_FK_2`
		FOREIGN KEY (`request_type_id`)
		REFERENCES `request_type` (`id`),
	INDEX `request_FI_3` (`physical_object_id`),
	CONSTRAINT `request_FK_3`
		FOREIGN KEY (`physical_object_id`)
		REFERENCES `physical_object` (`id`),
	INDEX `request_FI_4` (`status`),
	CONSTRAINT `request_FK_4`
		FOREIGN KEY (`status`)
		REFERENCES `request_status` (`id`)
)Engine=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
