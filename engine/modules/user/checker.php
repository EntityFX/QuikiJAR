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
    
    function secureStartSession()
    {
        session_start();
        /*if ($_COOKIE["HASH"]!=md5(session_id()."D'r Lightman"))
        {
            unset($_SESSION["user"]);
            session_destroy();
        }*/
    }
    
    /**
    * Возвращает IP клиента
    *
    * @return string 
    */
    function ipDetect()
    {
        $serverVars = array(
            "HTTP_X_FORWARDED_FOR",
            "HTTP_X_FORWARDED",
            "HTTP_FORWARDED_FOR",
            "HTTP_FORWARDED",
            "HTTP_VIA",
            "HTTP_X_COMING_FROM",
            "HTTP_COMING_FROM",
            "HTTP_CLIENT_IP"
        );
        foreach ($serverVars as $serverVar) //просмотреть все возможные варианты
        {
            if (!empty($_SERVER[$serverVar]))
            {
                $proxyIP = $_SERVER[$serverVar];
            }
        }
        if (!empty($proxyIP))
        {
            $isIP = preg_match('|^([0-9]{1,3}\.){3,3}[0-9]{1,3}|', $proxyIP, $regs);
            if ($isIP && (sizeof($regs) > 0))
                return $regs[0];
        }
        return $_SERVER['REMOTE_ADDR'];
    }
?>
