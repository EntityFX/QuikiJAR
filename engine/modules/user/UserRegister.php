<?php

    require_once "MailCheck.php";
    
    require_once "UserException.php";
        
    class UserRegister
    {
        private $_sql;

        public function __construct()
        {
            $this->_sql=new MySQL(DB_SERVER,DB_USER,DB_PASSWORD);
            $this->_sql->selectDB(DB_NAME);
        }
        
        public function register($mail,$password,$name,$surname,$burthday,$gender,$ip)
        {
            if (checkMail($mail))
            {
                if ($this->checkIfExsist($mail))
                {
                    throw new UserException($mail,UserException::USR_ALREADY_EXIST);
                }
                die($burthday);
                if (checkDateFormat($burthday))
                {
                    throw new UserException($mail,UserException::USR_CHECK_BURTHDAY);   
                }
                else if ($name=="" && $surname=="")
                {
                    throw new UserException($mail,UserException::USR_NAME_EMPTY);
                }
                $password=md5($password);
                $date=date("Y-m-d");
                $this->_sql->query("INSERT INTO `SITE_USERS` VALUES(0,'$mail','$password',$ip,'$date','$name','$surname',$gender,'$burthday','',NULL,NULL,NULL,'',0,0)");    
            }   
            else
            {
                throw new UserException($mail,UserException::USR_NAME_INCORRECT);
            } 
        }
        
        public function checkPassword($p1,$p2)
        {
            $pl=strlen($p1);
            if ($pl<7 || $pl>26)
            {
                throw new UserException($mail,UserException::USR_PASSWORD_LENGTH);   
            }
            if ($p1!=$p2)    
            {
                throw new UserException($mail,UserException::USR_PASSWORD_NEQ);    
            }
        }
        
        public function unregister($id)
        {
            
        }
        
        public function activate($id)
        {
            
        }
        
        public function ban($id)
        {
            
        }
        
        private function checkIfExsist($mail)
        {
            $res=$this->_sql->query("SELECT COUNT(`mail`) AS `c` FROM `SITE_USERS` WHERE `mail`='$mail'");
            $data=$this->_sql->GetRows($res);
            if ($data[0]["c"]==0)
            {
                return false;    
            }
            else
            {
                return true;
            }
        }
    }
?>
