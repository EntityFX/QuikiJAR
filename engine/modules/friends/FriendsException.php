<?php
    
    require_once "engine/modules/user/User.php"; 
        
    class FriendsException extends Exception
    {
        const FRND_ALRD_EX="This user already friend. Can't add him";
        const FRND_NOT_EX="This user is not FRIEND";
        const GRP_CANT_DEL="Can't delete this group. It is not exsist." ;
        const GRP_ALRD_EX="This group already exsist. Can't add it.";
        const GRP_CANT_EDT="Can't edit group. It is not exsist or not your.";  
        
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
