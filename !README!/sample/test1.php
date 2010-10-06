<?php

    interface IConc1
    {
        public function getLife();
    }
    
    interface IConc2
    {
        public function getAgility();
    }
    
    class GenConc1 implements IConc1
    {
        private $_x;
        
        public function __construct()
        {
            $this->x=5; 
        }
        
        public function getLife()
        {
            return $this->x+100;
        }
    }
    
    class GenConc2 implements IConc2
    {
        public function getAgility()
        {
            return array(1,2,3,4,5);
        }
    }
    
    class GenConc1Conc2 implements IConc1,IConc2
    {
        private $_g1;
        private $_g2;
        
        public function __construct()
        {
            $this->_g1=new GenConc1();
            $this->_g2=new GenConc2(); 
        }
        
        public function getLife()
        {
            return $this->_g1->getLife();
        }

        public function getAgility()
        {
            return $this->_g2->getAgility();
        }
        
        public function getName()
        {
            return "Vasya";
        }
    }
?>
