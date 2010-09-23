--
-- Описание для таблицы TEXT_MODULE
--
CREATE TABLE TEXT_MODULE(
  id INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID текстового модуля',
  `text` LONGTEXT NOT NULL COMMENT 'Контент модуля',
  PRIMARY KEY (id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Статьи текстового модуля'
ROW_FORMAT = DYNAMIC;