<?php
/**
* Реестр.
* @package registry
* @author Solopiy Artem
* @version 1.0
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar) © 2010 
*/

	/**
	* Класс Реестр. Хранит пару ключ - значение. Значение может быть любого типа. Зависим от класса MySQL
	* @static
	*/
	abstract class Registry
	{
		/**
		* объект для работы с БД
		* 
		* @var MySQL
		*/
		private static $_sql=null; 
		
		/**
		* Получить значение ключа
		* 
		* @param String $key Ключ
		* @throws Exception Если ключ не найден
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
		* Удаляет ключ
		* 
		* @param String $key
		*/
		public static function deleteKey($key)
		{
			self::dbConnector();
			self::$_sql->delete("REGISTRY","`key`='$key'");
		}
		
		/**
		* Получить список ключей в виде ассоциативного массива
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
		* Очищает реестр
		* 
		*/
		public static function clear()
		{
			self::dbConnector();
			self::$_sql->query("TRUNCATE `REGISTRY`");
		}
		
		/**
		* Добавить ключ если он не сущетсует или изменить его значение
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
		* Соединение с БД
		* 
		* @throws Exception Невозможно соединиться с БД
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
