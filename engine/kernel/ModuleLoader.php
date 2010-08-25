<?
/**
* ���� � ������� ModuleLoader. �������� �����, ������� ��������� ��� ������ �����.
* @package engine.kernel
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/
    
    /**
    * ��������� �������� ������� � ������� ���������� ��
    * @package engine.kernel
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
        * �����������
        * 
        * @param Integer $type ��� ������
        * @param Array $data ������ � ������������� ������� � ������
        * @return ModuleLoader
        */
        public function __construct($type,$data=NULL)
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