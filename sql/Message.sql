﻿-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 4.50.303.1
-- Дата: 27.09.2010 0:02:21
-- Версия сервера: 5.0.45-community-nt
-- Версия клиента: 4.1

-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

-- 
-- Вывод данных для таблицы Message
--
INSERT INTO Message VALUES 
  (1250, 'C', '2010-09-16 23:35:47', 911, 190, 0),
  (1251, 'a', '2010-09-16 23:35:47', 911, 190, 1),
  (1252, 'b', '2010-09-16 23:35:47', 911, 190, 0);

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;