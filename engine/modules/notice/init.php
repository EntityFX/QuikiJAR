<?php
/**
* Инициализация Объявлений    
* @author Shagiahmetov Aidar F.
* @version 1
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) © 2010  
*/  

/**
* подключение модуля
* @package NoticeCategoryPF.php    
*/
require_once "NoticeCategoryPF.php";

/**
* подключение модуля
* @package NoticeTopicsPF.php    
*/ 
require_once "NoticeTopicsPF.php";
  
/**
* подключение модуля
* @package SmartyExst.php    
*/
require_once "engine/kernel/SmartyExst.php";

$not = new NoticeTopicsPF();
$notADM = new NoticeCategoryPF(); 
$smarty=new SmartyExst();
$output["text"]=$smarty->fetch("notice/MainNotice.tpl");  
switch($data["parameters"][0]) 
    {  
        case "DoNew" : 
        { 
            $output["text"]=$smarty->fetch("notice/DoNew.tpl"); 
            break;
        }
        case "GetAllMy" : 
        {
            $arr=$not->allTopUI(255); 
            $smarty->assign("arr",$arr);
            $output["text"]=$smarty->fetch("notice/GetAllMy.tpl");
            break;
        }
        case "DoNewADM" : 
        {
            $output["text"]=$smarty->fetch("notice/DoNewADM.tpl");
            break;
        }
        case "GetAllADM" : 
        {
            $arr=$notADM->showAllCat();
            $smarty->assign("arr",$arr);
            $output["text"]=$smarty->fetch("notice/GetAllADM.tpl");
            break;
        }   
    }
?>
