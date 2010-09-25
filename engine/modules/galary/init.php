<?php
/**
 * Загрузчик модуля галерей
 * @author Timur 18.09.10 <gtimur666@gmail.com>
 * @version 1.0
 */
    
    /**
    * Подключает класс модуля галерей Galary
    * @filesource engine/kernel/ModuleLoader.php 
    */
	require_once("galary.php");
	require_once "engine/modules/user/User.php";
	require_once "engine/modules/numerator/numerator.php";
    
    try 
    {
    	$user=new User();
    	$visitor=$user->id;
    } 
    catch (Exception $e) 
    {
    	echo "юзер из нот афторизед! \n <br />";
    }
    
	//var_dump($visitor);
	$galOne = new Galary();
	//$visitor = 124;
	try
    {
        $params = $data["parameters"]; 
        switch (count($params)) 
        {
        	case 0:
        		//header("Location: /");;
        		break;
        	case 1:
        		$user = $params[0];
        		$listNum = $_GET["l"];
        		//НУЖНО ПОЛУЧИТЬ ID ПОСЕТИТЕЛЯ!!!!!
        		$temp = $galOne->showGalariesList($user, $visitor, $listNum);
        		//var_dump("user = $user , listNum = $listNum <br />");
        		$output = makeGalaryList($temp);
        		//var_dump($data);
        		break;
        	case 2:
        		$user = $params[0];
        		$listNum = $_GET["l"];
        		$altname = $params[1];
        		$temp = $galOne->showGalary($visitor, $altname, $listNum);
        		$output = makeGalaryFiles($temp);
        		//var_dump($output2);
        		break;
        	case 3:
        		;
        		break;
        	default:
        		header("Location: /");
        		break;
        }
    }
    catch(Exception $arr2)
    {
        $output = array("text" => "<span style=\"color: red; font-weight: bold;\">Эх, 404: ".$arr2->getMessage()."</span>", "id" => NULL);
    }
    //var_dump(makeNumerator(12, $_GET["l"]));
    //var_dump("<br />".htmlspecialchars($_GET["o"]));
    
    /**
     * Построение списка галерей по массиву
     * @param $arrray - массив, полученный из базы
     * @return string - строка с html
     */
    function makeGalaryList($array) 
    {
		$link = $data["url"];
    	foreach ($array as $index => $value) 
		{
			if ($index!="listCount" & $index!="listCurrent")
			{
				$cover = $value["cover"];
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
				$allTxt = "<a href=\"".$link.$value["id"]."/\"><b>".$value["name"]."</b></a><br />\n".$comment."Дата создания: ".$value["createdate"]
				."<br />$mod";
				$strTable = "<table border=\"1\">\n<tr>\n<td>
				<a href=\"$link$value[id]/\">
				<img src=\"$cover\" width=\"80\" height=\"60\"></a></td>\n<td>$allTxt</td>\n</tr>\n</table>";
				$sumStr = $sumStr.$strTable;
			}
		}
		$lCount = "<br />\nlistCount $array[listCount] <br />\n";
		$lCount = makeNumerator($array["listCount"], $array["listCurrent"]);
		$ret["text"] = $lCount.$sumStr.$lCount; 
		return $ret;
    }
    
    function makeGalaryFiles($array) 
    {
    	$url = $data["url"];
    	$i = 0;
    	foreach ($array as $index=>$value)
    	{
    		if ($index!="listCount" & $index!="listCurrent")
			{
	    		$i++;
	    		$trBegin="";
	    		$trEnd="";
	    		if ($i==4) 
	    		{
	    			$trEnd="</tr> \n";
	    			$i=0; 
	    		}
				if ($i==1)
				{
					$trBegin="<tr>\n";
				}
				$imgPath = $value["small_path"];
	    		$imgLink = $value["id"];
	    		$imgString = "<IMG SRC=\"$imgPath\" 
	    		onMouseOver=\"this.style.borderColor='#45688E'\" onMouseOut=\"this.style.borderColor=''\" style=\"max-width:130px; max-height: 90px;\">";
	    		$linkStr = "<a href=\"".$url.$imgLink."/\"> $imgString</a>";
	    		$td = $td.$trBegin."<td> $linkStr </td>".$trEnd;
			}
    	}
    	$lCount = makeNumerator($array["listCount"], $array["listCurrent"]);
    	$tableStr["text"] = $lCount."<br /><table border=\"0\" cellspacing=\"0\"> \n $td \n </table> \n <br />".$lCount;
    	return $tableStr;
    }
?>