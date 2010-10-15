<?php 

	class FileFs
	{
		public $_uFFN = "uploadFile";  //upload Form File Name
		
		public function deleteFile($path)
		{
			return unlink($path);
		}
		
		public function copyFile($oldPath, $newPath, $cut=FALSE)
		{
			//$oldPath = FS::getFullPath($oldPath);
			$newPath = FS::getFullPath($newPath);
			//die(var_dump($oldPath, $newPath));
			$r = copy($oldPath, $newPath);
			if ($cut)
			{
				$this->deleteFile($oldPath);
			}
			return $r;
		}
		/*
		public function createFile($fileName)
		{
			
		}*/
		
		public function sizeFile($path)
		{
			$r = filesize($path);
			return $r;
		}
		
		public function uploadFile($uploadDir, $filePropArr)
		{
			if (count($filePropArr)==0) return 0;
			$fName = FS::validateString(basename($filePropArr[$this->_uFFN]['name']));
			$uploadfile = $uploadDir."/".$fName;
			//var_dump($filePropArr[$this->_uFFN]['tmp_name'], $uploadfile);
			// �������� ���� �� �������� ��� ���������� �������� ������:
			//die(var_dump($filePropArr[$this->_uFFN]));
			if ($filePropArr[$this->_uFFN]['size']==0)
			{
				throw new Exception("������ ����� ��������� 2 ��������� :(. <br /> 
				���� �� ������������, ���� �� �� ������� ��������� ������ ���������� ;)");
			}
			if ($this->copyFile($filePropArr[$this->_uFFN]['tmp_name'], $uploadfile))
			{
				$retArr['lastName'] = $filePropArr[$this->_uFFN]['name'];
				$retArr['Mime'] = $filePropArr[$this->_uFFN]['type'];
				$retArr['size'] = $filePropArr[$this->_uFFN]['size'];
				$retArr['tmp_name'] = $filePropArr[$this->_uFFN]['tmp_name'];
			}
			else 
			{ 
				throw new Exception("������ �����������: ".$filePropArr[$this->_uFFN]['tmp_name']." � ".$uploadfile);
			}
			return $retArr;
		}
	}
?>