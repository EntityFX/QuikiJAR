<?php

    require_once "UserException.php"; 
    
    require_once "engine/libs/registry/Registry.php";
    
    require_once "engine/libs/mysql/MySQLConnector.php";
    
    require_once "checker.php";
    
    /**
    * Пользователи сайта    
    */
    class User extends MySQLConnector
    {
        const PHOTO_PATH="/photos/";
        
        /**
        * Имя пользователя
        * 
        * @var string
        */
        public $name;
        /**
        * Фамилия
        * 
        * @var string
        */
        public $secondName;
        /**
        * Почта
        * 
        * @var string
        */
        public $mail;
        /**
        * Пол (false - жен, true - муж)
        * 
        * @var bool
        */
        public $gender;
        /**
        * День рождения
        * 
        * @var string
        */
        public $burthday;
        /**
        * страна
        * 
        * @var integer
        */
        public $country;
        /**
        * Регион/область
        * 
        * @var integer
        */
        public $region;
        /**
        * Город
        * 
        * @var string
        */
        public $city;
        /**
        * IP
        * 
        * @var string
        */
        public $ip;
        /**
        * ID пользователя
        * 
        * @var integer
        */
        public $id;
        /**
        * Онлайн
        * 
        * @var bool
        */
        public $isOnline;
        
        /**
        * Файл фото
        * 
        * @var string
        */
        private $photo;
        
        /**
        * Промежуток, при превышении которого стоит обновить время присутствия на сайте
        * Измеряется в секундах
        * 
        * @var integer
        */
        public static $updateInterval=30;
        
        /**
        * Предельный промежуток времени, при котором пользователь помечается как OffLine
        * Измеряется в секундах 
        * 
        * @var integer
        */
        private static $offLineTime=900;
        
        /**
        * Конструктор
        * 
        * @param integer $id Необязательный параметр. Если NULL, то берутся данные
        * пользователя который вошёл в систему (сессии), иначе по ID другого пользователя
        * @return User
        */
        public function __construct($id=NULL)
        {
            parent::__construct();
            secureStartSession();
            if ($id==NULL || $id==$_SESSION["user"]["id"])
            {
                if (!isset($_SESSION["user"]))
                {
                    throw new UserException("",UserException::USR_NOT_AUTENT);
                }
                $this->name=$_SESSION["user"]["name"];
                $this->secondName=$_SESSION["user"]["second_name"];
                $this->burthday=$_SESSION["user"]["burthday"];
                $this->mail=$_SESSION["user"]["mail"];
                $this->photo=$_SESSION["user"]["photo"];
                $this->ip=$_SESSION["user"]["ip"];
                $this->id=$_SESSION["user"]["id"];
                $this->isOnline=true;
                $this->checkLastTime(self::$updateInterval);   
            }
            else
            {
                $res=$this->_sql->query("SELECT * FROM `SITE_USERS` WHERE `id`=$id");
                $resArray=$this->_sql->GetRows($res);
                $resArray=$resArray[0];
                $this->name=$resArray["name"];
                $this->secondName=$resArray["second_name"];
                $this->burthday=$resArray["burthday"];
                $this->mail=$resArray["mail"];
                $this->photo=$resArray["photo"];
                $this->ip=$resArray["ip"];
                $this->id=$resArray["id"];
                $this->isOnline=$this->isOnline();
            }
        }
        
        /**
        * Получить путь к фото
        * 
        * @return string
        */
        public function getPhoto()
        {
            if ($this->photo=="")
            {
                return Registry::getValue("USERS_NO_PHOTO");
            }
            return self::PHOTO_PATH.$this->mail."/".$this->photo;    
        }
        
        public function isOnline($interval=0)
        {
            if ($interval==0)
            {
                $interval=self::$offLineTime;
            }
            $lastUpdateTime=$this->getLastUpdate();
            if (time()-$lastUpdateTime>=$interval)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
       
        private function checkLastTime($value)
        {
            if (time()-$_SESSION["user"]["lastUpdateTime"]>=$value)
            {
                $_SESSION["user"]["lastUpdateTime"]=$this->setLastUpdate(time()); 
            }
        }
       
        private function getLastUpdate()
        {
            $this->_sql->query("SELECT `update_time` FROM `SITE_USERS` WHERE `id`=$this->id");
            $array=$this->_sql->GetRows();
            return $array[0]["update_time"];
        }
        
        public function setLastUpdate($time)
        {
            $this->_sql->query("UPDATE `SITE_USERS` SET `update_time`=$time WHERE `id`=$this->id");
            return $time;
        }
        
        public static function setOffLineTime($value)
        {
            self::$offLineTime=$value<self::$updateInterval ? self::$updateInterval : $value;
        }
        
        public static function getOffLineTime()
        {
            return self::$offLineTime;
        }
    }
?>
