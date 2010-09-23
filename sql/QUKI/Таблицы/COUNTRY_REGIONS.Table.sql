--
-- Описание для таблицы COUNTRY_REGIONS
--
CREATE TABLE COUNTRY_REGIONS(
  region_id INT(11) UNSIGNED NOT NULL COMMENT 'ID РЕГИОНА',
  country INT(11) UNSIGNED DEFAULT NULL COMMENT 'Страна',
  reg_name VARCHAR(100) DEFAULT NULL COMMENT 'Заголовок региона',
  PRIMARY KEY (region_id),
  INDEX COUNTRY___REGIONS (country)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 25
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Таблица регионов мира';