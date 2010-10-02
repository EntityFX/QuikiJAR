<?
/**
* Текстовый модуль
* @package modules.text 
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur) © 2010 
*/      
    
    require_once "engine/libs/mysql/MySQLConnector.php";    
    
    /**
    * Класс текстового модуля
    * @package modules.text
    */
    class TextModule extends MySQLConnector
    {
        public function getText($sectionId)
        {
            $result=$this->_sql->query("SELECT * FROM `TEXT_MODULE` WHERE `id` = $sectionId");
            if ($array=$this->_sql->fetchArr())
            {
                return $array;
            }
            else
            {
                throw new Exception("Записи отсутствуют");
            }                          
        }
    }

?>