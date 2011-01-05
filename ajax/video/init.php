<?php

//require_once '../../engine/modules/user/UserRegister.php';

require_once '../../engine/kernel/classLoader.php';//это в любом случае на первом месте само собой
/*
 * было так
 * require_once "../../engine/libs/mysql/MySQLConnector.php";
 * а стало так
 * Loader::loadClass("engine/libs/mysql/MySQLConnector.php");
 */

//Loader::loadClass("engine/modules/user/UserFull.php");
Loader::loadClass("engine/libs/mysql/MySQLConnector.php");
Loader::loadClass("engine/modules/video/Video.php");
Loader::loadClass("config/databaseConsts.php");


switch ($_POST["type"]) 
{
	case "add":
		die(add2my($_POST));
		break;
	case "del":
		die(delVideo($_POST));
		break;
	default:
		;
		break;
}

	function myOrUserVideos($postParams, $getParams, $siteParams) 
	{
		/*$user=new User();
		$visitor = $user->id;*/
		//$userocheg=new UserFull(); 
		$userocheg->id;
		$visitor = 13;
		//is_numeric($siteParams[0]) ? $userID=$siteParams[0]: $userID=$visitor;
		$userID=$siteParams[0];
		$checker = new UserRegister();
		$userID = $checker->checkIfExsistID($userID) ? $userID : $visitor;
		return $div;
	}

	/**
	 * Добавление видео.
	 * @param $postParams
	 */
	function add2my($postParams) 
	{
		/*$user=new User();
		$uID = $user->id;*/
		$uID = 13;
		$se = new VideoThing();
		$videoID = $postParams["vId"];
		$videoTitle = htmlspecialchars($postParams["vT"]);
		return iconv( "windows-1251","utf-8",$se->add2myVideos($uID, $videoID, $videoTitle) ? "Добавлено":"Ошибка");
	}

	function delVideo($postParams) 
	{
		//$user = new UserFull();
		//$userId = $user->id;
		$userId = 13;
		$se = new VideoThing();
		$vId = $postParams["id"];
		$visitor = $postParams["userId"];
		if ($visitor==$userId)
		{
			$state = $se->deleteFromMyvideos($userId, $vId) ? "Удалено." : "Ошибка.";
		}
		else 
		{
			$state = "Ошибка удаления.";
		}
		return iconv( "windows-1251","utf-8",$state);
	}

?>
