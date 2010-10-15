<?php 

	class DirFS
	{
		public $_currentDir;
		
		public function createPath($path) 
		{
			$path = FS::modifyPath($path);
			$pathArr = explode("/",$path);
			$this->_currentDir = FS::SITE_MAIN_PATH;
			foreach ($pathArr as $index => $value) 
			{
				if($value!="")
				{
					$this->createDir($value);
					$this->_currentDir = $this->_currentDir."/".$value;
				}
			}
			return TRUE;
		}
		
		private function createDir($dirName)
		{
			
			$dirName = $this->_currentDir."/".$dirName;
			if (!file_exists($dirName)) 
			{
				mkdir($dirName);
				chmod($dirName, 0777);
			}
		}
		
		public function deleteDir($path)
		{
			if (opendir($path)!=FALSE)
			{
				$tempArr = $this->getDirContent($path);
				if(count($tempArr)<3)
				{
					return rmdir($path);
				}
				else 
				{
					foreach ($tempArr as $key => $value) 
					{
						if ($value!="." && $value!="..")
						{
							$element=$path."/".$value;
							FS::deleteSmthg($element);
						}
					}
					return $this->deleteDir($path);
				}
				closedir($path);
			}
			else 
			{
				throw new Exception("���������� �����������");
				return FALSE;
			}
		}
		
		public function getDirContent($path)
		{
			$path = FS::getFullPath($path);
			return scandir($path);
		}
		
		/**
		 * ����������� �����
		 * @param string $oldPath ������� <b>������</b> ���� � �����
		 * @param string $newPath ������� <b>������</b> ���� � �����
		 */
		public function copyDir($oldPath, $newPath, $cut=FALSE)
		{
			if (file_exists($oldPath))
			{
				$tempArr = $this->getDirContent($oldPath);
				if (count($tempArr)<3)
				{
					$r1 = $this->createPath($newPath);
					if ($r1==TRUE && $cut==TRUE)
					{
						$this->deleteDir($oldPath);
					}
				}
				else 
				{
					$this->createPath($newPath);
					foreach ($tempArr as $key => $value) 
					{
						if ($value!="." && $value!="..")
						{//die("ok");
							FS::copyElement($oldPath."/".$value, $newPath."/".$value, $cut);
						}
					}
					FS::deleteSmthg($oldPath);
				}
			}
			else 
			{
				throw new Exception("������������ �������� ���� ��� �����������");
			}
			return TRUE;
		}
	}
?>