<?php
/**
* ���� ��� ��������� ���� ������������.
* @package user
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/

    require_once "UserException.php"; 
    
    require_once "UserSignInOut.php";
    
    require_once "UserAdditionalInfo.php";        
    
    require_once "engine/libs/registry/Registry.php";
    
    require_once "engine/libs/mysql/MySQLConnector.php";
    
    require_once "checker.php";
    
    
    /**
    * ������������ �����    
    */
    class User extends MySQLConnector
    {
        const PHOTO_PATH="/photos/";
        
        /**
        * ��� ������������
        * 
        * @var string
        */
        public $name;
        /**
        * �������
        * 
        * @var string
        */
        public $secondName;
        /**
        * �����
        * 
        * @var string
        */
        public $mail;
        /**
        * ��� (false - ���, true - ���)
        * 
        * @var bool
        */
        public $gender;
        /**
        * ���� ��������
        * 
        * @var string
        */
        public $burthday;
        /**
        * ������
        * 
        * @var integer
        */
        public $country;
        /**
        * ������/�������
        * 
        * @var integer
        */
        public $region;
        /**
        * �����
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
        * ID ������������
        * 
        * @var integer
        */
        public $id;
        /**
        * ������
        * 
        * @var bool
        */
        public $isOnline;
        
        /**
        * ���� ����
        * 
        * @var string
        */
        private $photo;
        
        /**
        * ����������, ��� ���������� �������� ����� �������� ����� ����������� �� �����
        * ���������� � ��������
        * 
        * @var integer
        */
        public static $updateInterval=30;
        
        /**
        * ���������� ���������� �������, ��� ������� ������������ ���������� ��� OffLine
        * ���������� � �������� 
        * 
        * @var integer
        */
        private static $offLineTime=900;
        
        /**
        * �����������
        * 
        * @param integer $id �������������� ��������. ���� NULL, �� ������� ������
        * ������������ ������� ����� � ������� (������), ����� �� ID ������� ������������
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
                    $userSignOut=new UserSignInOut();
                    if ($userSignOut->checkIfSave())
                    { 
                        $uId=(int)$_COOKIE["id"];
                        $this->_sql->query("SELECT `mail`,`password` FROM `SITE_USERS` WHERE `id`=$uId");
                        $secArr=$this->_sql->GetRows();
                        $mailSec=$secArr[0]["mail"];
                        $pass=$secArr[0]["password"];
                        if (md5($uId).md5($mailSec)!=$_COOKIE["sec"])        
                        {
                            throw new UserException("",UserException::USR_NOT_AUTENT);      
                        }
                        else
                        {
                            $userSignOut->authentication($mailSec,$pass,false,true);
                            $this->setDate($_SESSION["user"]);
                            $this->isOnline=true;
                        }
                    }
                    else
                    {
                        throw new UserException("",UserException::USR_NOT_AUTENT);
                    }
                }
                else
                {
                    $this->setDate($_SESSION["user"]);
                    $this->isOnline=true;
                    $this->checkLastTime(self::$updateInterval);
                }   
            }
            else
            {
                $this->setDate($this->getDataFromDb($id));
            }
        }
        
        /**
        * ������������� ��������� ����� �� �������
        * 
        * @param array $resArray
        */
        private function setDate($resArray)
        {
            $this->name=$resArray["name"];
            $this->secondName=$resArray["second_name"];
            $this->burthday=$resArray["burthday"];
            $this->mail=$resArray["mail"];
            $this->photo=$resArray["photo"];
            $this->ip=$resArray["ip"];
            $this->id=$resArray["id"];
            $this->isOnline=$this->isOnline();            
        }
        
        /**
        * ����������� ����� �� �� �� ID
        * 
        * @param int $id
        * @return Array[Array[String]]
        */
        private function getDataFromDb($id)
        {
            $res=$this->_sql->query("SELECT * FROM `SITE_USERS` WHERE `id`=$id");
            $resArray=$this->_sql->GetRows($res);
            $resArray=$resArray[0];
            return $resArray;
        }
        
        /**
        * �������� ���� � ����
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
        
        public function getInfo()
        {
            $info=new AdditionalInfo($this->id);
            return $info->getAll();
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
