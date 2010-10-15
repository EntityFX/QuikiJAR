<?php 

require_once "DirFS.php";
require_once "FileFS.php";

	class FS
	{
		const SITE_MAIN_PATH = "/home/timur/www/quki.ru";
		private $_d;
		private $_f;
		
		public function __construct()
		{
			$this->_d = new DirFS();
			$this->_f = new FileFs();
		}
		
		public function getCurrenrDir()
		{
			$r = FS::SITE_MAIN_PATH;
			$currentPath = getcwd(); 
			$ret = str_replace($r,"", $currentPath); 
			return $ret;
		}
		
		private function modifyPath($path) 
		{
			$r = FS::SITE_MAIN_PATH; 
			$ret = str_replace($r,"", $path);
			return $ret;
		}
		
		public function getFullPath($path)
		{
			$path = self::modifyPath($path);
			$path = FS::SITE_MAIN_PATH.$path;
			$path = self::validateString($path);
			return $path;
		}
		
		public function fileInfo($path)
		{
			return fileperms($path);
		}
		
		public function deleteSmthg($elementName)
		{
			$elementName = self::getFullPath($elementName);
			if (is_file($elementName))
			{
				return $this->_f->deleteFile($elementName);
			}
			if (is_dir($elementName))
			{
				return $this->_d->deleteDir($elementName);
			}
		}
		
		/**
		 * Переименовывание файла или директории.
		 * @param string $oldName - старый действующий относительный путь.
		 * @param string $newName -  новое имя файла или папки <b>(!!!!! БЕЗ ПУТИ !!!!)</b>
		 */
		public function renameElement($oldName, $newName)
		{
			$oldName = $this->getFullPath($oldName);
			$oldName = $this->validateString($oldName);
			if (is_dir($oldName) || is_file($oldName))
			{
				$newName = $this->validateString($newName);
				$tempArr = explode("/",$oldName);
				$tempArr[count($tempArr)-1]=$newName;
				$newName = implode("/", $tempArr);
				$ret = rename($oldName, $newName);
				if($ret==FALSE)
				{
					throw new Exception($this->validateString("Ошибка переименования элемента <b>'$oldName'</b> на новое имя <b>'$newName'</b>"));
				}
			}
			return $ret;
		}
		
		public function copyElement($oldPath, $newPath, $cut=FALSE)
		{
			$oldPath = $this->getFullPath($oldPath);
			$newPath = $this->getFullPath($newPath);
			if (is_file($oldPath))
			{
				return $this->_f->copyFile($oldPath, $newPath, $cut);
			}
			if (is_dir($oldPath))
			{
				return $this->_d->copyDir($oldPath, $newPath, $cut);
			}
			if (!file_exists($oldPath)) return FALSE;
		}
		
		/**
		 * Проверка валидности путей, имен.
		 * @param string $checkString
		 * 
		 */
		public function validateString($checkString)
		{
			//$checkString = iconv("windows-1251", "utf-8", $checkString);
			$checkString = str_replace(" ","_", $checkString);
			return $checkString;
		}
		
		function sizeElement($path) 
		{
			$path = $this->getFullPath($path);
			$totalsize=0;
			if (is_dir($path))
			{
				if ($dirstream = opendir($path)) 
				{
					while (false !== ($filename = readdir($dirstream))) 
					{
						if ($filename!="." && $filename!="..")
						{
							if (is_file($path."/".$filename))
							$totalsize += $this->_f->sizeFile($path."/".$filename);
						
							if (is_dir($path."/".$filename))
							$totalsize += $this->sizeElement($path."/".$filename);
						}
					}
				}
				closedir($dirstream);
			}
			if (is_file($path))
			{
				$totalsize += $this->_f->sizeFile($path);
			}
			return $totalsize;
		}
		
		public function upload2($uploadDir, $filePropArr)
		{
			$uploadDir = $this->getFullPath($uploadDir);
			return $this->_f->uploadFile($uploadDir, $filePropArr);
		}
		
		public function existing($path)
		{
			$path = $this->getFullPath($path);
			return file_exists($path);
		}
	}

?>