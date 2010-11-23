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
	
	/**
	* Класс для стандартной рассылки данных
	*/
	class UserMailer extends PHPMailer
	{
		public static $subjectRegister="Регистрация на сайте Quki.ru";
		public static $fromName="Сайт QUKI.RU";
		public static $from="service@quki.ru";
		
		public $mail="";
		
		public function __construct() 
		{
			$this->$from=self::$from;
			$this->$fromName=self::$$fromName;    
		}
		
		public function registerSend($content,$embedded=NULL)
		{
			$this->Subject=self::$subjectRegister;
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
