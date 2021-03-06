﻿--
-- Описание для таблицы COUNTRY_REGIONS_CITY
--
CREATE TABLE COUNTRY_REGIONS_CITY(
  city_id INT(11) UNSIGNED NOT NULL COMMENT 'ID города',
  country INT(11) UNSIGNED DEFAULT NULL COMMENT 'Страна',
  region INT(11) UNSIGNED DEFAULT NULL COMMENT 'Регион',
  title_city VARCHAR(100) DEFAULT NULL COMMENT 'Заголовок города',
  PRIMARY KEY (city_id)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 27
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Города';