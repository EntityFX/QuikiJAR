<?php
/**
* Файл с классом UserRegister. 
* Выполняет следующую роль:
*   Регистрация нового пользователя
*   Удаление пользователя
*   Активация пользователя
*       * Отправка на почту регистрационной формы
*   Деактивация пользователя
*   Проверка на существование
* @package user
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/
    require_once "checker.php";
    
    require_once "UserException.php";
    
    require_once "engine/libs/mysql/MySQLConnector.php";
    
    require_once "UserMailer.php";
    
    require_once SOURCE_PATH."engine/kernel/SmartyExst.php";
 
    /**
    * Регистратор нового пользователя    
    */
    class UserRegister extends MySQLConnector
    {
       
        /**
        * Путь к шаблону, который будет отправляться на почту пользователя
        *
        * @var string
        */
        public $mailTemplate;
        
        /**
        * Зарегистрировать нового пользователя в системе
        * 
        * @param string $mail Почта
        * @param string $password Пароль
        * @param string $name Имя
        * @param string $surname Фамилия 
        * @param string $burthday Дата рождения
        * @param bool $gender Пол 
        * @param integer $ip IP
        * @throws UserException Если пользователь уже существует
        * @throws UserException Если неверна дата рождения
        * @throws UserException Не заполнены имя и фамилия
        * @throws UserException Неверный формат почты
        */
        public function register($mail,$password,$name,$surname,$burthday,$gender,$ip)
        {
            if (checkMail($mail))
            {
                if ($this->checkIfExsist($mail))
                {
                    throw new UserException($mail,UserException::USR_ALREADY_EXIST);
                }
                if (!checkDateFormat($burthday))
                {
                    throw new UserException($mail,UserException::USR_CHECK_BURTHDAY);   
                }
                else if ($name=="" && $surname=="")
                {
                    throw new UserException($mail,UserException::USR_NAME_EMPTY);
                }
                $textPassword=$password;
                $password=md5($password);
                $date=date("Y-m-d");
                $query="
                INSERT INTO `SITE_USERS` SET
                    `mail`='$mail',
                    `password`='$password',
                    `ip`=$ip,
                    `register_date`='$date',
                    `name`='$name',
                    `second_name`='$surname',
                    `gender`=$gender,
                    `burthday`='$burthday'
                ";
                $this->_sql->query($query);
                $querySelectId=$this->_sql->selFieldsWhere("SITE_USERS","`mail`='$mail'","id");
                $arr=$this->_sql->GetRows($querySelectId);
                $id=$arr[0]["id"];
                $activationKey=$this->generateActivationKey(7);
                $insertActivationRowData=array($id,$activationKey);
                $this->_sql->insert("USERS_ACTIVATION_KEYS",$insertActivationRowData);
                $p=new UserMailer();
                $p->mail=$mail;
                $embeddedImages=array("photos/no-photo.jpg","photos/no-galary.jpg");
                $s=new SmartyExst();
                $s->assign("NAME","$name $surname");
                $s->assign("PASS",$textPassword);
                $s->assign("ID",$id);
                $s->assign("KEY",$activationKey);
                $sendString=$s->fetch($this->mailTemplate);
                $p->registerSend($sendString,$embeddedImages);
                return $id;
            }   
            else
            {
                throw new UserException($mail,UserException::USR_NAME_INCORRECT);
            } 
        }
        
        /**
        * Проверка пароля
        * 
        * @param string $p1
        * @param string $p2
        * @throws UserException Неверная длина пароля
        * @throws UserException Не совпадает дублирующий пароль
        */
        public function checkPassword($p1,$p2)
        {
            $pl=strlen($p1);
            if ($pl<7 || $pl>26)
            {
                throw new UserException($mail,UserException::USR_PASSWORD_LENGTH);   
            }
            if ($p1!=$p2)    
            {
                throw new UserException($mail,UserException::USR_PASSWORD_NEQ);    
            }
            return true;
        }
        
        /**
        * Сменить пароль
        * 
        * @param string $current Текущий пароль
        * @param string $new Новый пароль
        * @throws UserException Если неправильный текущий пароль
        */
        public function changePassword($current,$new)
        {
            $id=(int)$_SESSION["user"]["id"];
            if ($_SESSION["user"]["password"]!=md5($current))
            {
                throw new Exception(UserException::USR_PASSWORD_INCORRECT,$id);    
            }
            else
            {
                if ($this->checkPassword($new,$new))
                {
                    $pass=md5($new);
                    $this->_sql->query(
                        "
                        UPDATE `SITE_USERS` SET `password`='$pass' WHERE `id`=$id
                        "
                    );
                    $_SESSION["user"]["password"]=$pass;
                }
            }
        }
        
        /**
        * Удалить пользователя с сайта
        * 
        * @param integer $id ID пользователя
        */
        public function unregister($id)
        {
            if ($this->checkIfExsistID($id))
            {
                $this->_sql->query("DELETE FROM `SITE_USERS` WHERE `id`=$id");
            }
            else
            {
                throw new UserException($id,UserException::USR_NOT_EXSIST);
            }    
        }
        
        
        public function activateByKey($id,$key)
        {
            try
            {
                $qres=$this->_sql->selFieldsWhere("USERS_ACTIVATION_KEYS","`user_id`=$id","column1");
                $arr=$this->_sql->GetRows($qres);
            }
            catch (Exception $e)
            {
                throw new UserException($id,UserException::USR_NOT_EXSIST);
            }
            $dbKey=$arr[0]["column1"];
            if ($key==$dbKey)
            {
                $this->activate($id);
                $qres=$this->_sql->delete("USERS_ACTIVATION_KEYS","`user_id`=$id");
                return true;
            }
            else
            {
                return false;
            }
        }
        
        
        /**
        * Активировать пользователя
        * 
        * @param integer $id ID пользователя
        */
        public function activate($id)
        {
            $this->setState((int)$id);     
        }
                
        /**
        * Деактивировать пользователя
        * 
        * @param integer $id ID пользователя 
        */
        public function deactivate($id)
        {
            $this->setState((int)$id,false);    
        }
        
        /**
        * Установить состояние активирован/деактивирован
        * 
        * @param integer $id ID пользователя
        * @param string $value Значение
        * @throws UserException Если пользователь не существует.
        */
        private function setState($id,$value=true)
        {
            $state=(int)$value;
            if ($this->checkIfExsistID($id))
            {
                $this->_sql->query("UPDATE `SITE_USERS` SET `state` = $state WHERE `id` = $id");
            }
            else
            {
                throw new UserException($id,UserException::USR_NOT_EXSIST);
            }               
        }
        
        /**
        * Проверка, существует ли данный пользователь в системе
        * 
        * @param string $mail Почта пользователя
        */
        private function checkIfExsist($mail)
        {
            $res=$this->_sql->query("SELECT COUNT(`mail`) AS `c` FROM `SITE_USERS` WHERE `mail`='$mail'");
            $data=$this->_sql->GetRows($res);
            if ($data[0]["c"]==0)
            {
                return false;    
            }
            else
            {
                return true;
            }
        }
        
        private function generateActivationKey($size)
        {
            $result="";
            for($it=1;$it<=$size;$it++)      
            {
                $type=mt_rand(0,2);
                switch ($type)
                {
                    case 0:
                        $symbol=mt_rand(0,9);
                        break;
                    case 1:
                        $symbol=chr(mt_rand(ord("a"),ord("z")));
                        break;
                    case 2:
                        $symbol=chr(mt_rand(ord("A"),ord("Z")));
                        break;
                }
                $result.=$symbol;
            }
            return $result;
        }
        
        /**
        * Проверка на существование по $id
        * 
        * @param integer $id
        */
        public function checkIfExsistID($id)
        {
            $res=$this->_sql->query("SELECT COUNT(`mail`) AS `c` FROM `SITE_USERS` WHERE `id`='$id'");
            $data=$this->_sql->GetRows($res);
            if ($data[0]["c"]==0)
            {
                return false;    
            }
            else
            {
                return true;
            }
        }
    }
?>
