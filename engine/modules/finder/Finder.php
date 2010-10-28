<?php
/**
* Поиск людей на сайте. По многим параметрам
*
* @package finder
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/
    
    require_once "engine/modules/friends/Friends.php";
    
    
    /**
    * Поиск пользователей сайта с постраничным выводом
    * 
    */
    class Finder extends MySQLConnector
    {  
        /**
        * Текущее время
        * 
        * @var integer
        */
        private $_time;
        
        /**
        * Страница, которую выводит
        * 
        * @var integer
        */
        public $page=1;
        
        /**
        * Размер выводимой страницы
        * 
        * @var integer
        */
        public $size=30;
        
        /**
        * Сколько страниц в списке отображается
        * 
        * @var mixed
        */
        public $view=7;
        
        public $count=0;
        
        
        /**
        * Конструктор. Получает текущее время
        * 
        * @param integer $time Текущее время в секундах
        * @return Finder
        */
        public function __construct($time)
        {
            parent::__construct();
            $this->_time=$time; 
        }
        
        /**
        * Выполнить поиск по данным
        * 
        * @param string $firstName Имя
        * @param string $secondName Фамилия
        * @param bool $gender Пол
        * @param integer $ageFrom Возраст от
        * @param integer $ageTo Возраст до
        * @param bool $isOnline Только online
        * @param integer $country Код страны
        * @param integer $region Код региона
        * @param integer $city Код города
        * @return Array[User]
        */
        public function findByData($firstName,$secondName,$gender="",$ageFrom=0,$ageTo=0,$isOnline=false,$country=0,$region=0,$city=0)
        {
            $query="";
            $qArr=NULL;
            if ($firstName!=="")
            {
                //$qArr[]="MATCH (name) AGAINST ('$firstName')";
				$qArr[]="`name` LIKE '$firstName%'";    
            }
            if ($secondName!=="")
            {
                $qArr[]="`second_name` LIKE '$secondName%'";    
            }
            
            if ($gender!=="-")
            {
                $qArr[]="`gender` = $gender";    
            }
            $ages=$this->makeAgeRange($ageFrom,$ageTo);
            if ($ages!=="")
            {
               $qArr[]=$ages; 
            }
            if ($isOnline==true)
            {
                $qArr[]=time()."-`update_time` < ".User::$updateInterval*60;
            }
            $i=0;
            if ($qArr!=NULL)
            {
                foreach($qArr as $val)                        
                {
                    if ($i>0)
                    {
                        $query.=" AND ";
                    }
                    $query.=$val;
                    ++$i;    
                }
            }
            if ($query!=="")
            {
                return $this->getUsers($query);
            }
            else
            {
                return NULL;
            }
        }
        
        /**
        * Получить список пользователей по запросу
        * 
        * @param string $query Запрос
        * @return Array[User]
        */
        private function getUsers($query)
        {
            if ($this->page<1)
            {
                $this->page=1;
            }   
            $from=$this->size*($this->page-1);
            try
            {
                $c=$this->_sql->query("SELECT COUNT(*) as `COUNT` FROM `SITE_USERS` WHERE $query");
            }
            catch (Exception $exception)
            {
                return NULL;
            }
            $this->count=$this->_sql->GetRows($c);
            $this->count=$this->count[0]["COUNT"];
            $r=$this->_sql->selFieldsWhere("SITE_USERS",$query." ORDER BY `name` ASC LIMIT $from,$this->size","id");
            while($findRows=$this->_sql->fetchArr($r))
            {
               $usr[]=new User($findRows["id"]);
            }
            return $usr;            
        }
        
        /**
        * Поиск по почте
        * 
        * @param string $mail Почта
        * @return Array[User]
        */
        public function findByMail($mail)
        {
            return $this->getUsers("`mail`='$mail'");
        }
        
        /**
        * По ID
        * 
        * @param integer $id
        * @return Array[User]
        */
        public function findById($id)
        {
            return $this->getUsers("`id`='$id'");
        }
        
        /**
        * Преобразует данные из объекта User в ассоциативный массив
        * 
        * @param User $usr
        * @return Array
        */
        public function getForView(&$usr)
        {
            $friender=new Friends();
            if ($usr!=NULL)
            {
                foreach($usr as $value)
                {
                    $out["id"]=$value->id;
                    $out["name"]=$value->name." ".$value->secondName;
                    $out["photo"]=$value->getPhoto();
                    $out["online"]=$value->isOnline();
                    $out["isFriend"]=$friender->checkHasFriend($value->id);
                    $outer[]=$out;
                }
            }
            return $outer;
        }
        
        /**
        * Получить каталог страниц вида: <- 1..10 11..20 21..30 ->
        * Размер задаётся предварительно в поле $size=10
        * Размер каталога в поле $view=3
        * 
        * @return Array
        */
        public function getPages()
        {
            $pages=(int)ceil($this->count/$this->size);
            $substraction=$this->page % $this->view;
            if ($substraction == 0)
            {
                $begin=$this->page-$this->view + 1;    
            }
            else
            {
                $begin=$this->page - ($substraction) + 1;
            }
            if ($begin>1)
            {
                $arr["from"]="*";
                $arr["page"]=$begin-1;
                $out[]=$arr;                
            }
            $from=$begin*$this->size-($this->size)+1;
            $end=$begin+$this->view-1;
            if ($end>=$pages)
            {
                $end=$pages;
            }
            for($i=$begin;$i<=$end;$i++)
            {
                if ($i!=$pages)
                {
                    $to=$i*$this->size;
                }
                else
                {
                    $to=$this->count;
                }
                $arr["from"]=$from;
                $arr["page"]=$i;
                $arr["to"]=$to;
                if ($i==$this->page)
                {
                    $arr["current"]=true;    
                }
                else
                {
                    $arr["current"]=false;    
                }
                $from+=$this->size;
                $out[]=$arr;
            }
            if ($end<$pages)
            {
                $arr["to"]="*";
                $arr["from"]="";
                $arr["page"]=$end+1;
                $arr["current"]=false; 
                $out[]=$arr;                    
            }
            return $out;
        }
        
        /**
        * Вычислить минимальную дату для возраста
        * 
        * @param integer $age Возраст в годах
        * @return string
        */
        private function timeFromAge($age)
        {
            return date("Y-m-d",strtotime("-$age year"));
        }
        
        /**
        * Вычислить максимальную дату для возраста
        * 
        * @param integer $age Возраст в годах
        * @return string
        */        
        private function timeToAge($age)
        {
            ++$age;
            return date("Y-m-d",strtotime("-$age year +1 day"));
        }
        
        /**
        * Сформировать диапазон в дате для запроса
        * 
        * @param integer $ageFrom Возвраст от
        * @param integer $ageTo Возраст до
        * @return string
        */
        private function makeAgeRange($ageFrom=0,$ageTo=0)        
        {
            $field="burthday";
            $t=$f="";
            if ($ageFrom==0 && $ageTo==0)
            {
                return ""; 
            }
            else
            {
                if ($ageFrom=="")
                {
                    return "`$field` >= '".$this->timeToAge($ageTo)."'";
                }
                else if ($ageTo=="")
                {
                    return "`$field` <= '".$this->timeFromAge($ageFrom)."'";
                }
                else
                {
                    return "`$field` BETWEEN '".$this->timeToAge($ageTo)."' AND '".$this->timeFromAge($ageFrom)."'";    
                }
            }
        }
        
    }
?>
