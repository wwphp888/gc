<?php
/**
* 文件操作类
* 修改时间：2019-01-25 18:03
* 例子：
* createDir('a/1/2/3'); 建立文件夹 建一个a/1/2/3文件夹
* createFile('b/1/2/3'); 建立文件 在b/1/2/文件夹下面建一个3文件
* createFile('b/1/2/3.exe');建立文件 在b/1/2/文件夹下面建一个3.exe文件
* copyDir('b','d/e'); 复制文件夹 建立一个d/e文件夹，把b文件夹下的内容复制进去
* copyFile('b/1/2/3.exe','b/b/3.exe');复制文件 建立一个b/b文件夹，并把b/1/2文件夹中的3.exe文件复制进去
* moveDir('a/','b/c'); 移动文件夹 建立一个b/c文件夹,并把a文件夹下的内容移动进去，并删除a文件夹
* moveFile('b/1/2/3.exe','b/d/3.exe'); 移动文件 建立一个b/d文件夹，并把b/1/2中的3.exe移动进去
* unlinkFile('b/d/3.exe');删除文件 删除b/d/3.exe文件
* unlinkDir('d'); 删除文件夹 删除d文件夹
*/
class ShoukuanlaFileUtil{

/*
* 重命名文件夹(不重名)单级目录
* @param string $dir_path='a/1/2',$suffix=遇到重名给目录后边加的字符
* return string 成功返回目录路径，失败返回空
*/
public function updateUniqueDir($dir_path=null,$dir_path_new=null,$suffix='_backup_'){

  if($dir_path == '' || $dir_path_new == ''){ return ''; }
	if(!file_exists($dir_path)){ return ''; }

	$file_suffix=0;
	while(true){        
		 $file_suffix2=$file_suffix > 0 ? $suffix.$file_suffix : '';
		 if(file_exists($dir_path_new.$file_suffix2)){			 
				$file_suffix++;
		 }else{
			  $dir_path_new=$dir_path_new.$file_suffix2;
				if(!rename($dir_path,$dir_path_new)){ return '';  }
				return $dir_path_new; 
		 }
		 
	}
  
}

/*
* 建立文件夹(不重名)单级目录
* @param string $dir_path='a/1/2',$suffix=遇到重名给目录后边加的字符
* return string 成功返回目录路径，失败返回空
*/
public function createUniqueDir($dir_path=null,$suffix='_backup_'){

  if($dir_path == ''){ return ''; }

	$file_suffix=0;
	while(true){        
		 $file_suffix2=$file_suffix > 0 ? $suffix.$file_suffix : '';
		 if(file_exists($dir_path.$file_suffix2)){			 
				$file_suffix++;
		 }else{
				$dir_path=$dir_path.$file_suffix2;  
				if(!mkdir($dir_path,0777)){  return '';  }
				return $dir_path;		 
		 }
		 
	}
  
}

/*
* 建立文件夹
* @param string $aimUrl='a/1/2'
* return bool
*/
public function createDir($aimUrl){
  
	$aimUrl = str_replace('', '/', $aimUrl);
	$aimDir = '';
	$arr = explode('/', $aimUrl);
	foreach($arr as $str){
		$aimDir .= $str . '/';
		if(!file_exists($aimDir)){
			 if(!mkdir($aimDir,0777)){  return false;  }
		}
	}

  return true;
}
/**
* 建立文件
*
* @param string $aimUrl
* @param boolean $overWrite 该参数控制是否覆盖原文件
* @return boolean
*/
public function createFile($aimUrl, $overWrite = false){

  $aimurl_exists=file_exists($aimUrl); 
	if($aimurl_exists && !$overWrite){
	   return true;
	}elseif($aimurl_exists && $overWrite){
	   if(!$this->unlinkFile($aimUrl)){ return false; }
	}
	$aimDir = dirname($aimUrl);
	if(!$this->createDir($aimDir)){ return false; }
	
	return touch($aimUrl);

}
/**
* 移动文件夹
*
* @param string $oldDir
* @param string $aimDir
* @param boolean $overWrite 该参数控制是否覆盖原文件
* @return boolean
*/
public function moveDir($oldDir, $aimDir, $overWrite = false){

	$aimDir = str_replace('', '/', $aimDir);
	$aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
	$oldDir = str_replace('', '/', $oldDir);
	$oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
	if(!is_dir($oldDir)){  return false;	}
	if(!file_exists($aimDir)){ 
		 if(!$this->createDir($aimDir)){ return false; }
	}
	$dirHandle = opendir($oldDir);
	if(!$dirHandle){	return false;	}

	while(false !== ($file = readdir($dirHandle))){

		if($file == '.' || $file == '..'){	continue;	}
		if(!is_dir($oldDir.$file)){
		   if(!$this->moveFile($oldDir . $file, $aimDir . $file, $overWrite)){  closedir($dirHandle); return false;}
		}else{
		   if(!$this->moveDir($oldDir . $file, $aimDir . $file, $overWrite)){ closedir($dirHandle); return false; }
		}
	}
	closedir($dirHandle);
	return rmdir($oldDir);

}
/**
* 移动文件
*
* $fileUrl=要移动文件地址
* $aimUrl=移动到哪个位置
* $overWrite=该参数控制是否覆盖原文件（默认不覆盖）
* @return boolean
*/
public function moveFile($fileUrl, $aimUrl, $overWrite=false){

	if(!file_exists($fileUrl)){	return false;	}
	$aimurl_exists=file_exists($aimUrl);
	if($aimurl_exists && !$overWrite){
	   return true;
	}elseif($aimurl_exists && $overWrite){
	   if(!$this->unlinkFile($aimUrl)){ return false; }
	}

	if(!$this->createDir(dirname($aimUrl))){ return false;}
	return rename($fileUrl, $aimUrl);
}
/**
* 删除文件夹
*
* @param string $aimDir
* @return boolean
*/
public function unlinkDir($aimDir){

	$aimDir = str_replace('', '/', $aimDir);
	$aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir.'/';
	if(!is_dir($aimDir)){	return true;	}
	$dirHandle = opendir($aimDir);
	while(false !== ($file = readdir($dirHandle))){
		if($file == '.' || $file == '..'){	continue;	}
		if(!is_dir($aimDir.$file)){
		  if(!$this->unlinkFile($aimDir . $file)){  closedir($dirHandle); return false; }
		}else{
		  if(!$this->unlinkDir($aimDir . $file)){  closedir($dirHandle); return false; }
		}
	}
	closedir($dirHandle);
	return rmdir($aimDir);

}
/**
* 删除文件
*
* @param string $aimUrl
* @return boolean
*/
public function unlinkFile($aimUrl){
  if(file_exists($aimUrl)){
     return unlink($aimUrl);
  }else{
     return true;
  }
}
/**
* 复制文件夹
*
* @param string $oldDir
* @param string $aimDir
* @param boolean $overWrite 该参数控制是否覆盖原文件
* @return boolean
*/
public function copyDir($oldDir, $aimDir, $overWrite = false){

	$aimDir = str_replace('', '/', $aimDir);
	$aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir.'/';
	$oldDir = str_replace('', '/', $oldDir);
	$oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir.'/';
	if(!is_dir($oldDir)){	return false;	}
	if(!file_exists($aimDir)){	if(!$this->createDir($aimDir)){  return false;}	}
	$dirHandle = opendir($oldDir);
	while(false !== ($file = readdir($dirHandle))){
	   if($file == '.' || $file == '..'){	continue;	}
	   if(!is_dir($oldDir . $file)){
	     if(!$this->copyFile($oldDir . $file, $aimDir . $file, $overWrite)){ closedir($dirHandle);  return false; } 
	   }else{
	     if(!$this->copyDir($oldDir . $file, $aimDir . $file, $overWrite)){ closedir($dirHandle);  return false; }
	   }
	}
	closedir($dirHandle);
	return true;

}
/**
* 复制文件
*
* @param string $fileUrl
* @param string $aimUrl
* @param boolean $overWrite 该参数控制是否覆盖原文件
* @return boolean
*/
public function copyFile($fileUrl, $aimUrl, $overWrite=false){
	if(!file_exists($fileUrl)){  return false; }

	$aimurl_exists=file_exists($aimUrl);
	if($aimurl_exists && !$overWrite){
	   return true;
	}elseif($aimurl_exists && $overWrite){
	   if(!$this->unlinkFile($aimUrl)){  return false; }
	}

	if(!$this->createDir(dirname($aimUrl))){  return false; }
	return copy($fileUrl, $aimUrl);

}

}
?> 