<?php
require_once "engine/libs/mysql/MySQLConnector.php"; 
require_once "engine/modules/numerator/Numerator.php"; 

/**
 * Класс предназначен для записи, чтения комментариев.
 * 
 * @author Тимур 29.08.10 <gtimur666@gmail.com>
 * @version 1.0
*/
    class Commentor extends MySQLConnector
    {
		/**
		 * Функция добавления комментария к какому-либо модулю.
		 * @param integer $id номер элемента.
		 * @param string $module номер или название модуля.
		 * @param integer $visitor номер посетителя.
		 * @param string $comment комментарий посетителя.
		 * @param integer $user номер пользователя, у которого комментируют элемент.
		 * @todo разобраться насчет <b>нумерации</b> или <b>названия</b> модулей.
		 * @return boolean возвращает TRUE в случае, если комментарий успешно добавлен, иначе - FALSE.
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
			if ($comment!="") 
			{
				$comment=htmlspecialchars($comment);
			}
            $result=$this->_sql->query("INSERT INTO  `commentor` (  `id` ,  `module` ,  `pid` ,  `user` ,  `comment` ,  `comment_time` ,  `notanswered` ,  `poster_user` )
            VALUES ('',  'galary',  '$id',  '$user',  '$comment', NOW( ) ,  '$notanswered',  '$visitor')"); 	
            return $result;
		}
		
		/**
		 * Функция получения комментариев к одному элементу: при просмотре комментариев хозяином элемента ставится метка у всех 
		 * непрочитанных комментариев элемента о том, что они прочитаны.
		 * @param integer $id номер элемента. 
		 * @param string $module название/номер модуля.
		 * @param integer $listNum номер листа.
		 * @param integer $user номер просматриваемого пользователя.
		 * @param integer $visitor номер просматривающего пользователя.
		 * @return Array ассоциативный массив.
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
          /*  if (count($resArr)==0)
            {
            	throw new Exception("Комментарии отсутствуют.");
            }*/
            $resArr=listing($resArr, $listNum, 50);
            return $resArr;
		}

        
        public function readAllComments($user)
        {
        	$result = $this->_sql->query("SELECT * FROM `commentor` WHERE `user`='$user' ORDER BY `comment_time` DESC");
        	while ($tempArr = $this->_sql->fetchArr($result)) 
        	{
        		$resArr[]=$tempArr;
        	}
        	return $resArr;
        }
        /**
         * Удаление комментария.
         * @param $id - номер коммента.
         * @return bool
         */
        public function deleteComment($id)
        {
        	$result=$this->_sql->query("DELETE FROM `commentor` WHERE `id` ='$id' LIMIT 1 ;");
        	return $result;
        }
        
        /**
         * Получение id юзера, по id его комментария.
         * @param $id - id комментария
         * @return integer - id юзера
         */
        public function getUserIdFromComment($id) 
        {
        	$result=$this->_sql->query("SELECT * FROM `commentor` WHERE `id` = '$id'");
        	$res=$this->_sql->fetchArr($result);
        	return $res["poster_user"];
        }
    }
?>