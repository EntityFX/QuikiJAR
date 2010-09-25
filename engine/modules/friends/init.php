<?php
    require_once "Friends.php";
    
    require_once "GroupsCreator.php";
    
    require_once "engine/modules/user/User.php";
    
    /**
    * Подключает Smarty с настройками
    * @filesource engine/kernel/SmartyExst.php 
    */
    require_once SOURCE_PATH."engine/kernel/SmartyExst.php";    
    $friends=new Friends();
    $groups=new GroupsCreator();
    $groups->create("Vasya");
    switch ($data["parameters"][0])
    {
        case "delete":
            $friends->deleteFriend((int)$data["parameters"][1]);
            header("Location: /friends/");
            break;
        default:
            $arrayFriends=$friends->getAllFriends();
            $randomFriends=$friends->getRandomFriends(3);
            $smarty=new SmartyExst();
            $smarty->assign("FRIENDS",$arrayFriends);
            $smarty->assign("randomFRIENDS",$randomFriends); 
            $output["text"]=$smarty->fetch("friends.all.tpl");
            break;    
    }
?>