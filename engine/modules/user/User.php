<?php

    require_once "UserException.php"; 
    
    require_once "engine/libs/registry/Registry.php";
    
    require_once "engine/libs/mysql/MySQLConnector.php";
    
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
        * Конструктор
        * 
        * @param integer $id Необязательный параметр. Если NULL, то берутся данные
        * пользователя который вошёл в систему (сессии), иначе по ID другого пользователя
        * @return User
        */
        public function __construct($id=NULL)
        {
            parent::__construct();
            if ($id==NULL || $id==$_SESSION["user"]["id"])
            {
                session_start();
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
                $this->isOnline=$_SESSION["user"]["online"]; 
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
                $this->isOnline=(boolean)$resArray["online"];                
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
    }
?>
