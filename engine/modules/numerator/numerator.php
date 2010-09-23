<?php  
	/**
	 * Ведется обработка нумератора, т.е разбивки данных на листы.
	 * @author Тимур 22.09.10 <gtimur666@gmail.com>
	 * @version 1.0
	 */
	/**
     * Функция делает строчку нумератора
     * @param integer $listCount - количество листов. 
     * @param integer $listCurrent - текущее положение.
     * @return string - возвращает строку с ссылками.
     */
    function makeNumerator($listCount,$listCurrent) 
    {
    	$link = $data["url"];
    	//if ($listCount<=0) throw new Exception("Ошибка в нумерации :(");
    	if ($listCurrent<=0 | $listCurrent=="" | $listCurrent>$listCount ) 
    	{
    		$listCurrent=1;
    		header("Location: $link?l=1");
    	}
    	if ($listCount<=5)//это наиболее частый случай, когда количество страниц альбомов не более 5
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
    	else //случай когда листов более 5.
    	{
    		//первый случай: текущая позиция не более 5.
    		//последний (шестой) элемент отобразится стрелкой, а не цифрой.
    		if ($listCurrent <= 5)
    		{
    			$begin = 1;
    			$end = 5;
    			$ret=cycleLink($begin, $end, $listCurrent,$link);
    			$rArrowStr = "<a href=\"$link?l=6\">&rarr;</a> \n";
    			$ret = $ret.$rArrowStr;
    		}
    		//случай, когда текущая позиция более 5, но меньше, чем общее количество позиций за вычетом 5.
    		//предпервый и послепоследний элементы обозначаются стрелками. 
    		//хотя пока что последнее утверждение неверно - стрелки ссылаются на начало и конец списка листов.
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
    		//и вот он последний случай - апогей! - когда находимся ближе, чем за 5 элементов до конца списка 
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
     * Блок цикла
     * @param integer $begin - начальный индекс
     * @param integer $end - конечный индекс
     * @param integer $listCurrent - текущее положение
     * @param string $link - ссылка страницы, на которой находимся
     * @return string - возвращает строку с ссылками
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