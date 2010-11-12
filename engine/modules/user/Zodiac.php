<?
	class Zodiac
	{
		private static $names=array(
			"Козерог",
			"Водолей",
			"Рыбы",
			"Овен",
			"Телец",
			"Близнецы",
			"Рак",
			"Лев",
			"Дева",
			"Весы",
			"Скорпион",
			"Стрелец"
		);
		
		public static function calculateByDate($date)
		{
			$dateParsed=getdate(strtotime($date));
			$day=(int)$dateParsed["yday"];
			if ($day>=356 || $day<=19)
			{
				return self::$names[0];
			}
			else if ($day>=20 && $day<=49)
			{
				return self::$names[1];
			}
			else if ($day>=50 && $day<=79)
			{
				return self::$names[2];
			}
			else if ($day>=80 && $day<=110)
			{
				return self::$names[3];
			}
			else if ($day>=141 && $day<=141)
			{
				return self::$names[4];
			}
			else if ($day>=142 && $day<=172)
			{
				return self::$names[5];
			}
			else if ($day>=173 && $day<=203)
			{
				return self::$names[6];
			}
			else if ($day>=204 && $day<=235)
			{
				return self::$names[7];
			}
			else if ($day>=236 && $day<=266)
			{
				return self::$names[8];
			}
			else if ($day>=267 && $day<=296)
			{
				return self::$names[9];
			}
			else if ($day>=297 && $day<=326)
			{
				return self::$names[10];
			}
			else if ($day>=327 && $day<=355)
			{
				return self::$names[11];
			}
		}
	}
	/*$dates=array(
		array("from"=>"2000-12-22","to"=>"2000-01-20"),
		array("from"=>"2000-01-21","to"=>"2000-02-19"),
		array("from"=>"2000-02-20","to"=>"2000-03-20"),
		array("from"=>"2000-03-21","to"=>"2000-04-20"),
		array("from"=>"2000-05-21","to"=>"2000-05-21"),
		array("from"=>"2000-05-22","to"=>"2000-06-21"),
		array("from"=>"2000-06-22","to"=>"2000-07-22"),
		array("from"=>"2000-07-23","to"=>"2000-08-23"),
		array("from"=>"2000-08-24","to"=>"2000-09-23"),
		array("from"=>"2000-09-24","to"=>"2000-10-23"),
		array("from"=>"2000-10-24","to"=>"2000-11-22"),
		array("from"=>"2000-11-23","to"=>"2000-12-21")
	);
	$i=0;
	foreach($dates as $curDate)
	{
		echo "else if (\$day>=".Zodiac::calculateByDate($curDate["from"])." && \$day<=".Zodiac::calculateByDate($curDate["to"]).")\r\n{\r\n\treturn self::\$names[$i];\r\n}\r\n";
		$i++;
	}*/
	
	//echo Zodiac::calculateByDate("1902-03-17");
?>