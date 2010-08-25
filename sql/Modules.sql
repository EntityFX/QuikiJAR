CREATE TABLE `Modules` (
`moduleId` INT NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 100 ) NOT NULL ,
`descr` TINYTEXT NOT NULL ,
`path` TINYTEXT NOT NULL ,
PRIMARY KEY ( `moduleId` )
);
