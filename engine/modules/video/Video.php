<?php 
require_once 'engine/libs/Zend/Loader.php';

	class VideoThing
	{
		/**
		 * Объект обращения к YouTube
		 */
		//public $_yt;
		
		/**
		 * Количество файлов на странице.
		 * @var integer
		 */
		public $_filesCount;
		const FILES_COUNT = 5;
		
		/**
		 * Консртуктор. Выполняется подключение базового класса YT.
		 * Устанавливается количество файлов на странице поиска, и странице пользовательских видео.
		 */
		function __construct() 
		{
			Zend_Loader::loadClass('Zend_Gdata_YouTube');
			//$_yt = new Zend_Gdata_YouTube();
			//$_filesCount = 5; 	
		}
		
		/**
		 * Функция поиска по серверу YT.
		 * @param string $searchString строка поиска.
		 */
		function searchOnYT($searchString, $startID=0) 
		{
			//$yt = $this->_yt;
			//Zend_Loader::loadClass('Zend_Gdata_YouTube');
			$yt = new Zend_Gdata_YouTube();
			$query = $yt->newVideoQuery();
			$query->videoQuery = $serchString;
			$query->startIndex = $startID;
			$query->maxResults = VideoThing::FILES_COUNT;
			$query->orderBy = 'viewCount';
			//echo $query->queryUrl . "\n <br />";
			$videoFeed = $yt->getVideoFeed($query);
			$entry = $videoFeed->getEntry();
			
			//$videoFeed->getIcon();
			
			foreach ($videoFeed as $videoEntry) 
			{
				$tmpArr[] = $this->printVideoEntry($videoEntry);
			}
			
			return $tmpArr;
		}
		
		function printVideoEntry($videoEntry) 
		{
			// the videoEntry object contains many helper functions that access the underlying mediaGroup object
			$resArr["VideoTitle"] = $videoEntry->getVideoTitle();
			$resArr["FlashPlayer"] = $videoEntry->getFlashPlayerUrl();
			$resArr["VideoId"] = $videoEntry->getVideoId();
			
			$videoThumbnails = $videoEntry->getVideoThumbnails();
			$resArr["Preview"] = $videoThumbnails[0]['url'];
			return $resArr;
			/*
			echo 'Video: ' . $videoEntry->getVideoTitle() . "\n <br />";
			echo "\tDescription: " . $videoEntry->getVideoDescription() . "\n <br />";
			echo "\tCategory: " . $videoEntry->getVideoCategory() . "\n <br />";
			echo "\tTags: " . implode(", ", $videoEntry->getVideoTags()) . "\n <br />";
			echo "\tWatch page: " . $videoEntry->getVideoWatchPageUrl() . "\n <br />";
			echo "\tFlash Player Url: " . $videoEntry->getFlashPlayerUrl() . "\n <br />";
			echo "\tDuration: " . $videoEntry->getVideoDuration() . "\n <br />";
			echo "\tView count: " . $videoEntry->getVideoViewCount() ."\n <br />";
			echo "\tRating: " . $videoEntry->getVideoRatingInfo() . "\n <br />";
			echo "\tGeo Location: " . $videoEntry->getVideoGeoLocation() . "\n <br />";
			echo "\t  <b>VideoId</b> : <u>" . $videoEntry->getVideoId () . " </u>\n <br />";
			echo "<embed src=\"".$videoEntry->getFlashPlayerUrl()."\" width=\"400\" height=\"300\" type=\"application/x-shockwave-flash\"
		    pluginspage=\"http://www.macromedia.com/go/getflashplayer\"></embed> ";*/
			// see the paragraph above this function for more information on the 'mediaGroup' object
			// here we are using the mediaGroup object directly to its 'Mobile RSTP link' child
			/*
			foreach ($videoEntry->mediaGroup->content as $content) 
			{
				if ($content->type === "video/3gpp") 
				{
					echo "\tMobile RTSP link: " . $content->url . "\n <br />";
				}
			}
			
			echo "\tThumbnails:\n";
			$videoThumbnails = $videoEntry->getVideoThumbnails();
			echo "<img src=\"".$videoThumbnails[0]['url']."\" width=\"80\" height=\"72\" > <br />";*/
			/*foreach($videoThumbnails as $videoThumbnail) 
			{
				
				echo "\t\t" . $videoThumbnail->time . " - " . $videoThumbnail->url;
				echo " height=" . $videoThumbnail->height;
				echo " width=" . $videoThumbnail->width;
				echo "\n <br />";
			}*/
		}
		
	}
?>