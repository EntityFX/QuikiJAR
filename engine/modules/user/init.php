<?php
    /**
    * Подключает Smarty с настройками
    * @filesource engine/kernel/SmartyExst.php 
    */
    require_once SOURCE_PATH."engine/kernel/SmartyExst.php";
    
    require_once SOURCE_PATH."engine/modules/user/UserSignInOut.php";
    
    require_once SOURCE_PATH."engine/modules/user/UserRegister.php";
    
    require_once SOURCE_PATH."engine/modules/user/User.php";
    
    require_once SOURCE_PATH."engine/modules/accessLevelRights/AccessLevelController.php";
    $smarty=new SmartyExst();
    $links=array(
        "signInPath" => "/".$data["urlArray"][1]."/view/",
        "signOutPath" => "/".$data["urlArray"][1]."/logout/",
        "enter" => "/".$data["urlArray"][1]."/enter/",
        "register" => "/".$data["urlArray"][1]."/register/",
        "create" => "/".$data["urlArray"][1]."/create/"
    );
    $smarty->assign("links",$links);
    $usersSignInOut=new UserSignInOut();
    if (isset($_SESSION["error"]))
    {
        echo $_SESSION["error"];
        unset($_SESSION["error"]);
    }
    switch ($data["parameters"][0])
    {
        case "enter":
            try
            {
                if (!$usersSignInOut->isEntered())
                {
                    $sigin=$usersSignInOut->authentication($_POST["mail"],$_POST["password"]);
                }
            }
            catch (UserException $ex)
            {
                $_SESSION["error"]=$ex->getMessage();
                header("Location: /user/");
            }
            if ($sigin)
            {
                header("Location: $links[signInPath]");    
            }
            break;
        case "view":
            if ($usersSignInOut->isEntered())
            {
                if ($data["parameters"][1]!=NULL)
                {
                    $currentUser=new User($data["parameters"][1]);
                }
                else
                {
                    $currentUser=new User();                    
                }
                $perm=new AccessLevelController($currentUser);
                $smarty->assign("user",$currentUser);
                $smarty->assign("photo",$currentUser->getPhoto());
                $smarty->assign("accLevel",$perm->getLevel());
                $output["title"]=$currentUser->name." ".$currentUser->secondName;
                $output["text"]=$smarty->fetch("users.view.tpl");
            }
            else
            {
                header("Location: /user/");    
            }
            break;
        case "logout":
            $usersSignInOut->signOut();
            header("Location: $links[signInPath]");
            break;
        case "register":
            $output["text"]=$smarty->fetch("users.register.tpl");
            break;
        case "create": 
            $registerUser=new UserRegister();
            try
            {
                $registerUser->checkPassword($_POST["password1"],$_POST["password2"]);
                $registerUser->register($_POST["mail"],$_POST["password1"],$_POST["name"],$_POST["surname"],$_POST["burthday"],$_POST["gender"],0);
            }
            catch (UserException $usrException)
            {
                $_SESSION["error"]=$usrException->getMessage();
                header("Location: /user/register/");  
            }
            break;    
        default:
            if ($usersSignInOut->isEntered())
            {
                header("Location: $links[signInPath]");
            }
            $output["text"]=$smarty->fetch("users.sign_out.tpl"); 
            break;                 
    }
?>