<?
/**
* Текстовый модуль
* @package modules.text 
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur) © 2010 
*/      
    /**
    * Класс текстового модуля
    * @package modules.text
    */
    class TextModule
    {
        public function getText($sectionId)
        {
            $sql=new MySQL(DB_SERVER,DB_USER,DB_PASSWORD);
            $sql->selectDB(DB_NAME);
            $result=$sql->query("SELECT * FROM `TEXT_MODULE` WHERE `id` = $sectionId");
            if ($array=$sql->fetchArr())
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