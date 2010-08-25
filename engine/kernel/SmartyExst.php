<?
    require_once "./config/smartyConsts.php";
    require_once SOURCE_PATH."engine/libs/smarty/Smarty.class.php"; 
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