<?php
    require_once "Friends.php";
    
    require_once "engine/modules/user/User.php";
    
    /**
    * Подключает Smarty с настройками
    * @filesource engine/kernel/SmartyExst.php 
    */
    require_once SOURCE_PATH."engine/kernel/SmartyExst.php";    
    $friends=new Friends();
    $array=$friends->getAllFriends();
    $smarty=new SmartyExst();
    $smarty->assign("FRIENDS",$array);
    $output["text"]=$smarty->fetch("friends.all.tpl");
?>