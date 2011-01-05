<?php
//require_once 'Zend/Loader.php'; // the Zend dir must be in your include_path
require_once 'Video.php';
require_once 'engine/modules/numerator/Numerator.php';
require_once "engine/modules/user/User.php";
require_once 'engine/modules/user/UserFull.php';
require_once 'engine/libs/mysql/MySQLConnector.php';
require_once "config/databaseConsts.php";
$parameters = $data["parameters"];
//$v = new VideoThing();
//$v->authYT();
//var_dump($_SERVER["DOCUMENT_ROOT"]);
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
	{
		$get=UserFull::create();
		$visitor = $get->id;
		//is_numeric($siteParams[0]) ? $userID=$siteParams[0]: $userID=$visitor;
		$userID=$siteParams[0];
		//$checker = new UserRegister();
		//$userID = $checker->checkIfExsistID($userID) ? $userID : $visitor;
		
		$se = new VideoThing();
		$arr = $se->showMyVideos($userID,$getParams["i"]);
		if($arr==NULL) return "По Вашему запросу ничего не найдено.";
		foreach ($arr as $key ) 
		{//die(var_dump($key));
			$tmp = $se->getVideo($key["videoID"]);
			$img = "<img src = \"$tmp[Preview]\">"; 
			$div = $div."<div style=\"border:1px solid #999999; height: auto; margin: 3px; height: 140px; width: auto;\" id='video$key[id]'>
			<div style=\"float:left; border-right: 1px solid #999999; width: auto; \">$img</div> 
			<div style=\"float: left; height: auto; padding: 10px ;\"> $key[title] \n <br /> 
			<a href=\"/video/?v=$key[videoID]\"> просмотр</a>
			<br />
			
			<div id=\"added$key[videoID]\"> </div>
			<div>
			<form>
			<input type=\"button\" id=\"addVideo$key[videoID]\" value=\"Добавить к себе\" onClick=\"addVideo('$key[videoID]','$key[title]')\">
			<input type=\"button\" id=\"delVideo$key[videoID]\" value=\"Удалить\" onClick=\"delVideo('$key[id]','$visitor')\"> 
			</form>
			</div>
			</div>
			</div>";
		}
		$smarty=new SmartyExst();
		$smarty->assign("searchResults", $div);
		return $smarty->fetch("video/video.user.tpl");
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

	function drawSearchVideoList($searcTerm, $getParams) 
	{
		$se = new VideoThing();
		$searchFeed = $se->searchOnYT(iconv( "windows-1251","utf-8",$searcTerm));
		$sercherArr = $se->printVideoEntry($searchFeed);		
		if($sercherArr==NULL) return "По Вашему запросу ничего не найдено.";
		foreach ($sercherArr as $key ) 
		{
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
		return  $div;
	}
	
	
	function showOneVideo($getParams) 
	{
		$videoId = $_GET["v"];
		$videoId!="" ? $d  = drawOneVideo($videoId) : $d = "\n <br />нет видосов <br />";
		
		$smarty=new SmartyExst();
		$smarty->assign("searchResults", $d);
		return $smarty->fetch("video/video.tpl");
	}
	
	function drawOneVideo($videoId) 
	{
		$dr = new VideoThing();
		$resArr = $dr->getVideo($videoId);
		$url = "http://www.youtube.com/v/".$videoId."?fs=1";
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
		$addInput = "<input type=\"button\" id=\"addVideo$videoId\" value=\"Добавить к себе\" onClick=\"addVideo('$videoId','$title')\">";
		$delInput = "<input type=\"button\" id=\"delVideo$videoId\" value=\"Удалить\" onClick=\"delVideo('$key[id]','$visitor')\"> ";
		$div = "<div> $obj </div> <div>$title </div>";
		return $div;
	}
	
	
	function searcher($postParams,$getParams) 
	{
		$searchPath = "/video/search/";
		$searchStr = $postParams["searchStr"];
		if ($searchStr!="") 
		{
			$searchResult = drawSearchVideoList($searchStr,$getParams);
		}
		else 
		{
			$searchResult = "";
		}
		$numerator = "12345";
		$smarty=new SmartyExst();
		$smarty->assign("searchResults", $searchResult);
		$smarty->assign("numerator", $numerator);
		return $smarty->fetch("video/video.tpl");
	}

?>
