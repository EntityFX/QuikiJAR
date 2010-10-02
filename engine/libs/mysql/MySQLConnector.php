<?php
/**
* Файл с классом MySQLConnector.
* @package MySQL  
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur) © 2010 
*/
    
    /**
    * Создаёт класс и соединяет с БД
    * @abstract
    */
    abstract class MySQLConnector
    {

        /**
        * Объект MySQL
        * 
        * @var MySQL
        */
        protected $_sql;
        
        /**
        * Конструктор инициализирует объект $_sql
        * 
        * 
        */
        public function __construct()
        {
            $this->_sql=MySQL::creator(DB_SERVER,DB_USER,DB_PASSWORD);
            $this->_sql->selectDB(DB_NAME);
        }
    }
?>
