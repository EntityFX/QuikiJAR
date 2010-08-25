<?php
/**
* ���� � ������� Kernel. �������� �����, ������� ��������� ��� ������ �����.
* @package engine.kernel
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
    * @package engine.kernel
    * @author Solopiy Artem
	*/
    class Kernel
    {
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

        /*private $_field;
        private $_arr;*/
        
        /**
        * ����������� �����. ������ URL ����� � ��������� ��������� URL.
        */
        public function __construct()
        {
            $this->url=substr($_SERVER['REQUEST_URI'],1);
            $this->_urlArray[]="/";
            $this->_urlArray=array_merge($this->_urlArray,explode("/",$this->url));
            if (end($this->_urlArray)=="" || strpos(end($this->_urlArray),"?")===0)
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
			$this->_sql=new MySQL(DB_SERVER,DB_USER,DB_PASSWORD);
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
            $data["id"]=$this->_arr["id"];
            $module=new ModuleLoader($moduleType,$data);
            $smarty=new SmartyExst();
            //$smarty->caching=true;
            //$smarty->debugging=true;
            $out=$module->getOutput();
            //var_dump($out);
            $smarty->assign("TEXT_VAR",$out["text"]);
            $menu=new LinksList($this->_arr["id"]);
            $smarty->assign("MENU",$menu->makeMenu());
            $smarty->assign("CHILDREN_MENU",$menu->getMenuChildren());
            $smarty->assign("PATH",$menu->getPath()); 
			$subSections=$menu->getSubSection();
            $smarty->assign("SUB_SECTIONS",$subSections);
            $smarty->display("main.tpl");
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
        * ��������� URL ��� �������. 
        * @throws Exception ������ �� ������
        */
        private function urlScaner()
        {
            $pid=0;
            foreach($this->_urlArray as $key => $value)   
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
            }
        }
    }
?>