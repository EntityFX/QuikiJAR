<?php
/**
* Файл с классом исключений работы с друзьями.
* @package friends
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/
    
    require_once "engine/modules/user/User.php"; 
    
    /**
    * Класс для выдачи исключений, возникающих при работе с друзьями пользователя    
    */
    class FriendsException extends Exception
    {
        const FRND_ALRD_EX="This user already friend. Can't add him";
        const FRND_NOT_EX="This user is not your FRIEND";
        const GRP_CANT_DEL="Can't delete this group. It is not exsist." ;
        const GRP_ALRD_EX="This group already exsist. Can't add it.";
        const GRP_CANT_EDT="Can't edit group. It is not exsist or not your.";
        const GRP_FRND_CNT_ADD="Cant't add friend in group. It is already exsist here. GROUP ID";
        const GRP_FRND_CNT_DEL="Can't delete friend from group. Is is not exsist here. GROUP ID";
        const GRP_ACC_DEN="This group is access denied";
        
        /**
        * ID пользователя
        * 
        * @var mixed
        */
        public $friendId;
        
        /**
        * Конструктор
        * 
        * @param string $message Сообщение ошибки
        * @param integer $id Id друга
        * @return FriendsException
        */
        public function __construct($message,$id=NULL)
        {
            if ($id==NULL)
            {
                $usr=new User();
                $id=$usr->id;
                $this->friendId=$id;
            }

            $this->message=$message." :$id";
        }
        
    }
?>
