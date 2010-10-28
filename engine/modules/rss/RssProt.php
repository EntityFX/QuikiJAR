<?php
/**
* Класс для приёма, сохранения, просмотра, удаления и редактирования rss-лент.
* @author Shagiahmetov Aidar
* @version 1.04
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) © 2010  
*/
//-------------------------------------------------------------------------------------------------------------

/**
* подключение модуля
* @package MySQLConnector.php   
*/
require_once "engine/libs/mysql/MySQLConnector.php";   

abstract class RssProt extends MySQLConnector  
{
    /**
    * удаление конкретной RSS-ленты
    * @param int $ID - идентификатор конкретной RSS
    */
    protected function delRSS($ID)
    {
        $this->_sql->query("DELETE * FROM rss WHERE ID = '$ID'");
    }    
    /**
    * получение конкретной RSS-ленты
    * @param int $ID - идентификатор конкретной RSS
    */
    protected function getRSS($ID)
    {
        $query = $this->_sql->query("SELECT * FROM rss WHERE ID = '$ID'");
        return $this->_sql->GetRows($query);
    } 
    
    /**
    * добавление RSS-ленты
    * @param string $File - указание места сохранения файла (приёмник) 
    * @param string $Url - указание ссылки rss-ленты (источник)
    */ 
    protected function addRSS($File, $Url)
    {
         $this->_sql->query("INSERT INTO rss VALUES ('0', '$File', '$Url');");  
    }  
    
    /**
    * обновление RSS-ленты
    * @param string $File - указание места сохранения файла (приёмник) 
    * @param string $Url - указание ссылки rss-ленты (источник)
    */ 
    protected function updRSS($ID, $File, $Url)
    {
         $this->_sql->query("UPDATE rss SET file = '$File', url = '$Url' WHERE ID ='$ID'");
    } 
    
    /**
    * получение всех RSS-лент
    */
    protected function getallRSS()
    {
        $query = $this->_sql->query("SELECT * FROM rss");
        return $this->_sql->GetRows($query);
    }  
}
?>