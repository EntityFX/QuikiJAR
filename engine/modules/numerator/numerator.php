<?php  
	/**
	 * ������� ��������� ����������, �.� �������� ������ �� �����.
	 * @author ����� 22.09.10 <gtimur666@gmail.com>
	 * @version 1.0
	 */
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