<?php
    
    require_once "FriendsException.php";
    
    require_once "Group.php";
       
    class GroupsCreator extends MySQLConnector
    {
        
        private $_curentId;

        public function __construct()
        {
            parent::__construct();
            $user=new User();
            $this->_curentId=$user->id;
        }        
        
        public function create($groupName)
        {
            if ($this->checkIfExsistByName($groupName))
            {
                throw new FriendsException(FriendsException::GRP_ALRD_EX);
            }
            $this->_sql->query("INSERT INTO `USERS_GROUPS` VALUES(0,'$groupName',$this->_curentId)");
        }
        
        public function delete($groupId)
        {
            if ($this->checkIfExsistById($groupId))
            {
                $this->_sql->query("DELETE FROM `USERS_GROUPS` WHERE `group_id`=$groupId AND `owner`=$this->_curentId");    
            }
            else
            {
                throw new FriendsException(FriendsException::GRP_CANT_DEL,$groupId);                    
            }
        }
        
        public function rename($groupId,$newName)
        {
            if ($this->checkIfExsistById($groupId))
            {
                $this->_sql->query("UPDATE `USERS_GROUPS` SET `title`='$newName' WHERE `group_id`=$groupId");    
            }
            else
            {
                throw new FriendsException(FriendsException::GRP_CANT_EDT,$groupId);                    
            }   
        }
        
        public function getAllGroups()
        {
            $res=$this->_sql->query("SELECT * FROM `USERS_GROUPS` WHERE `owner`=$this->_curentId");
            $arr=$this->_sql->GetRows($res);
            $result=NULL; 
            foreach($arr as $value) 
            {
                $result[]=new Group($value["group_id"],$value["title"]);    
            }
            return  $result;  
        }
        
        public function getGroup($groupId)
        {
            $res=$this->_sql->query("SELECT * FROM `USERS_GROUPS` WHERE `owner`=$this->_curentId AND `group_id`=$groupId");
            $arr=$this->_sql->GetRows($res);  
            return new Group($groupId,$arr[0]["title"]);              
        }
        
        private function checkIfExsistById($groupId)
        {
            $countGroups=$this->_sql->countQuery("USERS_GROUPS","`group_id`=$groupId AND `owner`=$this->_curentId");
            return (Boolean)$countGroups;    
        }
        
        private function checkIfExsistByName($name)
        {
            $countGroups=$this->_sql->countQuery("USERS_GROUPS","`title`='$name' AND `owner`=$this->_curentId");
            return (Boolean)$countGroups;    
        }        
    }
?>
