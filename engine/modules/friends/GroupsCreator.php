<?php
    
    require_once "engine/modules/friends/FriendsException.php"; 
       
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
            $countGroups=$this->_sql->countQuery("USERS_GROUPS");
            if ($countGroups==0)
            {
                throw new FriendsException(FriendsException::GRP_ALRD_EX);
            }
            $this->_sql->query("INSERT INTO `USERS_GROUPS` VALUES(0,'$groupName',$this->_curentId)");
        }
        
        public function delete($groupId)
        {
            try
            {
                $this->_sql->query("DELETE FROM `USERS_GROUPS` WHERE `group_id`=$groupId AND `owner`=$this->_curentId");    
            }
            catch (Exception $exception)
            {
                throw new FriendsException(FriendsException::GRP_CANT_DEL,$groupId);
            }
            
        }
        
        public function rename()
        {
            
        }
        
        private function checkIfExsistById($groupId)
        {
            $countGroups=$this->_sql->countQuery("USERS_GROUPS","`group_id`=$groupId");
            return (Boolean)$countGroups;    
        }
        
        private function checkIfExsistByName($name)
        {
            $countGroups=$this->_sql->countQuery("USERS_GROUPS","`title`=$name");
            return (Boolean)$countGroups;    
        }        
    }
?>
