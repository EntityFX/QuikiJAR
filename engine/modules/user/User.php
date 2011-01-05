<?php
/**
* ���� ��� ��������� ���� ������������.
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
	* ������������ �����. ������� ������   
	*/
	class User extends MySQLConnector
	{
		const PHOTO_PATH="/photos/";
			
		/**
		* ID ������������
		* 
		* @var integer
		*/
		public $id;
		
		/**
		* ��� ������������
		* 
		* @var string
		*/
		public $name;
		
		/**
		* �������
		* 
		* @var string
		*/
		public $secondName;
		
		/**
		* �����
		* 
		* @var string
		*/
		public $mail;
		
		/**
		* ������
		* 
		* @var bool
		*/
		public $isOnline;

		/**
		* ���������� ���������� �������, ��� ������� ������������ ���������� ��� OffLine
		* ���������� � �������� 
		* 
		* @var integer
		*/
		protected static $offLineTime=900;
		
		/**
		* ����������, ��� ���������� �������� ����� �������� ����� ����������� �� �����
		* ���������� � ��������
		* 
		* @var integer
		*/
		public static $updateInterval=30;
		
		/**
		* �������� ���� � ����
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
		* ����������� ����� �� �� �� ID
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
		* ������������� ��������� ����� �� �������
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
		* �������� Online/Offline
		* 
		* @param integer $interval �����, ������� ��� ������� (� ��������)
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
		* �������� ����� ���������� ����������. �� ��
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
		* �������� ������� ���������� ����������
		* 
		* @param integer $value ���� �������� ������ �������� ��������, �� ����������� �������� 
		*/
		protected function checkLastTime($value)
		{
			if (time()-$_SESSION["user"]["lastUpdateTime"]>=$value)
			{
				$_SESSION["user"]["lastUpdateTime"]=$this->setLastUpdate(time()); 
			}
		}
		
		/**
		* �������� ����� ���������� ����������
		* 
		* @param integer $time ����� � ��������
		* @return integer
		*/
		public function setLastUpdate($time)
		{
			$this->_sql->query("UPDATE `SITE_USERS` SET `update_time`=$time WHERE `id`=$this->id");
			return $time;
		}
	}
?>
