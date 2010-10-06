<?php
    /**
    * Выдаёт исключения при ошибках работы с пользователем
    */
    final class UserException extends Exception
    {
        const USR_NOT_EXSIST="User is not exsist in database";
        const USR_PASSWORD_INCORRECT="User's password is incorrect";
        const USR_NAME_INCORRECT="Wrong mail format";
        const USR_NOT_AUTENT="Can't init User object. User is not autentificated.";
        const USR_ALREADY_EXIST="This user already exist";
        const USR_NAME_EMPTY="User's name or surname is empty. Enter them.";
        const USR_PASSWORD_NEQ="User's password1 NOT EQ password2.";
        const USR_PASSWORD_LENGTH="User's password length must be between 7 and 26."; 
        const USR_CHECK_BURTHDAY="User's burthday format uncorrect. Must be between 1900-01-01 AND 2099-12-31";
        const USR_NOT_ACTIVATED="You must activate your user to enter this site";
        const INFO_ALREADY_EXSIST="This info already exsist. Use other title";
        const INFO_NOT_EXSIST="This info not exsist. Can't delete it";
        
        /**
        * Конструктор
        * 
        * @param string $mail MAIL пользователя, от которого выдаётся ошибка
        * @param mixed $message Сообщение ошибки
        * @return UserException
        */
        public function __construct($mail,$message)
        {
            if ($message==self::USR_NAME_INCORRECT)
            {
                $this->message="<h1>$message: <span style=\"color: red;\">$mail</span></h1>";
            }
            else if ($message==self::USR_ALREADY_EXIST)
            {
                $this->message="<h1>$message: <span style=\"color: red;\">$mail</span></h1>";   
            }
            else
            {
                $this->message="<h1>$message</h1>";    
            }        
        }
    }
?>