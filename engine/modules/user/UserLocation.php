<?php
/**
* ���� ��� ��������� ������� ������������.
* @package user
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/
	/**
	* ��������� ���������� ������� ������������
	*/
	class UserLocation extends MySQLConnector
	{
		/**
		* ������ � ����������
		* 
		* @var mixed
		*/
		private $location=NULL;
		
		private $_userId=NULL;
		
		private $_user=NULL;
		
		/**
		* �������� �� ���� ������
		* 
		* @param Array $location �����: "countryId", "regionId", "cityId"
		* @return UserLocation
		*/
		public function __construct($location,&$user)
		{
			parent::__construct(); 
			if (isset($location["countryId"]) && isset($location["regionId"]) && isset($location["cityId"])) 	
			{
				$this->_userId=$user->id;
				$this->_user=$user;
				$this->location=$location;
			}
		}
		
		/**
		* �������� ID ������ ������������
		* 
		* @param integer $id
		* @return string
		*/
		public function getCountryById($id=NULL)
		{
			$id=$this->getLocationName("COUNTRY","country_id","COUNTRY","countryId",$id);
			if ($id==NULL)
			{
				throw new Exception("UserLocation: COUNTRY ID IS NO EXSIST",1);
			}
			return $id;
		}
		
		/**
		* �������� ����� ������������ �� ID ������
		* 
		* @param integer $id
		* @return string
		*/
		public function getCityById($id=NULL)
		{
			$id=$this->getLocationName("COUNTRY_REGIONS_CITY","city_id","title_city","cityId",$id);
			if ($id==NULL)
			{
				throw new Exception("UserLocation: CITY ID IS NO EXSIST",1);
			}
			return $id; 
		}
		
		
		/**
		* �������� ������ �� ID �������
		* 
		* @param integer $id
		* @return string
		*/
		public function getRegionById($id=NULL)
		{
			$id=$this->getLocationName("COUNTRY_REGIONS","region_id","reg_name","regionId",$id);
			if ($id==NULL)
			{
				throw new Exception("UserLocation: REGIONS ID IS NO EXSIST",1);
			}
			return $id; 		
		}
		
		private function getLocationName($tableName,$fieldKey,$field,$arrKey,$id=NULL)
		{
			$id=$this->inputCntryCityRegCheck($id,$arrKey);
			$qRes=$this->_sql->selFieldsWhere($tableName,"`$fieldKey`=$id",$field);
			$arr=$this->_sql->GetRows($qRes);
			return $arr[0][$field]; 
		}
		
		/**
		* ��� �� ����� ������ ����������
		* 
		* @param int $id
		* @param string $type
		* @return int
		*/
		private function inputCntryCityRegCheck($id,$type)
		{
			if ($this->location!=NULL && $id==NULL)
			{
				return $this->location[$type];
			}
			else
			{
				return (int)$id;
			}
		}
		
		/**
		* ���������� ������ � ���������� ������� ������, ������� � ������. �����: "countryId", "regionId", "cityId" 
		* 
		* @return Array
		*/
		public function getLocation()
		{     
			if ($this->location!=NULL)
			{
				return array(
					"country" => $this->getCountryById(),
					"region" => $this->getRegionById(),
					"city" => $this->getCityById()
				);
			}
			
		}
		
		public function getLocationId()
		{
			return $this->location;
		}
		
		public function changeLocation($location)
		{
			$error=false;
			try
			{
				$old=$this->location;
				$this->location=$location;
				$this->getLocation();
			}
			catch (Exception $exc)
			{
				$this->location=$old;
				$error=true;
			}
			if (!$error)
			{
				$id=$this->_userId;
				$this->_sql->query("UPDATE SITE_USERS SET 
										country=$location[countryId], 
										region=$location[regionId], 
										city=$location[cityId]
									WHERE id=$id");    
				$_SESSION["user"]["country"]=$location["countryId"];
				$_SESSION["user"]["region"]=$location["regionId"]; 
				$_SESSION["user"]["city"]=$location["cityId"]; 
				$this->_user->update();
			}   
		}

	}
?>
