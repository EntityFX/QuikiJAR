--
-- Описание для таблицы Modules
--
CREATE TABLE Modules(
  moduleId INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID модуля',
  name VARCHAR(100) NOT NULL COMMENT 'Заголовок модуля',
  descr TINYTEXT NOT NULL COMMENT 'Описание модуля',
  path TINYTEXT NOT NULL COMMENT 'Путь к модулю',
  PRIMARY KEY (moduleId)
)
ENGINE = MYISAM
AUTO_INCREMENT = 6
AVG_ROW_LENGTH = 88
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Таблица - список модулей';