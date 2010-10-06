<?php
    require_once "Friends.php";
    
    require_once "GroupsCreator.php";
    
    require_once "engine/modules/user/User.php";
    
    require_once "engine/modules/accessLevelRights/AccessLevelController.php";
    
    /**
    * Подключает Smarty с настройками
    * @filesource engine/kernel/SmartyExst.php 
    */
    require_once SOURCE_PATH."engine/kernel/SmartyExst.php";    
    $friends=new Friends();
    $groups=new GroupsCreator();
    $smarty=new SmartyExst();  
    switch ($data["parameters"][0])
    {
        case "delete":
            $friends->deleteFriend((int)$data["parameters"][1]);
            header("Location: /friends/");
            break;
        case "groups":
            $groups=new GroupsCreator();
            if (isset($data["parameters"][1]))
            {
                switch ($data["parameters"][1])
                {
                    case "add":
                        $groups->create($_POST["group_name"]);
                        header("Location: /friends/groups/"); 
                        break;
                    case "del":
                        $groups->delete($data["parameters"][2]);
                        header("Location: /friends/groups/"); 
                    case "show":
                        $group=$groups->getGroup($data["parameters"][2]);  
                        $smarty->assign("GROUP",$group);
                        $smarty->assign("FRIENDS",$friends->getAllFriends());
                        $smarty->assign("GROUP_FRIENDS",$group->getFriends());  
                        $output["text"]=$smarty->fetch("friends.groups.show.tpl");
                        break;
                    case "addfriend":
                        $group=$groups->getGroup($data["parameters"][2]);
                        $group->addFriend($_POST["friend_ID"]);
                        header("Location: /friends/groups/show/$group->id/");
                        break;
                    case "deletefriend":
                        $group=$groups->getGroup($data["parameters"][2]);
                        $group->delFriend($data["parameters"][3]);
                        header("Location: /friends/groups/show/$group->id/");
                        break;
                }
            }
            else
            {
                $smarty->assign("GROUPS",$groups->getAllGroups());
                $output["text"]=$smarty->fetch("friends.groups.tpl"); 
            }   
            break;
        default:
            $arrayFriends=$friends->getAllFriends();
            $randomFriends=$friends->getRandomFriends(3);
            $smarty->assign("FRIENDS",$arrayFriends);
            $smarty->assign("randomFRIENDS",$randomFriends);
            $output["text"]=$smarty->fetch("friends.all.tpl");
            break;    
    }
?>