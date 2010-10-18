<?php
/**
* Класс для отправки, приёма, сохранения, просмотра и редактирования сообщений.
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

abstract class MainMessage extends MySQLConnector
{                  
    /**
    * Сохранение сообщения  
    * @param string $Msg - Сообщение
    * @param int $FromID - От кого пришло
    * @param string $DateTime - Дата и время
    * @param int $UserID - Автор сообщения
    * @param int $State - Прочтено (1) или нет (0)
    */
    protected function saveMes($Msg, $FromID, $DateTime, $UserID, $State)
    {          
        $this->_sql->query("INSERT INTO Message VALUES ('0', '$Msg', '$DateTime', '$UserID', '$FromID', '$State');");  
    }

    /**
    * Получение черновика 
    * @param int $UserID - автор сообщения  
    */
    protected function getSaves($UserID)
    {
        $query=$this->_sql->query("SELECT * FROM Message WHERE UserID = '$UserID' AND FromID IS NULL");
        return $this->_sql->GetRows($query);
    }
    
    /**
    * Получение отправленных    
    * @param int $FromID - От кого пришло
    */
    protected function getSends($FromID)
    {
        $query=$this->_sql->query("SELECT * FROM Message WHERE FromID = '$FromID'");
        return $this->_sql->GetRows($query);
    } 

    /**
    * Получение принятых
    * @param int $UserID - Автор сообщения 
    */
    protected function getReads($UserID)
    {   
        $query=$this->_sql->query("SELECT * FROM Message WHERE UserID = '$UserID' AND FromID IS NOT NULL AND State = 1");
        return $this->_sql->GetRows($query);  
    }  
    
    /**
    * Получение новых 
    * @param int $UserID - Автор сообщения 
    */
    protected function getNew($UserID)
    {  
        $query=$this->_sql->query("SELECT * FROM Message WHERE UserID = '$UserID' AND FromID IS NOT NULL AND State = 0");
        return $this->_sql->GetRows($query);  
    }                                                                              
    
    /**
    * Удаление сообщения 
    * @param int $ID - Номер сообщения
    * @param int $UserID - Автор сообщения 
    */
    protected function delMes($ID, $UserID)
    { 
        $this->_sql->query("DELETE FROM Message WHERE ID = '$ID' AND UserID = '$UserID'");  
    } 
    
    /**
    * Все записи пользователя                               
    * @param int $UserID - Автор сообщения  
    */
    protected function allMes($UserID)
    { 
        $query = $this->_sql->query("SELECT * FROM Message WHERE UserID = '$UserID'");
        return $this->_sql->GetRows($query);
    } 
    
    /**
    * Редактирование
    * @param int $ID - Номер сообщения    
    * @param string $Msg - Сообщение
    * @param int $FromID - От кого пришло
    * @param string $DateTime - Дата и время
    * @param int $UserID - Автор сообщения
    * @param int $State - Прочтено (1) или нет (0)
    */
    protected function updateMes($ID, $UserID, $FromID, $Msg, $DateTime, $State)
    { 
        $this->_sql->query("UPDATE Message SET Message = '$Msg', DateTime = '$DateTime', FromID = '$FromID', State = '$State' 
                            WHERE ID ='$ID'");
    }   
}
?>