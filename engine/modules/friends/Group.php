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
            $this->_sql->query("INSERT INTO `USERS_FRIENDS_IN_GROUPS` VALUES(0,$friendID,$this->groupId)");    
        }
        
        public function getFriends()
        {
            $arr=$this->_sql->GetRows($this->_sql->query("SELECT `user_id` FROM `USERS_FRIENDS_IN_GROUPS` WHERE `group_id`=$this->id"));
            var_dump($arr);
        }
        
        public function delFriend($friendID)
        {
            $this->_sql->query("DELETE FROM `USERS_FRIENDS_IN_GROUPS` WHERE `user_id`=$friendID AND `group_id`=$this->id");    
        }
        
        public function clear()
        {
            
        }
    }
?>
