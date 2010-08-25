<?php
/**
* Файл с классом Kernel. Основной класс, который необходим для работы сайта.
* @package engine.kernel
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur) © 2010  
*/
    /**
    * Подключает загрузчик модулей
    * @filesource engine/kernel/ModuleLoader.php 
    */  
    require_once SOURCE_PATH."engine/kernel/ModuleLoader.php";
    
    /**
    * Подключает класс для работы с БД
    * @filesource engine/libs/mysql/MySQL.php
    */
    require_once SOURCE_PATH."engine/libs/mysql/MySQL.php";
    
    /**
    * Подключает Smarty с настройками
    * @filesource engine/kernel/SmartyExst.php 
    */
    require_once SOURCE_PATH."engine/kernel/SmartyExst.php";
    
    /**
    * Подключает класс выводимых ссылок
    * @filesource engine/kernel/Menu.php 
    */
    require_once SOURCE_PATH."engine/kernel/Menu.php";
    
	/**
	* Используется для разбора URL, распознавания параметров, загрузки модулей, перенаправления на страницы
    * @package engine.kernel
    * @author Solopiy Artem
	*/
    class Kernel
    {
        /**
        * Содержит разбитый по знаку "/" URL 
        * 
		* @var Array 
		*/
		private $_urlArray;
        
		/**
        *  Экземпляр для запросов
        * 
		* @var MySQL 
		*/
        private $_sql;

        /*private $_field;
        private $_arr;*/
        
        /**
        * Конструктор класс. Парсит URL адрес и испраляет ошибочный URL.
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
        * Запускает выполнение перехода на разделы, а также поиск и запуск соответствующего модуля
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
        * Выводит дамп URL строки ввиде массива элементов пути
        * 
        */
        public function showURL()
        {
            var_dump($this->urlArray);
        }
        
        /**
        * Сканирует URL как разделы. 
        * @throws Exception Раздел не найден
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