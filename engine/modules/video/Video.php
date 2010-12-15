<?php 
/**
 * ������ �����. ����� ����� �� YT, �� ����������� ����. �����, ����������, ��������������� ������.
 * @author ����� 22.09.10 <gtimur666@gmail.com>
 * @version 1.0
 */
	

//require_once "engine/libs/mysql/MySQLConnector.php";
Loader::loadClass('engine/libs/video/Zend/Loader.php');
Loader::loadClass("engine/libs/mysql/MySQLConnector.php");

	class VideoThing extends MySQLConnector
	{

		
		/**
		 * ���������� ������ �� ��������.
		 * @var integer
		 */
		const FILES_COUNT = 5;
		/**
		 * �����������. ����������� ����������� �������� ������ YT.
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
		 * ������� ���������� �����-����� �� ����������� ������ � ����������� ������ ������� �����-������ ������������.
		 * @param integer $user - id ������������.
		 * @param string $videoID - ����� �����-����� (������� � YT).
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
		 * ������� �������� �����-����� �� �������.
		 * @param integer $user - id ������������.
		 * @param string $videoID - ����� �����-����� (������� � YT).
		 */
		public function deleteFromMyvideos($user, $id)
		{
			$result = $this->_sql->query("DELETE FROM `Videos` WHERE `id` = '$id' AND `user`='$user' LIMIT 1;");
			return $result;
		}
		
		/**
		 * ������� ��������� ����������� ������ �� ������� YT � ���� ������� VideoFeed.
		 * @param string $searchString ������ ������.
		 * @return VideoFeed - ���������� ������ VideoFeed 
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
		 * ������� ��������� ������ �� ������� $videoFeed 
		 * @param $videoFeed
		 * @return ���������� ������������� ������.
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
		 * ������� ��������� ���������� � ���������� � ����� �� ������� $videoEntry
		 * @param $videoEntry
		 * @return ���������� ������������� ������.
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
		 * ������� ��������� �����-�����
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
		 * ��������� ���������� ������ � ���������� ������.
		 * @param $videoFeed ������, ���������� � ���������� ������.
		 * @return integer ���������� ������.
		 */
		public function getEntriesCount($videoFeed)
		{
			$entriesCount = $videoFeed->getTotalResults();
			$entriesCount = $entriesCount->getText();
			return $entriesCount;
		}
		
		/**
		 * ������� ��������� ���������� ������ ��� ������������ ���������� ������ �� ��������.
		 * @param $videoFeed
		 * @return integer ���������� ������.
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
