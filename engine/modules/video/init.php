<?php
//require_once 'Zend/Loader.php'; // the Zend dir must be in your include_path
require_once 'Video.php';
require_once 'engine/modules/numerator/Numerator.php';
require_once "engine/modules/user/User.php";
require_once 'engine/modules/user/UserRegister.php';
$parameters = $data["parameters"];
switch (count($parameters)) 
{
	case 0: // /video  -- show my video files
		$output["text"] = showOneVideo($_GET);
		break;
	case 1: 
		switch ($parameters[0]) 
		{
			case "add": // /video/add -- add video, blin
				add2my($_POST);
				break;
			case "search": // /video/search  -- searc videos
				$output["text"] = searcher($_POST,$_GET);
				break;
			default:// /video/userID  -- show user's videos
				//header("Location: /video/");
				$output["text"] = myOrUserVideos($_POST,$_GET,$parameters);
			break;
		}
		break;
	default:
		;
	break;
}

	function myOrUserVideos($postParams, $getParams, $siteParams) 
	{//die(var_dump($_SESSION));
		$user=new User(13); 
		$visitor = $user->id;
		//is_numeric($siteParams[0]) ? $userID=$siteParams[0]: $userID=$visitor;
		$userID=$siteParams[0];
		$checker = new UserRegister();
		$userID = $checker->checkIfExsistID($userID) ? $userID : $visitor;
		
		$se = new VideoThing();
		$arr = $se->showMyVideos($userID,$getParams["i"]);
		if($arr==NULL) return "По Вашему запросу ничего не найдено.";
		foreach ($arr as $key ) 
		{
			//$tmp = $se->getVideo($key[videoID]);
			$img = "<img src = \"  \">"; //$tmp[Preview]
			$div = $div."<div style=\"border:1px solid #999999; height: auto; margin: 3px; height: 140px; width: auto;\">
			<div style=\"float:left; border-right: 1px solid #999999; width: auto; \">$img</div> 
			<div style=\"float: left; height: auto; padding: 10px ;\"> $key[title] \n <br /> 
			<a href=\"/video/?v=$key[videoID]\"> просмотр</a>
			<br />
			
			<div id=\"added$key[videoID]\"> </div>
			<div>
			<form>
			<input type=\"button\" id=\"addVideo$key[videoID]\" value=\"Добавить к себе\" onClick=\"addVideo('$key[videoID]','$key[title]')\"> 
			</form>
			</div>
			</div>
			</div>";
		}
		$smarty=new SmartyExst();
		$smarty->assign("searchResults", $div);
		return $smarty->fetch("video.user.tpl");
		
		//return $div;
	}

	function add2my($postParams) 
	{
		$user=new User();
		$uID = $user->id;
		$se = new VideoThing();
		$videoID = $postParams["vId"];
		$videoTitle = htmlspecialchars($postParams["vT"]);
		die(iconv( "windows-1251","utf-8",$se->add2myVideos($uID, $videoID, $videoTitle) ? "Добавлено":"Ошибка"));
	}

//$output["text"] = makeNumerator(14, 1, "u");

	function drawSearchVideoList($searcTerm, $getParams) 
	{
		$se = new VideoThing();
		$searchFeed = $se->searchOnYT(iconv( "windows-1251","utf-8",$searcTerm));
		$sercherArr = $se->printVideoEntry($searchFeed);		
		if($sercherArr==NULL) return "По Вашему запросу ничего не найдено.";
		foreach ($sercherArr as $key ) 
		{/*
			foreach ($key as $index => $value) 
			{
				$div = $div."$index => $value <br />";
			}*/
			$img = "<img src = \"$key[Preview]\">";
			$title = "$key[VideoTitle] \n <br /> VideoID:  $key[VideoId] \n";
			$flashUrl = $key["FlashPlayer"];
			$div = $div."<div style=\"border:1px solid #999999; height: auto; margin: 3px; height: 140px; width: auto;\">
			<div style=\"float:left; border-right: 1px solid #999999; width: auto; \">$img</div> 
			<div style=\"float: left; height: auto; padding: 10px ;\"> $title \n <br /> 
			<a href=\"/video/?v=$key[VideoId]\"> просмотр</a>
			<br />
			$flashUrl
			<div id=\"added$key[VideoId]\"> </div>
			<div>
			<form>
			<input type=\"button\" id=\"addVideo$key[VideoId]\" value=\"Добавить к себе\" onClick=\"addVideo('$key[VideoId]','$key[VideoTitle]')\"> 
			</form>
			</div>
			</div>
			</div>";
		}
		
		/*$listCurrent = $getParams["l"];
		$lists = $se->getListsCount($searchFeed);	//die($lists);	
		$str = makeNumerator($lists, $listCurrent, "l");
		$div = $str.$div.$str;*/
		
		return  $div;
	}
	
	
	function showOneVideo($getParams) 
	{
		$videoId = $_GET["v"];
		$videoId!="" ? $d  = drawOneVideo($videoId) : $d = "\n <br />нет видосов <br />";
		
		$smarty=new SmartyExst();
		$smarty->assign("searchResults", $d);
		return $smarty->fetch("video.tpl");
	}
	
	function drawOneVideo($videoId) 
	{
		$dr = new VideoThing();
		$resArr = $dr->getVideo($videoId);
		$url = "http://www.youtube.com/v/".$videoId."?fs=1";
		//$url = $resArr["FlashPlayer"];
		$title = $resArr["VideoTitle"];
		$obj = "<object width=\"425\" height=\"344\">
<param name=\"movie\" value=\"$url\"></param>
<param name=\"allowFullScreen\" value=\"true\"></param>
<embed src=\"$url\"
  type=\"application/x-shockwave-flash\"
  allowfullscreen=\"true\"
  width=\"425\" height=\"344\">
</embed>
</object>";
		
		$div = "<div> $obj </div> <div>$title </div>";
		return $div;
	}
	
	
	function searcher($postParams,$getParams) 
	{
		$searchPath = "/video/search/";
		$searchStr = $postParams["searchStr"];
		if ($searchStr!="") 
		{
			/*$searchForm = "<form action=\"$searchPath\" method=\"post\"> \n
			<input value=\"$searchStr\" name=\"searchStr\">
			<input type=\"submit\" value=\"Поиск\">
			</form>";*/
			$searchResult = drawSearchVideoList($searchStr,$getParams);
		}
		else 
		{
			/*$searchForm = "<form action=\"$searchPath\" method=\"post\"> \n
			<input value=\"$searchStr\" name=\"searchStr\">
			<input type=\"submit\" value=\"Поиск\">
			</form>";*/
			$searchResult = "";
		}
		$numerator = "12345";
		$smarty=new SmartyExst();
		$smarty->assign("searchResults", $searchResult);
		$smarty->assign("numerator", $numerator);
		return $smarty->fetch("video.tpl");
		//return $searchResult;
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
