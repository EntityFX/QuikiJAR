<?php
/**
* ���� � ������� Kernel. �������� �����, ������� ��������� ��� ������ �����.
* @package kernel
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur) � 2010  
*/
    /**
    * ���������� ��������� �������
    * @filesource engine/kernel/ModuleLoader.php 
    */  
    require_once SOURCE_PATH."engine/kernel/ModuleLoader.php";
    
    /**
    * ���������� ����� ��� ������ � ��
    * @filesource engine/libs/mysql/MySQL.php
    */
    require_once SOURCE_PATH."engine/libs/mysql/MySQL.php";
    
    /**
    * ���������� Smarty � �����������
    * @filesource engine/kernel/SmartyExst.php 
    */
    require_once SOURCE_PATH."engine/kernel/SmartyExst.php";
    
    /**
    * ���������� ����� ��������� ������
    * @filesource engine/kernel/Menu.php 
    */
    require_once SOURCE_PATH."engine/kernel/Menu.php";
    
	/**
	* ������������ ��� ������� URL, ������������� ����������, �������� �������, ��������������� �� ��������
    * @package kernel
    * @author Solopiy Artem
	*/
    class Kernel
    {
        private $url;
        
        
        /**
        * �������� �������� �� ����� "/" URL 
        * 
		* @var Array 
		*/
		private $_urlArray;
        
		/**
        *  ��������� ��� ��������
        * 
		* @var MySQL 
		*/
        private $_sql;

        /**
        * ���������
        *        
        * @var Array[String] 
        */
        private $_parameters;
        
        
        /**
        * �������� ������ ��� ������
        * 
        * @var Array[Mixed]
        */
        private $_out;
        
        /**
        * ����������� �����. ������ URL ����� � ��������� ��������� URL.
        */        
        public function __construct()
        {
            $this->url=substr($_SERVER['REQUEST_URI'],1);
            $this->_urlArray[]="/";
            $this->_urlArray=array_merge($this->_urlArray,explode("/",$this->url));
            if (end($this->_urlArray)=="" || strstr(end($this->_urlArray),"?")!==false)
            {
                array_pop($this->_urlArray);
            }
            else
            {
                $newURL=$_SERVER['REQUEST_URI'];
                header("Location: $newURL/");
            }
        }
        
        /**
        * ��������� ���������� �������� �� �������, � ����� ����� � ������ ���������������� ������
        */
        public function run()
        {
			$this->_sql=MySQL::creator(DB_SERVER,DB_USER,DB_PASSWORD);
            $this->_sql->selectDB(DB_NAME);
            try
            {
                $this->urlScaner();
            }
            catch(Exception $ex)
            {
                if ($ex->getCode()==404)
                {
                    header("Location: /error/404/");
                }
            }
            header("Content-type: text/html; charset=\"win-1251\"");
            $moduleType=(int)$this->_arr["module"];
            $data=$this->makeGet();
            $module=new ModuleLoader($moduleType,$data);
            $this->_out=$module->getOutput(); 
            if ($this->_out["title"]=="")
            {
                $this->_out["title"]=SITE_NAME.": ".$this->_out["title"].$this->_arr["title_tag"];
            }
            else
            {
                $this->_out["title"]=SITE_NAME.": ".$this->_out["title"];
            }
        }
        
        /**
        * ������� ���� URL ������ ����� ������� ��������� ����
        * 
        */
        public function showURL()
        {
            var_dump($this->urlArray);
        }
        
        /**
        * ���������� ���� �� ������� �������
        * 
        * @param Sring $templatePath ��������� ������ � ����� engine/templates
        */
        public function view($templatePath)
        {
            $smarty=new SmartyExst();
            //$smarty->caching=true;
            //$smarty->debugging=true;
            //var_dump($out);
            include_once("engine/init.php");
            try
            {
                $smarty->display($templatePath);
            } 
            catch (Exception $ex)
            {
                echo("PROBLEM SMARTY >> KERNEL >> Can't load $templatePath");
            }           
        }
        
        private function &makeGet()
        {
            $data["id"]=$this->_arr["id"];
            $data["urlArray"]=&$this->_urlArray;
            $data["parameters"]=&$this->_parameters;
            $data["url"]=&$this->url;
            return $data;            
        }
        
        /**
        * ��������� URL ��� �������. 
        * @throws Exception ������ �� ������
        */
        private function urlScaner()
        {
            $pid=0;
            $flag=false;
            foreach($this->_urlArray as $key => $value)   
            {
                if (!$flag)
                {
                    $queryWhere="`link`='$value' AND `pid`=$pid";
                    $this->_sql->SelAllWhere("URL",$queryWhere);
                    $arr=$this->_sql->getTable();
                    $this->_arr=$arr[0];
                    $pid=$arr[0]["id"];
                    if ($arr==NULL)
                    {
                        throw new Exception("Error",404);
                    }
                    else if ($arr[0]["use_parameters"]==1)
                    {
                        $flag=true;       
                    }
                }
                else
                {
                    $this->_parameters[]=$value;
                }
            }
        }
    }
?>