<?php
/**
* Инициализация     
* @author Shagiahmetov Aidar F.
* @version 1.01
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) © 2010  
*/  

/**
* подключение модуля
* @package Message.php    
*/
require_once "Message.php";  

/**
* подключение модуля
* @package SmartyExst.php    
*/
require_once "engine/kernel/SmartyExst.php";

$mess = new Message();
$smarty=new SmartyExst();
$output["text"]=$smarty->fetch("message/MainMessage.tpl");  
switch($data["parameters"][0]) 
    {  
        case "DoSend" : 
        { 
            $arr=$mess->allMes(911);
            $smarty->assign("arr",$arr);
            $output["text"]=$smarty->fetch("message/DoSend.tpl"); 
            break;
        }
        case "GetNew" : 
        {
            $arr=$mess->getNew(911);
            $smarty->assign("arr",$arr); 
            $output["text"]=$smarty->fetch("message/GetNew.tpl");
            break;
        }
        case "GetSaves" : 
        {
            $arr=$mess->getSaves(911);
            $smarty->assign("arr",$arr); 
            $output["text"]=$smarty->fetch("message/GetSaves.tpl");
            break;
        }
        case "GetAllMy" : 
        {
            $arr=$mess->allmes(911);
            $smarty->assign("arr",$arr);
            $output["text"]=$smarty->fetch("message/GetAllMy.tpl");
            break;
        }  
        case "GetSends" : 
        {
            $arr=$mess->getSends(332);  
            $smarty->assign("arr",$arr);
            $output["text"]=$smarty->fetch("message/GetSends.tpl");
            break;
        }   
    }
?>
