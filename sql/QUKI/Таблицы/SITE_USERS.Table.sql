--
-- Описание для таблицы SITE_USERS
--
CREATE TABLE SITE_USERS(
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Уникальный идентификатор пользователя',
  mail VARCHAR(100) NOT NULL COMMENT 'Почта',
  `password` BINARY(32) NOT NULL COMMENT 'Пароль',
  ip INT(11) UNSIGNED NOT NULL COMMENT 'IP-адрес регистрации',
  register_date DATE NOT NULL COMMENT 'Дата регистрации',
  name VARCHAR(25) NOT NULL COMMENT 'Имя человека',
  second_name VARCHAR(50) NOT NULL COMMENT 'Фамилия',
  gender TINYINT(1) NOT NULL DEFAULT 1,
  burthday DATE DEFAULT NULL,
  photo VARCHAR(255) DEFAULT NULL,
  country TINYINT(4) UNSIGNED DEFAULT NULL COMMENT 'Страна',
  region INT(11) UNSIGNED DEFAULT NULL COMMENT 'Регион',
  city INT(11) UNSIGNED DEFAULT NULL COMMENT 'Город',
  street VARCHAR(100) DEFAULT NULL COMMENT 'Улица',
  `utc_time` TINYINT(2) DEFAULT 0 COMMENT 'Часовой пояс',
  state TINYINT(2) UNSIGNED DEFAULT 0 COMMENT 'Статус: забанен/активен/не активен',
  PRIMARY KEY (id),
  UNIQUE INDEX mail (mail),
  FULLTEXT INDEX mail_2 (mail),
  FULLTEXT INDEX photo (photo)
)
ENGINE = MYISAM
AUTO_INCREMENT = 15
AVG_ROW_LENGTH = 595
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Пользователи сайта'
ROW_FORMAT = FIXED;