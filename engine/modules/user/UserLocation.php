<?php
	class UserLocation extends MySQLConnector
	{
		private $location;
		
		public function __construct($location)
		{
			parent::__construct();
			if (isset($location["countryId"]) && isset($location["regionId"]) && isset($location["cityId"])) 	
			{
				$this->location=$location;
			}
		}
		
		public function getCountryById($id=NULL)
		{
			$id=$this->inputCntryCityRegCheck($id,"countryId");
			$qRes=$this->_sql->selFieldsWhere("COUNTRY","`country_id`=$id","COUNTRY");
			$arr=$this->_sql->GetRows($qRes);
			return $arr[0]["COUNTRY"];
		}
		
		public function getCityById($id=NULL)
		{
			$id=$this->inputCntryCityRegCheck($id,"cityId");
			$qRes=$this->_sql->selFieldsWhere("COUNTRY_REGIONS_CITY","`city_id`=$id","title_city");
			$arr=$this->_sql->GetRows($qRes);
			return $arr[0]["title_city"];    	
		}
		
		public function getRegionById($id=NULL)
		{
			$id=$this->inputCntryCityRegCheck($id,"regionId");
			$qRes=$this->_sql->selFieldsWhere("COUNTRY_REGIONS","`region_id`=$id","reg_name");
			$arr=$this->_sql->GetRows($qRes);
			return $arr[0]["reg_name"];    		
		}
		
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
