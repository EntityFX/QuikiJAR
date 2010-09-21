<?php
    $smarty->assign("TEXT_VAR",$this->_out["text"]);
    $smarty->assign("TITLE",$this->_out["title"]); 
    $menu=new LinksList($this->_arr["id"]);
    $smarty->assign("MENU",$menu->makeMenu());
    $smarty->assign("CHILDREN_MENU",$menu->getMenuChildren());
    $smarty->assign("PATH",$menu->getPath()); 
    $subSections=$menu->getSubSection();
    $smarty->assign("SUB_SECTIONS",$subSections);
?>