--
-- Описание для таблицы URL
--
CREATE TABLE URL(
  id INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id раздела',
  link VARCHAR(255) NOT NULL DEFAULT '/' COMMENT 'Адрес раздела',
  title VARCHAR(100) NOT NULL COMMENT 'Заголовок',
  title_tag VARCHAR(255) NOT NULL COMMENT 'Для тэга title',
  module INT(11) NOT NULL DEFAULT 1 COMMENT 'Тип модуля',
  position INT(11) NOT NULL COMMENT 'Позиция в админке',
  pid INT(11) NOT NULL DEFAULT 1 COMMENT 'ID родительского раздела',
  use_parameters TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Использовать параметры',
  PRIMARY KEY (id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 14
AVG_ROW_LENGTH = 37
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Таблица URL адресов и соответствующих модулей';