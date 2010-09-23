--
-- Описание для таблицы USERS_FRIENDSHIP
--
CREATE TABLE USERS_FRIENDSHIP(
  id_friendship INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Индекс',
  user_id INT(11) DEFAULT NULL COMMENT 'ID пользователя',
  friend_id VARCHAR(255) DEFAULT NULL COMMENT 'ID друга',
  PRIMARY KEY (id_friendship)
)
ENGINE = MYISAM
AUTO_INCREMENT = 2
AVG_ROW_LENGTH = 20
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Друзья пользователя';