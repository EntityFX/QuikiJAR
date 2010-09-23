--
-- Описание для таблицы REGISTRY
--
CREATE TABLE REGISTRY(
  `key` VARCHAR(30) CHARACTER SET cp1251 COLLATE cp1251_bin COMMENT 'Ключ',
  value_serealized TEXT CHARACTER SET cp1251 COLLATE cp1251_bin DEFAULT NULL COMMENT 'Значение',
  PRIMARY KEY (`key`)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 52
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Реестр сайта';