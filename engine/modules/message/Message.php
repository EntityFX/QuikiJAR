<?php
/**
* Класс Message, который имеет открытые методы, которые обращаются к закрытым методам класса MainMessage     
* @author Shagiahmetov Aidar F.
* @version 1.01
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) © 2010  
*/  
//-------------------------------------------------------------------------------------------------------------

/**
* подключение модуля
* @package MainMessage.php    
*/
require_once "MainMessage.php";

class Message extends MainMessage
{       
    /**
    * Сохранение сообщения  
    * @param string $Msg - Сообщение
    * @param int $FromID - От кого пришло
    * @param string $DateTime - Дата и время
    * @param int $UserID - Автор сообщения
    * @param int $State - Прочтено (1) или нет (0)
    */
    public function saveMes($Msg, $FromID, $UserID, $State)
    {
        parent::saveMes($Msg, $FromID, date('Y-m-d [H:i:s]'), $UserID, $State);     
    }

    /**
    * Получение черновика 
    * @param int $UserID - Автор сообщения  
    */
    public function getSaves($UserID)
    {     
       return parent::getSaves($UserID);
    }
    
    /**
    * Получение отправленных    
    * @param int $FromID - От кого пришло    
    */  
    public function getSends($FromID)
    {
        return parent::getSends($FromID);
    } 
    
    /**
    * Получение принятых
    * @param int $UserID - Автор сообщения
    */  
    public function getReads($UserID)
    {
        return parent::getReads($UserID);
    }  
    
    /**
    * Получение новых 
    * @param int $UserID - Автор сообщения
    */  
    public function getNew($UserID)
    {
        return parent::getNew($UserID);
    }                                                                              
    
    /**
    * Удаление сообщения 
    * @param int $ID - Номер сообщения
    * @param int $UserID - Автор сообщения    
    */  
    public function delMes($ID, $UserID)
    {
        parent::delMes($ID, $UserID);    
    } 
    
    /**
    * Все записи пользователя                               
    * @param int $UserID - Автор сообщения
    * @param object $mm - Экземпляр класса MainMessage   
    */                                    
    public function allMes($UserID)
    {
        return parent::allMes($UserID);
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
    public function updateMes($ID, $UserID, $FromID, $Msg, $State)
    {
        parent::updateMes($ID, $UserID, $FromID, $Msg, date('Y-m-d [H:i:s]'), $State);
    }   
}
?>