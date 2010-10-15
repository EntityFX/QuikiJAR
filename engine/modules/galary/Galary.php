<?php
require_once "engine/libs/mysql/MySQLConnector.php";
require_once "engine/modules/numerator/Numerator.php";
require_once "engine/libs/fs/FS.php";

/**
 * �����, ���������� � ���������.
 * @author ����� 06.08.10 <gtimur666@gmail.com>
 * @version 1.0
 */
class Galary extends MySQLConnector
{
	/**
	 * ���������� ������ ������� �� ������� `galary_list` � ����������� ������������ $user
	 * @param integer $user ����� ������������, � �������� ������������� ������ ��������.
	 * @param integer $visitor ����� ������������, ���������������� ������ ��������.
	 * @param integer|string $listNum - ����� ����� � ������ ���� �������� �����. ����� ����� ������ ��������,
	 * �������� ��������, ���� �������� <b>"all"</b>
	 * @return Array ���������� ������������� ������.
	 */
	public function showGalariesList($user, $visitor, $listNum)
	{
		$result=$this->_sql->query("SELECT * FROM `galary_list` WHERE `user`='$user' ORDER BY `pos` ASC");
		while ($array=$this->_sql->fetchArr($result))
		{
			//��� ������ ��������, �� ������ �� ������ ����, � ����� �� ���������
			//�� ������� � ������� �����������
			//������ ����������� ��������� � ������� galary_list � ����� sequrity.
			//����� ������������ ������������� ����������� �������� ";"
			if (count($array)!=0)
			{
				if ($this->checkSQRTY($visitor,$array["sequrity"],$array["trusted"]))
				{
					$array["cover"]=$this->getPreviewPathById($array["cover"]);
					$resArr[$array["id"]]=$array;
				}
			}
			else
			{
				throw new Exception("������� �����������. :( ");
			}
		}
		if (count($resArr)==0) throw new Exception("������� �����������. :( ");
		$resArr = listing($resArr,$listNum,50); //���������, ���������� �� 50 �������� �� ����
		return $resArr;
	}

	/**
	 * ������� ��������� ���� � ������-����� �� ������ $id
	 * @param integer $id ����� ��������
	 * @return string ���� � ������-�����
	 */
	public function getPreviewPathById($id)
	{
		$result=$this->_sql->query("SELECT * FROM `galary_files` WHERE `id`='$id'");
		$resArr =  $this->_sql->fetchArr();
		$res = $resArr["small_path"];
		return $res;
	}

	/**
	 * �������� �����������.
	 * @param integer $visitorname ����� ����������
	 * @param string $ignoreStr ������ � ������������� ������������ �������������/������� ������������� (������)
	 * @param string $trustStr ������ � ������������� ���������� �������������/������� ������������� (������)
	 * @return boolean ���������� TRUE, ���� ����������� �����������, ����� FALSE
	 */
	private function checkSQRTY($visitorname, $ignoreStr, $trustStr)
	{
		$ignoreArr=explode(";", $ignoreStr);
		$ignState=0;
		foreach($ignoreArr as $index => $val)
		{
			if ($val==$visitorname)
			{
				$ignState= 1;
			}
		}

		$trustArr=explode(";", $trustStr);
		$trustState=0;
		foreach($trustArr as $index => $val)
		{
			if ($val==$visitorname)
			{
				$trustState= 1;
			}
		}

		if ($ignState==1 && $trustState==0)
		{
			$ret=FALSE;
		}
		else
		{
			$ret=TRUE;
		}

		if ($ignoreStr=="-1")  //������ ��� ����
		{
			$ret=FALSE;
		}
		return $ret;
	}

	/**
	 * ������� ��������� ������ � ������� $altname
	 * @param integer $visitor ����� ����������
	 * @param integer $altname ����� ������� � ������� `galary_list`. ���� �������� ����� -1, �� �������� ������ ���� ������ �����
	 * @param integer $listNum ����� �����
	 * @param integer $user - id �����
	 * @return Array ���������� ������������� ������.
	 */
	public function showGalary($visitor,$altname,$listNum,$user)
	{
		$result=$this->_sql->query("SELECT * FROM `galary_list` WHERE `id`='$altname'");

		$array=$this->_sql->fetchArr($result);
		if (count($array)!=0)
		{
			if ($this->checkSQRTY($visitor,$array["sequrity"],$array["trusted"])) //�������� �� �����������
			{
				//�������� ������, ������� � ������� � ������� ���� �� ����� � ������
				$result=$this->_sql->query("SELECT * FROM `galary_files` WHERE `pid`='$altname' ORDER BY `pos` ASC");
				while ($ar=$this->_sql->fetchArr($result))
				{
					if (count($ar)!=0)
					{
						$resArr[$ar["id"]]=$ar;
					}
					else
					{
						throw new Exception("���-�� �� ���");
					}
				}
				//���������
				if ($listNum!="all")
				{
					$resArr= listing($resArr,$listNum,20);
				}
			}
			else
			{   //�������� �� ������, ���������� ������
				throw new Exception("� ������������ ��� ������ �������. ��� � ������ ������� ��� ������ �������. ��������� ������ ;)");

			}
		}
		else
		{
			throw new Exception("� ������������ ��� ������ �������. ��� � ������ ������� ��� ������ �������. ��������� ������ ;)");
		}
		return $resArr;
	}

	//��������� �������� �����
	//������� ������: ������ �������� (100 �), ����� ����� (1 ��), ���������� �� ����� (�� ����). ��������� ���������� � ������ ��������


	/**
	 * ������� ��������� ��������� ����������.
	 * @param integer $user ����� ������������.
	 * @param integer $visitor ����� ����������.
	 * @param integer $altname ����� �������.
	 * @param integer $id ����� ��������.
	 * @return Array ������������� ������ � ������� � ����������, ������� � ��������� ��������.
	 */
	public function showPhoto($user, $visitor, $altname, $id)
	{
		$result=$this->_sql->query("SELECT * FROM `galary_list` WHERE `id`='$altname'"); //��� �������� �� �����������
		$array=$this->_sql->fetchArr($result);
		if (count($array)!=0)
		{
			if ($this->checkSQRTY($visitor,$array["sequrity"],$array["trusted"]))
			{
				$pid=$array["id"];
				$i=0;
				$currPhoto=-1;
				$qResult=$this->_sql->query("SELECT * FROM `galary_files` WHERE `pid`='$pid' ORDER BY `pos` ASC");//������� ���� ������ � �������
				while ($ar=$this->_sql->fetchArr($qResult))
				{
					if (count($ar)!=0)
					{
						$photoArr[]=$ar;
						if ($ar["id"]==$id)
						{
							$currPhoto=$i;//����� ����� �� ������ ���������� ����� � �������
							//die("$ar[id]");
						}
						$i++;
					}
					else
					{
						throw new Exception("���������� ��� �� ���������. :( ");
					}
				}

				if ($currPhoto!=-1)
				{
					$resArr["current"]=$photoArr[$currPhoto];
					$countArr=count($photoArr);
					if ($currPhoto==0)    //��������� ����� ����� ��������
					{
						$resArr["previous"]=$photoArr[$countArr-1]; //���� ������� ������ �����, �� ���������� ����� ����� ��������� ����� � �������
						$resArr["next"]=$photoArr[$currPhoto+1];// � ��������� ����� "������� + 1"
					}
					if ($currPhoto==$countArr-1)
					{
						$resArr["previous"]=$photoArr[$currPhoto-1];
						$resArr["next"]=$photoArr[0];
					}
					if ($currPhoto!=0 && $currPhoto!=$countArr-1)
					{
						$resArr["previous"]=$photoArr[$currPhoto-1];
						$resArr["next"]=$photoArr[$currPhoto+1];
					}
				}
				else
				{
					throw new Exception("���-�� ����� �� ���!");
				}

				//�������� ��������� �� �����������. ���� �����������, �� �������� �� �������� "0". ������������� ����������� ����� �������� "1"
				//��� ��� �������� � ��� ������, ���� ������� ���������.
				$tempArr=$resArr["current"];
				if ($visitor==$user)
				{
					if ($tempArr["isreadedcomments"]==1)
					{
						$this->_sql->query("UPDATE `galary_files` SET `isreadedcomments`='0' WHERE `id`='$id' ");
					}
				}
				else
				{}
			}
			else
			{
				throw new Exception("������ ������� �� ����������. :( ");
			}
		}
		else
		{
			throw new Exception("������ ������� �� ����������. :( ");
		}
		return $resArr;
	}


	//�������� ����������� � ��������� �����
	//������� ������: id �����.
	//�������� ������: ������ �����������. �����,�����,����� ���������, ��������� ��� ���.
	/*
	 * ������� ��������� ������������ � ����������
	 * @param $pid
	 * @param $user
	 * @param $altname
	 * @param $visitor
	 */
	/* public function showComments ($pid, $user,$altname,$visitor)
	 {
	 $result=$this->_sql->query("SELECT * FROM `galary_list` WHERE `id`='$altname'"); //��� �������� �� �����������
	 $array=$this->_sql->fetchArr($result);
	 if (count($array)!=0)
	 {
	 if ($this->checkSQRTY($visitor,$array["sequrity"],$array["trusted"]))
	 {
	 $result2=$this->_sql->query("SELECT * FROM `galary_comments` WHERE `pid`='$pid'"); //���� ��� ����������� � ����
	 while ($ar=$this->_sql->fetchArr($result2))
	 {
	 $returnArr[]=$ar;
	 if ($ar["notanswered"]==1)
	 {
	 $this->_sql->query("UPDATE `galary_comments` SET `notanswered`='0' WHERE `pid`='$pid'");
	 }
	 }
	 }
	 else
	 {
	 throw new Exception("����������� ��������������� ����������. :( ");
	 }
	 }
	 else
	 {
	 throw new Exception("����� ������ �� ����������. :( ");
	 }
	 return $returnArr;
	 }
	 */
	/*
	 ���������� ������ ����������� � ���������.
	 ������� ���������: ����� �����, ��� �����, ��� ����������, ����� ������� - ���� �� ����� ���� �� �������� ��� �����������
	 �������� ���������: ������, ����� �����������, ���� �����������, �������� ��� ���, ��� �����������, ��������� - ������� �� ���� � �����
	 */ /*
	 public function showCommentsAndPreview($user,$altname,$visitor, $listnum)
	 {
	 $result=$this->_sql->query("SELECT * FROM `galary_list` WHERE `id`='$altname'"); //��� �������� �� �����������
	 $array=$this->_sql->fetchArr($result);
	 if (count($array)!=0)
	 {
	 if ($this->checkSQRTY($visitor,$array["sequrity"],$array["trusted"])) //�������� �����������
	 {
	 $result2 = $this->_sql->query("SELECT * FROM `galary_comments` WHERE `user`='$user' ORDER BY `datetime` ASC"); //���� ��� ����������� � ������� �����
	 while ($ar=$this->_sql->fetchArr($result2))
	 {
	 if ($this->checkSQRTY($visitor,$ar["sequrity"],$ar["trusted"]))
	 {
	 $ar["small"]=$this->getPreviewPathById($ar["pid"]);
	 $retArr[]=$ar;
	 }
	 else
	 {
	 throw new Exception("����������� ��������������� ����������. :( ");
	 }
	 }
	 }
	 else
	 {
	 throw new Exception("������ ������� �� ����������, ���� ��� ��������� ���������� ����������� �����������. :( ");
	 }
	 }
	 else
	 {
	 throw new Exception("����� ������ �� ����������. :( ");
	 }
	 $retArr2= $this->listing($retArr,2,50);
	 return $retArr2;
	 }*/

	/*
	 ���������� �������� ��������� �����������.
	 �������� ������ � ��������� ���������!
	 */
	public function getPrivateState($visitor, $id)
	{
		$result=$this->_sql->query("SELECT * FROM `galary_list` WHERE `id`=(SELECT `pid` FROM `galary_files` WHERE `id`='$id')"); //��� �������� �� �����������
		$array=$this->_sql->fetchArr($result);
		$ret = $this->checkSQRTY($visitor,$array["sequrity"],$array["trusted"]);
		return $ret;
	}

	public function getGalaryIDs($altname)
	{
		$result=$this->_sql->query("SELECT * FROM `galary_files` WHERE `pid`='$altname'");
		while ($array=$this->_sql->fetchArr($result))
		{
			$retArr[]=$array;
		}
		return $retArr;
	}

	public function addNewGalary($user, $newGalaryName, $comment)
	{
		$newGalaryName = htmlspecialchars($newGalaryName);
		$comment = htmlspecialchars($comment);
		 
		$result=$this->_sql->query("SELECT MAX(`pos`) FROM `galary_list` WHERE `user`='$user'");
		$maxPos=$this->_sql->fetchArr($result);
		$pos=$maxPos["MAX(`pos`)"]+1;
		 
		$result=$this->_sql->query("INSERT INTO `galary_list`
        	(`id`, `user`, `name`, `type`, `comment`, `createdate`, `modified`, `sequrity`, `cover`, `sqcomment`, `pos`, `trusted`) 
        	VALUES ('', '$user', '$newGalaryName', NULL, '$comment', NOW(), NULL, '', NULL, NULL, '$pos', '')");
		$res = $this->_sql->query("SELECT * FROM `galary_list` WHERE `user`='$user' AND `pos`='$pos'");
		$temp = $this->_sql->fetchArr($res);
		$id = $temp['id'];
		return $id;
	}

	public function deleteGalary($id, $user)
	{
		$result = $this->deleteElement("galary", $id, $user);
		return $result;
	}
	/**
	 * �������� �������� �� �������. (������� ��������� ����, ��������� �������)
	 * @param string $elType - ��� ���������� ��������. ��������� ��������: "galary", "photo"
	 * @param integer $id - id ���������� ��������.
	 * @param integer $user - ����, � �������� ������� ��������. ���������� ��� ����, ����� ������������������ ������������
	 * �� ������ ����� ������
	 * @todo �������� ������ �������� ����� ������ ����� ������
	 */
	private function deleteElement($elType, $id, $user)
	{
		$deletingFile = new FS();
		switch ($elType)
		{
			case "galary":
				$result=$this->_sql->query("SELECT * FROM `galary_files` WHERE `pid` = '$id'");
				while ($temp = $this->_sql->fetchArr($result))
				{
					$path = $temp["path"];
					$smallPath = $temp["small_path"];
					$deletingFile->deleteSmthg($path);
					$deletingFile->deleteSmthg($smallPath);
					$fID = $temp["id"];
					$result=$this->_sql->query("DELETE FROM `galary_files` WHERE `id` = '$fID'");
				}
				return $result=$this->_sql->query("DELETE FROM `galary_list` WHERE `id` = '$id' AND `user`='$user'"); 
				break;
			default:
				break;
		}
	}

	public function getImgProperties($id)
	{
		$result = $this->_sql->query("SELECT * FROM `galary_files` WHERE `id`='$id'");
		$temp = $this->_sql->fetchArr($result);
		$resArr["altname"]=$temp["pid"];
		$pid=$temp["pid"];
		$resArr["id"]=$temp["id"];
		 
		$result = $this->_sql->query("SELECT * FROM `galary_list` WHERE `id`='$pid'");
		$temp2 = $this->_sql->fetchArr($result);
		$resArr["user"]=$temp2["user"];
		return $resArr;
	}

	public function addPhoto($user, $altname, $path)
	{
		$fss = new FS();
		$arr = $this->giveMeNewName($path);
		 
		$newN = $arr["path2new"];
		 
		$fss->renameElement($path, $arr["name"]);
		$res = $this->_sql->query("SELECT MAX(`pos`) FROM `galary_files` WHERE `pid`='$altname'");
		$temp = $this->_sql->fetchArr($res);
		$newPos = $temp["MAX(`pos`)"]+1;
		$result = $this->_sql->query("INSERT INTO `galary_files`
        	( `id` , `path` , `small_path` , `text` , `pos` , `isreadedcomments` , `createdate` , `comment` , `pid` , `type` , `productname` , `content` , `cost` )
			VALUES (
			'', '$newN', '$newN', NULL , '$newPos' , '', NOW( ) , NULL , '$altname', NULL , NULL , NULL , NULL)");
		return $result;
	}

	private function giveMeNewName($path)
	{
		$result = $this->_sql->query("SELECT MAX(`id`) FROM `galary_files`");
		$temp3 = $this->_sql->fetchArr($result);
		$max = $temp3["MAX(`id`)"];
		 
		$newName = md5($max);
		$temp1 = explode("/",$path);
		$temp2 = explode(".", $temp1[count($temp1)-1]);
		$ext = $temp2[count($temp2)-1];
		$newName = $newName.".".$ext; //��� �����
		$temp1[count($temp1)-1] = $newName;
		$newN = implode("/", $temp1);//die(var_dump($newN));  ���� � ������ �����
		 
		$ret["name"] = $newName;
		$ret["path2new"] = $newN;

		return $ret;
	}

	public function getGalaryPropertise($altname)
	{
		$result = $this->_sql->query("SELECT * FROM `galary_list` WHERE `id`='$altname'");
		$resArr = $this->_sql->fetchArr($result);
		return $resArr;
	}

	public function updateGalaryProperties($altname, $newName, $newComment, $newBlackList, $newTruthList)
	{
		if ($altname!=0 && $newName!="")
		{
			$newName = htmlspecialchars($newName);
			return $this->_sql->query("UPDATE `galary_list` SET `name`='$newName', `comment`='$newComment',
        		`sequrity` = '$newBlackList', `trusted` = '$newTruthList' WHERE `id`='$altname'");
		}
		else
		{
			return FALSE;
		}
	}
}
?>