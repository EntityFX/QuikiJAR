<?php
    
    require_once("UserException.php");
    
    require_once("checker.php");
    
    require_once "engine/libs/mysql/MySQLConnector.php";  
    
    class UserSignInOut extends MySQLConnector
    {
   
        private $userID;
        
        public function __construct()
        {
            session_start();
            parent::__construct();
        }
        
        public function authentication($mail,$password)
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
                if ($userResult["password"]!=md5($password))
                {
                    throw new UserException($mail,UserException::USR_PASSWORD_INCORRECT);
                }
                else
                {
                    if ($this->checkIfActivated($userResult))
                    {
                        $this->changeOnline(true);
                        $_SESSION["user"]=$userResult;
                        $_SESSION["user"]["online"]=true;
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
        
        public function signOut()
        {
            $this->changeOnline(false);
            unset($_SESSION["user"]); 
        }
        
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
        
        private function checkIfActivated(&$qRes)
        {
            return (boolean)$qRes["state"];
        }
        
        private function changeOnline($value)
        {
            $value=(int)$value;
            if ($this->userID==NULL)
            {
                $this->userID=$_SESSION["user"]["id"];
            }
            $this->_sql->query("UPDATE `SITE_USERS` SET `online`=$value WHERE `id`=$this->userID"); 
        }
        
    }
?>