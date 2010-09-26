<?
/**
* ���� � ������� ModuleLoader. �������� �����, ������� ��������� ��� ������ �����.
* @package kernel
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/
    
    /**
    * ��������� �������� ������� � ������� ���������� ��
    * @package kernel
    * @author Solopiy Artem 
    */
    class ModuleLoader
    {
        /**
        * ��������� �������� ��-��������� ���� � �������
        */
        const MODULE_PATH="engine/modules/";
        
        /**
        * �������� ������
        * 
        * @var mixed
        */
        private $_output;
        
        /**
        * ������� ������
        * 
        * @var mixed
        */
        private $_data;
        
        /**
        * ID ������
        * 
        * @var Integer
        */
        private $_moduleID;
        
        /**
        * �����������
        * 
        * @param Integer $type ��� ������
        * @param Array $data ������ � ������������� ������� � ������
        * @return ModuleLoader
        */
        public function __construct($type,&$data=NULL)
        {
            $this->loadModule($type,$data);
        }
        
        /**
        * ��������� ������ � ������� ��� ����������
        * 
        * @param Integer $type ID ������
        * @param Array $data ������������ ������
        * @throws Exception ���� ������ �� ����������
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
        * ���������� ID �����
        * 
        * @return Integer
        */
        public function getModuleID()
        {
            return (int)$this->_moduleID;    
        }
        
        /**
        * ���������� ������ ������ ����� ������ ������
        *
        * @return Array[Mixed] 
        */
        public function getOutput() 
        {
            return $this->_output;
        }
    }
?>