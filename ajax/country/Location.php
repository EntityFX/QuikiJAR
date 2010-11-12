<?php
	require_once "../../engine/libs/mysql/MySQLConnector.php";
	require_once "../../config/databaseConsts.php";
	class Location extends MySQLConnector
	{
		public function getCountries()
		{
			$this->_sql->selAll("COUNTRY");
			$arr=$this->_sql->getTable();
			foreach($arr as $value)
			{
				$res[]=array("id" => $value["country_id"],"text" => $value["COUNTRY"]);
			}
			return $res;
		}
		
		public function getRegions($countryID)
		{
			$countryID=(int)$countryID;
			$this->_sql->selFieldsWhere("COUNTRY_REGIONS","`country`=$countryID","region_id","reg_name");
			$arr=$this->_sql->GetRows(); 
			if ($arr!=NULL)
			{
				foreach($arr as $value)
				{
					$res[]=array("id" => $value["region_id"],"text" => $value["reg_name"]);
				}
			}
			return $res;                
		}
		
		public function getCities($regionID)
		{
			$countryID=(int)$countryID;
			$this->_sql->selFieldsWhere("COUNTRY_REGIONS_CITY","`region`=$regionID","city_id","title_city");
			$arr=$this->_sql->GetRows();

			if ($arr!=NULL)
			{
				foreach($arr as $value)
				{
					$res[]=array("id" => $value["city_id"],"text" => $value["title_city"]);
				}
			}
			return $res;   
		}
	}
?>
