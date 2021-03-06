<?php
    require_once "Finder.php";
	
    $links=array(
        "base" => "/".$data["urlArray"][1]."/",
        "search" => "/".$data["urlArray"][1]."/search/"
    );
    
    
    $smarty=new SmartyExst();
    $smarty->assign("links",$links);
    
    $finder=new Finder(time());
    session_start();
    switch ($data["parameters"][0])
    {
        case "search":
            if (!isset($_SESSION["search"]))
            {
                $_SESSION["search"]=$_POST;
            }
            else if ($_SESSION["search"]!=$_POST && $_POST!=NULL)
            {
                $_SESSION["search"]=$_POST;
            }   
            $finder->page=$data["parameters"][1];
            $datS=&$_SESSION["search"];
            switch($_GET["type"])
            {
                case "byData":
                    $fUsers=$finder->findByData($datS["name"],$datS["surname"],$datS["gender"],$datS["ageFrom"],$datS["ageTo"],(bool)$datS["isOnline"]);
                    $smarty->assign("COUNT",$finder->count);
                    $smarty->assign("PAGES",$finder->getPages());
                    break;
                case "byMail":
                    $fUsers=$finder->findByMail($datS["mail"]);
                    break;
                case "byId":;
                    $fUsers=$finder->findById($datS["id"]);
                    break;                    
                default:
                    $fUsers=$finder->findByData($datS["name"],$datS["surname"],$datS["gender"],$datS["ageFrom"],$datS["ageTo"],(bool)$datS["isOnline"]);
            }
            
            $smarty->assign("FINDERS",$finder->getForView($fUsers));
            $output["text"]=$smarty->fetch("finder.users.tpl");
            break;
        default:
            unset($_SESSION["search"]);
            $output["text"]=$smarty->fetch("finder.main.tpl");            
    }
	
	//require_once "generator.php";
    
?>
