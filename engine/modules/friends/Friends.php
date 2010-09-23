<?php

    require_once "engine/libs/mysql/MySQLConnector.php"; 
    
    require_once "engine/modules/user/User.php";  
    
    class Friends extends MySQLConnector  
    {
        private $_curentId;

        public function __construct()
        {
            parent::__construct();
            $user=new User();
            $this->_curentId=$user->id;
        }
        
        public function addFriend($friendId)
        {
            $userId=$this->_curentId;
            $this->_sql->query("INSERT INTO `USERS_FRIENDSHIP` VALUES (0,$userId,$friendId)");
        }
        
        public function checkHasFriend($friendId,$userId=NULL)
        {
            if ($userId!=NULL)    
            {
                $userId=$this->_curentId;    
            }
            else
            {
                
            }
        }
        
        public function deleteFriend($friendId)
        {
                
        }
        
        public function getAllFriends()
        {
            
        }
        
        public function getFriendsList($from,$to)
        {
            
        }
        
        
    }  
?>
