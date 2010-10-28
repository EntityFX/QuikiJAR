<?php
/**
* ����� Message, ������� ����� �������� ������, ������� ���������� � �������� ������� ������ MainMessage     
* @author Shagiahmetov Aidar F.
* @version 1.01
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) � 2010  
*/  
//-------------------------------------------------------------------------------------------------------------

/**
* ����������� ������
* @package MainMessage.php    
*/
require_once "MainMessage.php";

class Message extends MainMessage
{       
    /**
    * ���������� ���������  
    * @param string $Msg - ���������
    * @param int $FromID - �� ���� ������
    * @param string $DateTime - ���� � �����
    * @param int $UserID - ����� ���������
    * @param int $State - �������� (1) ��� ��� (0)
    */
    public function saveMes($Msg, $FromID, $UserID, $State)
    {
        parent::saveMes($Msg, $FromID, date('Y-m-d [H:i:s]'), $UserID, $State);     
    }

    /**
    * ��������� ��������� 
    * @param int $UserID - ����� ���������  
    */
    public function getSaves($UserID)
    {     
       return parent::getSaves($UserID);
    }
    
    /**
    * ��������� ������������    
    * @param int $FromID - �� ���� ������    
    */  
    public function getSends($FromID)
    {
        return parent::getSends($FromID);
    } 
    
    /**
    * ��������� ��������
    * @param int $UserID - ����� ���������
    */  
    public function getReads($UserID)
    {
        return parent::getReads($UserID);
    }  
    
    /**
    * ��������� ����� 
    * @param int $UserID - ����� ���������
    */  
    public function getNew($UserID)
    {
        return parent::getNew($UserID);
    }                                                                              
    
    /**
    * �������� ��������� 
    * @param int $ID - ����� ���������
    * @param int $UserID - ����� ���������    
    */  
    public function delMes($ID, $UserID)
    {
        parent::delMes($ID, $UserID);    
    } 
    
    /**
    * ��� ������ ������������                               
    * @param int $UserID - ����� ���������
    * @param object $mm - ��������� ������ MainMessage   
    */                                    
    public function allMes($UserID)
    {
        return parent::allMes($UserID);
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
    public function updateMes($ID, $UserID, $FromID, $Msg, $State)
    {
        parent::updateMes($ID, $UserID, $FromID, $Msg, date('Y-m-d [H:i:s]'), $State);
    }   
}
?>