<?php
/**
* ����� RssPub, ������� ����� �������� ������, ������� ���������� � �������� ������� ������ RssProt     
* @author Shagiahmetov Aidar F.
* @version 1.01
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) � 2010  
*/  
//-------------------------------------------------------------------------------------------------------------

/**
* ����������� ������
* @package RssProt.php    
*/
require_once "RssProt.php";

class RssPub extends RssProt
{
    /**
    * �������� ���������� RSS-�����
    * @param int $ID - ������������� ���������� RSS
    */
    public function delRSS($ID)
    {
        parent::delRSS($ID);
    }
        
    /**
    * ��������� ���������� RSS-�����
    * 
    */
    public function getRSS($ID)
    {
        parent::getRSS($ID); 
    } 
    
    /**
    * ���������� RSS-�����
    * 
    */ 
    public function addRSS($File, $Url)
    {
        parent::addRSS($File, $Url);
    }  
    
    /**
    * ���������� RSS-�����
    * 
    */ 
    public function updRSS($ID, $File, $Url)
    {
        parent::addRSS($File, $Url);
    } 
    
    /**
    * ��������� ���� RSS-����
    * 
    */
    public function getallRSS()
    {
        return parent::getallRSS();
    }  
}
?>