<?php
    require_once "AccessLevelRights.php";
    
    class AccessLevelController extends MySQLConnector
    {
        const LEVEL_ALL   = 0x0;
        const LEVEL_REG   = 0x1;
        const LEVEL_CONT  = 0x2;
        const LEVEL_FRND  = 0x3;
        const LEVEL_NOB   = 0x4;
        
        const ACCESS_SHOW = 0x0;
        const ACCESS_ADDF = 0x1;
        const ACCESS_MSGW = 0x2;
        
        private $_rights;
        private $_userId;
        private $_perm;
        
        public function __construct(User &$user)
        {
            parent::__construct();
            $this->_userId=$user->id;
            $this->_perm=new AccessLevelRights($this->getPermissions()); 
        }
        
        public function getLevel()
        {
            return $this->_perm->getAccessLevel();
        }
        
        public function getRights()
        {
            return $this->_perm->getAcessRights();
        }
        
        public function setLevel($level)
        {
            $this->_perm->setAccessLevel($level);
            $this->setPermissions();
        }
        
        public function setRights($rights)
        {
            $this->_perm->setAcessRights($rights);
            $this->setPermissions();
        }
        
        private function getPermissions()
        {
            $res=$this->_sql->query("SELECT `permissions` FROM `SITE_USERS` WHERE `id`=$this->_userId");
            $arr=$this->_sql->GetRows();
            return $arr[0]["permissions"];
        }
        
        private function setPermissions()                                  
        {
            $permissions=$this->_perm->getRights();
            $this->_sql->query("UPDATE `SITE_USERS` SET `permissions`=$permissions WHERE `id`=$this->_userId");
        }
    }
?>
