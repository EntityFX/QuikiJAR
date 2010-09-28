<?php

    require_once "UserException.php"; 
    
    require_once "engine/libs/registry/Registry.php";
    
    require_once "engine/libs/mysql/MySQLConnector.php";
        
    class User extends MySQLConnector
    {
        const PHOTO_PATH="/photos/";
        
        public $name;
        public $secondName;
        public $mail;
        public $gender;
        public $burthday;
        public $country;
        public $region;
        public $city;
        public $ip;
        public $id;
        public $isOnline;
        private $photo;
        
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
