<?php
  
	/**
	 * ������� ��������� ����������, �.� �������� ������ �� �����.
	 * @author ����� 22.09.10 <gtimur666@gmail.com>
	 * @version 1.0
	 */
	

        /**
         * ��������� - ����� �������� ������ �� ����� �� $fileCount ����. 
         * @param Array $resourseArray �������� ������.
         * @param integer $listNum ����� �����.
         * @param integer $fileCount ���������� ��������� �� �����.
         * @return Array ������������� ������. + ��������� �������� listCount - ����� ���������� ������,
         *  listCurrent - ������� ����.
         */
        function listing($resourseArray,$listNum, $fileCount)
        {
            if (count($resourseArray)>$fileCount)   
            {
                $listCount = ceil((count($resourseArray))/$fileCount); 
                if ($listNum==1 | $listNum=="")//���� ������ �� ������ ����
                {   
                    for($i=0; $i<$fileCount; $i++)
                    {   
                        $returnArray[$i]=$resourseArray[$i];
                    }

                    $returnArray["listCount"]=$listCount;
                    $returnArray["listCurrent"]=1;
                }
                if ($listNum>1 && $listNum<=$listCount) 
                {
                    $e=$fileCount*($listNum-1);
                    $f=($fileCount*$listNum)-1;
                    for ($b=$e;$b<=$f;$b++)  
                    {
                        if ($resourseArray[$b]!="")
                        {
                            $returnArray[]=$resourseArray[$b];    
                        }
                    }    
                    $returnArray["listCount"]=$listCount;
                    $returnArray["listCurrent"]=$listNum;
                }
                if ($listNum<1 | $listNum>$listCount)
                {   
                    if ($listNum!="")
                    {
                        throw new Exception("������ � ������. :( ");   
                    }
                    //$returnArray = listing($resourseArray,1,$fileCount);
                    /*$returnArray=$resourseArray;
                    $returnArray["listCount"]=$listCount;
                    $returnArray["listCurrent"]="1";*/ 
                }
            }
            else
            {
                $returnArray=$resourseArray;
                $returnArray["listCount"]=1;
                $returnArray["listCurrent"]=1;
            } 
            return $returnArray;            
        }


	/**
     * ������� ������ ������� ����������
     * @param integer $listCount - ���������� ������. 
     * @param integer $listCurrent - ������� ���������.
     * @param string $getParamName - ��� ���-��������� ��� ����������� ��������.
     * @return string - ���������� ������ � ��������.
     */
    function makeNumerator($listCount,$listCurrent,$getParamName) 
    {
    	$link = $data["url"];
    	//if ($listCount<=0) throw new Exception("������ � ��������� :(");
    	if ($listCurrent<=0 | $listCurrent=="" | $listCurrent>$listCount ) 
    	{
    		$listCurrent=1;
    		header("Location: $link?$getParamName=1");
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
    			$rArrowStr = "<a href=\"$link?$getParamName=6\">&rarr;</a> \n";
    			$ret = $ret.$rArrowStr;
    		}
    		//������, ����� ������� ������� ����� 5, �� ������, ��� ����� ���������� ������� �� ������� 5.
    		//���������� � �������������� �������� ������������ ���������. 
    		//���� ���� ��� ��������� ����������� ������� - ������� ��������� �� ������ � ����� ������ ������.
    		//27.09.10 - ��������� ����������� ���-���� �������. ����� ������. � ������. �� �� ������. 
    		if ($listCurrent > 5 && $listCurrent <= $listCount-5)
    		{
    			$begin = $listCurrent -3;
    			$end = $listCurrent +1;
    			$preBegin = $listCurrent -1;
    			$afterEnd = $listCurrent+1;
				$ret=cycleLink($begin, $end, $listCurrent, $link);
				/*$lArrowStr = "<a href=\"$link?l=1\">&larr;</a> \n";
				$rArrowStr = "<a href=\"$link?l=$listCount\">&rarr;</a> \n";*/
				$lArrowStr = "<a href=\"$link?$getParamName=$preBegin\">&larr;</a> \n";
				$rArrowStr = "<a href=\"$link?$getParamName=$afterEnd\">&rarr;</a> \n";
    			$ret = $lArrowStr.$ret.$rArrowStr;
    		}
    		//� ��� �� ��������� ������ - ������! - ����� ��������� �����, ��� �� 5 ��������� �� ����� ������ 
    		if ($listCurrent > $listCount -5 && $listCurrent <= $listCount && $listCurrent >=5)
    		{
    			$begin = $listCount - 4;
    			$end = $listCount;
    			$preBegin = $begin-1;
    			$ret=cycleLink($begin, $end, $listCurrent, $link, $getParamName);
    			$lArrowStr = "<a href=\"$link?$getParamName=$preBegin\">&larr;</a> \n";
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
    function cycleLink($begin, $end, $listCurrent, $link, $getParamName)
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
		    $linkStr = "<a href=\"$link?$getParamName=$i\">$strNum</a>\n";
		    $ret2 = $ret2.$linkStr; 
	    }
	    return $ret2;
    }
?>