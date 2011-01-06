<?php
require_once "engine/libs/mysql/MySQLConnector.php";
require_once "engine/modules/numerator/Numerator.php";
require_once "engine/libs/fs/FS.php";
require_once "engine/modules/commentor/Commentor.php";

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
					if ($array["auto_cover"]==0)//поставлен НЕавтоматический режим простановки обложки альбома
					{
						if ($array["cover"]!==NULL)//узнал сегодня о вариантах появления вселенной и ее возможной смерти.. грустно.. 15.10.2010
						{
							$array["cover"]=$this->getPreviewPathById($array["cover"]);
						}
						else //если все же ничегошеньки нет, то рандомно выбрать
						{
							$array["cover"]=$this->randomCover($array["id"]);
						}
					}
					else 
					{
						$array["cover"]=$this->randomCover($array["id"]);
					}
					$resArr[$array["id"]]=$array;
				}
			}
			else
			{
				//throw new Exception("Альбомы отсутствуют. :( ");
			}
		}
		//if (count($resArr)==0) throw new Exception("Альбомы отсутствуют. :( ");
		$resArr = listing($resArr,$listNum,50); //нумератор, показывать по 50 альбомов на лист
		return $resArr;
	}

	/**
	 * Получение обложки альбома наугад
	 * @param integer $altname - номер альбома
	 * @return путь к файлу
	 */
	public function randomCover($altname)
	{
		$result = $this->_sql->query("SELECT count(*) FROM `galary_files` WHERE `pid`='$altname'");
		$temp1 = $this->_sql->fetchArr($result);
		$temp1['count(*)']!=1 ? $pos = rand(1,$temp1['count(*)']) : $pos = 1; 
		$result2 = $this->_sql->query("SELECT * FROM `galary_files` WHERE `pos`='$pos' AND `pid`='$altname'");
		$temp2 = $this->_sql->fetchArr($result2); 
		$cover = $this->getPreviewPathById($temp2["id"]);
		return $cover;
		
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

		$fss = new FS();
		$fss->_d->createPath("/photos/$user/mini/");
		$fss->_d->createPath("/photos/$user/galary/");
		$result=$this->_sql->query("SELECT MAX(`pos`) FROM `galary_list` WHERE `user`='$user'");
		$maxPos=$this->_sql->fetchArr($result);
		$pos=$maxPos["MAX(`pos`)"]+1;
		 
		$result=$this->_sql->query("INSERT INTO `galary_list`
        	(`id`, `user`, `name`, `type`, `comment`, `createdate`, `modified`, `sequrity`, `cover`, `sqcomment`, `pos`, `trusted`, `auto_cover`) 
        	VALUES ('', '$user', '$newGalaryName', NULL, '$comment', NOW(), NULL, '', NULL, NULL, '$pos', '', '1')");
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
	
	public function deletePhoto($altname, $id)
	{
		$result = $this->deleteElement("photo", $id, $user);
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
		$comm = new Commentor();
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
					
					$comm->deleteCommentsByPID("galary", $fID);
					$this->_sql->query("DELETE FROM `galary_files` WHERE `id` = '$fID'");
				}
				$res = $this->_sql->query("SELECT * FROM `galary_list` WHERE `id`='$id'");
				$temp2 = $this->_sql->fetchArr($res);
				$pos = $temp2["pos"];
				$result=$this->_sql->query("DELETE FROM `galary_list` WHERE `id` = '$id' AND `user`='$user'");
				$this->_sql->query("UPDATE `galary_list` SET `pos`=`pos`-1 WHERE `user`='$user' AND `pos`>'$pos'"); 
				return $result;
				break;
			case "photo":
				$result = $this->_sql->query("SELECT * FROM `galary_files` WHERE `pid` = '$id'");
				$temp = $this->_sql->fetchArr($result);
				
				$path = $temp["path"];
				$smallPath = $temp["small_path"];
				$fID = $temp["id"];
				$pid = $temp["pid"];
				
				$deletingFile->deleteSmthg($path);
				$deletingFile->deleteSmthg($smallPath);
				
				$comm->deleteCommentsByPID("galary", $fID);
				
				$result = $this->_sql->query("DELETE FROM `galary_files` WHERE `id` = '$fID'");
				$this->_sql->query("UPDATE `galary_files` SET `pos`=`pos`-1 WHERE `pid`='$pid' AND pos`>'$pos'"); 
				
				return $result;
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
		
		$tmp = $this->make_small($newN, $user);
		$fullImg = $tmp["full"];
		$miniImg = $tmp["mini"];
		
		$result = $this->_sql->query("INSERT INTO `galary_files`
        	( `id` , `path` , `small_path` , `text` , `pos` , `isreadedcomments` , `createdate` , `comment` , `pid` , `type` , `productname` , `content` , `cost` )
			VALUES (
			'', '$fullImg', '$miniImg', NULL , '$newPos' , '', NOW( ) , NULL , '$altname', NULL , NULL , NULL , NULL)");
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
			$newComment = htmlspecialchars($newComment);
			return $this->_sql->query("UPDATE `galary_list` SET `name`='$newName', `comment`='$newComment',
        		`sequrity` = '$newBlackList', `trusted` = '$newTruthList' WHERE `id`='$altname'");
		}
		else
		{
			return FALSE;
		}
	}
	
	public function setPhoto2Cover($id, $altname)
	{
		return $this->_sql->query("UPDATE `galary_list` SET `cover`='$id', `auto_cover`=0 WHERE `id` = '$altname'");
	}
	
	public function getCoverId($altname)
	{
		$this->_sql->query("SELECT * FROM `galary_list` WHERE `id`='$altname'");
	}
	
	//public function make_small($from,$to,$qua)
	public function make_small($from,$userId)  
	{
		/*
		 * требуется сделать 2 вещи:
		 * 1) проверить размер фотки. Если превышает разрешенные - изменить размер и пересохранить. (1024 * 1024)
		 * 2) у каждой фотки сделать превью. Максимальные размеры 130 х 130
		 */
		
		$fss = new FS();
		$from = $fss->getFullPath($from);
		$qualif=1024;
		$info=getimagesize($from);
		$fr_width=$info[0];
		$fr_height=$info[1];
		$temp = explode("/",$from);
		$imgName = $temp[count($temp)-1];
		$qua = 80;
		
		if ($fr_height > 768 || $fr_width > 1024)//делается уменьшение фотки
		{
			
			$reducedPath = $from;
			
			if($fr_width >= $fr_height) 
			{
				$del=$fr_width/$qualif;
				$new_height=$fr_height/$del;
				$this->resize_copy($from,$reducedPath,$qualif,$new_height,$qua);
			} 
			else 
			{
				$del=$fr_height/$qualif;
				$new_width=round($fr_width/$del);
				$this->resize_copy($from,$reducedPath,$new_width,$qualif,$qua);			
			}
		}
		//создается превью
		$reducedPath = "/photos/$userId/mini/$imgName";//путь к превью-файлу
		$reducedPath = $fss->getFullPath($reducedPath);
		$qualif = 130;
		if($fr_width>=$fr_height) 
		{
			$del=$fr_width/$qualif;
			$new_height=$fr_height/$del;
			$this->resize_copy($from,$reducedPath,$qualif,$new_height,$qua);
		} 
		else 
		{
			$del=$fr_height/$qualif;
			$new_width=round($fr_width/$del);
			$this->resize_copy($from,$reducedPath,$new_width,$qualif,$qua);			
		}
		$resArr["full"] = $fss->modifyPath($from);
		$resArr["mini"] = $fss->modifyPath($reducedPath); 
		return $resArr;
	}
	
	private function resize_copy($from,$to,$w,$h,$qua) 
	{
		$arr=getimagesize($from);
		$type=$arr[2];
		switch ($type) {
			case 1: $img=imagecreatefromgif($from); break; //GIF
			case 2: $img=imagecreatefromjpeg($from); break; //Jpeg
			case 3: $img=imagecreatefrompng($from); break; //PNG
		}
		$img_pro=imagecreatetruecolor($w,$h);
		imagecopyresampled($img_pro,$img,0,0,0,0,$w,$h,imagesx($img),imagesy($img));
		imagejpeg($img_pro,$to,$qua);
		imagedestroy($img);
		imagedestroy($img_pro);
	}
}
?>