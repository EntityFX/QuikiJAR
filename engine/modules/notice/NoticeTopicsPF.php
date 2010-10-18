<?php
/**
* ����� NoticeTopicsPF, ������� ����� �������� ������, ������� ���������� � �������� ������� ������ NoticeTopics
* @author Shagiahmetov Aidar F.
* @version 1
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) � 2010  
*/

/**
* ����������� ������
* @package NoticeTopics.php   
*/
require_once "NoticeTopics.php";

class NoticeTopicsPF extends NoticeTopics
{    
    /**
    * ���������� ���������� 
    * @param string $Theme - ���� ���������
    * @param string $Text - ���������� ���������
    * @param int $CategoryID - ����� �������, � ������� ��������� ������ ���� 
    * @param string $DateTime - ���� � ����� �������� ��� ���������
    * @param int $UserID - ����� ����������
    */
	public function saveTop($Theme, $Text, $CategoryID, $DateTime, $UserID)
	{    
        parent::saveTop($Theme, $Text, $CategoryID, $DateTime, $UserID);
    }                                                                           
    
    /**
    * �������� ����������
    * @param int $ID - ����� ����������
    * @param int $UserID - ����� ����������
    */
    public function delTop($ID, $UserID)
	{
        parent::delTop($ID, $UserID);  
    } 
    
    /**
    * ��������� ���� ���������� ������������                                  
    * @param int $UserID - ����� ����������   
    */
	public function allTopUI($UserID)
	{
        return parent::allTopUI($UserID);
	} 
    
    /**
    * ��������� ��� ���������� 
    */
	public function allTop()
	{
        return parent::allTop();
	} 
    
    /**
    * �������������� ���������� 
    * @param string $Theme - ���� ���������
    * @param string $Text - ���������� ���������
    * @param int $CategoryID - ����� �������, � ������� ��������� ������ ���� 
    * @param string $DateTime - ���� � ����� �������� ��� ���������
    * @param int $UserID - ����� ����������
    * @param int ID - ����� ����������
    */
    public function updateTop($ID, $CategoryID, $Theme, $DateTime, $Text)
    { 
        parent::updateTop($ID, $CategoryID, $Theme, $DateTime, $Text);
    }   
}
?>