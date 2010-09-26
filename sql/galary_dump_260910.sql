-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- ����: localhost
-- ����� ��������: ��� 26 2010 �., 22:18
-- ������ �������: 5.1.41
-- ������ PHP: 5.3.2-1ubuntu4.5
-- 
-- ��: `QUKI`
-- 

-- --------------------------------------------------------

-- 
-- ��������� ������� `galary_files`
-- 

CREATE TABLE `galary_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(1000) DEFAULT NULL,
  `small_path` varchar(255) DEFAULT NULL,
  `text` varchar(1000) DEFAULT NULL,
  `pos` int(11) DEFAULT NULL,
  `isreadedcomments` tinyint(1) NOT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `comment` varchar(1000) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `productname` varchar(1000) DEFAULT NULL,
  `content` varchar(1000) DEFAULT NULL,
  `cost` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=6 ;

-- 
-- ���� ������ ������� `galary_files`
-- 

INSERT INTO `galary_files` VALUES (1, '/photos/decline666@bk.ru/galary/1.png', '/photos/decline666@bk.ru/galary/1.png', 'textys', 1, 0, '2010-09-23 18:58:53', 'muComment', 1, 1, 'nonameproduct', 'nocontent', '$7000');
INSERT INTO `galary_files` VALUES (2, '/photos/decline666@bk.ru/galary/4.jpg', '/photos/decline666@bk.ru/galary/4.jpg', 'sdgwerg', 2, 0, '2010-09-23 18:59:30', 'noooo', 1, NULL, 'rrr', NULL, NULL);
INSERT INTO `galary_files` VALUES (3, '/photos/decline666@bk.ru/galary/5.jpg', '/photos/decline666@bk.ru/galary/5.jpg', 'wert54', 3, 0, '2010-09-23 18:59:46', NULL, 1, NULL, NULL, NULL, NULL);
INSERT INTO `galary_files` VALUES (4, '/photos/decline666@bk.ru/galary/3.jpg', '/photos/decline666@bk.ru/galary/3.jpg', NULL, 4, 0, '2010-09-25 16:59:20', NULL, 1, NULL, NULL, NULL, NULL);
INSERT INTO `galary_files` VALUES (5, '/photos/decline666@bk.ru/galary/20090330170938.jpg', '/photos/decline666@bk.ru/galary/20090330170938.jpg', NULL, 5, 0, '2010-09-25 17:00:08', NULL, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- ��������� ������� `galary_list`
-- 

CREATE TABLE `galary_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `sequrity` varchar(1000) NOT NULL,
  `cover` varchar(500) DEFAULT NULL,
  `sqcomment` varchar(1000) DEFAULT NULL,
  `pos` int(11) DEFAULT NULL,
  `trusted` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=14 ;

-- 
-- ���� ������ ������� `galary_list`
-- 

INSERT INTO `galary_list` VALUES (1, 13, '������ ������', NULL, '����������� ������ � �������', '2010-08-21', NULL, '125;127;', '1', '125;', 1, '');
INSERT INTO `galary_list` VALUES (2, 127, '������ ������� ������������', NULL, NULL, '2010-08-21', NULL, '', NULL, NULL, 1, '');
INSERT INTO `galary_list` VALUES (3, 125, '������ ������ �������� ������������', NULL, '�����������', '2010-08-21', NULL, '123;', NULL, NULL, 1, '');
INSERT INTO `galary_list` VALUES (4, 13, '������ ������ ����� 123', NULL, NULL, '2010-09-19', NULL, '', '2', NULL, 2, '');
        
