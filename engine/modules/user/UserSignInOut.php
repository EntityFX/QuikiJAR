<?php
    
    require_once("UserException.php");
    
    require_once("MailCheck.php");
    
    require_once "engine/libs/mysql/MySQLConnector.php";  
    
    class UserSignInOut extends MySQLConnector
    {
   
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
                if ($userResult["password"]!=md5($password))
                {
                    throw new UserException($mail,UserException::USR_PASSWORD_INCORRECT);
                }
                else
                {
                    $_SESSION["user"]=$userResult;
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
        
    }
?>