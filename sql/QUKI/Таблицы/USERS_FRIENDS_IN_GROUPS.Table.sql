--
-- Описание для таблицы USERS_FRIENDS_IN_GROUPS
--
CREATE TABLE USERS_FRIENDS_IN_GROUPS(
  reference_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID связи группа-пользователь',
  user_id INT(11) UNSIGNED DEFAULT NULL COMMENT 'ID пользователя',
  group_id VARCHAR(255) DEFAULT NULL COMMENT 'ID группы',
  PRIMARY KEY (reference_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Списки пользователей и в какие группы он входит';