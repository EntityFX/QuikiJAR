<?php
    /**
    * Подключает Smarty с настройками
    * @filesource engine/kernel/SmartyExst.php 
    */
    require_once SOURCE_PATH."engine/kernel/SmartyExst.php";
    
    require_once SOURCE_PATH."UserSignInOut.php";
    
    require_once SOURCE_PATH."UserRegister.php";
    
    require_once SOURCE_PATH."User.php";

    require_once "AdditionalInfo.php";
    
    require_once SOURCE_PATH."engine/modules/accessLevelRights/AccessLevelController.php";
    
    require_once "engine/modules/finder/Finder.php";
    
    $r=new UserRegister();
    
    //var_dump($data["urlArray"]);
    
    $smarty=new SmartyExst();
    $links=array(
        "enterForm" => "/".$data["urlArray"][1]."/",
        "signInPath" => "/".$data["urlArray"][1]."/view/",
        "signOutPath" => "/".$data["urlArray"][1]."/logout/",
        "enter" => "/".$data["urlArray"][1]."/enter/",
        "register" => "/".$data["urlArray"][1]."/register/",
        "create" => "/".$data["urlArray"][1]."/create/",
        "settings" => "/".$data["urlArray"][1]."/settings/"
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
        case "activate":
            if (isset($data["parameters"][1]))
            {
                $smarty->assign("ID",$data["parameters"][1]);
                $output["text"]=$smarty->fetch("users.activate.tpl");    
            }
            break;
        case "doactivate":
            $registerUser=new UserRegister();
            try
            {
                if (!$registerUser->activateByKey($_POST["id"],$_POST["key"]))
                {
                    $_SESSION["error"]="WRONG KEY";
                    header("Location: /user/activate/$_POST[id]/");                     
                }
            }
            catch (UserException $usEx)
            {
                $_SESSION["error"]=$usEx->getMessage();
                header("Location: /user/activate/$_POST[id]/");                  
            }
            break;
        case "enter":
            try
            {
                if (!$usersSignInOut->isEntered())
                {
                    $sigin=$usersSignInOut->authentication($_POST["mail"],$_POST["password"],$_POST["save"]);
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
            try
            {
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
                }
                else
                {
                    $currentUser=new User();   
                }
            }
            catch (UserException $ex)
            {
                header("Location: /user/");
            }       
            $smarty->assign("info",$currentUser->getInfo());
            $smarty->assign("user",$currentUser);
            $smarty->assign("photo",$currentUser->getPhoto());
            $output["title"]=$currentUser->name." ".$currentUser->secondName;
            $output["text"]=$smarty->fetch("users.view.tpl");
            break;
        case "logout":
            try
            {
                $usersSignInOut->signOut();
            }
            catch (UserException $e) {}
            header("Location: $links[enterForm]");
            break;
        case "register":
            $output["text"]=$smarty->fetch("users.register.tpl");
            break;       
        case "create": 
            $registerUser=new UserRegister();
            $registerUser->mailTemplate="users.mail.tpl";
            try
            {
                $registerUser->checkPassword($_POST["password1"],$_POST["password2"]);
                $id=$registerUser->register($_POST["mail"],$_POST["password1"],$_POST["name"],$_POST["surname"],$_POST["burthday"],$_POST["gender"],0);
            }
            catch (UserException $usrException)
            {
                $_SESSION["error"]=$usrException->getMessage();
                header("Location: /user/register/");  
            }
            header("Location: /user/activate/$id/");
            break;
        case "settings":
            $perm=new AccessLevelController(new User());
            if (isset($data["parameters"][1]))
            {
                switch ($data["parameters"][1])
                {
                    case "save":
                        $perm->setLevel($_POST["level"]);
                        break;
                    case "passchange":
                        $ps=new UserRegister();
                        $ps->changePassword($_POST["oldpass"],$_POST["newpass"]);
                        break;                      
                }
                header("Location: $links[settings]");
            } 
            $levelStrings=array("Всем","Только зарегистрированным","Друзьям и для знакомств","Друзьям","Никто");
            $smarty->assign("levelstr",$levelStrings);    
            $smarty->assign("accLevel",$perm->getLevel());
            $output["text"]=$smarty->fetch("users.settings.tpl");
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