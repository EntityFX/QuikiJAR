<?php
	class UserStatus extends MySQLConnector
	{
		private $_id;
		
		public function __construct($id=NULL)	
		{
			parent::__construct();
			if ($id==NULL)
			{
				$user=new User();
				$this->_id=$user->id;
			}
			else
			{
				$this->_id=(int)$id;
			}
		}
		
		public function getStatus()
		{
			$id=$this->_id;
			if (!isset($_SESSION["user"]["status"]))
			{
				$qRes=$this->_sql->selFieldsWhere("SITE_USERS","`id`=$id","status");
				$arr=$this->_sql->GetRows($qRes);
				return $arr[0]["status"];
			}
			else
			{
				return $_SESSION["user"]["status"];
			}
		}
		
		public function setStatis($value)
		{
			$id=$this->_id;
			$this->_sql->query("UPDATE `SITE_USERS` SET `status`='$value' WHERE `id`=$id");
			$_SESSION["user"]["status"]=$value;
		}
	}
?>
