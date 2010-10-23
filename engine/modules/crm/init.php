<?php 
	require_once "Crm.php";
	require_once "engine/modules/user/User.php";

	
	//die(var_dump($data));
	$parameters = $data["parameters"];
	switch (count($parameters)) 
	{
		case 0:
			//$output["text"]="evo";
			$output["text"]=showWithTabs();
			break;
		/*case 1:
			$output["text"]=$tabs. date("Y-m-d 00:00:00"); 
			break;*/
		default:
			;
		break;
	}

	function showWithTabs()
	{
		$smarty=new SmartyExst();
		$todayForm = showTodayThings();
		$futureForm = showFutureThings();
		$lateForm = showLateThings();
		$addForm = addThing();
		$smarty->assign("todayForm", $todayForm);
		$smarty->assign("lateForm", $lateForm);
		$smarty->assign("futureForm", $futureForm);
		$smarty->assign("addForm", $addForm);
		return $smarty->fetch("crm.tpl");
	}
	
	function showAllTable()
	{
		$c = new Crm();
		$tableArr = $c->readTable();
		$tableNames = $c->getCollNames();
		return drawTable($tableNames, $tableArr);
	}
	
	function drawTable($tableNames, $tableArr)
	{
		$i = count($tableArr[0]);
		for ($it = 0; $it < count($tableArr); $it++)
		{
			foreach ($tableArr[$it] as $index => $value) //здесь делается основная часть
			{
				$TDstr = $TDstr."<td>".$value."</td>";
			}
			$trStr = $trStr."<tr>".$TDstr."</tr>";
		}
		foreach ($tableNames as $index => $value) //здесь заголовок делается
		{
			if ($index == "id") $value = "№";
			if ($index == "add_time") $value = "Дата добавления";
			if ($index == "todo_time") $value = "Дата выполнения";
			$tdName = $tdName."<td>".$value."</td>";
			//echo "$index => $value <br />";
		}//var_dump($tableNames);
		$trName = "<tr> $tdName </tr>";
		$tableStr = "<table border=\"1\">
		$trName
		$trStr
		</table>";
		//var_dump($i);
		return $tableStr;		
	}
	
	function showTodayThings()
	{
		$c = new Crm();
		$tempArr = $c->todayThings();
		if (count($tempArr)==0)
		{
			return "На сегодня ничего нет.";
		}
		$tableNames = $c->getCollNames();
		return drawTable($tableNames, $tempArr);
	}
	
	function showFutureThings()
	{
		$c = new Crm();
		$tempArr = $c->futureORlateThings("f");
		if (count($tempArr)==0)
		{
			return "Пока ничего не запланировано.";
		}
		$tableNames = $c->getCollNames();
		return drawTable($tableNames, $tempArr);
	}

	function showLateThings()
	{
		$c = new Crm();
		$tempArr = $c->futureORlateThings("l");
		if (count($tempArr)==0)
		{
			return "Записей нет.";
		}
		$tableNames = $c->getCollNames();
		return drawTable($tableNames, $tempArr);
	}
	
	function addThing()
	{
		$c = new Crm();
		$namesTable = $c->getCollNames();
		
		$script = "
		<script type=\"text/javascript\" src=\"/engine/js/jquery.form.js\"></script>
		<script type=\"text/javascript\">
        // ожидаем загрузки всего документа
        $(document).ready(function() {
            // назначаем 'addForm' обрабатываемой формой и задаем ей простецкую функцию
            $('#addForm').ajaxForm(function() {
                alert(\"Спасибо за комментарий!\");
            });
        });
    </script>";
		foreach ($namesTable as $index => $value) 
		{
			if ($index == "id") 
			{
				$value = "№";
			}
			if ($index == "add_time") $value = "Дата добавления";
			if ($index == "todo_time") $value = "Дата выполнения";
			switch ($index) 
			{
				case "id":
					$value = "№";;
					break;
				case "add_time":
					$value = "Дата добавления";
					$addStr = $addStr."<dd>$value</dt><dt><input type=\"text\" name=\"$index\" value=\"\"></dt>";
					break;
				case "add_time":
					$value = "Дата выполнения";
					$addStr = $addStr."<dd>$value</dt><dt><input type=\"text\" name=\"$index\" value=\"\"></dt>";
					break;
				
				default:
					$addStr = $addStr."<dd>$value</dt><dt><input type=\"text\" name=\"$index\" value=\"\"></dt>";
					break;
			}
		}
		$addStr = $script."<dl><form action=\"/crm/\" method=\"post\" name =\"addForm\"> $addStr 
		<input type=\"submit\" value=\"Сохранить\" />
		</form></dl>";
		return $addStr;
	}
?>