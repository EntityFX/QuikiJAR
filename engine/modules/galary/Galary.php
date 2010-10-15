<?php
require_once "engine/libs/mysql/MySQLConnector.php";
require_once "engine/modules/numerator/Numerator.php";
require_once "engine/libs/fs/FS.php";

/**
 * Класс, работающий с галереями.
 * @author Тимур 06.08.10 <gtimur666@gmail.com>
 * @version 1.0
 */
class Galary extends MySQLConnector
{
	/**
	 * Показывает список галерей из таблицы `galary_list` у конкретного пользователя $user
	 * @param integer $user номер пользователя, у которого просматривают список альбомов.
	 * @param integer $visitor номер пользователя, просматривающего список альбомов.
	 * @param integer|string $listNum - номер листа в случае если альбомов много. Может иметь пустое значение,
	 * цифровое значение, либо значение <b>"all"</b>
	 * @return Array Возвращает ассоциативный массив.
	 */
	public function showGalariesList($user, $visitor, $listNum)
	{
		$result=$this->_sql->query("SELECT * FROM `galary_list` WHERE `user`='$user' ORDER BY `pos` ASC");
		while ($array=$this->_sql->fetchArr($result))
		{
			//Для начала проверим, не пустой ли массив ваще, а потом не находится
			//ли визитер в списках ограничения
			//Списки ограничения находятся в таблице galary_list в графе sequrity.
			//Имена ограниченных пользователей разделяются символом ";"
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
				throw new Exception("Альбомы отсутствуют. :( ");
			}
		}
		if (count($resArr)==0) throw new Exception("Альбомы отсутствуют. :( ");
		$resArr = listing($resArr,$listNum,50); //нумератор, показывать по 50 альбомов на лист
		return $resArr;
	}

	/**
	 * Функция получения пути к превью-файлу по номеру $id
	 * @param integer $id номер элемента
	 * @return string путь к превью-файлу
	 */
	public function getPreviewPathById($id)
	{
		$result=$this->_sql->query("SELECT * FROM `galary_files` WHERE `id`='$id'");
		$resArr =  $this->_sql->fetchArr();
		$res = $resArr["small_path"];
		return $res;
	}

	/**
	 * Проверка ограничений.
	 * @param integer $visitorname номер посетителя
	 * @param string $ignoreStr строка с перечислением игнорируемых пользователей/списков пользователей (номера)
	 * @param string $trustStr строка с перечислением доверенных пользователей/списков пользователей (номера)
	 * @return boolean возвращает TRUE, если отсутствуют ограничения, иначе FALSE
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

		if ($ignoreStr=="-1")  //закрыт для всех
		{
			$ret=FALSE;
		}
		return $ret;
	}

	/**
	 * Функция просмотра файлов в галерее $altname
	 * @param integer $visitor номер посетителя
	 * @param integer $altname номер альбома в таблице `galary_list`. Если значение равно -1, то выдается список всех файлов юзера
	 * @param integer $listNum номер листа
	 * @param integer $user - id юзера
	 * @return Array Возвращает ассоциативный массив.
	 */
	public function showGalary($visitor,$altname,$listNum,$user)
	{
		$result=$this->_sql->query("SELECT * FROM `galary_list` WHERE `id`='$altname'");

		$array=$this->_sql->fetchArr($result);
		if (count($array)!=0)
		{
			if ($this->checkSQRTY($visitor,$array["sequrity"],$array["trusted"])) //проверка на приватность
			{
				//проверку прошли, заходим в таблицу и смотрим есть ли файлы в альбом
				$result=$this->_sql->query("SELECT * FROM `galary_files` WHERE `pid`='$altname' ORDER BY `pos` ASC");
				while ($ar=$this->_sql->fetchArr($result))
				{
					if (count($ar)!=0)
					{
						$resArr[$ar["id"]]=$ar;
					}
					else
					{
						throw new Exception("Что-то не так");
					}
				}
				//нумератор
				if ($listNum!="all")
				{
					$resArr= listing($resArr,$listNum,20);
				}
			}
			else
			{   //проверку не прошли, показываем ошибку
				throw new Exception("У пользователя нет такого альбома. Или у такога альбома нет такого хозяина. Проверьте ссылку ;)");

			}
		}
		else
		{
			throw new Exception("У пользователя нет такого альбома. Или у такога альбома нет такого хозяина. Проверьте ссылку ;)");
		}
		return $resArr;
	}

	//нумератор делается здесь
	//входные данные: массив исходный (100 г), номер листа (1 шт), количество на листе (на глаз). Тщительно перемешать и подать холодным


	/**
	 * Функция просмотра отдельной фотографии.
	 * @param integer $user номер пользователя.
	 * @param integer $visitor номер посетителя.
	 * @param integer $altname номер альбома.
	 * @param integer $id номер элемента.
	 * @return Array ассоциативный массив с данными о предыдущем, текущем и следующем элементе.
	 */
	public function showPhoto($user, $visitor, $altname, $id)
	{
		$result=$this->_sql->query("SELECT * FROM `galary_list` WHERE `id`='$altname'"); //для проверки на приватность
		$array=$this->_sql->fetchArr($result);
		if (count($array)!=0)
		{
			if ($this->checkSQRTY($visitor,$array["sequrity"],$array["trusted"]))
			{
				$pid=$array["id"];
				$i=0;
				$currPhoto=-1;
				$qResult=$this->_sql->query("SELECT * FROM `galary_files` WHERE `pid`='$pid' ORDER BY `pos` ASC");//соберем весь массив с фотками
				while ($ar=$this->_sql->fetchArr($qResult))
				{
					if (count($ar)!=0)
					{
						$photoArr[]=$ar;
						if ($ar["id"]==$id)
						{
							$currPhoto=$i;//чтобы потом не искать записываем номер в массиве
							//die("$ar[id]");
						}
						$i++;
					}
					else
					{
						throw new Exception("Фотографии еще не загружены. :( ");
					}
				}

				if ($currPhoto!=-1)
				{
					$resArr["current"]=$photoArr[$currPhoto];
					$countArr=count($photoArr);
					if ($currPhoto==0)    //проверить потом здесь значения
					{
						$resArr["previous"]=$photoArr[$countArr-1]; //если смотрим первую фотку, то предыдущей будет самая последняя фотка в альбоме
						$resArr["next"]=$photoArr[$currPhoto+1];// а следующей будет "текущая + 1"
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
					throw new Exception("Что-то пошло не так!");
				}

				//проверка прочитаны ли комментарии. если непрочитаны, то меняется на значение "0". непрочитанный комментарии имеют значение "1"
				//все это менятеся в том случае, если заходит Создатель.
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
				throw new Exception("Такого альбома не существует. :( ");
			}
		}
		else
		{
			throw new Exception("Такого альбома не существует. :( ");
		}
		return $resArr;
	}


	//показать комментарии к отдельной фотке
	//входные данные: id фотки.
	//выходные данные: массив многомерный. автор,время,текст сообщения, прочитано или нет.
	/*
	 * Функция просмотра комментариев к фотографии
	 * @param $pid
	 * @param $user
	 * @param $altname
	 * @param $visitor
	 */
	/* public function showComments ($pid, $user,$altname,$visitor)
	 {
	 $result=$this->_sql->query("SELECT * FROM `galary_list` WHERE `id`='$altname'"); //для проверки на приватность
	 $array=$this->_sql->fetchArr($result);
	 if (count($array)!=0)
	 {
	 if ($this->checkSQRTY($visitor,$array["sequrity"],$array["trusted"]))
	 {
	 $result2=$this->_sql->query("SELECT * FROM `galary_comments` WHERE `pid`='$pid'"); //ищем все комментарии к фото
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
	 throw new Exception("Возможность комментирования ограничена. :( ");
	 }
	 }
	 else
	 {
	 throw new Exception("Такой альбом не существует. :( ");
	 }
	 return $returnArr;
	 }
	 */
	/*
	 показывать только комментарии и превьюшки.
	 входные параметры: номер листа, имя юзера, имя посетителя, номер альбома - если он равен нулю то показать все комментарии
	 выходные параметры: массив, автор комментария, дата комментария, прочитан или нет, сам комментарий, айдишники - таблица из базы в общем
	 */ /*
	 public function showCommentsAndPreview($user,$altname,$visitor, $listnum)
	 {
	 $result=$this->_sql->query("SELECT * FROM `galary_list` WHERE `id`='$altname'"); //для проверки на приватность
	 $array=$this->_sql->fetchArr($result);
	 if (count($array)!=0)
	 {
	 if ($this->checkSQRTY($visitor,$array["sequrity"],$array["trusted"])) //проверка приватности
	 {
	 $result2 = $this->_sql->query("SELECT * FROM `galary_comments` WHERE `user`='$user' ORDER BY `datetime` ASC"); //ищем все комментарии у данного юзера
	 while ($ar=$this->_sql->fetchArr($result2))
	 {
	 if ($this->checkSQRTY($visitor,$ar["sequrity"],$ar["trusted"]))
	 {
	 $ar["small"]=$this->getPreviewPathById($ar["pid"]);
	 $retArr[]=$ar;
	 }
	 else
	 {
	 throw new Exception("Возможность комментирования ограничена. :( ");
	 }
	 }
	 }
	 else
	 {
	 throw new Exception("Такого альбома не существует, либо его видимость ограничена настройками приватности. :( ");
	 }
	 }
	 else
	 {
	 throw new Exception("Такой альбом не существует. :( ");
	 }
	 $retArr2= $this->listing($retArr,2,50);
	 return $retArr2;
	 }*/

	/*
	 возвращает итоговый результат приватности.
	 работает только с отдельным элементом!
	 */
	public function getPrivateState($visitor, $id)
	{
		$result=$this->_sql->query("SELECT * FROM `galary_list` WHERE `id`=(SELECT `pid` FROM `galary_files` WHERE `id`='$id')"); //для проверки на приватность
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
	 * Удаление элемента из таблицы. (удаляет отдельный файл, отдельную галерею)
	 * @param string $elType - тип удаляемого элемента. Возможные значения: "galary", "photo"
	 * @param integer $id - id удаляемого элемента.
	 * @param integer $user - юзер, у которого ведется удаление. Необходимо для того, чтобы зарегистрированный пользователь
	 * не удалил чужие данные
	 * @todo подумать насчет удаления самих файлов прямо отсюда
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
		$newName = $newName.".".$ext; //имя файла
		$temp1[count($temp1)-1] = $newName;
		$newN = implode("/", $temp1);//die(var_dump($newN));  путь к новому файлу
		 
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