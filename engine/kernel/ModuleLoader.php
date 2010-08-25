<?
/**
* Файл с классом ModuleLoader. Основной класс, который необходим для работы сайта.
* @package engine.kernel
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/
    
    /**
    * Выполняет загрузку модулей и передаёт управление им
    * @package engine.kernel
    * @author Solopiy Artem 
    */
    class ModuleLoader
    {
        /**
        * Константа содержит по-умолчанию путь к модулям
        */
        const MODULE_PATH="engine/modules/";
        
        /**
        * Выходные данные
        * 
        * @var mixed
        */
        private $_output;
        
        /**
        * Входные данные
        * 
        * @var mixed
        */
        private $_data;
        
        /**
        * Конструктор
        * 
        * @param Integer $type Тип модуля
        * @param Array $data Массив с передаваемыми данными в модуль
        * @return ModuleLoader
        */
        public function __construct($type,$data=NULL)
        {
            $this->loadModule($type,$data);
        }
        
        /**
        * Загружает модуль и передаёт ему управление
        * 
        * @param Integer $type ID модуля
        * @param Array $data передаваемые данные
        * @throws Exception Ффйл модуля не существует
        */
        public function loadModule($type,$data=NULL)    
        {
            $sql=new MySQL(DB_SERVER,DB_USER,DB_PASSWORD);
            $sql->selectDB(DB_NAME);
            $result=$sql->query("SELECT `path` FROM `Modules` WHERE `moduleid`=$type");
            $array=$sql->fetchArr();
            $fullPath=ModuleLoader::MODULE_PATH.$array["path"]."/init.php";
            if (file_exists($fullPath))
            {
                require_once($fullPath);
            }
            else
            {
                throw new Exception("ENGINE: FILE MODULE IS NOT EXSIST");
            }
            $this->_output=$output;
            return $output;
        }
        
        public function getOutput() 
        {
            return $this->_output;
        }
    }
?>