<?php
    class Group extends MySQLConnector
    {
        public $title;
        public $id;
        
        public function __construct($groupId,$title)
        {
            parent::__construct();
            $this->id=$groupId;
            $this->title=$title;
        }
        
        public function addFriend($friendID)
        {
            $friends=new Friends();
            if (!$friends->checkHasFriend($friendID))
            {
                throw new FriendsException(FriendsException::FRND_NOT_EX,$friendID);
            }

            if (!$this->checkFriendInGroup($friendID))
            {
                $this->_sql->query("INSERT INTO `USERS_FRIENDS_IN_GROUPS` VALUES(0,$friendID,$this->id)");    
            }
            else
            {
                throw new FriendsException(FriendsException::GRP_FRND_CNT_ADD,$this->id);
            }    
        }
        
        public function getFriends()
        {
            $arr=$this->_sql->GetRows($this->_sql->query("SELECT `user_id` FROM `USERS_FRIENDS_IN_GROUPS` WHERE `group_id`=$this->id"));
            $friends=NULL;
            if ($arr!=NULL)
            {
                foreach($arr as $value)
                {
                    $friends[]=new User($value["user_id"]);
                }
            }
            return $friends;
        }
        
        public function delFriend($friendID)
        {
            $this->_sql->query("DELETE FROM `USERS_FRIENDS_IN_GROUPS` WHERE `user_id`=$friendID AND `group_id`=$this->id");    
        }
        
        public function checkFriendInGroup($friendID)
        {
            $exsist=$this->_sql->countQuery("USERS_FRIENDS_IN_GROUPS","`user_id`=$friendID AND `group_id`=$this->id"); 
            return (bool)$exsist;
        }
        
        public function clear()
        {
            $this->_sql->delete("USERS_FRIENDS_IN_GROUPS","`group_id`=$this->id");  
        }
    }
?>
