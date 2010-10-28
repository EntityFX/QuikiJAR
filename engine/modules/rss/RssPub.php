<?php
/**
* Класс RssPub, который имеет открытые методы, которые обращаются к закрытым методам класса RssProt     
* @author Shagiahmetov Aidar F.
* @version 1.01
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) © 2010  
*/  
//-------------------------------------------------------------------------------------------------------------

/**
* подключение модуля
* @package RssProt.php    
*/
require_once "RssProt.php";

class RssPub extends RssProt
{
    /**
    * удаление конкретной RSS-ленты
    * @param int $ID - идентификатор конкретной RSS
    */
    public function delRSS($ID)
    {
        parent::delRSS($ID);
    }
        
    /**
    * получение конкретной RSS-ленты
    * 
    */
    public function getRSS($ID)
    {
        parent::getRSS($ID); 
    } 
    
    /**
    * добавление RSS-ленты
    * 
    */ 
    public function addRSS($File, $Url)
    {
        parent::addRSS($File, $Url);
    }  
    
    /**
    * обновление RSS-ленты
    * 
    */ 
    public function updRSS($ID, $File, $Url)
    {
        parent::addRSS($File, $Url);
    } 
    
    /**
    * получение всех RSS-лент
    * 
    */
    public function getallRSS()
    {
        return parent::getallRSS();
    }  
}
?>