--
-- Описание для таблицы USERS_GROUPS
--
CREATE TABLE USERS_GROUPS(
  group_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID группы',
  title VARCHAR(100) DEFAULT NULL COMMENT 'Заголовок группы',
  owner INT(11) UNSIGNED DEFAULT NULL COMMENT 'ID владельца группы',
  PRIMARY KEY (group_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Таблица групп пользователей';