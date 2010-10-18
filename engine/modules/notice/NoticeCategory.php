<?php
/**
* ���� � ������� NoticeCategory. ����� ��� ����������, ��������������, �������� � �������� ������ ����������.
* @author Shagiahmetov Aidar F.
* @version 1
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) � 2010  
*/

/**
* ����������� ������
* @package MySQLConnector.php   
*/
require_once "engine/libs/mysql/MySQLConnector.php";
   
class NoticeCategory extends MySQLConnector
{
    /**
    * �������� �������      
    * @param int $ID - ����� �������
    */
	protected function delCat($ID)
	{
        $this->_sql->query("DELETE FROM NoticeCategory WHERE ID='$ID'");   
	} 
    
    /**
    * ���������� �������  
    * @param string $Themes - �������� �������
    */
	protected function saveCat($Themes)
	{   
	    $this->_sql->query("INSERT INTO NoticeCategory VALUES (0, '$Themes');");   
	}
    
    /**
    * ��������� ���� ������  
    */
	protected function showAllCat()
	{   
	    $query=$this->_sql->query("SELECT * FROM NoticeCategory");
	    return $this->_sql->GetRows($query);      
	} 
    
    /**
    * ��������� ������, �� ������� ������ ������������ 
    * @param int $UserID - ����� �������   
    */
	protected function showCat($UserID, $CatID)
    {   
	    $query=$this->_sql->query("SELECT * FROM NoticeTopics WHERE CategoryID='$CatID'
                                            AND UserID='$UserID'");
                                            
        return $this->_sql->GetRows($query);       
	}
    
    /**
    * �������������� �������, �� ������� ������ ������������ 
    * @param int $UserID - ����� �������   
    */  
	protected function updateCat($ID, $Themes)
	{
		$this->_sql->query("UPDATE NoticeCategory SET Themes = '$Themes' WHERE ID ='$ID'");
	}  
}
?>