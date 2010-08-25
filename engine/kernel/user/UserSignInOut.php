<?
    
    require_once("UserException.php");
    
    class UserSignInOut
    {
        private $_sql;
        
        public function __constructor()
        {
            $this->_sql=new MySQL(DB_SERVER,DB_USER,DB_PASSWORD);
            $this->_sql->selectDB(DB_NAME);  
        }
        
        public function signIn($mail,$password)
        {
            $bad=true;
            if ($this->checkMail($mail))
            {
                $this->_sql->query("SELECT `mail`,`password`,`ip` FROM  `")    
            }
            else
            {
                
            }                
        }
        
        private function checkMail($mail)
        {
            if (preg_match("/^[a-zA-Z0-9._-]+@([a-zA-Z0-9_-]+.)*[a-zA-Z]{2,}$/",$login)==1)
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