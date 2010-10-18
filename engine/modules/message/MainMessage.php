<?php
/**
* ����� ��� ��������, �����, ����������, ��������� � �������������� ���������.
* @author Shagiahmetov Aidar
* @version 1.04
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) � 2010  
*/
//-------------------------------------------------------------------------------------------------------------
/**
* ����������� ������
* @package MySQLConnector.php   
*/
require_once "engine/libs/mysql/MySQLConnector.php";   

abstract class MainMessage extends MySQLConnector
{                  
    /**
    * ���������� ���������  
    * @param string $Msg - ���������
    * @param int $FromID - �� ���� ������
    * @param string $DateTime - ���� � �����
    * @param int $UserID - ����� ���������
    * @param int $State - �������� (1) ��� ��� (0)
    */
    protected function saveMes($Msg, $FromID, $DateTime, $UserID, $State)
    {          
        $this->_sql->query("INSERT INTO Message VALUES ('0', '$Msg', '$DateTime', '$UserID', '$FromID', '$State');");  
    }

    /**
    * ��������� ��������� 
    * @param int $UserID - ����� ���������  
    */
    protected function getSaves($UserID)
    {
        $query=$this->_sql->query("SELECT * FROM Message WHERE UserID = '$UserID' AND FromID IS NULL");
        return $this->_sql->GetRows($query);
    }
    
    /**
    * ��������� ������������    
    * @param int $FromID - �� ���� ������
    */
    protected function getSends($FromID)
    {
        $query=$this->_sql->query("SELECT * FROM Message WHERE FromID = '$FromID'");
        return $this->_sql->GetRows($query);
    } 

    /**
    * ��������� ��������
    * @param int $UserID - ����� ��������� 
    */
    protected function getReads($UserID)
    {   
        $query=$this->_sql->query("SELECT * FROM Message WHERE UserID = '$UserID' AND FromID IS NOT NULL AND State = 1");
        return $this->_sql->GetRows($query);  
    }  
    
    /**
    * ��������� ����� 
    * @param int $UserID - ����� ��������� 
    */
    protected function getNew($UserID)
    {  
        $query=$this->_sql->query("SELECT * FROM Message WHERE UserID = '$UserID' AND FromID IS NOT NULL AND State = 0");
        return $this->_sql->GetRows($query);  
    }                                                                              
    
    /**
    * �������� ��������� 
    * @param int $ID - ����� ���������
    * @param int $UserID - ����� ��������� 
    */
    protected function delMes($ID, $UserID)
    { 
        $this->_sql->query("DELETE FROM Message WHERE ID = '$ID' AND UserID = '$UserID'");  
    } 
    
    /**
    * ��� ������ ������������                               
    * @param int $UserID - ����� ���������  
    */
    protected function allMes($UserID)
    { 
        $query = $this->_sql->query("SELECT * FROM Message WHERE UserID = '$UserID'");
        return $this->_sql->GetRows($query);
    } 
    
    /**
    * ��������������
    * @param int $ID - ����� ���������    
    * @param string $Msg - ���������
    * @param int $FromID - �� ���� ������
    * @param string $DateTime - ���� � �����
    * @param int $UserID - ����� ���������
    * @param int $State - �������� (1) ��� ��� (0)
    */
    protected function updateMes($ID, $UserID, $FromID, $Msg, $DateTime, $State)
    { 
        $this->_sql->query("UPDATE Message SET Message = '$Msg', DateTime = '$DateTime', FromID = '$FromID', State = '$State' 
                            WHERE ID ='$ID'");
    }   
}
?>