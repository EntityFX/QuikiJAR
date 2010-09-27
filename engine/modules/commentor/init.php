<?php
/**
 * Загрузчик модуля комментариев
 * @author Timur 25.09.10 <gtimur666@gmail.com>
 * @version 1.0
 */

	require_once "Commentor.php";
	require_once "engine/modules/user/User.php";
	require_once "engine/modules/numerator/Numerator.php";
	
	
	function showElementComments($elementID, $module, $listNum, $user, $visitor)
	{
		$comm = new Commentor();
		
    	
    	//$userLink=$user->
		$array = $comm->readComments($elementID, $module, $listNum, $user, $visitor);
		foreach ($array as $index => $value) 
		{
			if ($index!=="listCount" && $index!=="listCurrent")
			{
				$user=new User($value["user"]);
				$photo=$user->getPhoto();
				$userName = $user->name."&nbsp;".$user->secondName;
				$commDate = $value["comment_time"];
				$comment = $value["comment"];
				$strTable = $strTable."<table border=\"1\">\n<tr>\n<td>\n  <img src=\"$photo\" style=\"max-width:90px; max-height: 90px;\" > 
				\n</td>\n
				<td> $userName   $commDate <br /> $comment </td>
				</tr>\n</table>\n";
			}
		}
		$numstr = makeNumerator($array["listCount"],$array["listCurrent"],"c");
		$listingDiv="<div> $numstr </div>";
		$commentsDiv="<div>$strTable </div>";
		$ret["text"]=$listingDiv.$commentsDiv.$listingDiv;//die(var_dump($ret));
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
?>