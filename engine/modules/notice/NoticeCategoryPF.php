<?php
/**
* ����� NoticeCategoryPF, ������� ����� �������� ������, ������� ���������� � �������� ������� ������ NoticeCategory 
* @author Shagiahmetov Aidar F.
* @version 1
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) � 2010  
*/

/**
* ����������� ������
* @package NoticeCategory.php   
*/
require_once "NoticeCategory.php";
   
class NoticeCategoryPF extends NoticeCategory
{
    /**
    * �������� �������      
    * @param int $ID - ����� ������� 
    */
	public function delCat($ID)
	{
        parent::delCat($ID);
	} 
    
    /**
    * ���������� �������  
    * @param string $Themes - �������� �������
    */
	public function saveCat($Themes)
	{   
        parent::saveCat($Themes);  
	}
    
    /**
    * ��������� ���� ������
    */
	public function showAllCat()
	{   
        return parent::showAllCat();      
	} 
    
    /**
    * ��������� ������, �� ������� ������� ���� �� 1 ������ � ������� ������������ 
    * @param int $UserID - ����� �������  
    */
	public function showCat($UserID, $CatID)
    {    
        return parent::showCat($UserID, $CatID); 
	}
    
    /**
    * ��������� ������� 
    * @param int $ID - ����� �������
    * @param int $Themes - �������� �������
    */
	public function updateCat($ID, $Themes)
	{
        parent::updateCat($ID, $Themes);
	}   
}
?>