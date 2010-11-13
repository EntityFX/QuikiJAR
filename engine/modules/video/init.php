<?php
//require_once 'Zend/Loader.php'; // the Zend dir must be in your include_path
require_once 'Video.php';

$se = new VideoThing();
//phpinfo();

$parameters = $data["parameters"];
switch (count($parameters)) 
{
	case 0: // /video  -- show my video files
		//$output["text"]=showWithTabs($_POST);
		break;
	case 1: // /video/userID  -- show user's videos
		
		// /video/search  -- searc videos
		
		// /video/add -- add video, blin
		
		// 
		break;
	default:
		;
	break;
}




$sercherArr = $se->searchOnYT("php vs perl");
$output["text"] = drawSearchVideoList($sercherArr);

	function drawSearchVideoList($searcArr) 
	{
		foreach ($searcArr as $key ) 
		{
			foreach ($key as $index => $value) 
			{
				$div = $div."$index => $value <br />";
			}
			/*$img = "<img src = \"$key[Preview]\">";
			$title = "<b>$key[Preview]</b>";
			$div = $div."<div style=\"float: clear; border:1px solid silver;\">
			<div style=\"float:left; \">$img</div> 
			<div  style=\"float:left; \"> $title </div>
			</div>";*/
		}
		//$div = "<div style=\"border:1px solid silver;\"> $div </div>";
		return $div;
	}
/*
searchMeVideo(iconv( "utf-8","windows-1251", "sleepy kitten"));
Zend_Loader::loadClass('Zend_Gdata_YouTube');
$yt = new Zend_Gdata_YouTube(); */



//searchAndPrint("sleepy kitten");	
/*	function searchMeVideo($serchString)
	{
		Zend_Loader::loadClass('Zend_Gdata_YouTube');

		$yt = new Zend_Gdata_YouTube(); 
		$query = $yt->newVideoQuery();
		$query->videoQuery = $serchString;
		$query->startIndex = 0;
		$query->maxResults = 5;
		$query->orderBy = 'viewCount';
		echo $query->queryUrl . "\n <br />";
		$videoFeed = $yt->getVideoFeed($query);
		$entry = $videoFeed->getEntry();
		
		//$videoFeed->getIcon();
		
		foreach ($videoFeed as $videoEntry) 
		{
			printVideoEntry($videoEntry);
			/*echo "---------VIDEO----------\n <br />";
			echo "Title: " . $videoEntry->getVideoTitle() . "\n <br />";
			echo "<strong> icon </strong>"."<br />";
		//	echo "\nDescription:\n <br />";
		//	echo $videoEntry->getVideoDescription();
			echo "<br /><br />\n\n\n";
			echo "<br /> <hr />";
			
		}
	}*/
	/*
	function searchAndPrint($searchTerms)
	{
		$yt = new Zend_Gdata_YouTube(); 
		$query = $yt->newVideoQuery();
		$query->setOrderBy('viewCount');
		$query->setRacy('include');
		$query->setVideoQuery($searchTerms);
		$videoFeed = $yt->getVideoFeed($query);
		printVideoFeed($videoFeed, 'Search results for: ' . $searchTerms);
	}*/
	/*
	function printVideoEntry($videoEntry, $tabs = "") 
	{
		// the videoEntry object contains many helper functions that access the underlying mediaGroup object
		echo $tabs . 'Video: ' . $videoEntry->getVideoTitle() . "\n <br />";
		echo $tabs . "\tDescription: " . $videoEntry->getVideoDescription() . "\n <br />";
		echo $tabs . "\tCategory: " . $videoEntry->getVideoCategory() . "\n <br />";
		echo $tabs . "\tTags: " . implode(", ", $videoEntry->getVideoTags()) . "\n <br />";
		echo $tabs . "\tWatch page: " . $videoEntry->getVideoWatchPageUrl() . "\n <br />";
		echo $tabs . "\tFlash Player Url: " . $videoEntry->getFlashPlayerUrl() . "\n <br />";
		echo $tabs . "\tDuration: " . $videoEntry->getVideoDuration() . "\n <br />";
		echo $tabs . "\tView count: " . $videoEntry->getVideoViewCount() ."\n <br />";
		echo $tabs . "\tRating: " . $videoEntry->getVideoRatingInfo() . "\n <br />";
		echo $tabs . "\tGeo Location: " . $videoEntry->getVideoGeoLocation() . "\n <br />";
		echo $tabs . "\t  <b>VideoId</b> : <u>" . $videoEntry->getVideoId () . " </u>\n <br />";
echo "<embed src=\"".$videoEntry->getFlashPlayerUrl()."\" width=\"400\" height=\"300\" type=\"application/x-shockwave-flash\"
	    pluginspage=\"http://www.macromedia.com/go/getflashplayer\"></embed> ";
		// see the paragraph above this function for more information on the 'mediaGroup' object
		// here we are using the mediaGroup object directly to its 'Mobile RSTP link' child
		foreach ($videoEntry->mediaGroup->content as $content) 
		{
			if ($content->type === "video/3gpp") 
			{
				echo $tabs . "\tMobile RTSP link: " . $content->url . "\n <br />";
			}
		}
		
		echo $tabs . "\tThumbnails:\n";
		$videoThumbnails = $videoEntry->getVideoThumbnails();
		echo "<img src=\"".$videoThumbnails[0]['url']."\" width=\"80\" height=\"72\" > <br />";
		/*foreach($videoThumbnails as $videoThumbnail) 
		{
			
			echo $tabs . "\t\t" . $videoThumbnail->time . " - " . $videoThumbnail->url;
			echo " height=" . $videoThumbnail->height;
			echo " width=" . $videoThumbnail->width;
			echo "\n <br />";
		}
	}
*/
?>
