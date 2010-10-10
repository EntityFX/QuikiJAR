<?php
/**
 * Загрузчик модуля комментариев
 * @author Timur 25.09.10 <gtimur666@gmail.com>
 * @version 1.0
 */

	require_once "Commentor.php";
	require_once "engine/modules/user/User.php";
	require_once "engine/modules/numerator/Numerator.php";
	require_once "engine/modules/galary/Galary.php";
	
	function showElementComments($elementID, $module, $listNum, $userID, $visitor, $link)
	{
		$comm = new Commentor();
		
    	
    	//$userLink=$user->
		$array = $comm->readComments($elementID, $module, $listNum, $userID, $visitor);
		foreach ($array as $index => $value) 
		{
			if ($index!=="listCount" && $index!=="listCurrent")
			{
				$user=new User($value["poster_user"]);
				$photo=$user->getPhoto();
				$userName = $user->name."&nbsp;".$user->secondName;
				$commDate = $value["comment_time"];
				$comment = $value["comment"];
				if ($visitor==$userID || $visitor==$value["poster_user"]) 
				{
					$delUser=$value["poster_user"];
					$commID=$value["id"];
					$delLink = "<a href=\"/$link?comm=$commID\">Удалить</a>";;
				}
				else 
				{
					$delLink ="";
				}
				$strTable = $strTable."<table border=\"1\">\n<tr>\n<td>\n  <img src=\"$photo\" style=\"max-width:90px; max-height: 90px;\" > 
				\n</td>\n
				<td> $userName  &nbsp;&nbsp;&nbsp; $commDate &nbsp;&nbsp;&nbsp;   $delLink <br /> $comment </td>
				</tr>\n</table>\n";
			}
		}
		$numstr = makeNumerator($array["listCount"],$array["listCurrent"],"c");
		$listingDiv="<div> $numstr </div>";
		$commentsDiv="<div>$strTable </div>";
		$ret["text"]=$listingDiv.$commentsDiv.$listingDiv;//die(var_dump($ret));
		if ($array[0]["id"]==0) 
		{
			$ret["text"]="<div> Комментарии отсутствуют! </div>";
		}
		return $ret;
	}
	
	function writeCommentForm($link) 
	{
		$formStr="<form method=\"post\" action=\"/$link\" name=\"add_comment\">
		Комментарий. Ваш комментарий: <br />
		
		<textarea cols=\"23\" rows=\"5\" name=\"id_comment\"></textarea><br />
		<input value=\"Комментировать\" type=\"submit\"><br />
		</form>";
    	$ret["text"]=$formStr;
    	return $ret;
	}
	
	function writeComment2($id, $module, $visitor, $comment, $user) 
	{
		$comm = new Commentor();
		return $comm->writeComment($id, $module, $visitor, $comment, $user);
	}
	
	function deleteComment2($id) 
	{
		$comm = new Commentor();
		$ret=$comm->deleteComment($id);
		return $ret;
	}
	/**
	 * Формирование массива доступных комментариев
	 * @param $module - имя модуля
	 * @param $visitor - id посетителя
	 * @param $user - id пользователя
	 * @param $listnum - номер страницы
	 * @return строка хтмл комментариев.
	 */
	function showAllComments($module, $visitor, $userID, $listNum) 
	{
		$comm = new Commentor();
		$gal = new Galary();
		$ret = $comm->readAllComments($userID);
		foreach ($ret as $index => $value) 
		{
			if ($gal->getPrivateState($visitor, $value["pid"])) 
			{	
				$tempA=$ret;
			}	
		}
		$tempArr=listing($tempA, $listNum, 20);//20 - здесь указывается количество элементов на листе
		foreach ($tempArr as $index => $value) 
		{
			if ($index!=="listCount" && $index!=="listCurrent")
			{
				$user=new User($value["poster_user"]);
				$photo=$user->getPhoto();
				$userName = $user->name."&nbsp;".$user->secondName;
				$commDate = $value["comment_time"];
				$comment = $value["comment"];
				$imgProp = $gal->getImgProperties($value["pid"]);
				$link="/"."galary"."/".$imgProp["user"]."/".$imgProp["altname"]."/".$imgProp["id"]."/";
				if ($visitor==$userID || $visitor==$value["poster_user"]) 
				{
					$delUser=$value["poster_user"];
					$commID=$value["id"];
					$delLink = "<a href=\"$link?comm=$commID\">Удалить</a>";;
				}
				else 
				{
					$delLink ="";
				}
				$previewPath=$gal->getPreviewPathById($value["pid"]);
				
				$imgCommented = "<a href=\"$link\"> <img src=\"$previewPath\" style=\"max-width:90px; max-height: 90px;\"> </a>";
				$strTable = $strTable."<table border=\"1\">\n<tr>\n<td>\n  
				<img src=\"$photo\" style=\"max-width:90px; max-height: 90px;\" > 
				\n</td>\n
				<td> $userName  &nbsp;&nbsp;&nbsp; $commDate &nbsp;&nbsp;&nbsp;   $delLink <br /> $comment </td>
				<td> $imgCommented </td></tr>\n</table>\n";
			}
		}
		$numerator = makeNumerator($tempArr["listCount"], $tempArr["listCurrent"], "i");
		$res["text"]=$numerator.$strTable.$numerator;
		return $res;
	}
	

	function showComments4OneGalary($module, $visitor, $userID, $listNum, $altname) 
	{
		$gal = new Galary();
		$comm = new Commentor();
		$commArr = $comm->readAllComments($userID);
		if (count($commArr)==0) 
		{
			$r["text"]="Отсутствуют комментарии!";
			return $r;
		}
		$galIdsArr = $gal->getGalaryIDs($altname);
		if (count($galIdsArr)==0) 
		{
			$r["text"]="Отсутствуют изображения!";
			return $r;
		}
		foreach ($commArr as $index => $value) 
		{
			//$tempArr[] = $commArr[$index][$value["id"]];
			//$tempArr[] =
			foreach ($galIdsArr as $index2 => $value2) 
			{
				if ($value["pid"]==$value2["id"])
				{
					$tempArr[] = $value;
				}
			} 
		}
		$sortedArr = listing($tempArr, $listNum, 10);//20 - здесь указывается количество элементов на листе
		foreach ($sortedArr as $index => $value) 
		{
			if ($index!=="listCount" && $index!=="listCurrent")
			{
				$user=new User($value["poster_user"]);
				$photo=$user->getPhoto();
				$userName = $user->name."&nbsp;".$user->secondName;
				$commDate = $value["comment_time"];
				$comment = $value["comment"];
				$imgProp = $gal->getImgProperties($value["pid"]);
				$link="/"."galary"."/".$imgProp["altname"]."/".$imgProp["id"]."/";
				if ($visitor==$userID || $visitor==$value["poster_user"]) 
				{
					$delUser=$value["poster_user"];
					$commID=$value["id"];
					$delLink = "<a href=\"$link?comm=$commID\">Удалить</a>";;
				}
				else 
				{
					$delLink ="";
				}
				$previewPath=$gal->getPreviewPathById($value["pid"]);
				
				$imgCommented = "<a href=\"$link\"> <img src=\"$previewPath\" style=\"max-width:90px; max-height: 90px;\"> </a>";
				$strTable = $strTable."<table border=\"1\">\n<tr>\n<td>\n  
				<img src=\"$photo\" style=\"max-width:90px; max-height: 90px;\" > 
				\n</td>\n
				<td> $userName  &nbsp;&nbsp;&nbsp; $commDate &nbsp;&nbsp;&nbsp;   $delLink <br /> $comment </td>
				<td> $imgCommented </td></tr>\n</table>\n";
			}
		}
		$numerator = makeNumerator($sortedArr["listCount"], $sortedArr["listCurrent"], "g");
		$res["text"]=$numerator.$strTable.$numerator;
		return $res;
	}
?>