<?php
/**
* Файл для получении инфы пользователя.
* @package user
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/

	require_once "UserException.php"; 
	
	require_once "UserSignInOut.php";
	
	require_once "UserLocation.php";  
	
	require_once "engine/libs/registry/Registry.php";
	
	require_once "engine/libs/mysql/MySQLConnector.php";
	
	require_once "checker.php";
	
	/**
	* Пользователи сайта. Краткие данные   
	*/
	class User extends MySQLConnector
	{
		const PHOTO_PATH="/photos/";
			
		/**
		* ID пользователя
		* 
		* @var integer
		*/
		public $id;
		
		/**
		* Имя пользователя
		* 
		* @var string
		*/
		public $name;
		
		/**
		* Фамилия
		* 
		* @var string
		*/
		public $secondName;
		
		/**
		* Почта
		* 
		* @var string
		*/
		public $mail;
		
		/**
		* Онлайн
		* 
		* @var bool
		*/
		public $isOnline;

		/**
		* Предельный промежуток времени, при котором пользователь помечается как OffLine
		* Измеряется в секундах 
		* 
		* @var integer
		*/
		protected static $offLineTime=900;
		
		/**
		* Промежуток, при превышении которого стоит обновить время присутствия на сайте
		* Измеряется в секундах
		* 
		* @var integer
		*/
		public static $updateInterval=30;
		
		/**
		* Получить путь к фото
		* 
		* @return string
		*/
		public function getPhoto()
		{
			if ($this->photo=="")
			{
				return Registry::getValue("USERS_NO_PHOTO");
			}
			return self::PHOTO_PATH.$this->mail."/".$this->photo;    
		}
		
		public function __construct($id=NULL)
		{
			parent::__construct();
			secureStartSession(); 
			if ($id==NULL || $id==$_SESSION["user"]["id"])
			{
				$this->setData($_SESSION["user"]);
				$this->isOnline=true;
				$this->checkLastTime(self::$updateInterval);
			}
			else
			{
				try
				{
					$this->setData($this->getDataFromDb($id));
				}
				catch (Exception $ex)
				{
					throw new UserException($id,UserException::USR_NOT_EXSIST);
				}
			} 
		}
		
		/**
		* Вытаскивает логин из БД по ID
		* 
		* @param int $id
		* @return Array[Array[String]]
		*/
		private function getDataFromDb($id)
		{
			$res=$this->_sql->query("SELECT `id` , `mail` , `name` , `second_name` , `photo` , `country` , `region` , `city` , `update_time` FROM `SITE_USERS` WHERE `id`=$id");
			$resArray=$this->_sql->GetRows($res); 
			$resArray=$resArray[0];
			return $resArray;
		}
		
		/**
		* Устанавливает состояния полей из массива
		* 
		* @param array $resArray
		*/
		private function setData($resArray)
		{
			$this->name=$resArray["name"];
			$this->secondName=$resArray["second_name"];
			$this->mail=$resArray["mail"];
			$this->photo=$resArray["photo"];
			$this->id=$resArray["id"];
			$this->isOnline=$this->isOnline();
			$this->location=new UserLocation(array("countryId" => $resArray["country"], "regionId" => $resArray["region"],"cityId" => $resArray["city"]),$this);                                                                                                 
			$arrLoc=$this->location->getLocation();
			$this->lastUpdate=$resArray["update_time"];
		}
		
		/**
		* Проверка Online/Offline
		* 
		* @param integer $interval Время, котором уже оффлайн (в секундах)
		*/
		public function isOnline($interval=0)
		{
			if ($interval==0)
			{
				$interval=self::$offLineTime;
			}
			$lastUpdateTime=$this->getLastUpdate();
			$this->lastUpdate=$lastUpdateTime;
			if (time()-$lastUpdateTime>=$interval)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		/**
		* Получить время последнего обновления. Из БД
		* 
		*/
		private function getLastUpdate()
		{
			$id=$this->id;
			$this->_sql->query("SELECT `update_time` FROM `SITE_USERS` WHERE `id`=$id");
			$array=$this->_sql->GetRows();
			return $array[0]["update_time"];
		}
		
		/**
		* Проверка времени последнего обновления
		* 
		* @param integer $value Если разность больше текущего значения, то обновляется повторно 
		*/
		protected function checkLastTime($value)
		{
			if (time()-$_SESSION["user"]["lastUpdateTime"]>=$value)
			{
				$_SESSION["user"]["lastUpdateTime"]=$this->setLastUpdate(time()); 
			}
		}
		
		/**
		* Изменить время последнего обновления
		* 
		* @param integer $time Время в секундах
		* @return integer
		*/
		public function setLastUpdate($time)
		{
			$this->_sql->query("UPDATE `SITE_USERS` SET `update_time`=$time WHERE `id`=$this->id");
			return $time;
		}
	}
?>
