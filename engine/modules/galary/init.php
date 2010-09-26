<?php
/**
 * Загрузчик модуля галерей
 * @author Timur 18.09.10 <gtimur666@gmail.com>
 * @version 1.0
 */

	require_once "Galary.php";
	require_once "engine/modules/user/User.php";
	require_once "engine/modules/numerator/Numerator.php";
    
    try 
    {
    	$user=new User();
    	$visitor=$user->id;
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
        $listNum = $_GET["l"]; 
        $urlArr = $data["urlArray"]; 

        switch (count($params)) 
        {
        	case 0:
        		//header("Location: /");;
        		break;
        	case 1:
        		$temp = $galOne->showGalariesList($user, $visitor, $listNum);
        		$output = makeGalaryList($temp,$link,$urlArr,$user,$visitor);
        		break;
        	case 2:
        		if ($altname!="add")
        		{
        			$temp = $galOne->showGalary($visitor, $altname, $listNum);
        			$output = makeGalaryFiles($temp,$link,$urlArr);
        		}
        		else 
        		{
        			if (count($_POST)!=0) 
        			{
        				if ($visitor==$user) 
						{        				
	        				$newGalaryName = $_POST["galary_name"];
	        				$comment = $_POST["galary_comment"];
	        				$galOne->addNewGalary($user, $newGalaryName, $comment);
						}
        			}
        			else 
        			{
						if ($visitor==$user) 
						{
							$temp = makeAddForm($link);
        					$output=$temp;
						}
        			}
        		}
        		
        		break;
        	case 3:
        		$temp = $galOne->showPhoto($user, $visitor, $altname, $elementID);
        		$output = makeElement($temp, $urlArr);
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
			if ($index!="listCount" & $index!="listCurrent")
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
				$allTxt = "<a href=\"/".$link.$value["id"]."/\"><b>".$value["name"]."</b></a><br />\n".$comment."Дата создания: ".$value["createdate"]
				."<br />$mod";
				$strTable = "<table border=\"1\">\n<tr>\n<td>
				<a href=\"/".$link.$value["id"]."/\">
				<img src=\"$cover\" width=\"80\" height=\"60\"></a></td>\n<td>$allTxt</td>\n</tr>\n</table>";
				$sumStr = $sumStr.$strTable;
			}
		}
		$lCount = "<br />\nlistCount $array[listCount] <br />\n";
		$lCount = makeNumerator($array["listCount"], $array["listCurrent"]);
		if ($visitor==$user) 
		{
			$newAlbCreate = "<a href=\"/".$link."add/\"> Новый альбом </a> <br /> \n";
		}
		else 
		{
			$newAlbCreate = "";
		}
		$ret["text"] = $newAlbCreate.$lCount.$sumStr.$lCount; 
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
    		if ($index!="listCount" & $index!="listCurrent")
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
    	$lCount = makeNumerator($array["listCount"], $array["listCurrent"]);
    	$tableStr["text"] = $lCount."<br /> \n <table border=\"0\" cellspacing=\"0\">  \n $td \n $tr </table> \n <br />".$lCount;
    	return $tableStr;
    }

    /**
     * Представление элемента галереи
     * @param $array входной массив с данными
     * @param array $url ссылка на текущую страницу, разбитую в массив
     * @return string возвращает html-код
     */
    function makeElement($array, $url) 
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
    	$ret["text"]=$retStr;
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
?>