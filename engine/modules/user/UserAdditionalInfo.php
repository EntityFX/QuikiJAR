<?php
/**
* ���� ��� ��������� �������������� ���� ������������.
* @package user
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur, Shagiakhmetov Aidar)
*/

    /**
    * ��������� �������� � �������� �������������� ����������
    */
    final class UserAdditionalInfo
    {
        /**
        * �������� ���������
        * 
        * @var string
        */
        public $text;
        
        /**
        * ���������� ��������
        * 
        * @var string
        */
        public $title;
        
        /**
        * �����������. �������� �� ���� ������������� ������
        * 
        * @param Array $arr ������ � ������� attribute � value
        * @return UserAdditionalInfo
        */
        public function __construct(&$arr)
        {
            if ($arr!=NULL)    
            {
                $this->title=$arr["attribute"];
                $this->text=$arr["value"];
            }
        }
    }
?>
