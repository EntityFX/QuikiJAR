<?php 

class Loader
{
	public static function loadClass($classname)
	{
		$root = $_SERVER["DOCUMENT_ROOT"];
		$scriptName = $_SERVER["SCRIPT_NAME"];
		$defaultScript = "/index.php";
		$i = 1;
		
		if ($scriptName == $defaultScript)
		{
			if(file_exists($classname)==FALSE)
			throw new Exception(iconv("windows-1251", "utf-8","Кажись файла не существует."));
			else
			require_once $classname; 
		}
		else
		{
			while(1)
			{
				$classname = "../".$classname;
				$i++;
				if($i > 20) {throw new Exception(iconv("windows-1251", "utf-8","Количество итераций достигло $i. <br />
				<b>classname:</b> $classname "));}
				if(file_exists($classname))
				{
					require_once $classname;
					break;
				}
			}
		}
	}
}
?>