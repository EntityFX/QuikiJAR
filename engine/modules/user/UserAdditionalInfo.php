<?php
/**
* Файл для получении дополнительной инфы пользователя.
* @package user
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/

    final class UserAdditionalInfo
    {
        public $text;
        
        public $title;
        
        public function __construct(&$arr)
        {
            if ($arr!=NULL)    
            {
                $this->title=$arr["attribute"];
                $this->text=$arr["value"];
            }
        }
    }
?>
