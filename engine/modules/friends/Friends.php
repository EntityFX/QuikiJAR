<?php

    require_once "engine/libs/mysql/MySQLConnector.php";  
    
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
