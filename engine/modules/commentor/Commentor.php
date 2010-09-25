<?php
require_once "engine/libs/mysql/MySQLConnector.php"; 
require_once "engine/modules/numerator/Numerator.php"; 

/**
 * ����� ������������ ��� ������, ������ ������������.
 * 
 * @author ����� 29.08.10 <gtimur666@gmail.com>
 * @version 1.0
*/
    class Commentor extends MySQLConnector
    {
		/**
		 * ������� ���������� ����������� � ������-���� ������.
		 * @param integer $id ����� ��������.
		 * @param string $module ����� ��� �������� ������.
		 * @param integer $visitor ����� ����������.
		 * @param string $comment ����������� ����������.
		 * @param integer $user ����� ������������, � �������� ������������ �������.
		 * @todo ����������� ������ <b>���������</b> ��� <b>��������</b> �������.
		 * @return boolean ���������� TRUE � ������, ���� ����������� ������� ��������, ����� - FALSE.
		*/
    	public function writeComment ($id, $module, $visitor,$comment, $user)
		{
        	 
            
            if ($visitor!=$user) 
            {
				$notanswered=1;
            }
			else 
			{
				$notanswered=0;
				$this->_sql->Query("UPDATE `commentor` SET `notanswered`='0' WHERE `pid`='$id' AND `module`='$module'");
			}
            $result=$this->_sql->query("INSERT INTO  `commentor` (  `id` ,  `module` ,  `pid` ,  `user` ,  `comment` ,  `comment_time` ,  `notanswered` ,  `poster_user` )
            VALUES ('',  'galary',  '$id',  '$user',  '$comment', NOW( ) ,  '$notanswered',  '$visitor')"); 
			if ($result!=0)
			{
				$ret=TRUE;
			}    		
			else 
			{
				$ret=FALSE;
			}	
            return $ret;
		}
		
		/**
		 * ������� ��������� ������������ � ������ ��������: ��� ��������� ������������ �������� �������� �������� ����� � ���� 
		 * ������������� ������������ �������� � ���, ��� ��� ���������.
		 * @param integer $id ����� ��������. 
		 * @param string $module ��������/����� ������.
		 * @param integer $listNum ����� �����.
		 * @param integer $user ����� ���������������� ������������.
		 * @param integer $visitor ����� ���������������� ������������.
		 * @return Array ������������� ������.
		*/
		public function readComments ($id, $module, $listNum, $user, $visitor)
		{
        	 
            
			$result=$this->_sql->query("SELECT * FROM `commentor` WHERE `pid`='$id' AND `module`='$module'");
			while ($ar=$this->_sql->fetchArr($result))
			{
				$resArr[]=$ar;
			}
		    if ($visitor==$user) 
            {
				$notanswered=0;
				$this->_sql->Query("UPDATE `commentor` SET `notanswered`='0' WHERE `pid`='$id' AND `module`='$module'");
            }
            if (count($resArr)==0)
            {
            	throw new Exception("����������� �����������.");
            }
            $resArr=$this->listing($resArr, $listNum, 50);
            return $resArr;
		}

        
        public function readAllComments($user,$visitor,$listnum,$module,$arrayIds)
        {
        	
        }
    }
?>