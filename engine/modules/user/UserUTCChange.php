<?php
	class UserUTCChange extends MySQLConnector
	{
		private $_id;
		
		private $_arrUtcCity=array(
			"-48" => "Date Line",
			"-44" => "Samoa",
			"-40" => "Hawaii",
			"-36" => "Alaska ",
			"-32" => "The North American Pacific Time",
			"-28" => "Mountain Time, Mexico",
			"-24" => "Central Time, Central American, Mexico",
			"-20" => "North American Eastern Time, The South American Pacific Time",
			"-18" => "Caracas",
			"-16" => "Atlantic Time (Canada), The South American Pacific Time",
			"-14" => "Newfoundland",
			"-12" => "South American Eastern Time",
			"-8"  => "Mid",
			"-4"  => "Azores, Cape Verde",
			"0"   => "Western European Time (Dublin, Edinburgh, Lisbon, London, Casablanca, Monrovia)",
			"4"   => "Central European Time (Amsterdam, Berlin, Bern, Brussels, Vienna, Copenhagen, Madrid, Paris, Rome, Stockholm, Belgrade, Bratislava, Budapest, Warsaw, Ljubljana, Prague, Sarajevo, Skopje, Zagreb) Western Central African Time",
			"8"   => "Eastern European Time (Athens, Bucharest, Vilnius, Kiev, Kishinev, Minsk, Riga, Sofia, Tallinn, Helsinki, Kaliningrad), Egypt, Israel, Lebanon, Libya, Turkey, South Africa", 
			"12"  => "Moscow time, the East African Time (Nairobi, Addis Ababa), Iraq, Kuwait, Saudi Arabia",
			"14"  => "Tehran Time",
			"16"  => "United Arab Emirates, Oman, Azerbaijan, Armenia, Georgia",
			"18"  => "Afghanistan",
			"20"  => "Yekaterinburg time, while Western Asia (Islamabad, Karachi, Uzbekistan)",
			"22"  => "India, Sri Lanka", 
			"23"  => "Nepal", 
			"24"  => "Omsk Time, Novosibirsk, Russia, Central Asian time (Bangladesh, Kazakhstan, Kyrgyzstan)", 
			"26"  => "Myanmar", 
			"28"  => "Krasnoyarsk time, Southeast Asia (Bangkok, Jakarta, Hanoi)", 
			"32"  => "Irkutsk time, Ulan Bator, Kuala Lumpur, Hong Kong, China, Singapore, Taiwan, while West Australian (Perth)", 
			"36"  => "Yakutsk time, Korea, Japan",
			"38"  => "Central Australian Time (Adelaide, Darwin)", 
			"40"  => "Vladivostok time East Australian Time (Brisbane, Canberra, Melbourne, Sydney), Tasmania, Western Pacific Standard Time (Guam, Port Moresby)", 
			"44"  => "Magadan time, the Central Pacific Standard Time (Solomon Islands, New Caledonia)", 
			"48"  => "Marshall Islands, Fiji, New Zealand", 
			"52"  => "Tonga", 
			"56"  => "Line Islands (Kiribati)");
			
		private $_utcValues=array(-48,-44,-40,-36,-32,-28,-24,-20,-18,-16,-14,-12,-8,-4,0,4,8,12,14,16,18,20,22,23,24,26,28,32,36,38,40,44,48,52,56);
		
		public function __construct()
		{
			parent::__construct();
			$user=new User();
			$this->_id=$user->id;
		}
		
		public function getUTC()
		{
			$id=$this->_id;
			$qRes=$this->_sql->selFieldsWhere("SITE_USERS","`id`=$id","utc_time");
			$arr=$this->_sql->GetRows($qRes);
			return (int)$arr[0]["utc_time"];
		}
		
		public function getUTCName()
		{
			return $this->_arrUtcCity[(string)$this->getUTC()];
		}
		
		public function setUTC($utc)
		{
			$utc=(int)$utc;
			$id=$this->_id;
			if (in_array($utc,$this->_utcValues))
			{
				$qRes=$this->_sql->query("UPDATE `SITE_USERS` SET `utc_time`=$utc WHERE `id`=$id");
			}
			else
			{
				throw new Exception("This UTC value not exsist");
			}
		}
	}
?>
