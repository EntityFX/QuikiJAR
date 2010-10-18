<?php
/**
* ‘айл с классом NoticeTopics.  ласс дл€ сохранени€, редактировани€, удалени€ и загрузки тем объ€влений.
* @author Shagiahmetov Aidar F.
* @version 1
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) © 2010  
*/

/**
* подключение модул€
* @package MySQLConnector.php   
*/
require_once "engine/libs/mysql/MySQLConnector.php";

class NoticeTopics extends MySQLConnector
{      
    /**
    * —охранение объ€влени€ 
    * @param string $Theme - тема сообщени€
    * @param string $Text - содержание сообщени€
    * @param int $CategoryID - номер рубрики, в которой находитс€ данна€ тема 
    * @param string $DateTime - ƒата и врем€ создани€ или изменени€
    * @param int $UserID - автор объ€влени€
    */
	protected function saveTop($Theme, $Text, $CategoryID, $DateTime, $UserID)
	{    
		$this->_sql->query("INSERT INTO NoticeTopics VALUES (0, '$Theme', '$Text', '$CategoryID', '$DateTime', '$UserID');");  
    }                                                                           
    
    /**
    * ”даление объ€влени€
    * @param int $ID - номер объ€влени€
    * @param int $UserID - автор объ€влени€ 
    */
    protected function delTop($ID, $UserID)
	{
        $this->_sql->query("DELETE FROM NoticeTopics WHERE ID='$ID' AND UserID='$UserID'");
    } 
    
    /**
    * ѕолучение всех объ€влений пользовател€                                  
    * @param int $UserID - автор объ€влени€   
    */
	protected function allTopUI($UserID)
	{
	    $query=$this->_sql->query("SELECT * FROM NoticeTopics WHERE UserID='$UserID'");
		return $this->_sql->GetRows($query); 
	} 
    
    /**
    * јбсолютно все объ€влени€ 
    */
	protected function allTop()
	{
		$query=$this->_sql->query('SELECT * FROM NoticeTopics');
		return $this->_sql->GetRows($query);  
	} 
    
    /**
    * редактирование объ€влени€ 
    * @param string $Theme - тема сообщени€
    * @param string $Text - содержание сообщени€
    * @param int $CategoryID - номер рубрики, в которой находитс€ данна€ тема 
    * @param string $DateTime - ƒата и врем€ создани€ или изменени€
    * @param int $UserID - автор объ€влени€
    * @param int ID - номер объ€влени€
    */
    protected function updateTop($ID, $CategoryID, $Theme, $DateTime, $Text)
    { 
        $this->_sql->query("UPDATE NoticeTopics SET Theme = '$Theme', Text = '$Text',
                            CategoryID = '$CategoryID', DateTime = '$DateTime' WHERE ID ='$ID'");
    }   
}
?>