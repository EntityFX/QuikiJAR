<?php

    require_once "UserException.php"; 
    
    require_once "engine/libs/registry/Registry.php";
    
    class User
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
        private $photo;
        
        public function __construct($id=NULL)
        {
            if ($id==NULL)
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
