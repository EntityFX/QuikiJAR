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
	
	require_once "UserAdditionalInfo.php";        
	
	require_once "engine/libs/registry/Registry.php";
	
	require_once "engine/libs/mysql/MySQLConnector.php";
	
	require_once "checker.php";
	
	require_once "UserLocation.php";
	
	require_once "Zodiac.php";
	
	/**
	* Пользователи сайта    
	*/
	class User extends MySQLConnector
	{
		const PHOTO_PATH="/photos/";
		
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
		* Пол (false - жен, true - муж)
		* 
		* @var bool
		*/
		public $gender;
		/**
		* День рождения
		* 
		* @var string
		*/
		public $burthday;
		/**
		* страна
		* 
		* @var integer
		*/
		public $country;
		/**
		* Регион/область
		* 
		* @var integer
		*/
		public $region;
		/**
		* Город
		* 
		* @var string
		*/
		public $city;
		/**
		* IP
		* 
		* @var string
		*/
		public $ip;
		/**
		* ID пользователя
		* 
		* @var integer
		*/
		public $id;
		/**
		* Онлайн
		* 
		* @var bool
		*/
		public $isOnline;
		
		/**
		* Файл фото
		* 
		* @var string
		*/
		private $photo;
		
		/**
		* Место проживания пользователя. ассоциативный массив с полями "country", "region", "city"
		* 
		* @var Array
		*/
		public $location;
		
		/**
		* Время последнего обновления
		* 
		* @var mixed
		*/
		public $lastUpdate;
		
		/**
		* Промежуток, при превышении которого стоит обновить время присутствия на сайте
		* Измеряется в секундах
		* 
		* @var integer
		*/
		public static $updateInterval=30;
		
		/**
		* Предельный промежуток времени, при котором пользователь помечается как OffLine
		* Измеряется в секундах 
		* 
		* @var integer
		*/
		private static $offLineTime=900;
		
		private $other=false;
		
		/**
		* Конструктор
		* 
		* @param integer $id Необязательный параметр. Если NULL, то берутся данные
		* пользователя который вошёл в систему (сессии), иначе по ID другого пользователя
		* @return User
		*/
		public function __construct($id=NULL)
		{
			parent::__construct();
			secureStartSession();
			if ($id==NULL || $id==$_SESSION["user"]["id"])
			{
				if (!isset($_SESSION["user"]))
				{
					$userSignOut=new UserSignInOut();
					if ($userSignOut->checkIfSave())
					{ 
						$uId=(int)$_COOKIE["id"];
						$this->_sql->query("SELECT `mail`,`password` FROM `SITE_USERS` WHERE `id`=$uId");
						$secArr=$this->_sql->GetRows();
						$mailSec=$secArr[0]["mail"];
						$pass=$secArr[0]["password"];
						if (md5($uId).md5($mailSec)!=$_COOKIE["sec"])        
						{
							throw new UserException("",UserException::USR_NOT_AUTENT);      
						}
						else
						{
							$userSignOut->authentication($mailSec,$pass,false,true);
							$this->setData($_SESSION["user"]);
							$this->isOnline=true;
						}
					}
					else
					{
						throw new UserException("",UserException::USR_NOT_AUTENT);
					}
				}
				else
				{
					$this->setData($_SESSION["user"]);
					$this->isOnline=true;
					$this->checkLastTime(self::$updateInterval);
				}   
			}
			else
			{
				try
				{
					$this->other=true;
					$this->setData($this->getDataFromDb($id));
				}
				catch (Exception $ex)
				{
					throw new UserException($id,UserException::USR_NOT_EXSIST);
				}
			}
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
			$this->burthday=$resArray["burthday"];
			$this->mail=$resArray["mail"];
			$this->photo=$resArray["photo"];
			$this->ip=$resArray["ip"];
			$this->id=$resArray["id"];
			$this->isOnline=$this->isOnline();
			$loc=new UserLocation(array("countryId" => $resArray["country"], "regionId" => $resArray["region"],"cityId" => $resArray["city"]));
			$this->location=$loc->getLocation();
			$this->country=$this->location["country"];
			$this->region=$this->location["region"];
			$this->city=$this->location["city"];
			$this->zodiac=Zodiac::calculateByDate($this->burthday);
			if ($this->other)
			{
				$this->lastUpdate=$resArray["update_time"];
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
			$res=$this->_sql->query("SELECT `id` , `mail` , `password` , INET_NTOA( `ip` ) AS `ip` , `register_date` , `name` , `second_name` , `gender` , `burthday` , `photo` , `country` , `region` , `city` , `street` , `utc_time` , `update_time` FROM `SITE_USERS` WHERE `id`=$id");
			$resArray=$this->_sql->GetRows($res);
			$resArray=$resArray[0];
			return $resArray;
		}
		
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
		* Проверка времени последнего обновления
		* 
		* @param integer $value Если разность больше текущего значения, то обновляется повторно 
		*/
		private function checkLastTime($value)
		{
			if (time()-$_SESSION["user"]["lastUpdateTime"]>=$value)
			{
				$_SESSION["user"]["lastUpdateTime"]=$this->setLastUpdate(time()); 
			}
		}
	   
		/**
		* Получить время последнего обновления. Из БД
		* 
		*/
		private function getLastUpdate()
		{
			$this->_sql->query("SELECT `update_time` FROM `SITE_USERS` WHERE `id`=$this->id");
			$array=$this->_sql->GetRows();
			return $array[0]["update_time"];
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
		
		/**
		* Получить дополнительные данные о пользователе
		* 
		* @return Array[UserAdditionalInfo]
		*/
		public function getInfo()
		{
			$info=new AdditionalInfo($this->id);
			return $info->getAll();
		}
		
		/**
		* Установить время оффлайн в секундах
		* 
		* @param integer $value Время в секундах
		*/
		public static function setOffLineTime($value)
		{
			self::$offLineTime=$value<self::$updateInterval ? self::$updateInterval : $value;
		}
		
		/**
		* Получить время для оффлайн в секундах
		* 
		* @return integer
		*/
		public static function getOffLineTime()
		{
			return self::$offLineTime;
		}
	}
?>
