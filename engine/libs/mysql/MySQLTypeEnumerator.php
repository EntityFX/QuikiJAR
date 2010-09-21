<?php
/**
* Файл c классом MySQLTypeEnumerator.
* @package MySQL
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/

    
    /**
    * Перечисление констант для типов полей
    * @package MySQL
    * @author Solopiy Artem
    * @abstract MySQLTypeEnumerator 
    */
    abstract class MySQLTypeEnumerator
    {
        const INT="int";
        const SMALLINT="smallint";        
        const FLOAT="float";
        const DATETIME="datetime";
        const DATE="date";
        const TIME="time";
        const TEXT="text";
        const MEDIUMTEXT="mediumtext";        
        const VARCHAR="varchar";
        const BLOB="blob";
        const MEDIUMBLOB="mediumblob";         
    }
?>