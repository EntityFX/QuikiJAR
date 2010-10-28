<?php
/**
* ������������� � �������������� ������������.
* @package user
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/
    
    require_once "UserException.php";
    
    require_once"checker.php";
    
    require_once "engine/libs/mysql/MySQLConnector.php";  
    
    /**
    * �������� �� �������������� � ��������������� ������������
    */
    class UserSignInOut extends MySQLConnector
    {
        /**
        * ID ������������
        * 
        * @var integer
        */
        private $userID;
       
        /**
        * �����������
        * 
        */
        public function __construct()
        {
            secureStartSession();
            parent::__construct();
        }
        
        /**
        * ��������� ��������������
        * 
        * @param string $mail �����
        * @param string $password ������
        * @param bool $autologin ������� �� �����
        * @throws UserException ������������ �� ����������
        * @throws UserException �������� ������
        * @throws UserException ������������ �� �����������
        * @throws UserException �������� ������ �����
        */
        public function authentication($mail,$password,$autologin=false,$isMd5=false)
        {
            $bad=true;
            if (checkMail($mail))
            {
                $res=$this->_sql->query("SELECT `id` , `mail` , `password` , INET_NTOA( `ip` ) AS `ip` , `register_date` , `name` , `second_name` , `gender` , `burthday` , `photo` , `country` , `region` , `city` , `street` , `utc_time` , `state` FROM `SITE_USERS` WHERE `mail`='$mail'");
                $userResult=$this->_sql->GetRows($res);
                if ($userResult==NULL)    
                {
                    throw new UserException($mail,UserException::USR_NOT_EXSIST);
                }
                $userResult=$userResult[0];
                $this->userID=$userResult["id"];
                if (!$isMd5)
                {
                    $password=md5($password);    
                }
                if ($userResult["password"]!=$password)
                {
                    throw new UserException($mail,UserException::USR_PASSWORD_INCORRECT);
                }
                else
                {
                    if ($this->checkIfActivated($userResult))
                    {
                        if ($autologin)
                        {
                            setcookie("sec",md5($this->userID).md5($userResult["mail"]),time()+221356800,"/");
                            setcookie("id",$this->userID,time()+221356800,"/");
                        }
                        $_SESSION["user"]=$userResult; 
                    }
                    else
                    {
                        throw new UserException($mail,UserException::USR_NOT_ACTIVATED);
                    }
                }
            }
            else
            {
                throw new UserException($mail,UserException::USR_NAME_INCORRECT);
            }
            return true;                
        }
        
        /**
        * ����� �� �������
        * 
        */
        public function signOut()
        {
            $user=new User();
            $user->setLastUpdate(0);
            setcookie("id", "",time()-3600,"/");
            setcookie("sec", "",time()-3600,"/");
            unset($_SESSION["user"]);
        }
        
        /**
        * ��������, ������� �� ������������
        * 
        * @return boolean
        */
        public function isEntered()
        {
            if (isset($_SESSION["user"]))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        
        /**
        * ��������, �������� �� ��������������
        * 
        * @param resource $qRes ��������� �������
        * @return bool
        */
        private function checkIfActivated(&$qRes)
        {
            return (boolean)$qRes["state"];
        }
        
        /**
        * ��������, ����� �� ����� "������� ������������"
        * @return boolean   
        */
        public function checkIfSave()
        {
            if (isset($_COOKIE["sec"]) && isset($_COOKIE["id"]))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        
    }
?>