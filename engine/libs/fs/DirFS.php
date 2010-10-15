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
				throw new Exception("Директория отсутствует");
				return FALSE;
			}
		}
		
		public function getDirContent($path)
		{
			$path = FS::getFullPath($path);
			return scandir($path);
		}
		
		/**
		 * Копирование папки
		 * @param string $oldPath текущий <b>полный</b> путь к папке
		 * @param string $newPath будущий <b>полный</b> путь к папке
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
				throw new Exception("Неправильный исходный путь для копирования");
			}
			return TRUE;
		}
	}
?>