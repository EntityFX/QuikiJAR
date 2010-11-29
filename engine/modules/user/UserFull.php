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
	
	require_once "UserAdditionalInfo.php";        
	
	require_once "engine/libs/registry/Registry.php";
	
	require_once "engine/libs/mysql/MySQLConnector.php";
	
	require_once "checker.php";
	
	require_once "UserStatus.php";
	
	require_once "User.php"; 
	
	require_once "Zodiac.php";
	
	class UserFull extends User 
	{
		/**
		* ��� (false - ���, true - ���)
		* 
		* @var bool
		*/
		public $gender;
		/**
		* ���� ��������
		* 
		* @var string
		*/
		public $burthday;
		/**
		* ������
		* 
		* @var integer
		*/
		public $country;
		/**
		* ������/�������
		* 
		* @var integer
		*/
		public $region;
		/**
		* �����
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
		* ���� ����
		* 
		* @var string
		*/
		private $photo;
		
		/**
		* ����� ���������� ������������. ������������� ������ � ������ "country", "region", "city"
		* 
		* @var Array
		*/
		public $location;
		
		/**
		* ����� ���������� ����������
		* 
		* @var mixed
		*/
		public $lastUpdate;
		
		/**
		* ������ ������������
		* 
		* @var mixed
		*/
		public $status;
		
		/**
		* ������� ����: ����� � ������� / 15 
		* 
		* @var integer
		*/
		public static $utc=12;
		
		private $other=false;
		
		/**
		* �����������
		* 
		* @param integer $id �������������� ��������. ���� NULL, �� ������� ������
		* ������������ ������� ����� � ������� (������), ����� �� ID ������� ������������
		* @return User
		*/
		public function __construct($id=NULL)
		{
			MySQLConnector::__construct();
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
					$this->checkLastTime(parent::$updateInterval);
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
		* ������������� ��������� ����� �� �������
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
			$this->location=new UserLocation(array("countryId" => $resArray["country"], "regionId" => $resArray["region"],"cityId" => $resArray["city"]),$this);                                                                                                 
			$arrLoc=$this->location->getLocation();
			$this->zodiac=Zodiac::calculateByDate($this->burthday);
			$this->utc=$resArray["utc_time"];
			$status=new UserStatus($this->id);
			$this->status=$status->getStatus();
			if ($this->other)
			{
				$this->lastUpdate=$resArray["update_time"];
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
			$res=$this->_sql->query("SELECT `id` , `mail` , `password` , INET_NTOA( `ip` ) AS `ip` , `register_date` , `name` , `second_name` , `gender` , `burthday` , `photo` , `country` , `region` , `city` , `street` , `utc_time` , `update_time` FROM `SITE_USERS` WHERE `id`=$id");
			$resArray=$this->_sql->GetRows($res);
			$resArray=$resArray[0];
			return $resArray;
		}
		
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
	   
		/**
		* �������� �������������� ������ � ������������
		* 
		* @return Array[UserAdditionalInfo]
		*/
		public function getInfo()
		{
			$info=new AdditionalInfo($this->id);
			return $info->getAll();
		}
		
		/**
		* ���������� ����� ������� � ��������
		* 
		* @param integer $value ����� � ��������
		*/
		public static function setOffLineTime($value)
		{
			parent::$offLineTime=$value<parent::$updateInterval ? parent::$updateInterval : $value;
		}
		
		/**
		* �������� ����� ��� ������� � ��������
		* 
		* @return integer
		*/
		public static function getOffLineTime()
		{
			return self::$offLineTime;
		}
		
		public function update()
		{
			$this->__construct();
		}
	}
?>
