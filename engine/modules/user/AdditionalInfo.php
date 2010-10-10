<?php
/**
* Дополнительная информация о пользователях.
* @package user
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/


    require_once "engine/libs/mysql/MySQLConnector.php";
    
    require_once "UserException.php";
    
    require_once "UserAdditionalInfo.php"; 
    
    class AdditionalInfo extends MySQLConnector
    {
        private $_userId;
        
        public function __construct($id)
        {
            parent::__construct();
            $this->_userId=$id;
        }
        
        public function add($attribute,$text)
        {
            if (!$this->checkIfExsist($attribute))
            {
                $val=array(0,$this->_userId,"'$attribute'","'$text'");
                $this->_sql->insert("SITE_USERS_ADDONS",$val);
            }   
            else
            {
                throw new UserException($this->_userId,UserException::INFO_ALREADY_EXSIST);
            } 
        } 
        
        public function delete($attribute)   
        {
            if ($this->checkIfExsist($attribute))
            {
                $this->_sql->delete("SITE_USERS_ADDONS","`user_id`=$this->_userId AND `attribute`='$attribute'");
            }   
            else
            {
                throw new UserException($this->_userId,UserException::INFO_NOT_EXSIST);
            }             
        }
        
        public function edit($attribute,$value)
        {
            if ($this->checkIfExsist($attribute))
            {
                $this->_sql->query("UPDATE `SITE_USERS_ADDONS` SET `value`='$value' WHERE `user_id`=$this->_userId AND `attribute`='$attribute'");
            }   
            else
            {
                throw new UserException($this->_userId,UserException::INFO_NOT_EXSIST);
            }              
        }
        
        public function getAll()
        {
            $this->_sql->selAllWhere("SITE_USERS_ADDONS","`user_id`=$this->_userId");
            $arr=$this->_sql->getTable();
            if ($arr!=NULL)
            {
                foreach($arr as $value)
                {
                    $objcts[]=new UserAdditionalInfo($value);
                }
            }
            return $objcts;
        }
        
        private function checkIfExsist($attribute)
        {
            return (bool)$this->_sql->countQuery("SITE_USERS_ADDONS","`user_id`=$this->_userId AND `attribute`='$attribute'");
        }
        
        public function count()
        {
            return $this->_sql->countQuery("SITE_USERS_ADDONS","`user_id`=$this->_userId");    
        }
        
    }
?>
