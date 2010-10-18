<?php
/**
* Файл с классом NoticeCategory. Класс для сохранения, редактирования, удаления и загрузки рубрик объявлений.
* @author Shagiahmetov Aidar F.
* @version 1
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) © 2010  
*/

/**
* подключение модуля
* @package MySQLConnector.php   
*/
require_once "engine/libs/mysql/MySQLConnector.php";
   
class NoticeCategory extends MySQLConnector
{
    /**
    * Удаление рубрики      
    * @param int $ID - номер рубрики
    */
	protected function delCat($ID)
	{
        $this->_sql->query("DELETE FROM NoticeCategory WHERE ID='$ID'");   
	} 
    
    /**
    * Сохранение рубрики  
    * @param string $Themes - название рубрики
    */
	protected function saveCat($Themes)
	{   
	    $this->_sql->query("INSERT INTO NoticeCategory VALUES (0, '$Themes');");   
	}
    
    /**
    * Получение всех рубрик  
    */
	protected function showAllCat()
	{   
	    $query=$this->_sql->query("SELECT * FROM NoticeCategory");
	    return $this->_sql->GetRows($query);      
	} 
    
    /**
    * Получение рубрик, на которых записи пользователя 
    * @param int $UserID - номер рубрики   
    */
	protected function showCat($UserID, $CatID)
    {   
	    $query=$this->_sql->query("SELECT * FROM NoticeTopics WHERE CategoryID='$CatID'
                                            AND UserID='$UserID'");
                                            
        return $this->_sql->GetRows($query);       
	}
    
    /**
    * Редактирование рубрики, на которых записи пользователя 
    * @param int $UserID - номер рубрики   
    */  
	protected function updateCat($ID, $Themes)
	{
		$this->_sql->query("UPDATE NoticeCategory SET Themes = '$Themes' WHERE ID ='$ID'");
	}  
}
?>