<?php
/**
* Инициализация     
* @author Shagiahmetov Aidar F.
* @version 1.01
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) © 2010  
*/  

/**
* подключение модуля для работы с друзьями
* @package Friends.php    
*/
require_once "engine/modules/friends/Friends.php";  

/**
* подключение модуля для работы с сообщениями
* @package Message.php    
*/
require_once "Message.php";  

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

/*
$f=new Friends();          //если надо добавить друга быстро
$f->addFriend(1233);  
*/
  /*- - -*/
$flag = false;    
try 
{
    $user=new User();
    $UserID=$user->id;
    $frnd=new Friends($UserID); 
    $flag = true;
} 
catch (Exception $e) 
{
    echo " <font color=red>Пользователь не залогинился </font><br/>";  
}  
$mess = new Message();
$smarty=new SmartyExst();
if ($flag) 
{
    $output["text"]=$smarty->fetch("message/MainMessage.tpl");  
    switch($data["parameters"][0]) 
        {  
            case "DoSend" : 
            { 
                $smarty->assign("arr",$frnd->getAllFriends());
                $output["text"]=$smarty->fetch("message/DoSend.tpl");
                // if (isset($_POST["sbmt"])) {$str="NULL";} 
                //if (isset($_POST["sv"])) {$state=0;} 
                if (isset($_POST["sbmt"])) 
                {
                    $mess->saveMes($_POST["mes"],$_POST["sel"],$UserID,1);
                    unset($_POST["sbmt"]);
                }
                if (isset($_POST["sv"])) 
                {
                    $mess->saveMes($_POST["mes"],NULL,$UserID,1);
                    unset($_POST["sv"]);
                }
                break;
            }
            case "GetNew" : 
            {
                $arr=$mess->getNew($UserID);
                $smarty->assign("arr",$arr); 
                $output["text"]=$smarty->fetch("message/GetNew.tpl");
                break;
            }
            case "GetSaves" : 
            {
                $arr=$mess->getSaves($UserID);
                $smarty->assign("arr",$arr); 
                $output["text"]=$smarty->fetch("message/GetSaves.tpl");
                break;
            }
            case "GetAllMy" : 
            {
                $arr=$mess->allmes($UserID);
                $smarty->assign("arr",$arr);
                $output["text"]=$smarty->fetch("message/GetAllMy.tpl");
                break;
            }  
            case "GetSends" : 
            {
                $arr=$mess->getSends($UserID);  
                $smarty->assign("arr",$arr);
                $output["text"]=$smarty->fetch("message/GetSends.tpl");
                break; 
            }     
        }
}
?>
