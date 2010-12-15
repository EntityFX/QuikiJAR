<?php 
/**
 * Модуль видео. Поиск видео по YT, по собственной базе. Вывод, добавление, комментирование файлов.
 * @author Тимур 22.09.10 <gtimur666@gmail.com>
 * @version 1.0
 */
	

//require_once "engine/libs/mysql/MySQLConnector.php";
Loader::loadClass('engine/libs/video/Zend/Loader.php');
Loader::loadClass("engine/libs/mysql/MySQLConnector.php");

	class VideoThing extends MySQLConnector
	{

		
		/**
		 * Количество файлов на странице.
		 * @var integer
		 */
		const FILES_COUNT = 5;
		/**
		 * Консртуктор. Выполняется подключение базового класса YT.
		 */
		function __construct() 
		{
			Zend_Loader::loadClass('Zend_Gdata_YouTube');
			parent::__construct();
			$string = "CREATE TABLE IF NOT EXISTS `Videos` (
			  `id` int(11) NOT NULL,
			  `videoID` varchar(255) DEFAULT NULL,
			  `title` varchar(255) DEFAULT NULL,
			  `comment` varchar(500) DEFAULT NULL,
			  `user` int(11) NOT NULL,
			  `sqrty` int(11) DEFAULT NULL,
			  `sq_list` varchar(1000) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ;";
			$this->_sql->query($string);
		}
		/**
		 * Функция добавления видео-файла из результатов поиска и результатов показа списков видео-файлов пользователя.
		 * @param integer $user - id пользователя.
		 * @param string $videoID - номер видео-файла (берется с YT).
		 * @param string $videoTitle
		 */
		public function add2myVideos($user, $videoID, $videoTitle)
		{
			$result = $this->_sql->query("INSERT INTO `Videos` ( `id` , `videoID` , `title` , `comment` , `user` , `sqrty` , `sq_list` )
			VALUES (
			'', '$videoID', '$videoTitle', '', '$user', NULL , '');");
			return $result;
		}	
		
		/**
		 * Функция удаления видео-файла из таблицы.
		 * @param integer $user - id пользователя.
		 * @param string $videoID - номер видео-файла (берется с YT).
		 */
		public function deleteFromMyvideos($user, $id)
		{
			$result = $this->_sql->query("DELETE FROM `Videos` WHERE `id` = '$id' AND `user`='$user' LIMIT 1;");
			return $result;
		}
		
		/**
		 * Функция получения результатов поиска по серверу YT в виде объекта VideoFeed.
		 * @param string $searchString строка поиска.
		 * @return VideoFeed - возвращает объект VideoFeed 
		 */
		function searchOnYT($searchString, $startID=0) 
		{			
			$yt = new Zend_Gdata_YouTube();
			$query = $yt->newVideoQuery();
			$query->videoQuery = $searchString;
			$query->startIndex = $startID;
			$query->maxResults = VideoThing::FILES_COUNT;
			$query->orderBy = 'viewCount';
			//echo $query->queryUrl . "\n <br />";
			$videoFeed = $yt->getVideoFeed($query);
			
			return $videoFeed;
		}
		/**
		 * Функция получения данных по объекту $videoFeed 
		 * @param $videoFeed
		 * @return возвращает ассоциативный массив.
		 */
		function printVideoEntry($videoFeed) 
		{
			foreach ($videoFeed as $videoEntry) 
			{
				// the videoEntry object contains many helper functions that access the underlying mediaGroup object
				$resArr["VideoTitle"] = iconv("utf-8", "windows-1251",$videoEntry->getVideoTitle());
				$resArr["FlashPlayer"] = $videoEntry->getFlashPlayerUrl();
				$resArr["VideoId"] = $videoEntry->getVideoId();
				
				$videoThumbnails = $videoEntry->getVideoThumbnails();
				$resArr["Preview"] = $videoThumbnails[0]['url'];
				
				$tmpArr[] = $resArr;
			}
			return $tmpArr;
		}
		
		/**
		 * Функция получения информации и метаданных о видео по объекту $videoEntry
		 * @param $videoEntry
		 * @return возвращает ассоциативный массив.
		 */
		function getVideoInfo($videoEntry) 
		{
			// the videoEntry object contains many helper functions that access the underlying mediaGroup object
			$resArr["VideoTitle"] = iconv("utf-8", "windows-1251",$videoEntry->getVideoTitle());
			$resArr["FlashPlayer"] = $videoEntry->getFlashPlayerUrl();
			$resArr["VideoId"] = $videoEntry->getVideoId();
			
			$videoThumbnails = $videoEntry->getVideoThumbnails();
			$resArr["Preview"] = $videoThumbnails[0]['url'];
			return $resArr;
		}
		
		
		/**
		 * Функция получения видео-файла
		 * @param $videoId
		 */
		public function getVideo($videoId)
		{
			$yt = new Zend_Gdata_YouTube();
			$videoEntry = $yt->getVideoEntry($videoId);
			$resArr = $this->getVideoInfo($videoEntry);
			return $resArr;
		}
		
		/**
		 * Получение количества файлов в результате поиска.
		 * @param $videoFeed объект, полученный в результате поиска.
		 * @return integer количество файлов.
		 */
		public function getEntriesCount($videoFeed)
		{
			$entriesCount = $videoFeed->getTotalResults();
			$entriesCount = $entriesCount->getText();
			return $entriesCount;
		}
		
		/**
		 * Функция получения количества Листов при определенном количестве файлов на страницу.
		 * @param $videoFeed
		 * @return integer количество Листов.
		 */
		public function getListsCount($videoFeed)
		{
			$total = $this->getEntriesCount($videoFeed); 
			$lists = ceil($total/VideoThing::FILES_COUNT);
			$total!==0 ? $lists = ceil($total / VideoThing::FILES_COUNT) : $lists=0;
			return $lists;
		}
		
		public function showMyVideos($user, $startIndex=0)
		{
			$maxRes = VideoThing::FILES_COUNT;
			$startIndex = is_numeric($startIndex) ? $startIndex : 0;
			//die("startIndex --- $startIndex");
			$result = $this->_sql->query("SELECT * FROM `Videos` WHERE `user`='$user' LIMIT $startIndex, $maxRes");
			while ($tmp = $this->_sql->fetchArr($result))
			{
				$res[] = $tmp;
			}
			return $res;
		}
	}
?>
