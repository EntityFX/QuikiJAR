<?php
/**
* Файл для получении локации пользователя.
* @package user
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/
	/**
	* Управляет настройкой локации пользователя
	*/
	class UserLocation extends MySQLConnector
	{
		
		private $location;
		
		/**
		* Получает на вход массив
		* 
		* @param Array $location Ключи: "countryId", "regionId", "cityId"
		* @return UserLocation
		*/
		public function __construct($location)
		{
			parent::__construct();
			if (isset($location["countryId"]) && isset($location["regionId"]) && isset($location["cityId"])) 	
			{
				$this->location=$location;
			}
		}
		
		/**
		* Получает ID страны пользователя
		* 
		* @param integer $id
		* @return string
		*/
		public function getCountryById($id=NULL)
		{
			$id=$this->inputCntryCityRegCheck($id,"countryId");
			$qRes=$this->_sql->selFieldsWhere("COUNTRY","`country_id`=$id","COUNTRY");
			$arr=$this->_sql->GetRows($qRes);
			return $arr[0]["COUNTRY"];
		}
		
		/**
		* Получает город пользователя по ID города
		* 
		* @param integer $id
		* @return string
		*/
		public function getCityById($id=NULL)
		{
			$id=$this->inputCntryCityRegCheck($id,"cityId");
			$qRes=$this->_sql->selFieldsWhere("COUNTRY_REGIONS_CITY","`city_id`=$id","title_city");
			$arr=$this->_sql->GetRows($qRes);
			return $arr[0]["title_city"];    	
		}
		
		
		/**
		* Получает регион по ID региона
		* 
		* @param integer $id
		* @return string
		*/
		public function getRegionById($id=NULL)
		{
			$id=$this->inputCntryCityRegCheck($id,"regionId");
			$qRes=$this->_sql->selFieldsWhere("COUNTRY_REGIONS","`region_id`=$id","reg_name");
			$arr=$this->_sql->GetRows($qRes);
			return $arr[0]["reg_name"];    		
		}
		
		/**
		* Для БД полей значит необходимо
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
		* Возвращает массив с ID города, региона и страны. Ключи: "countryId", "regionId", "cityId" 
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
	}
?>
