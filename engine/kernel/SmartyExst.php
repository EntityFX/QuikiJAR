<?
/**
* Файл с классом SmartyExst. Расширяет Smarty дополняя настройками.
* @package kernel
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/
    
    /**
    * Подключает константы для Smarty
    * @filesource config/smartyConsts.php 
    */  
    require_once "./config/smartyConsts.php";
    
    /**
    * Подключает Smarty
    * @filesource engine/libs/smarty/Smarty.class.php 
    */    
    require_once SOURCE_PATH."engine/libs/smarty/Smarty.class.php";
    
    
    /**
    * Расширяет Smarty дополняя настройками
    * @package kernel
    * @author Solopiy Artem
    * @final 
    */     
    final class SmartyExst extends Smarty
    {
        public function __construct()
        {
            $this->template_dir=SmartyConsts::TEMPLATES_DIR;
            $this->cache_dir=SmartyConsts::CACHE_DIR;
            $this->compile_dir=SmartyConsts::COMPILE_DIR;   
        }
    }
?>