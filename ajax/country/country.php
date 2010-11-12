<?php
	require_once "Location.php";
	if (isset($_GET["location"]))
	{
		$loc=new Location();
		switch ($_GET["location"])
		{
			case "country":
				$res=$loc->getCountries();
				break;
			case "region":
				$res=$loc->getRegions((int)$_GET["countryid"]);
				break;
			case "city":
				$res=$loc->getCities((int)$_GET["regionid"]);
				break;
		}
		echo json_encode((utf8Encode($res)));
	}
	
	function utf8Encode($res)
	{
		if (is_array($res))
		{
			foreach($res as $key => $element)
			{
				$out[$key]=utf8Encode($element);
			}
			return $out;
		}
		else if (is_string($res))
		{
			return iconv("cp1251", "UTF-8",$res);
		}
		else
		{
			return $res;
		}
	}
?>
