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
        	$sql=new MySQL(Constants::DB_SERVER,Constants::DB_USER,Constants::DB_PASSWORD); 
            $sql->selectDB(Constants::DB_NAME);
            if ($visitor!=$user) 
            {
				$notanswered=1;
            }
			else 
			{
				$notanswered=0;
				$sql->Query("UPDATE `commentor` SET `notanswered`='0' WHERE `pid`='$id' AND `module`='$module'");
			}
            $result=$sql->query("INSERT INTO  `commentor` (  `id` ,  `module` ,  `pid` ,  `user` ,  `comment` ,  `comment_time` ,  `notanswered` ,  `poster_user` )
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
        	$sql=new MySQL(Constants::DB_SERVER,Constants::DB_USER,Constants::DB_PASSWORD); 
            $sql->selectDB(Constants::DB_NAME);
			$result=$sql->query("SELECT * FROM `commentor` WHERE `pid`='$id' AND `module`='$module'");
			while ($ar=$sql->fetchArr($result))
			{
				$resArr[]=$ar;
			}
		    if ($visitor==$user) 
            {
				$notanswered=0;
				$sql->Query("UPDATE `commentor` SET `notanswered`='0' WHERE `pid`='$id' AND `module`='$module'");
            }
            if (count($resArr)==0)
            {
            	throw new Exception("����������� �����������.");
            }
            $resArr=$this->listing($resArr, $listNum, 50);
            return $resArr;
		}
		
		/**
         * ��������� - ����� �������� ������ �� ����� �� $fileCount ����. 
         * @param Array $resourseArray �������� ������.
         * @param integer $listNum ����� �����.
         * @param integer $fileCount ���������� ��������� �� �����.
         * @return Array ������������� ������. + ��������� �������� listCount - ����� ���������� ������,
         *  listCurrent - ������� ����.
        */
        private function listing($resourseArray,$listNum, $fileCount)
        {
            if (count($resourseArray)>$fileCount)   
            {
                $listCount = ceil((count($resourseArray))/$fileCount); 
                if ($listNum==1 | $listNum=="")//���� ������ �� ������ ����
                {   
                    for($i=0; $i<$fileCount; $i++)
                    {   
                        $returnArray[$i]=$resourseArray[$i];
                    }

                    $returnArray["listCount"]=$listCount;
                    $returnArray["listCurrent"]=1;
                }
                if ($listNum>1 & $listNum<=$listCount) 
                {
                    $e=$fileCount*($listNum-1);
                    $f=($fileCount*$listNum)-1;
                    for ($b=$e;$b<=$f;$b++)  
                    {
                        if ($resourseArray[$b]!="")
                        {
                            $returnArray[]=$resourseArray[$b];    
                        }
                    }    
                    $returnArray["listCount"]=$listCount;
                    $returnArray["listCurrent"]=$listNum;
                }
                if ($listNum<1 | $listNum>$listCount)
                {   
                    if ($listNum!="")
                    {
                        throw new Exception("������ � ������. :( ");   
                    }
                    //$returnArray = listing($resourseArray,1,$fileCount);
                    /*$returnArray=$resourseArray;
                    $returnArray["listCount"]=$listCount;
                    $returnArray["listCurrent"]="1";*/ 
                }
            }
            else
            {
                $returnArray=$resourseArray;
            } 
            return $returnArray;            
        }
        
        public function readAllComments($user,$visitor,$listnum,$module,$arrayIds)
        {
        	
        }
    }
?>