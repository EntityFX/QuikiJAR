<?php
/**
 * Загрузчик модуля галерей
 * @author Timur 18.09.10 <gtimur666@gmail.com>
 * @version 1.0
 */

	require_once "Galary.php";
	require_once "engine/modules/user/User.php";
	require_once "engine/modules/numerator/Numerator.php";
	require_once "engine/modules/commentor/init.php";
    require_once "engine/libs/fs/FS.php";
    
	
	$deleteGalary_get = $_GET["id"];
	$showAllComments_get = $_GET["i"];
	$makeElement_get = $_GET["c"];
	$deleteComment2_get = $_GET["comm"];
	$showComments4OneGalary_get = $_GET["g"];
	$listNum = $_GET["l"]; 
	
    try 
    {
    	$userSession=new User();
    	$visitor=$userSession->id;
    } 
    catch (Exception $e) 
    {
    	echo "юзер из нот афторизед! \n <br />";
    }
	
	try
    {
        $galOne = new Galary();
    	$params = $data["parameters"]; 
        $link = $data["url"];
        $user = $params[0];
        $altname = $params[1];
        $elementID = $params[2];
        $urlArr = $data["urlArray"]; 

        switch (count($params)) 
        {
        	case 0:
        		header("Location: /galary/$visitor/");
        		//$userName = $visitor;
        		break;
        	case 1:
        		$temp = $galOne->showGalariesList($user, $visitor, $listNum);
        		$output = makeGalaryList($temp,$link,$urlArr,$user,$visitor);
        		break;
        	case 2:
        		switch ($altname) 
        		{
        			case "add":        // quki.ru/galary/13/add
        		    	if ($visitor==$user)
        		    	{
							if (count($_POST)!=0) 
		        			{       				
		        				$newGalaryName = $_POST["galary_name"];
		        				$comment = $_POST["galary_comment"];
		        				$id = $galOne->addNewGalary($user, $newGalaryName, $comment);
		        				if ($id != 0)
		        				{
		        					header("Location: /galary/$user/$id/edit/2/");
		        				}
		        			}
		        			/*
		        			if (count($_FILES)!=0)   
		        			{
		        				$altname = $_POST["id"];
		        				$fss = new FS();
		        				$userID = $user;
		        				$uploadDir = "/photos/$userID/galary";
		        				$filePropArr = $_FILES;
		        				$uFile = $fss->upload2($uploadDir, $filePropArr);
		        				$galOne->addPhoto($userID, $altname, $uploadDir."/".$uFile["lastName"]);
		        			} */
		        			
		        			if (count($_POST)==0 && count($_FILES)==0) 
		        			{
								$temp = makeAddForm($link);
	        					$output=$temp;
		        			}    		    		
        		    	}
        		    	else 
        		    	{
        		    		$urlStr = $urlArr[0].$urlArr[1]."/".$urlArr[2]."/";
        		    		header("Location: $urlStr");
        		    	}

        				break;
        			
        			case "del": // quki.ru/galary/13/del
        				if ($visitor==$user)
        		    	{
        		    		if (count($_GET)!=0) 
		        			{ 
        		    			$galOne->deleteGalary($deleteGalary_get,$user);
        		    			//die("Удалено!");
		        			}
		        			else 
		        			{
		        				$urlStr = $urlArr[0].$urlArr[1]."/".$urlArr[2]."/";
		        				header("Location: /");
		        			}
        		    	}
        		    	else 
        		    	{
        		    		$urlStr = $urlArr[0].$urlArr[1]."/".$urlArr[2]."/";
        		    		header("Location: $urlStr");
        		    	}
        				break;
        			case "comments":  // quki.ru/galary/13/comments
        				$output=showAllComments("galary", $visitor, $user, $showAllComments_get);
        				break;
        				
        			case "edit": //раздел для редактирования альбома.   quki.ru/galary/13/edit
        				$output="";
        				break;
        				
        			default:
        				$temp = $galOne->showGalary($visitor, $altname, $listNum, $user);
        				$output = makeGalaryFiles($temp,$link,$urlArr);
        				break;
        		}
        		
        		break;
        	case 3:  // quki.ru/galary/13/1/comments
        		if ($elementID!="comments" && $elementID!="edit") 
        		{
	        		$temp = $galOne->showPhoto($user, $visitor, $altname, $elementID);
	        		$output = makeElement($temp, $urlArr, $user,$visitor,$makeElement_get,$link);
	        		if (count($_POST)!=0) 
	        		{
	        			writeComment2($elementID, "galary", $visitor, $_POST["id_comment"], $user);
	        			header("Location: /$link");
	        		}
        		
	        		if (count($_GET)!=0) 
	        		{
	        			$urlStr = $urlArr[0].$urlArr[1]."/".$urlArr[2]."/".$urlArr[3]."/".$urlArr[4]."/";
	        			deleteComment2($deleteComment2_get);//die("$urlStr");
	        			header("Location: $urlStr");
		        	}
        		}
        		if($elementID=="comments")
				{
					$output = showComments4OneGalary("galary", $visitor, $user, $showComments4OneGalary_get, $altname);
				}
        		if($elementID=="edit")
				{
					header("Location: /$link"."1");
				}
        		break;
        		
        	case 4:
        		if ($visitor==$user)
        		{
        			$output["text"] = editAlbumForm($altname, $user, $urlArr, $urlArr[5], $_POST, $_FILES, $_GET);
        		}
        		break;
        	default:
        		header("Location: /");
        		break;
        }
    }
    catch(Exception $arr2)
    {
    	$output = array("text" => "<span style=\"color: red; font-weight: bold;\">Эх, ".$arr2->getMessage()."</span>", "id" => NULL);
    }
    
    /**
     * Построение списка галерей по массиву
     * @param $arrray - массив, полученный из базы
     * @return string - строка с html
     */
    function makeGalaryList($array,$link,$urlArr,$user,$visitor) 
    {
    	foreach ($array as $index => $value) 
		{
			if ($index!="listCount" && $index!="listCurrent")
			{
				if ($value["cover"]!=NULL)
				{
					$cover = $value["cover"];
				}
				else 
				{
					$cover = "/photos/no-galary.jpg";
				}
				if ($value["modified"]!=NULL) 
				{
					$mod = "Последние изменения: ".$value["modified"];
				}
				else 
				{
					$mod = "";
				}
				if ($value["comment"]!=NULL) 
				{
					$comment = $value["comment"]."<br />\n";
				}
				else 
				{
					$comment ="";
				}
				if ($visitor==$user)
				{
					//$delAlb = "<td> <a href=\"/".$link."del/?id=".$value["id"]."\"> Удалить </a> \n </td>";
					$editAlb = "<td> <a href=\"/".$link.$value["id"]."/edit/"."\"> Редактировать </a> \n </td>";
				}
				else 
				{
					//$delAlb="";
					$editAlb = "";
				}
				$galComments="<td> <a href=\"/".$link.$value["id"]."/comments/\"> Комментарии </a></td>";
				$allTxt = "<a href=\"/".$link.$value["id"]."/\"><b>".$value["name"]."</b></a><br />\n".$comment."Дата создания: ".$value["createdate"]
				."<br />$mod";
				$strTable = "<table border=\"1\">\n<tr>\n<td>
				<a href=\"/".$link.$value["id"]."/\">
				<img src=\"$cover\" width=\"80\" height=\"60\"></a></td>\n<td>$allTxt</td>\n   $galComments $editAlb</tr>\n</table>";
				$sumStr = $sumStr.$strTable;
			}
		}
		$lCount = "<br />\nlistCount $array[listCount] <br />\n";
		$lCount = makeNumerator($array["listCount"], $array["listCurrent"],"l");
		if ($visitor==$user) 
		{
			$newAlbCreate = "<a href=\"/".$link."add/\"> Новый альбом </a> <br /> \n";
		}
		else 
		{
			$newAlbCreate = "";
		}
		$commentsStr="<a href=\"/".$link."comments/\"> Комментарии</a> <br /> \n";
		$ret["text"] = $newAlbCreate.$commentsStr.$lCount.$sumStr.$lCount; 
		return $ret;
    }
    
    /**
     * Построение таблицы файлов в галерее
     * @param $array входной массив с данными
     * @param string $url ссылка на текущую страницу
     * @param array $urlArr ссылка на текущую страницу, разбитую в массив
     * @return string возвращает html-код
     */
    function makeGalaryFiles($array,$url,$urlArr) 
    {
    	$i = 0;
    	foreach ($array as $index=>$value)
    	{
    		if ($index!="listCount" && $index!="listCurrent")
			{
				if ($i==0)
				{
					$tr="<tr>\n";
				}
	    		if ($i==3) 
	    		{
	    			$trE="</tr> \n";
	    			$i=-1; 
	    		}
				$imgPath = $value["small_path"];
	    		$imgLink = $value["id"];
	    		$imgString = "\n <IMG SRC=\"$imgPath\" 
	    		onMouseOver=\"this.style.borderColor='#45688E'\" onMouseOut=\"this.style.borderColor=''\" style=\"max-width:130px; max-height: 90px;\"> \n";
	    		$linkStr = "<a href=\"/".$url.$imgLink."/\"> $imgString</a> ";
	    		$td = $td.$tr."<td> $linkStr </td> \n $trE";
	    		$i++;
	    		$tr="";
	    		$trE="";
			}
    	}
    	if ($tr=="") $tr="</tr>";
    	$lCount = makeNumerator($array["listCount"], $array["listCurrent"],"l");
    	$tableStr["text"] = $lCount."<br /> \n <table border=\"0\" cellspacing=\"0\">  \n $td \n $tr </table> \n <br />".$lCount;
    	return $tableStr;
    }

    /**
     * Представление элемента галереи
     * @param $array входной массив с данными
     * @param array $url ссылка на текущую страницу, разбитую в массив
     * @return string возвращает html-код
     */
    function makeElement($array, $url, $user,$visitor,$commentlistNum, $link) 
    {
    	$imgPath= $array["current"]["path"];
    	$currStr="<img src=\"$imgPath\" onMouseOver=\"this.style.borderColor='#45688E'\" 
    	onMouseOut=\"this.style.borderColor=''\" style=\"max-width:600px; max-height: 800px;\"> \n";
    	
    	$previousImgID=$array["previous"]["id"];
    	$nextImgID=$array["next"]["id"];
    	
    	$urlStr = $url[0].$url[1]."/".$url[2]."/".$url[3]."/";
    	
    	$nextImgLink="<a href=\"".$urlStr.$nextImgID."\">$currStr</a>";
    	$prevLink="<a href=\"".$urlStr.$previousImgID."\">&larr;</a>";
    	$nextLink="<a href=\"".$urlStr.$nextImgID."\">&rarr;</a>";
    	
    	$creataDate="<div>Добавлено ".$array["current"]["createdate"] ."</div>";
    	if ($array["current"]["comment"]!=NULL)
    	{
    		$comment="<div> ".$array["current"]["comment"] ."</div>";
    	}
    	else 
    	{
    		$comment ="";
    	}
    	
    	
    	$retStr = "<div align=\"center\">$prevLink &nbsp; $nextLink </div> \n
    	<div align=\"center\">$nextImgLink </div> \n
    	$creataDate \n $comment \n";
    	
    	$commentors = showElementComments($array["current"]["id"], "galary", $commentlistNum, $user, $visitor,$link);
    	$commForm = writeCommentForm($link);
    	$ret["text"]=$retStr.$commentors["text"].$commForm["text"];
    	return $ret;
    }
    
    /* да ну нафиг, ненужная хрень. не хочу доделывать
    function makePath($urlArr)
    {
    	switch (count($urlArr))
    	{
    		case 3://когда только выбран пункт "альбомы"
    			$str1=$urlArr[0].$urlArr[1]."/".$urlArr[2]."/";
    			$link1 = "<a href=\"$str1\">Альбомы</a>";
    			break;
    		case 4://когда выбран "альбом"
    			$str1=$urlArr[0].$urlArr[1]."/".$urlArr[2]."/";
    			$str2=$urlArr[0].$urlArr[1]."/".$urlArr[2]."/".$urlArr[3]."/";
    			$link1 = "<a href=\"$str1\">Альбомы</a>";
    			$link2 = "<a href=\"$str2\">Альбомы</a>";
    			break;
    		case 5://когда выбран элемент "альбома"
    			$str1=$urlArr[0].$urlArr[1]."/".$urlArr[2]."/";
    			$str3=$urlArr[0].$urlArr[1]."/".$urlArr[2]."/".$urlArr[3]."/";
    			break;
    		default:
    			$str1=$urlArr[0].$urlArr[1]."/".$urlArr[2]."/";;
    			break;
    	}
    }*/
    
    /**
     * Функция добавления формы добавки нового альбома.
     * @param $link - строчка ссылки на текущую страницу.
     */
    function makeAddForm($link) 
    {
    	$formStr="<form method=\"post\" action=\"/$link\" name=\"add_new\">
		Название альбома <br />
		<input name=\"galary_name\"><br />
		Комментарий <br />
		
		<textarea cols=\"23\" rows=\"5\" name=\"galary_comment\"></textarea><br />
		<input value=\"Создать\" type=\"submit\"><br />
		</form>";
    	$ret["text"]=$formStr;
    	return $ret;
    }
    
    function addPhotoForm($link, $id)
    {
    	$formStr = "<form action=\"/$link\" method=\"post\" enctype=\"multipart/form-data\">
<input type=\"file\" name=\"uploadFile\">
<input type=\"submit\" value=\"Send\">
<input type=\"hidden\" name=\"id\" value=\"$id\">
</form>";
    	
    	$ret["text"]=$formStr;
    	return $ret;
    }
    
    
    function editAlbumForm($altname, $userID, $linkArr, $mode, $postArray, $filesArray, $getsArray)
    {
    	$g = new Galary();
    	$ret2="";
    	if (count($postArray)!=0 && $mode==1)
    	{
    		$g->updateGalaryProperties($altname, $postArray["name"], $postArray["comment"], $postArray["blackestlist"], $postArray["whitestlist"]);
    	}
    	if (count($filesArray)!=0 && $mode==2)
    	{
    		$fss = new FS();
    		$uploadDir = "/photos/$userID/galary";
    		$uFile = $fss->upload2($uploadDir, $filesArray);
    		$g->addPhoto($userID, $altname, $uploadDir."/".$uFile["lastName"]);
    		$ret2 = "Фотография добавлена";
    	}
    	
    	$editGalUrl = $linkArr[0].$linkArr[1]."/".$linkArr[2]."/".$linkArr[3]."/".$linkArr[4]."/1/";
    	$editGalAddPhoto = $linkArr[0].$linkArr[1]."/".$linkArr[2]."/".$linkArr[3]."/".$linkArr[4]."/2/";
    	$editGalSort = $linkArr[0].$linkArr[1]."/".$linkArr[2]."/".$linkArr[3]."/".$linkArr[4]."/3/";
    	$editGalDel = $linkArr[0].$linkArr[1]."/".$linkArr[2]."/".$linkArr[3]."/".$linkArr[4]."/4/";
    	$albumsURL = $linkArr[0].$linkArr[1]."/".$linkArr[2]."/";
    	
    	$albumsLink =  "<a href=\"$albumsURL$altname/\"> Просмотр альбома</a>";
    	$editUrlLink = "<a href=\"$editGalUrl\"> Редактировать альбом</a>";
    	$addPhotoLink = "<a href=\"$editGalAddPhoto\"> Добавить фото</a>";
    	$sortlLink = "<a href=\"$editGalSort\"> Редактировать фотографии</a>";
    	$delLink = "<a href=\"$editGalDel\"> Удалить альбом</a>";
    	
    	$linkBlock = "<div>$albumsLink</div>
    	<div>$editUrlLink</div>
    	<div>$addPhotoLink</div>
    	<div>$sortlLink</div>
    	<div>$delLink</div>";
    	
    	
    	
    	$formDel = "$linkBlock";
    	
    	
    	$tempArr = $g->getGalaryPropertise($altname);
    	$galName = $tempArr["name"];
    	$galComm = $tempArr["comment"];
    	$galBlacketstList = $tempArr["sequrity"] !="" ? $tempArr["sequrity"] : "";//если чо потом вставить чо-нить
    	$galWhitestList = $tempArr["trusted"] !="" ? $tempArr["trusted"] : "";
    	
    	$formEdit = "$linkBlock
    	<br />
 <form action=\"$editGalUrl\" method=\"post\">
название <input name=\"name\" value=\"$galName\"><br />
комментарий <textarea cols=\"23\" rows=\"5\" name=\"comment\">$galComm</textarea><br />
Черный список: <input name=\"blackestlist\" value=\"$galBlacketstList\"><br />
Белый список: <input name=\"whitestlist\" value=\"$galWhitestList\"><br />
<input value=\"Сохранить\" type=\"submit\"><br />
</form>";
    	
    	$formAdd = "$linkBlock 
    	<br />
    	<form action=\"$editGalAddPhoto\" method=\"post\" enctype=\"multipart/form-data\"
<input type=\"file\" name=\"uploadFile\">
<input type=\"submit\" value=\"Send\">
</form>";
    	
    	
    	$temp1 = $g->showGalary($userID, $altname, "all", $userID);
    	//var_dump($temp1);
    	foreach ($temp1 as $index => $value) 
    	{
    		if ($index!="listCount" && $index!="listCurrent")
    		{
    			$small_path = $value["small_path"];
    			$comment = $value["comment"];
    			$photoId = $value["id"];
    			$link2element = $linkArr[0].$linkArr[1]."/".$linkArr[2]."/$altname/$photoId/";
    			$properties = "<input type=\"checkbox\" name = \"cover\" value=\"set\">Обложка альбома";
    			$table = $table."<table border=\"1\"> <tr> <td><a href=\"$link2element\"> 
    			<img src=\"$small_path\"  style=\"max-width:130px; max-height: 90px;\"> </a></td> 
	    		<td><textarea cols=\"40\" rows=\"5\" name=\"comment\">$comment</textarea></td> 
	    		<td> $properties </td> </tr> </table>";
    		}
    	}
    	$formSort = "$linkBlock
    	<br />$table";
    	switch ($mode)
    	{
    		case 1:
    			$ret = $formEdit;
    			break;
    		case 2:
    			$ret = $formAdd;
    			$ret2!="" ? $ret=$ret.$ret2 : $ret=$ret;
    			break;
    		case 3:
    			$ret = $formSort;
    			break;
    		case 4: 
    			//$ret = $formDel;
    			$ret = $g->deleteGalary($altname, $userID) ;
    			$ret ? $ret = header("Location: $albumsURL") : $ret = "Ошибочка вышла :( <br /> <a href=\"$albumsURL\"> Назад</a>";
    			break;
    		default:
    			
    			break;
    	}
    	
    	
    	return $ret;
    }
?>