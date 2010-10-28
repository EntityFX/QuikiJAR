<?php
/**
* ����� ��� �����, ����������, ���������, �������� � �������������� rss-����.
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

abstract class RssProt extends MySQLConnector  
{
    /**
    * �������� ���������� RSS-�����
    * @param int $ID - ������������� ���������� RSS
    */
    protected function delRSS($ID)
    {
        $this->_sql->query("DELETE * FROM rss WHERE ID = '$ID'");
    }    
    /**
    * ��������� ���������� RSS-�����
    * @param int $ID - ������������� ���������� RSS
    */
    protected function getRSS($ID)
    {
        $query = $this->_sql->query("SELECT * FROM rss WHERE ID = '$ID'");
        return $this->_sql->GetRows($query);
    } 
    
    /**
    * ���������� RSS-�����
    * @param string $File - �������� ����� ���������� ����� (�������) 
    * @param string $Url - �������� ������ rss-����� (��������)
    */ 
    protected function addRSS($File, $Url)
    {
         $this->_sql->query("INSERT INTO rss VALUES ('0', '$File', '$Url');");  
    }  
    
    /**
    * ���������� RSS-�����
    * @param string $File - �������� ����� ���������� ����� (�������) 
    * @param string $Url - �������� ������ rss-����� (��������)
    */ 
    protected function updRSS($ID, $File, $Url)
    {
         $this->_sql->query("UPDATE rss SET file = '$File', url = '$Url' WHERE ID ='$ID'");
    } 
    
    /**
    * ��������� ���� RSS-����
    */
    protected function getallRSS()
    {
        $query = $this->_sql->query("SELECT * FROM rss");
        return $this->_sql->GetRows($query);
    }  
}
?>