<?php
/**
* ���� � ������� UserRegister. 
* ��������� ��������� ����:
*   ����������� ������ ������������
*   �������� ������������
*   ��������� ������������
*   ����������� ������������
*   �������� �� �������������
* @package kernel
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/
    require_once "checker.php";
    
    require_once "UserException.php";
    
    require_once "engine/libs/mysql/MySQLConnector.php";
    
    /**
    * ����������� ������ ������������    
    */
    class UserRegister extends MySQLConnector
    {
        /**
        * ���������������� ������ ������������ � �������
        * 
        * @param string $mail �����
        * @param string $password ������
        * @param string $name ���
        * @param string $surname ������� 
        * @param string $burthday ���� ��������
        * @param bool $gender ��� 
        * @param integer $ip IP
        * @throws UserException ���� ������������ ��� ����������
        * @throws UserException ���� ������� ���� ��������
        * @throws �� ��������� ��� � �������
        * @throws �������� ������ �����
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
            }   
            else
            {
                throw new UserException($mail,UserException::USR_NAME_INCORRECT);
            } 
        }
        
        /**
        * �������� ������
        * 
        * @param string $p1
        * @param string $p2
        * @throws UserException �������� ����� ������
        * @throws UserException �� ��������� ����������� ������
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
        }
        
        /**
        * ������� ������������ � �����
        * 
        * @param integer $id ID ������������
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
        
        /**
        * ������������ ������������
        * 
        * @param integer $id ID ������������
        */
        public function activate($id)
        {
            $this->setState((int)$id);     
        }
        
        /**
        * �������������� ������������
        * 
        * @param integer $id ID ������������ 
        */
        public function deactivate($id)
        {
            $this->setState((int)$id,false);    
        }
        
        /**
        * ���������� ��������� �����������/�������������
        * 
        * @param integer $id ID ������������
        * @param string $value ��������
        * @throws UserException ���� ������������ �� ����������.
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
        * ��������, ���������� �� ������ ������������ � �������
        * 
        * @param string $mail ����� ������������
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
        
        /**
        * �������� �� ������������� �� $id
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
