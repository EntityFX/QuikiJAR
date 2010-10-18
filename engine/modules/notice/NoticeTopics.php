<?php
/**
* ���� � ������� NoticeTopics. ����� ��� ����������, ��������������, �������� � �������� ��� ����������.
* @author Shagiahmetov Aidar F.
* @version 1
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) � 2010  
*/

/**
* ����������� ������
* @package MySQLConnector.php   
*/
require_once "engine/libs/mysql/MySQLConnector.php";

class NoticeTopics extends MySQLConnector
{      
    /**
    * ���������� ���������� 
    * @param string $Theme - ���� ���������
    * @param string $Text - ���������� ���������
    * @param int $CategoryID - ����� �������, � ������� ��������� ������ ���� 
    * @param string $DateTime - ���� � ����� �������� ��� ���������
    * @param int $UserID - ����� ����������
    */
	protected function saveTop($Theme, $Text, $CategoryID, $DateTime, $UserID)
	{    
		$this->_sql->query("INSERT INTO NoticeTopics VALUES (0, '$Theme', '$Text', '$CategoryID', '$DateTime', '$UserID');");  
    }                                                                           
    
    /**
    * �������� ����������
    * @param int $ID - ����� ����������
    * @param int $UserID - ����� ���������� 
    */
    protected function delTop($ID, $UserID)
	{
        $this->_sql->query("DELETE FROM NoticeTopics WHERE ID='$ID' AND UserID='$UserID'");
    } 
    
    /**
    * ��������� ���� ���������� ������������                                  
    * @param int $UserID - ����� ����������   
    */
	protected function allTopUI($UserID)
	{
	    $query=$this->_sql->query("SELECT * FROM NoticeTopics WHERE UserID='$UserID'");
		return $this->_sql->GetRows($query); 
	} 
    
    /**
    * ��������� ��� ���������� 
    */
	protected function allTop()
	{
		$query=$this->_sql->query('SELECT * FROM NoticeTopics');
		return $this->_sql->GetRows($query);  
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
    protected function updateTop($ID, $CategoryID, $Theme, $DateTime, $Text)
    { 
        $this->_sql->query("UPDATE NoticeTopics SET Theme = '$Theme', Text = '$Text',
                            CategoryID = '$CategoryID', DateTime = '$DateTime' WHERE ID ='$ID'");
    }   
}
?>