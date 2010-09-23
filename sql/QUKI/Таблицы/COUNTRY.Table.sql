--
-- Описание для таблицы COUNTRY
--
CREATE TABLE COUNTRY(
  country_id INT(11) UNSIGNED NOT NULL COMMENT 'ID страны',
  COUNTRY VARCHAR(100) NOT NULL COMMENT 'Страна',
  PRIMARY KEY (country_id)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 20
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Страны';