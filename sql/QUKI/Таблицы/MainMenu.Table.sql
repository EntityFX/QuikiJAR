--
-- Описание для таблицы MainMenu
--
CREATE TABLE MainMenu(
  section_id INT(11) NOT NULL COMMENT 'ID меню',
  show_sub TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Показывать подменю. ФЛАГ',
  title VARCHAR(50) DEFAULT NULL COMMENT 'Заголовок ссылки меню',
  PRIMARY KEY (section_id)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 40
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Ссылки основного меню';