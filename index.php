<?php
/**
* ���� ����� �����. ������������ �������� ��������� � ������.
* @package *
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur) � 2010 
*/
    /**
    * @global Const ���� � ������
    * @name SOURCE_PATH
    */
    define("SOURCE_PATH","");
    
    /**
    * ���������� ��������� ��
    * @filesource config/databaseConsts.php 
    */    
    require_once "config/databaseConsts.php";
    
    /**
    * ���������� ������� �����
    * @filesource engine/kernel/Kernel.php 
    */    
    require_once SOURCE_PATH."engine/kernel/Kernel.php";
    
    /**
    * ������ ��������� ������
    * 
    * @var Kernel
    */
    $kernel=new Kernel();
    $kernel->run();
	$kernel->view("mail.tpl");
?>
