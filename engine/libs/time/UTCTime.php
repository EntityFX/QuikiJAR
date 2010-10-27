<?php

    require_once "engine/libs/mysql/MySQLConnector.php"; 
    
    require_once "engine/libs/registry/Registry.php";       

    class UTCTime
    {
        
        public $localCorrect=0;
        
        public function __construct($useCorrect)
        {                    
            session_start();
            if ($useCorrect)
            {
                if (isset($_SESSION["localCorrect"]))
                {
                    $this->localCorrect=$_SESSION["localCorrect"];    
                }
                else
                {
                    try
                    {
                        $this->localCorrect=Registry::getValue("UTCcorrect");
                        $_SESSION["localCorrect"]=$this->localCorrect;
                    }
                    catch (Exception $e)
                    {
                        die("���������� ���� ��� �������� �����");
                    }
                }
            }
        }
        
        public function getTime()
        {
            $time=time();
            try
            {
                $utc=$this->getUTC();
            }
            catch (Exception $e)
            {
                $utc=0;
            }
            return $time+($utc-$this->localCorrect)*900;
        }
        
        private function getUTC()
        {
            if (isset($_SESSION["user"]["utc_time"]))
            {
                return $_SESSION["user"]["utc_time"];    
            }
            else
            {
                throw new Exception("CAN'T get UTC time");
            }
        }
    }
?>
