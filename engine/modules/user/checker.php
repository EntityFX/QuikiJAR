<?php
    /**
    * Проверка формата почты
    * 
    * @param string $mail
    */
    function checkMail($mail)
    {
        if (preg_match("/^[a-zA-Z0-9._-]+@([a-zA-Z0-9_-]+.)*[a-zA-Z]{2,}$/",$mail)==1)
        {
            return true;    
        }
        else
        {
            return false;
        }
    }
    
    /**
    * Проверка формата даты
    * 
    * @param string $date
    */
    function checkDateFormat($date)
    {
        if (preg_match("/^(19|20)[0-9]{2}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)==1)
        {
            return true;    
        }
        else
        {
            return false;
        }        
    }
?>
