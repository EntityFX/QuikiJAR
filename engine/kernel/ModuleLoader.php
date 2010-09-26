<?
/**
* Файл с классом ModuleLoader. Основной класс, который необходим для работы сайта.
* @package kernel
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/
    
    /**
    * Выполняет загрузку модулей и передаёт управление им
    * @package kernel
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
        * ID модуля
        * 
        * @var Integer
        */
        private $_moduleID;
        
        /**
        * Конструктор
        * 
        * @param Integer $type Тип модуля
        * @param Array $data Массив с передаваемыми данными в модуль
        * @return ModuleLoader
        */
        public function __construct($type,&$data=NULL)
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
        public function loadModule($type,&$data)    
        {
            try
            {
                $sql=new MySQL(DB_SERVER,DB_USER,DB_PASSWORD);
                $sql->selectDB(DB_NAME);
                $result=$sql->query("SELECT `path`,`moduleId` FROM `Modules` WHERE `moduleid`=$type"); 
            }
            catch (Exception $dbError)
            {
                throw new Exception("MODULE LOADER ERROR: CHECK DB CONNECTION");
            }
            $array=$sql->fetchArr();
            $this->_moduleID=$array["moduleId"];
            $fullPath=ModuleLoader::MODULE_PATH.$array["path"]."/init.php";
            if (file_exists($fullPath))
            {
                require_once($fullPath);
            }
            else
            {
                throw new Exception("ENGINE: INIT FILE FOR $array[path] MODULE IS NOT EXSIST");
            }
            $this->_output=$output;
            return $output;
        }
        
        /**
        * Возвращает ID моуля
        * 
        * @return Integer
        */
        public function getModuleID()
        {
            return (int)$this->_moduleID;    
        }
        
        /**
        * Возвращает массив данных после работы модуля
        *
        * @return Array[Mixed] 
        */
        public function getOutput() 
        {
            return $this->_output;
        }
    }
?>