<?php
/**
* ‘айл c классом MySQLField.
* @package MySQL
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/
    
    /**
    *  ласс создаваемых полей
    * @package MySQL
    * @author Solopiy Artem    
    */
    class MySQLField extends MySQLTypeEnumerator
    {               
        /**
        * —ообщение о неправильном типе пол€
        */
        const EXCEPTION_WRONG_TYPE="Wrong type of field";
        
        /**
        * »м€ пол€
        * 
        * @var String
        */
        public $name;
        
        /**
        * “ип создаваемого пол€
        *  
        * @var MySQLTypeEnumerator
        */
        public $type;
        
        /**
        * ћаксимальна€ длина дл€ текстового пол€
        * 
        * @var String
        */
        public $max_length="255";
        
        /**
        * явл€етс€ NOT NULL
        * 
        * @var Bool
        */
        public $not_null;
        
        /**
        * явл€етс€  лючевым
        * 
        * @var Bool
        */
        public $primary_key;
        
        /**
        * явл€етс€ auto_increment
        * 
        * @var Bool
        */        
        public $auto_inc;
        
        /**
        *  онструктор
        * @param String $name »м€ пол€
        * @param MySQLTypeEnumerator $type “ип пол€
        * @param Bool $not_null явл€етс€ NOT NULL
        * @param Bool $primary_key явл€етс€  лючевым
        * @param Bool $auto_inc явл€етс€ auto_increment 
        * @return MySQLField
        */
        public function __construct($name,$type,$not_null=false,$primary_key=false,$auto_inc=false)
        {
            $this->name=$name;
            switch ($type)
            {
                case MySQLField::INT:
                    $this->type=$type;
                    break;
                case MySQLField::SMALLINT:
                    $this->type=$type;
                    break;
                case MySQLField::FLOAT:
                    $this->type=$type;
                    $this->max_length="8,4";
                    break;
                case MySQLField::DATETIME:
                    $this->type=$type;
                    $this->max_length="";
                    break;
                case MySQLField::DATE:
                    $this->type=$type;
                    $this->max_length="";
                    break;
                case MySQLField::TIME:
                    $this->type=$type;
                    $this->max_length="";
                    break;
                case MySQLField::MEDIUMTEXT:
                    $this->type=$type;
                    break;
                case MySQLField::TEXT:
                    $this->type=$type;
                    break;
                case MySQLField::VARCHAR:
                    $this->type=$type;
                    break;
                case MySQLField::BLOB:
                    $this->type=$type;
                    $this->max_length="";
                    break;
                case MySQLField::MEDIUMBLOB:
                    $this->type=$type;
                    $this->max_length="";
                    break;                    
                default :
                    throw new Exception(MySQLField::EXCEPTION_WRONG_TYPE);
                    break;
            }
            $this->not_null=$not_null;
            $this->primary_key=$primary_key;
            $this->auto_inc=$auto_inc;    
        }
        
        /**
        * ‘ормирует строку создани€ пол€ в таблице
        * @return String
        */
        public function GetFieldString()
        {
            $str="`".$this->name."` ".$this->type." ";
            if ($this->max_length!="")   
            {
                $str.="(".$this->max_length.") ";    
            }
            if ($this->not_null) $str.="NOT NULL ";    
            else $str.="NULL ";
            if ($this->auto_inc) $str.="auto_increment ";
            return $str;           
        }   
    }
?>