<?php
/**
*  ласс NoticeCategoryPF, который имеет открытые методы, которые обращаютс€ к закрытым методам класса NoticeCategory 
* @author Shagiahmetov Aidar F.
* @version 1
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiahmetov Aidar) © 2010  
*/

/**
* подключение модул€
* @package NoticeCategory.php   
*/
require_once "NoticeCategory.php";
   
class NoticeCategoryPF extends NoticeCategory
{
    /**
    * ”даление рубрики      
    * @param int $ID - номер рубрики 
    */
	public function delCat($ID)
	{
        parent::delCat($ID);
	} 
    
    /**
    * —охранение рубрики  
    * @param string $Themes - название рубрики
    */
	public function saveCat($Themes)
	{   
        parent::saveCat($Themes);  
	}
    
    /**
    * ѕолучение всех рубрик
    */
	public function showAllCat()
	{   
        return parent::showAllCat();      
	} 
    
    /**
    * ѕолучение рубрик, на которых имеетс€ хот€ бы 1 запись у данного пользовател€ 
    * @param int $UserID - номер рубрики  
    */
	public function showCat($UserID, $CatID)
    {    
        return parent::showCat($UserID, $CatID); 
	}
    
    /**
    * »зменение рубрики 
    * @param int $ID - номер рубрики
    * @param int $Themes - название рубрики
    */
	public function updateCat($ID, $Themes)
	{
        parent::updateCat($ID, $Themes);
	}   
}
?>