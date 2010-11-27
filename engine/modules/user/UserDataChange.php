<?php
	class UserDataChange extends MySQLConnector
	{
	
		private $_id;
		
		public function __construct() 
		{
			parent::__construct();
			$user=new User();
			$this->_id=$user->id;    
		}
		
		public function changeName($new)
		{
			$this->changeField($new,"name");
		}
		
		public function changeSecondName($new)
		{
			$this->changeField($new,"second_name");
		}
		 
		public function changeButhday($newDate)
		{
			if (checkDateFormat($newDate))
			{
				$this->changeField($newDate,"burthday");	
			}
		}
		
		public function changeLocation()
		{
		}
		
		private function changeField(&$new,$name)
		{
			if ($new!="")
			{
				$id=$this->_id;
				try
				{
					$this->_sql->query("UPDATE SITE_USERS SET $name='$new' WHERE id=$id");
					$_SESSION["user"]["$name"]=$new;
				}
				catch (Exception $exception)
				{
					die("USER INFO CHANGE: ".$exception->getMessage());
				}
			}
		}		 
				
	}
?>
