<?php
/**
* Загрузчик текстового модуля.
* @package modules.text 
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur) © 2010 
*/
require_once("class_text_module.php");
$txtM = new TextModule();
try
{
    $output = $txtM->getText($data["id"]); 
}
catch(Exception $arr2)
{
    $output = array("text" => "<span style=\"color: red; font-weight: bold;\">Текстовый модуль: ".$arr2->getMessage()."</span>", "id" => NULL);
}
?>