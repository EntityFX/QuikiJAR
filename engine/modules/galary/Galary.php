<?php   
require_once "engine/libs/mysql/MySQLConnector.php"; 

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
            $resArr = $this->listing($resArr,$listNum,50); //���������, ���������� �� 50 �������� �� ����  
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
            
            if ($ignState==1 & $trustState==0)
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
         * @param integer $altname ����� ������� � ������� `galary_list`
         * @param integer $listNum ����� �����
         * @return Array ���������� ������������� ������.
         */
        public function showGalary($visitor,$altname,$listNum)
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
                        $resArr= $this->listing($resArr,$listNum,20);  
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
                $returnArray["listCount"]=1;
                $returnArray["listCurrent"]=1;
            } 
            return $returnArray;            
        }

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
                    $currPhoto=0;
                    $qResult=$this->_sql->query("SELECT * FROM `galary_files` WHERE `pid`='$pid' ORDER BY `pos` ASC");//������� ���� ������ � �������
                    while ($ar=$this->_sql->fetchArr($qResult))
                    {   
                        if (count($ar)!=0) 
                        {  
                            $photoArr[]=$ar;
                            if ($id==$ar["id"])
                            {   
                                $currPhoto=$i;//����� ����� �� ������ ���������� ����� � �������
                            }
                            $i++;  
                        }
                        else
                        {
                            throw new Exception("���������� ��� �� ���������. :( ");
                        }
                    }  
                    
                    if ($currPhoto!="")
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
                $retArr[]=$array[id]; 
            }
            return $retArr;
        }
    }
?>