<?php
    class AccessLevelRights
    {
        const ALR_ALL  =  0x0;
        const ALR_REG  =  0x1;
        const ALR_CONT =  0x2;
        const ALR_FRND =  0x3;
        const ALR_NOB  =  0x4;
        
        private $_rights;
        
        public function __construct($rights)
        {
            $this->_rights=$rights;    
        }
        
        public function getAccessLevel()
        {
            return $this->_rights & 0xFF;      
        }
        
        public function setAccessLevel($level)
        {
            $this->_rights=($this->_rights & 0xFFFFFF00) | ($level & 0xFF);     
        } 
        
        public function getAcessRights()
        {
            $rights=($this->_rights >> 0x8) & 0xFF;
            $resArray=NULL;
            for($it=0;$it<=7;++$it)    
            {
                $resArray[$it]=(boolean)(($rights >> $it) & 0x1);       
            }
            return $resArray;
        }               
        
        public function setAcessRights($rights)
        {
            $resData=0;
            $len=count($rights);
            if ($len>8)
            {
                throw new Exception("Size of array more than 8 elements");
            }
            for($it=0;$it<=$len;++$it)    
            {
                $resData|=((int)$rights[$it] << $it);     
            }
            $this->_rights=($resData << 0x8) | $this->getAccessLevel();           
        }
        
        public function getRights()
        {
            return $this->_rights;
        }
             
    }
?>
