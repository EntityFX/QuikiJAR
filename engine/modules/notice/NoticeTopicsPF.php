<?php
/**
*  ласс NoticeTopicsPF, который имеет открытые методы, которые обращаютс€ к закрытым методам класса NoticeTopics
* @author Shagiahmetov Aidar F.
* @version 1
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) © 2010  
*/

/**
* подключение модул€
* @package NoticeTopics.php   
*/
require_once "NoticeTopics.php";

class NoticeTopicsPF extends NoticeTopics
{    
    /**
    * —охранение объ€влени€ 
    * @param string $Theme - тема сообщени€
    * @param string $Text - содержание сообщени€
    * @param int $CategoryID - номер рубрики, в которой находитс€ данна€ тема 
    * @param string $DateTime - ƒата и врем€ создани€ или изменени€
    * @param int $UserID - автор объ€влени€
    */
	public function saveTop($Theme, $Text, $CategoryID, $DateTime, $UserID)
	{    
        parent::saveTop($Theme, $Text, $CategoryID, $DateTime, $UserID);
    }                                                                           
    
    /**
    * ”даление объ€влени€
    * @param int $ID - номер объ€влени€
    * @param int $UserID - автор объ€влени€
    */
    public function delTop($ID, $UserID)
	{
        parent::delTop($ID, $UserID);  
    } 
    
    /**
    * ѕолучение всех объ€влений пользовател€                                  
    * @param int $UserID - автор объ€влени€   
    */
	public function allTopUI($UserID)
	{
        return parent::allTopUI($UserID);
	} 
    
    /**
    * јбсолютно все объ€влени€ 
    */
	public function allTop()
	{
        return parent::allTop();
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
    public function updateTop($ID, $CategoryID, $Theme, $DateTime, $Text)
    { 
        parent::updateTop($ID, $CategoryID, $Theme, $DateTime, $Text);
    }   
}
?>