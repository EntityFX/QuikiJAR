CREATE TABLE `MainMenu` (
`section_id` INT NOT NULL ,
`title` VARCHAR( 150 ) NOT NULL ,
`show_sub` TINYINT( 1 ) DEFAULT '1' NOT NULL ,
PRIMARY KEY ( `section_id` )
);