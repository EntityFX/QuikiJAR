<?php
/**
* ������.
* @package registry
* @author Solopiy Artem
* @version 1.0
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar) � 2010 
*/

	/**
	* ����� ������. ������ ���� ���� - ��������. �������� ����� ���� ������ ����. ������� �� ������ MySQL
	* @static
	*/
	abstract class Registry
	{
		/**
		* ������ ��� ������ � ��
		* 
		* @var MySQL
		*/
		private static $_sql=null; 
		
		/**
		* �������� �������� �����
		* 
		* @param String $key ����
		* @throws Exception ���� ���� �� ������
		* @return Mixed
		*/
		public static function getValue($key)
		{
			self::dbConnector();
			$r=self::$_sql->selFieldsWhere("REGISTRY","`key`='$key'","value_serealized");
			$serealizedVal=self::$_sql->GetRows($r);
			if ($serealizedVal==NULL)
			{
				throw new Exception("REGISTRY:: KEY '$key' IS NOT EXSIST");
			}
			return unserialize($serealizedVal[0]["value_serealized"]);
		}
		
		/**
		* ������� ����
		* 
		* @param String $key
		*/
		public static function deleteKey($key)
		{
			self::dbConnector();
			self::$_sql->delete("REGISTRY","`key`='$key'");
		}
		
		/**
		* �������� ������ ������ � ���� �������������� �������
		* 
		* @return Array
		*/
		public static function getKeys()
		{
			self::dbConnector();
			self::$_sql->selAll("REGISTRY");
			$out=null;
			if (self::$_sql->getTable()!=null)
			{
				foreach(self::$_sql->getTable() as $value)
				{
					$out[]=array("key" => $value["key"], "value" => unserialize($value["value_serealized"]));      
				}
			}
			return $out;
		}
		
		/**
		* ������� ������
		* 
		*/
		public static function clear()
		{
			self::dbConnector();
			self::$_sql->query("TRUNCATE `REGISTRY`");
		}
		
		/**
		* �������� ���� ���� �� �� ��������� ��� �������� ��� ��������
		* 
		* @param mixed $key
		* @param string $value
		*/
		public static function setKey($key,$value)
		{
			$value=serialize($value);
			self::dbConnector(); 
			try
			{
				self::$_sql->query("INSERT INTO `REGISTRY` VALUES ('$key','$value')");
			}
			catch (Exception $ex)
			{
				self::$_sql->query("UPDATE `REGISTRY` SET `value_serealized`='$value' WHERE `key`='$key'");        
			}
		}
		
		/**
		* ���������� � ��
		* 
		* @throws Exception ���������� ����������� � ��
		*/
		private static function dbConnector()
		{
			if (self::$_sql==null)
			{
				try
				{
					self::$_sql=MySQL::creator(DB_SERVER,DB_USER,DB_PASSWORD); 
					self::$_sql->selectDB(DB_NAME);
				}
				catch (Exception $dbEx)
				{
					throw new Exception("REGISTRY ERROR: ".$dbEx->getMessage());
				}
			}
		}
	}
?>
