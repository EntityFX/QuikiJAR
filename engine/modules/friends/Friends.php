<?php
/**
* Файл с классом для работы с друзьями.
* @package friends
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/

    require_once "engine/libs/mysql/MySQLConnector.php"; 
    
    require_once "engine/modules/user/User.php";
    
    require_once "engine/modules/user/UserRegister.php";    
    
    require_once "engine/modules/friends/FriendsException.php";    
    
    /**
    * Класс управляет добавлением, добавлением и выводом друзей пользователя
    *
    */
    class Friends extends MySQLConnector  
    {
        /**
        * Id текущего пользователя
        * 
        * @var integer
        */
        private $_curentId;

        /**
        * Конструктор. Получает данные текущего пользователя, создаёт объект для доступа к бд
        * 
        */
        public function __construct($id=NULL)
        {
            parent::__construct();
            if ($id==NULL)
            {
                $user=new User();
                $this->_curentId=$user->id;     
            }
            else
            {
                $this->_curentId=$id;
            }
        }
        
        /**
        * Добавить друга в список друзей по ID
        * 
        * @param integer $friendId
        * @throws FriendsException Если пользователь не существует или друг уже был добавлен.
        */
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
                    $this->_sql->query
                    ("
                        INSERT INTO `USERS_FRIENDSHIP` VALUES (0,$userId,$friendId),(0,$friendId,$userId)
                    ");
                }
            }
            else
            {
                throw new FriendsException(FriendsException::FRND_ALRD_EX,$friendId);
            }
        }
        
        /**
        * Проверяет, является ли пользователь другом
        * 
        * @param integer $friendId ID друга
        * @param integer $userId ID пользователя. Если опущено, то ID вошедшего в систему пользователя
        * @return bool
        */
        public function checkHasFriend($friendId,$userId=NULL)
        {
            if ($userId==NULL)    
            {
                $userId=$this->_curentId;    
            }
            $res=$this->_sql->query("SELECT COUNT(*) AS `c` FROM `USERS_FRIENDSHIP` WHERE `user_id`=$userId AND `friend_id`=$friendId");
            $counter=$this->_sql->GetRows($res);
            return (bool)$counter[0]["c"];
        }
        
        /**
        * Удалить друга по ID
        * 
        * @param integer $friendId ID друга
        * @throws FriendsException Если друга нету в списке друзей
        */
        public function deleteFriend($friendId)
        {
            if ($this->checkHasFriend($friendId))
            {
                $userId=$this->_curentId;
                $this->_sql->query
                ("
                    DELETE FROM `USERS_FRIENDSHIP` WHERE `user_id`=$userId AND `friend_id`=$friendId;
                    DELETE FROM `USERS_FRIENDSHIP` WHERE `user_id`=$friendId AND `friend_id`=$userId;
                ");
            }
            else
            {
                throw new FriendsException(FriendsException::FRND_NOT_EX,$friendId);
            }                
        }
        
        /**
        * Получить список всех друзей
        * 
        * @return array[User] 
        */
        public function getAllFriends()
        {
            $userId=$this->_curentId;
            $res=$this->_sql->query("SELECT `friend_id` FROM `USERS_FRIENDSHIP` WHERE `user_id`=$userId"); 
            return $this->getFriendsResultQuery($res);
        }
        
        /**
        * Получить часть друзей постранично 
        * 
        * @param integer $size Число друзей на странице
        * @param integer $page Номер страницы
        * @return array[User]
        */
        public function getFriendsPage($size,$page)
        {
            $from=$size*($page-1);
            $userId=$this->_curentId;
            $res=$this->_sql->query("SELECT `friend_id` FROM `USERS_FRIENDSHIP` WHERE `user_id`=$userId LIMIT $from,$size"); 
            return $this->getFriendsResultQuery($res);           
        }
        
        /**
        * Получить случайных N друзей
        * 
        * @param integer $num Количество друзей
        * @return array[User]
        */
        public function getRandomFriends($num)
        {
            $num=(int)$num;
            $userId=$this->_curentId;
            $res=$this->_sql->query("SELECT * FROM `USERS_FRIENDSHIP` WHERE `user_id`=$userId ORDER BY RAND( ) LIMIT $num"); 
            return $this->getFriendsResultQuery($res);
        }
        
        /**
        * Возращает по результату запроса массив объектов класса User
        * 
        * @param resource $queryResult
        * @return array[User]
        */
        private function getFriendsResultQuery($queryResult)
        {
            $table=$this->_sql->GetRows($queryResult);
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
        
        
    }  
?>
