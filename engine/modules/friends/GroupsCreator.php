<?php
/**
* Создание групп
* @package friends
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/
    
    require_once "FriendsException.php";
    
    require_once "Group.php";
    
    /**
    * Необходим для создания, удаления, редактирования группы  
    */
    class GroupsCreator extends MySQLConnector
    {
        /**
        * ID пользователя в системе
        * 
        * @var integer
        */
        private $_curentId;

        /**
        * Конструктор
        * 
        */
        public function __construct()
        {
            parent::__construct();
            $user=new User();
            $this->_curentId=$user->id;
        }        
        
        /**
        * Создаёт группу
        * 
        * @param string $groupName Заголовок группы
        * @throws FriendsException Если такая группа уже существует 
        */
        public function create($groupName)
        {
            if ($this->checkIfExsistByName($groupName))
            {
                throw new FriendsException(FriendsException::GRP_ALRD_EX);
            }
            $this->_sql->query("INSERT INTO `USERS_GROUPS` VALUES(0,'$groupName',$this->_curentId)");
        }
        
        /**
        * Удаляет группу
        * 
        * @param integer $groupId ID группы
        */
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
        
        /**
        * Переименует группу
        * 
        * @param integer $groupId ID группы
        * @param string $newName Новый заголовок
        * @throws FriendsException Если группа не существует
        */
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
        
        /**
        * Получить список всех групп
        * 
        * @return array[Group]
        */
        public function getAllGroups()
        {
            $res=$this->_sql->query("SELECT * FROM `USERS_GROUPS` WHERE `owner`=$this->_curentId ORDER BY `title`");
            $arr=$this->_sql->GetRows($res);
            $result=NULL;
            if ($arr!=NULL) 
            {
                foreach($arr as $value) 
                {
                    $result[]=new Group($value["group_id"],$value["title"]);    
                }
            }
            return  $result;  
        }
        
        /**
        * Получить группу по ID
        * 
        * @param integer $groupId ID группы
        * @return Group
        */
        public function getGroup($groupId)
        {
            $res=$this->_sql->query("SELECT * FROM `USERS_GROUPS` WHERE `owner`=$this->_curentId AND `group_id`=$groupId");
            $arr=$this->_sql->GetRows($res); 
            if ($arr!=NULL)
            {
                return new Group($groupId,$arr[0]["title"]);              
            }
            else
            {
                throw new Exception(FriendsException::GRP_ACC_DEN);
            }
        }
        
        /**
        * Проверить, существует ли заданная группа
        * 
        * @param integer $groupId ID группы
        * @return bool
        */
        private function checkIfExsistById($groupId)
        {
            $countGroups=$this->_sql->countQuery("USERS_GROUPS","`group_id`=$groupId AND `owner`=$this->_curentId");
            return (Boolean)$countGroups;    
        }
        
        /**
        * Проверить существование группы по её заголовку (только для текущего пользователя)
        * 
        * @param string $name Заголовок группы
        * @return bool
        */
        private function checkIfExsistByName($name)
        {
            $countGroups=$this->_sql->countQuery("USERS_GROUPS","`title`='$name' AND `owner`=$this->_curentId");
            return (Boolean)$countGroups;    
        }        
    }
?>
