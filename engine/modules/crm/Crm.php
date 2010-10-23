<?php 
require_once "engine/libs/mysql/MySQLConnector.php"; 
class Crm extends MySQLConnector
{
	/**
	 * ������ ������� ���������, � ����������� �� ���� (�� ������� � ������)
	 * @return array ������������� ������
	 */
	public function readTable()
	{
		$result = $this->_sql->query("SELECT * FROM `crm_main_table` WHERE `id`>'0' ORDER BY `add_time` ASC");
		while ($temp = $this->_sql->fetchArr($result))
		{
			$resArr[] = $temp;
		}
		return $resArr;
	}
	
	/**
	 * ��������� ������ �������� � ������� ���������
	 */
	public function getCollNames()
	{
		$result = $this->_sql->query("SELECT * FROM `crm_main_table` WHERE `id`='0'");
		$resArr = $this->_sql->fetchArr($result);
		return $resArr;
	}
	
	/**
	 * ���������� ������ �������
	 * @param string $name - ��� ������� � ������� (����������)
	 * @param string $rusName - ��� ������� � ������� (�� �������)
	 * @param string $type - ��� ����������
	 */
	public function addNewColl($name, $rusName, $type)
	{
		$name = $this->validateStr($name);
		$rusName = $this->validateStr($rusName);
		$result1 = $this->_sql->query("ALTER TABLE `crm_main_table` ADD `$name` VARCHAR( 1000 ) NOT NULL ");
		$result2 = $this->_sql->query("UPDATE `crm_main_table` SET `$name`='$rusName' WHERE `id`='0'");
		
		return $result1;
	}
	
	/**
	 * ��������� �����
	 * @param string $str - ������ ��� ��������
	 * @todo �������� ��������, �� ������.
	 */
	public function validateStr($str)
	{
		return $str;
	}
	
	/**
	 * �������� ������� �� �������
	 * @param string $name - �������� ������� (�� ���������)
	 */
	public function deleteColl($name)
	{
		$name = $this->validateStr($str);
		return $result = $this->_sql->query("ALTER TABLE `crm_main_table` DROP `$name`");
	}
	
	public function todayThings()
	{
		$todayDateTime = date("Y-m-d 00:00:00");
		$tomorowDateTime = date("Y-m-d 23:59:59");
		$result = $this->_sql->query("SELECT * FROM `crm_main_table` WHERE `todo_time`>'$todayDateTime' AND `todo_time` < '$tomorowDateTime'");
		//die("SELECT * FROM `crm_main_table` WHERE `todo_time`>'$todayDateTime' AND `todo_time` < '$tomorowDateTime'");
		while ($arr = $this->_sql->fetchArr($result))
		{
			$resArr[] = $arr;
		}
		return $resArr;
	}
	
	public function futureORlateThings($str)
	{
		if ($str=="f")
		{
			$todayDateTime = date("Y-m-d 23:59:59");
			$sign = ">";
		}
		if ($str=="l")
		{
			$todayDateTime = date("Y-m-d 00:00:00");
			$sign = "<";
		}
		$todayDateTime = date("Y-m-d 23:59:59");
		$result = $this->_sql->query("SELECT * FROM `crm_main_table` WHERE `todo_time` $sign '$todayDateTime'
		AND `id`>'0' ORDER BY `add_time` ASC");
		//die("");
		while ($temp = $this->_sql->fetchArr($result))
		{
			$resArr[]=$temp;
		}
		return $resArr;
	}
	
	
}

?>