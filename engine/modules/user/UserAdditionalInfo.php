<?php
/**
* Файл для получении дополнительной инфы пользователя.
* @package user
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/

    /**
    * Формирует аттрибут и значение дополнительной информации
    */
    final class UserAdditionalInfo
    {
        /**
        * Название аттрибута
        * 
        * @var string
        */
        public $text;
        
        /**
        * Содержимое атрибута
        * 
        * @var string
        */
        public $title;
        
        /**
        * Конструктор. Получает на вход ассоциативный массив
        * 
        * @param Array $arr Массив с ключами attribute и value
        * @return UserAdditionalInfo
        */
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
