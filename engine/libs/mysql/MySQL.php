<?php
/**
* Файл с классами для работы с MySQL.
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
    
    /**
    * Класс создаваемых полей
    * @package MySQL
    * @author Solopiy Artem    
    */
    class MySQLField extends MySQLTypeEnumerator
    {               
        /**
        * Сообщение о неправильном типе поля
        */
        const EXCEPTION_WRONG_TYPE="Wrong type of field";
        
        /**
        * Имя поля
        * 
        * @var String
        */
        public $name;
        
        /**
        * Тип создаваемого поля
        *  
        * @var MySQLMySQLTypeEnumerator
        */
        public $type;
        
        /**
        * Максимальная длина для текстового поля
        * 
        * @var String
        */
        public $max_length="255";
        
        /**
        * Является NOT NULL
        * 
        * @var Bool
        */
        public $not_null;
        
        /**
        * Является Ключевым
        * 
        * @var Bool
        */
        public $primary_key;
        
        /**
        * Является auto_increment
        * 
        * @var Bool
        */        
        public $auto_inc;
        
        /**
        * Конструктор
        * @param String $name Имя поля
        * @param MySQLMySQLTypeEnumerator $type Тип поля
        * @param Bool $not_null Является NOT NULL
        * @param Bool $primary_key Является Ключевым
        * @param Bool $auto_inc Является auto_increment 
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
        * Формирует строку создания поля в таблице
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
    
    /**
    * Класс MySQL запросов
    * @package MySQL 
    */
    class MySQLquery 
    {
        const EXCEPTION_NO_PRIMARY_KEY="No field with primary key";
        
        /**
        * Возращает массив строк таблицы
        * 
        * @param Resource $query_res Результат запроса
        * @return Array
        */
         private $_internalResource;
         
        /**
        * Массив строк
        * 
        * @var Array
        */
        protected $rows;
        
        /**
        * Адрес сервера
        * 
        * @var String
        */        
        protected $_server;
        
        /**
        * Имя пользователя
        * 
        * @var String
        */
        protected $_user;
        
        /**
        * Пароль
        * 
        * @var String
        */
        protected $_password;
        
        
        /**
        * Конструктор
        * 
        * @param String  $server
        * @param String  $user
        * @param String  $password
        * @return MySQLquery
        */
        public function __construct($server,$user,$password)
        {
            $this->rows=array();
            try
            {
                mysql_connect($server,$user,$password);
            }
            catch (Exception $e)
            {
                throw new Exception(NO_CONNECTION);                
            }
            $this->_server=$server;
            $this->_user=$user;
            $this->_password=$password;
            return true; 
        }
        
        public function GetRows($query_res)
        {
            $rows=null;
            while ($row=mysql_fetch_assoc($query_res))
            {
                $rows[]=$row;    
            }
            return $rows;
        }
        
        /**
        * Возвращает массив заголовков полей таблицы
        * 
        * @param Resource $resourse Результат запроса
        * @return Array
        */
        public function GetFields($resourse)
        {
            $res=null;
            while ($row=mysql_fetch_field($resourse))
            {
                $res[]=$row;    
            }
            return $res;
        }
        /**
        * Возвращает шаблон CREATE TABLE
        * 
        * @param String $table_name Имя таблицы
        * @param Array $fields Массив полей MySQLField
        * @return String
        */
        public function CreateTableTemplate($table_name,&$fields)
        {
            $str="CREATE TABLE `$table_name` (\r\n";
            $is_primary_key=false;
            $primary_key;
            foreach($fields as $val)
            {
                $str.="\t".$val->GetFieldString().",\r\n";
                if (!$is_primary_key && $val->primary_key)
                {
                    $primary_key=$val->name;
                    $is_primary_key=true;    
                }
            }
            if ($is_primary_key)
            {
                $str.= "\tPRIMARY KEY (`$primary_key`)\r\n);";    
            } else throw new Exception(MySQLquery::EXCEPTION_NO_PRIMARY_KEY);
            return $str;    
        }
        
        /**
        * Шаблон INSERT INTO
        * 
        * @param String $table_name Имя таблицы
        * @param Array $values Массив значений полей добавляемых записей
        * @param Array $fields Массив имён полей для добавляения записей. По-умолчанию для всех полей 
        * @return String
        */
        public function InsertIntoTemplate($table_name,&$values,&$fields=null)
        {
            $values=$this->MakeFieldString($values,"");
            $res="INSERT INTO `$table_name`";
            $flds="";
            if ($fields!=null)
            {
                $flds=$this->MakeFieldString($fields,"`");
                $res.=" ($flds)";
            }
            return $res." VALUES ($values)";
        }
        
        /**
        * put your comment there...
        * 
        * @param Array $fields_arr Массив полей MySQLField 
        * @param mixed $char
        * @return String
        */
        protected function MakeFieldString(&$fields_arr,$char="`")
        {
            $fields="";
            $fld="";
            for($i=0;$i<count($fields_arr);++$i)
            {
                $fld=$fields_arr[$i];
                if ($i==0)
                {
                    $fields.="$char$fld$char";
                } else $fields.=",$char$fld$char";   
            }
            return $fields;            
        } 
          
        /**
        * Выполняет MySQL-запрос
        *      
        * @param String $string SQL-запрос
        * @return Resource
        */
        public function query($string)
        {
            $resource=mysql_query($string);
            if ($resource==false)
            {
                throw new Exception("ENGINE: BAD QUERY");
            }
            $this->_internalResource=$resource;
            return $resource;
        }
        
        /**
        * Возращает массив текущей строки из ресурса БД
        * 
        * @param mixed $resource Ресурс БД
        * @return array
        */
        public function fetchArr($resource=NULL)
        {
            $res=NULL;
            if ($resource==NULL)
            {
                $res=$this->_internalResource;
            }
            else
            {
                $res=$resource;
            }
            return mysql_fetch_assoc($res);
        }
    }
    /**
    * Класс MySQL. Оболочка MySQL-запросов
    * @package MySQL 
    * @author Solopiy Artem
    */
    class MySQL extends MySQLquery
    {
        /**
        * Флаг. Выбрана ли БД
        * 
        * @var Bool
        */
        private $base_selected;
        private $data_bases;
        /**
        * Имя БД
        * 
        * @var String
        */
        private $db_name;
        const NO_DB_SELECTION="Database didn't selected";
        const NO_CONNECTION="No MySQL connection";
        const NO_QUERY="BAD MySQL query";
        /**
        * Конструктор. Подключается к БД
        *   
        * @param String $server Адрес сервера
        * @param String $user Имя пользователя
        * @param String $password пароль
        * @return MySQL
        */
        public function __construct($server,$user,$password)
        {
            parent::__construct($server,$user,$password);
        }
        /**
        * Выбор БД
        * 
        * @param Bool $db_name Имя БД
        */
        public function selectDB($db_name)
        {
            try
            {
                mysql_select_db($db_name);
                $this->db_name=$db_name;
            }
            catch (Exception $e)
            {
                return false;               
            }
            $this->base_selected=true;
            return true;
        }
        /**
        * Выбрать всё из таблицы
        * 
        * @param String $table_name Имя БД
        */
        public function selAll($table_name)
        {
            $query_res=$this->selector("SELECT * FROM `$table_name`");
            $this->rows=$this->getRows($query_res);
        }    
        /**
        * Выбрать всё из таблицы с WHERE
        * 
        * @param String $table_name Имя БД
        * @param String $where WHERE ""
        */
        public function selAllWhere($table_name,$where)
        {
            $query_res=$this->selector("SELECT * FROM `$table_name` WHERE $where");
            $this->rows=$this->getRows($query_res);    
        }
        
        public function selFieldsA($table_name,$fields_arr)
        {
            $fields=$this->MakeFieldString($fields_arr);
            $query_res=$this->Selector("SELECT $fields FROM `$table_name`");
            $this->rows=$this->GetRows($query_res);    
        }
        
        public function selFields($table_name)
        {
            $args = func_get_args();
            $fields_arr=array_slice($args,1);
            $fields=$this->MakeFieldString($fields_arr);
            $query_res=$this->selector("SELECT $fields FROM `$table_name`");
            $this->rows=$this->getRows($query_res);    
        }
        
        public function selFieldsWhereA($table_name,$fields_arr,$where)
        {
            $fields=$this->MakeFieldString($fields_arr);
            $query_res=$this->selector("SELECT $fields FROM `$table_name` WHERE $where");
            $this->rows=$this->getRows($query_res);    
        } 
               
        public function selFieldsWhere($table_name,$where)
        {
            $args = func_get_args();
            $fields_arr=array_slice($args,2);
            $fields=$this->MakeFieldString($fields_arr);            
            $query_res=$this->Selector("SELECT $fields FROM `$table_name` WHERE $where");
            $this->rows=$this->GetRows($query_res);    
        }
        
        public function createTable($table_name,&$fields)
        {
            $template=$this->CreateTableTemplate($table_name,$fields);
            $this->selector($template);
        }
        
        public function insert($table_name,&$values,&$fields=null)
        {
            $query=$this->InsertIntoTemplate($table_name,$values,$fields);
            return $this->selector($query);   
        }
        
        public function dropTable($table_name)
        {
            $this->selector("DROP TABLE `$table_name`");
        }
        
        public function getTable()
        {
            return $this->rows;    
        }
        
        public function getTableList()
        {
            if (!$this->base_selected) 
            {
               throw new Exception(MySQL::NO_DB_SELECTION);
            }
            else 
            {
                return $this->getRows(mysql_listtables($this->db_name));
            }
        }
        
        public function getFieldList($table_name)
        {
            if (!$this->base_selected) 
            {
               throw new Exception(MySQL::NO_DB_SELECTION);
            }
            else 
            {
                return $this->getFields(mysql_list_fields($this->db_name,$table_name));
            }                
        }
                
        private function selector($query)
        {
            $query_res=null;
            if (!$this->base_selected) 
            {
               throw new Exception(MySQL::NO_DB_SELECTION);
            }
            else 
            {
                $query_res=$this->Query($query);
                if (!$query_res)
                throw new Exception(MySQL::NO_QUERY." $query_res");
            }
            return $query_res;
        }
        
    } 
    
    /**TEST*
    $field[]=new MySQLField("id",MySQLField::INT,true,true,true);
    $f=new MySQLField("flag",MySQLField::VARCHAR,true,false);
    $f->max_length=50;
    $field[]=&$f;
    $field[]=new MySQLField("time",MySQLField::DATETIME); 
    $field[]=new MySQLField("image",MySQLField::BLOB);
    $field[]=new MySQLField("flo",MySQLField::FLOAT);
    $sql=new MySQL("localhost","root","");
    $sql->SelectDB("idelmedia");
    //$val=array("0","'hello'","NOW()","''","3.14");
    $val=array("0","'hello'","89");
    $fld=array("id","flag","flo");
    //echo $sql->InsertIntoTemplate("s",$val,$fld);
    //$sql->CreateTable("s",$field);
    $sql->Insert("s",$val,$fld);
    //$sql->DropTable("s");
    //$sql->SelAllWhere("users","`login`='1'");
    //var_dump($sql->getTable());
    $tables=$sql->getTableList();
    for($tbls_c=0;$tbls_c<count($tables);++$tbls_c)
    {
        echo "<table border=\"1\"><thead>"; 
        echo "<caption>".$tables[$tbls_c][0]."</caption>";
        echo "<tr>";
        $field_arr=$sql->getFieldList($tables[$tbls_c][0]);
        foreach($field_arr as $field_obj)
        {
            if ($field_obj->primary_key)
            echo  "<th><i>".$field_obj->name."</i></th>";
            else echo  "<th>".$field_obj->name."</th>"; 
        }
        echo "</tr>";
        echo "</thead><tbody>";
        $sql->SelAll($tables[$tbls_c][0]);
        $list=&$sql->getList();
        for($i=0;$i<$list->Count();++$i)
        {
            $l=$list->getElement($i);
            echo "<tr>";
            for($j=0;$j<count($l);++$j)
            {
                echo "<td>".htmlspecialchars($l[$j])."</td>";    
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    }*/
?>
