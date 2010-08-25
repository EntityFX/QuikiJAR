<?
/**
* Ôàéë ñ êëàññîì LinksList.
* @package engine.kernel  
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur) © 2010 
*/
    
    /**
    * Âûïîëíÿåò ğîëü ïîñòğîåíèÿ ñïèñêà ññûëîê îñíîâíîãî ìåíş, ñïèñîê ññûëîê îò êîğíåâîãî ğàçäåëà ê òåêóùåìó,
    * ñïèñîê ïîäğàçäåëîâ
    * @package engine.kernel
    * @author Solopiy Artem   
    * @final
    */
    final class LinksList
    {
        /**
        * İêçåìïëÿğ äëÿ ğàáîòû ñ ÁÄ 
        * 
        * @var MySQL 
        */
        private $_sql;
        
        /**
        * URL àäğåñ ââèäå ìàññèâà ğàçäåëîâ
        *   
        * @var Array 
        */
        private $_urlPath;
        
        /**
        * ID òåêóùåãî ğàçäåëà
        * 
        * @var mixed
        */
        private $_currentID;
		
        /**
        * Êîíñòğóêòîğ
        * 
        * @param Integer $curSectionID ID òåêöùåãî ğàçäåëà
        */
        public function __construct($curSectionID)
        {
            $this->_currentID=$curSectionID;
            $this->_sql=new MySQL(DB_SERVER,DB_USER,DB_PASSWORD);
            $this->_sql->selectDB(DB_NAME);            
        }
        
        /**
        * Âîçğàùàåò ìàññèâ Ãëàâíîãî ìåíş
        * 
        * @return Array
        */
        private function getMenuTable()
        {
            $resource=$this->_sql->query("SELECT a.`title`, b.`section_id`, b.`show_sub`, a.`id` FROM `URL` a, `MainMenu` b WHERE a.`id`=b.`section_id`");
            return $this->_sql->GetRows($resource);
        } 
        
        
        public function makeMenu()
        {
            $res=NULL;
            $menuArray=$this->getMenuTable();
			if ($menuArray!=NULL)
			{
				foreach($menuArray as $value)
				{
					$href=$this->restoreBackLink($value["section_id"]);
                    $res[]=array("title" => $value["title"], "href" => $href);
				}
			    $str.="</ul>";
			}
            return $res;
        } 
        
        /**
        * Ôîğìèğóåò ññûëêó òåêóùåãî ğàçäåëà
        * 
        * @param Integer $sectionId ID ğàçäåëà
        * @return String
        */
        public function restoreBackLink($sectionId)     
        {
            $this->_sql->selAllWhere("URL","`id`=$sectionId");
            $res=$this->_sql->getTable();
            if ($res[0]["module"]!=0)
            {
                $urlPath[]=$res[0]["link"];
                $urlFull[]=$res[0];
            }
            $pid=$res[0]["pid"];
            while ($pid!=0)
            {
                $this->_sql->selAllWhere("URL","`id`=$pid");
                $subRes=$this->_sql->getTable();
                if ($subRes[0]["module"]!=0)
                {
                    $urlPath[]=$subRes[0]["link"];
                    $urlFull[]=$subRes[0];
                }               
                $pid=$subRes[0]["pid"];
            }
            for($index=count($urlPath)-2;$index>=0;--$index)
            {
                $link.=$urlPath["$index"]."/";
            }
            if ($sectionId==$this->_currentID) $this->_urlPath=$urlFull; 
            return "/".$link;
        }
        
        /**
        * Ïîëó÷àåò ïóòü îò êîğíåâîãî ğàçäåëà äî òåêóùåãî
        * 
        * @return Array
        */
        public function getPath()
        {
            if ($this->_urlPath==NULL)
            {
                $this->restoreBackLink($this->_currentID);    
            }
            $res=NULL;
            $link="";
            foreach(array_reverse($this->_urlPath) as $uValue)
            {
                $link.=$uValue["link"];
                if ($link!="/")
                {
                    $link.="/";
                }
                $res[]=array("title" => $uValue["title"], "link" => $link);
            }
            return $res;    
        }
        
        /**
        * Ïîëó÷àåò ñïèñîê äî÷åğíèõ ğàçäåëîâ ñ ó÷¸òîì ïîëÿ show_sub òàáëèöû URL
        * 
        * @return Array
        */
        public function getMenuChildren()
        {
            $r=$this->_sql->query("SELECT `show_sub` FROM `MainMenu` WHERE `section_id`=$this->_currentID");
            $t=$this->_sql->GetRows($r); 
            $tOne=$t[0];
            if ($tOne["show_sub"]==1)
            {
                $q=$this->_sql->query("SELECT `title`,`link` FROM `URL` WHERE `pid`=$this->_currentID AND `module`!=0");
                $res=$this->_sql->GetRows($q);
            }
            return $res;
        }
		
        /**
        * Ïîëó÷àåò ñïèñîê äî÷åğíèõ ğàçäåëîâ ñ ó÷¸òîì ïîëÿ show_sub òàáëèöû URL
        * 
        * @return Array
        */
		public function getSubSection()
		{
			$r=$this->_sql->query("SELECT `link`,`title` FROM `URL` WHERE `pid`=$this->_currentID AND `module`!=0");
			$t=$this->_sql->GetRows($r);
			$bitOfString=$this->url=substr($_SERVER['REQUEST_URI'],1);
			$resArr=NULL;
			if ($t!=NULL)
			{
				foreach($t as $value)
				{
					$resArr[]=array("link" => $value["link"]="/".$bitOfString.$value["link"]."/", "title" => $value["title"]);
				}
			}
			return $resArr;
		}
    }
?>