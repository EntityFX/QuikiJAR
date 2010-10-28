<?php
/**
* Подрубает почту в QUKI-JAR
*
* @package PHPMailer
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/

    require_once "engine/libs/phpmailer/PHPMailer.php";
    
    class UserMailer extends PHPMailer
    {
        const SUBJECT_REGISTER="Регистрация на сайте Quki.ru";
        const FROM_NAME="Сайт QUKI.RU";
        const FROM="service@quki.ru";
        
        public $mail="";
        
        public function __construct() 
        {
            $this->From=self::FROM;
            $this->FromName=self::FROM_NAME;    
        }
        
        public function registerSend($content,$embedded=NULL)
        {
            $this->Subject=self::SUBJECT_REGISTER;
            $this->Body=$content;
            if ($this->mail=="")
            {
                throw new Exception("MAILER EXCEPTION: MAIL FILED IS NOT UNDERFINED");
            }
            $this->AddAddress($this->mail,"Артём Солопий");
            $this->IsHTML(true);
            if ($embedded!=NULL)
            {
                foreach($embedded as $value)
                {
                    list($width, $height, $type, $attr) =getimagesize($value);
                    $mimeType=image_type_to_mime_type($type);
                    $this->AddEmbeddedImage($value,$value,$value,"base64",$mimeType);
                }
            }
            $this->Send();    
        }
        
        public function passwordChangeSend()
        {
            
        }
    }
?>
