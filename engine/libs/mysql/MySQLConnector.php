<?php
/**
* ���� � ������� MySQLConnector.
* @package MySQL  
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur) � 2010 
*/
    
    /**
    * ������ ����� � ��������� � ��
    * @abstract
    */
    abstract class MySQLConnector
    {

        /**
        * ������ MySQL
        * 
        * @var MySQL
        */
        protected $_sql;
        
        /**
        * ����������� �������������� ������ $_sql
        * 
        * 
        */
        public function __construct()
        {
            $this->_sql=new MySQL(DB_SERVER,DB_USER,DB_PASSWORD);
            $this->_sql->selectDB(DB_NAME);
        }
    }
?>