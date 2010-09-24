<?php
    
    require_once "engine/modules/user/User.php"; 
        
    class FriendsException extends Exception
    {
        const FRND_ALRD_EX="This user already friend. Can't add him";
        const FRND_NOT_EX="This user is not FRIEND";
        
        public $friendId;
        
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
