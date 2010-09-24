<?php

    require_once "engine/libs/mysql/MySQLConnector.php"; 
    
    require_once "engine/modules/user/User.php";
    
    require_once "engine/modules/user/UserRegister.php";    
    
    require_once "engine/modules/friends/FriendsException.php";    
    
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
            if (!$this->checkHasFriend($friendId))
            {
                $userChecker=new UserRegister();
                if (!$userChecker->checkIfExsistID($friendId))
                {
                    throw new FriendsException(UserException::USR_NOT_EXSIST,$friendId);
                }
                else
                {
                    $userId=$this->_curentId;
                    $this->_sql->query("INSERT INTO `USERS_FRIENDSHIP` VALUES (0,$userId,$friendId)");
                }
            }
            else
            {
                throw new FriendsException(FriendsException::FRND_ALRD_EX,$friendId);
            }
        }
        
        public function checkHasFriend($friendId,$userId=NULL)
        {
            if ($userId==NULL)    
            {
                $userId=$this->_curentId;    
            }
            $res=$this->_sql->query("SELECT COUNT(*) AS `c` FROM `USERS_FRIENDSHIP` WHERE `user_id`=$userId AND `friend_id`=$friendId");
            $counter=$this->_sql->GetRows($res);
            return (Bool)$counter[0]["c"];
        }
        
        public function deleteFriend($friendId)
        {
            if ($this->checkHasFriend($friendId))
            {
                $userId=$this->_curentId;
                $this->_sql->query("DELETE FROM `USERS_FRIENDSHIP` WHERE `user_id`=$userId AND `friend_id`=$friendId");
            }
            else
            {
                throw new FriendsException(FriendsException::FRND_NOT_EX,$friendId);
            }                
        }
        
        public function getAllFriends()
        {
            $userId=$this->_curentId;
            $res=$this->_sql->query("SELECT `friend_id` FROM `USERS_FRIENDSHIP` WHERE `user_id`=$userId"); 
            $table=$this->_sql->GetRows($res);
            $result=NULL;
            if ($table!=NULL)
            {
                foreach($table as $value)
                {
                   $result[]=new User((int)$value["friend_id"]);
                }
            }
            return $result;
        }
        
        public function getFriendsList($size,$page)
        {
            
        }
        
        
    }  
?>
