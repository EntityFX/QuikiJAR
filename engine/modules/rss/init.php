<?php
/**
* Инициализация     
* @author Shagiahmetov Aidar F.
* @version 1.01
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) © 2010  
*/  

/**
* подключение модуля для работы с Rss
* @package RssPub.php    
*/
require_once "RssPub.php";  

/**
* подключение модуля для работы с пользователями
* @package User.php    
*/
require_once "engine/modules/user/User.php";

/**
* подключение модуля для работы с шаблонами
* @package SmartyExst.php    
*/
require_once "engine/kernel/SmartyExst.php";

$flag = false;    
try 
{
    $user=new User();
    $UserID=$user->id;
    $flag = true;
} 
catch (Exception $e) 
{
    echo " <font color=red>Пользователь не залогинился </font><br/>";  
}  
$rss = new RssPub();
$smarty=new SmartyExst();
if ($flag) 
{
    $output["text"]=$smarty->fetch("rss/MainRSS.tpl");  
    switch($data["parameters"][0]) 
        {  
            case "DoNew" : 
            { 
                $output["text"]=$smarty->fetch("rss/DoNew.tpl");
                //$arr=$rss->addRSS();
                break;
            }
            case "Del" : 
            { 
                $smarty->assign("arr",$rss->getallRSS());
                $output["text"]=$smarty->fetch("rss/Del.tpl");
                //$arr=$rss->delRSS();  
                break;
            }
            case "Show" : 
            { 
                $smarty->assign("arr",$rss->getallRSS());
                $output["text"]=$smarty->fetch("rss/Show.tpl"); 
                break;
            }
            case "Upd" : 
            { 
                $smarty->assign("arr",$rss->getallRSS());
                $output["text"]=$smarty->fetch("rss/Upd.tpl"); 
                break;
            }
        }
}
?>
