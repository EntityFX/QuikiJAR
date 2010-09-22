<?php
/**
 * ��������� ������ �������
 * @author Timur 18.09.10 <gtimur666@gmail.com>
 * @version 1.0
 */
    
    /**
    * ���������� ����� ������ ������� Galary
    * @filesource engine/kernel/ModuleLoader.php 
    */
	require_once("galary.php");
	$galOne = new Galary();
	$visitor = 124;
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
        		//����� �������� ID ����������!!!!!
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
        		var_dump($temp);
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
        $output = array("text" => "<span style=\"color: red; font-weight: bold;\">��, 404: ".$arr2->getMessage()."</span>", "id" => NULL);
    }
    //var_dump(makeNumerator(12, $_GET["l"]));
    //var_dump("<br />".htmlspecialchars($_GET["o"]));
    /**
     * ���������� ������ ������� �� �������
     * @param $arrray - ������, ���������� �� ����
     * @return string - ������ � html
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
					$mod = "��������� ���������: ".$value["modified"];
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
				$allTxt = "<a href=\"".$link.$value["id"]."/\"><b>".$value["name"]."</b></a><br />\n".$comment."���� ��������: ".$value["createdate"]
				."<br />$mod";
				$strTable = "<table border=\"1\">\n<tr>\n<td><img src=\"$cover\" width=\"80\" height=\"60\"></td>\n<td>$allTxt</td>\n</tr>\n</table>";
				$sumStr = $sumStr.$strTable;
			}
		}
		$lCount = "<br />\nlistCount $array[listCount] <br />\n";
		$lCount = makeNumerator($array["listCount"], $array["listCurrent"]);
		$ret["text"] = $lCount.$sumStr.$lCount; 
		return $ret;
    }
    /**
     * ������� ������ ������� ����������
     * @param integer $listCount - ���������� ������. 
     * @param integer $listCurrent - ������� ���������.
     * @return string - ���������� ������ � ��������.
     */
    function makeNumerator($listCount,$listCurrent) 
    {
    	$link = $data["url"];
    	//if ($listCount<=0) throw new Exception("������ � ��������� :(");
    	if ($listCurrent<=0 | $listCurrent=="" | $listCurrent>$listCount ) 
    	{
    		$listCurrent=1;
    		header("Location: $link?l=1");
    	}
    	if ($listCount<=5)//��� �������� ������ ������, ����� ���������� ������� �������� �� ����� 5
    	{
    		if ($listCount!=1)
    		{
	    		$ret=cycleLink(1, $listCount, $listCurrent, $link);	
    		}
    		else 
    		{
    			$ret="";
    		}
    	}
    	else //������ ����� ������ ����� 5.
    	{
    		//������ ������: ������� ������� �� ����� 5.
    		//��������� (������) ������� ����������� ��������, � �� ������.
    		if ($listCurrent <= 5)
    		{
    			$begin = 1;
    			$end = 5;
    			$ret=cycleLink($begin, $end, $listCurrent,$link);
    			$rArrowStr = "<a href=\"$link?l=6\">&rarr;</a> \n";
    			$ret = $ret.$rArrowStr;
    		}
    		//������, ����� ������� ������� ����� 5, �� ������, ��� ����� ���������� ������� �� ������� 5.
    		//���������� � �������������� �������� ������������ ���������. 
    		//���� ���� ��� ��������� ����������� ������� - ������� ��������� �� ������ � ����� ������ ������.
    		if ($listCurrent > 5 & $listCurrent <= $listCount-5)
    		{
    			$begin = $listCurrent -3;
    			$end = $listCurrent +1;
    			$preBegin = $listCurrent -1;
    			$afterEnd = $listCurrent+1;
				$ret=cycleLink($begin, $end, $listCurrent, $link);
				/*$lArrowStr = "<a href=\"$link?l=1\">&larr;</a> \n";
				$rArrowStr = "<a href=\"$link?l=$listCount\">&rarr;</a> \n";*/
				$lArrowStr = "<a href=\"$link?l=$preBegin\">&larr;</a> \n";
				$rArrowStr = "<a href=\"$link?l=$afterEnd\">&rarr;</a> \n";
    			$ret = $lArrowStr.$ret.$rArrowStr;
    		}
    		//� ��� �� ��������� ������ - ������! - ����� ��������� �����, ��� �� 5 ��������� �� ����� ������ 
    		if ($listCurrent > $listCount -5 & $listCurrent <= $listCount & $listCurrent >=5)
    		{
    			$begin = $listCount - 4;
    			$end = $listCount;
    			$preBegin = $begin-1;
    			$ret=cycleLink($begin, $end, $listCurrent, $link);
    			$lArrowStr = "<a href=\"$link?l=$preBegin\">&larr;</a> \n";
    			$ret = $lArrowStr.$ret;
    		}
    	}
    	
    	//$linkStr = "<a href=\"$link?l=6\">&rarr;</a> \n";
		//$ret = $ret.$linkStr;
    	return $ret;
    }
    /**
     * ���� �����
     * @param integer $begin - ��������� ������
     * @param integer $end - �������� ������
     * @param integer $listCurrent - ������� ���������
     * @param string $link - ������ ��������, �� ������� ���������
     * @return string - ���������� ������ � ��������
     */
    function cycleLink($begin, $end, $listCurrent, $link)
    {
    	for ($i=$begin; $i<=$end; $i++)
	    {
		    if ($i!=$listCurrent) 
		    {
		    	$strNum = "$i";
		    }
		    else 
		    {
		    	$strNum = "<b>$i</b>";
		    }
		    $linkStr = "<a href=\"$link?l=$i\">$strNum</a>\n";
		    $ret2 = $ret2.$linkStr; 
	    }
	    return $ret2;
    }
?>