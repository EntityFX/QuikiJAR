<?php 
	require_once "Crm.php";
	require_once "engine/modules/user/User.php";

	$parameters = $data["parameters"];
	switch (count($parameters)) 
	{
		case 0:
			$output["text"]=showWithTabs($_POST);
			break;
		case 1:
			postCheckParams($_POST);var_dump($_POST);
			break;
		default:
			;
		break;
	}
	
	function postCheckParams($posts)
	{
		$c = new Crm();
		switch ($posts["d"]) 
		{
			case "today":
				$retForm = showTodayThings();
				break;
			case "late":
				$retForm = showLateThings();
				break;
			case "future":
				$retForm = showFutureThings();
				break;	
			case "all":
				$retForm = showAllTable();
				break;	
			case "del":
				$retForm = $c->deleteThing($_POST["i"]) ? "�������" : "�� �������";		
			default:
				$retForm="not valid value";
			break;
		}
		die(iconv("windows-1251", "utf-8", $retForm));
	}
	
	function showWithTabs($postParams)
	{
		$smarty=new SmartyExst();
		$todayForm = showTodayThings();
		$futureForm = showFutureThings();
		$lateForm = showLateThings();
		$addForm = addThing($postParams);
		$allForm = showAllTable();
		$smarty->assign("todayForm", $todayForm);
		$smarty->assign("lateForm", $lateForm);
		$smarty->assign("futureForm", $futureForm);
		$smarty->assign("addForm", $addForm);
		$smarty->assign("allForm", $allForm);
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
		foreach ($tableArr as $key => $val) 
		{
			foreach ($val as $index => $value) //����� �������� �������� �����
			{
				$TDstr = $TDstr."<td>\n".$value."\n</td> \n";
			}
			$trStr = $trStr."<tr id=\"$val[id]\" > \n <td> 
			<img src=\"/photos/del.jpg\" width=\"15\" height=\"15\" alt=\"�������\" onClick = \"deleteItem('$val[id]')\">
			</td>".$TDstr."\n</tr>\n";
			$TDstr="";
		}
		foreach ($tableNames as $index => $value) //����� ��������� ��������
		{
			if ($index == "id") $value = "�";
			if ($index == "add_time") $value = "���� ����������";
			if ($index == "todo_time") $value = "���� ����������";
			$tdName = $tdName."<td >".$value."</td>";
			//echo "$index => $value <br />";
		}//var_dump($tableNames);
		$trName = "<tr><td> </td> $tdName </tr>";
		$tableStr = "<table border=\"1\" >
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
			return "�� ������� ������ ���.";
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
			return "���� ������ �� �������������.";
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
			return "������� ���.";
		}
		$tableNames = $c->getCollNames();
		return drawTable($tableNames, $tempArr);
	}
	
	function addThing($postParams)
	{
		$c = new Crm();
		$namesTable = $c->getCollNames();
		if (count($postParams)>0) 
		{
			die($c->addTask($postParams) ? iconv("windows-1251", "utf-8","������� ���������.") : iconv("windows-1251", "utf-8","������� �� ���������."));
		}
		foreach ($namesTable as $index => $value) 
		{
			switch ($index) 
			{
				case "id":
					$value = "�";;
					break;
				case "add_time":
					$value = "���� ����������";
					$date = date("Y-m-d");
					$addStr = $addStr."<dd>$value</dt><dt><input type=\"text\" name=\"$index\" value=\"$date\"></dt> \n";
					break;
				case "todo_time":
					$value = "���� ����������";
					$addStr = $addStr."<dd>$value</dt><dt><input type=\"text\" name=\"$index\" value=\"\" id=\"datepicker\"></dt> \n";
					break;
				
				default:
					$addStr = $addStr."<dd>$value</dt><dt><input type=\"text\" name=\"$index\" value=\"\"></dt> \n";
					break;
			}
		}
		$addStr = "<dl><form action=\"\" method=\"post\" name =\"addForm\" id=\"addForm\"> $addStr 
		<input type=\"submit\" value=\"���������\" />
		
		<div id=\"added\"> </div>
		</form></dl>";
		return $addStr;
	}
	
	/*<script type="text/javascript">
	// ������� �������� ����� ���������
	$(document).ready(function() {
	// ��������� 'myForm' �������������� ������ � ������ �� ���������� �������
	$('#addForm').ajaxForm(function() {
	alert("������� �� �����������!");
	//var divEl = document.getElementById("added");
	//var sendName = document.meSend.name.value;
	//var sendCom = document.meSend.comment.value;
	//divEl.innerHTML = 
	$("#added").load("/engine/modules/crm/test.php",{name: sendName,comment:sendCom}); ;
</script>*/
?>