<?php
    
    require_once "UserException.php";
    
    require_once"checker.php";
    
    require_once "engine/libs/mysql/MySQLConnector.php";  
    
    /**
    * Отвечает за индентификацию и аунтентификацию пользователя
    */
    class UserSignInOut extends MySQLConnector
    {
        /**
        * ID пользователя
        * 
        * @var integer
        */
        private $userID;
       
        /**
        * Конструктор
        * 
        */
        public function __construct()
        {
            secureStartSession();
            parent::__construct();
        }
        
        /**
        * Выполнить аутентификацию
        * 
        * @param string $mail почта
        * @param string $password пароль
        * @throws UserException Пользователь не существует
        * @throws UserException Неверный пароль
        * @throws UserException Пользователь не активирован
        * @throws UserException Неверный формат почты
        */
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
        * Выход из системы
        * 
        */
        public function signOut()
        {
            $user=new User();
            $user->setLastUpdate(0);
            unset($_SESSION["user"]); 
        }
        
        /**
        * Проверка, активен ли пользователь
        * 
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
        * Проверка, является ли активированным
        * 
        * @param resource $qRes Результат запроса
        * @return bool
        */
        private function checkIfActivated(&$qRes)
        {
            return (boolean)$qRes["state"];
        }
        
    }
?>