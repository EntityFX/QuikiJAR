<?php
/**
* Файл точка входа. Подключаются основные константы и классы.
* @package *
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur) © 2010 
*/
    /**
    * @global Const Путь к файлам
    * @name SOURCE_PATH
    */
    define("SOURCE_PATH","");
    
    /**
    * Подключает константы БД
    * @filesource config/databaseConsts.php 
    */    
    require_once "config/databaseConsts.php";
    
    /**
    * Подключает базовый класс
    * @filesource engine/kernel/Kernel.php 
    */    
    require_once SOURCE_PATH."engine/kernel/Kernel.php";
    
    /**
    * Важный экземпляр класса
    * 
    * @var Kernel
    */
    $kernel=new Kernel();
    $kernel->run();
	$kernel->view("mail.tpl");
?>
