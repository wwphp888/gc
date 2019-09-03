<?php
/*
功能:zip文件解压
修改时间：2019-01-17 18:04
*/
class ShoukuanlaUnZip{
private $Comment = '';
private $Entries = array();
private $Name = '';
private $Size = 0;
private $Time = 0;	

private $FileUtil;

function __construct(){  
	
	require_once(SKL_ClASS_PATH.'ShoukuanlaFileUtil.class.php');
	$this->FileUtil=new ShoukuanlaFileUtil();
}
/*
解压文件
参数：$filename=文件路径,$save_path=保存路径
返回值(int)：解压文件总数,如果没完全解压也返回0
*/
public function unzip($filename=null,$save_path=null){
  $filecount = 0;
  if($filename == ''){ return $filecount; } 
	if($save_path == ''){ $save_path='./'; }
	
	$this->ReadFile($filename);

	if($this->Count() == 0 || $this->GetError(0) != 0){  return $filecount;  }
	if(!$this->FileUtil->createDir($save_path)){  skl_error($save_path.'该目录创建失败！');  }
	if(!is_writable($save_path)){ skl_error($save_path.' 该解压目录没有写入权限！');  }
	
	foreach($this->Entries as $entry){ 

    $un_dir=$save_path.'/'.$entry->Path;
		if(!$this->FileUtil->createDir($un_dir)){ skl_error($un_dir.'该目录创建失败！'); }
		$fp = fopen($un_dir.'/'.$entry->Name,'w');
		$is_write=fwrite($fp, $entry->Data);
		fclose($fp);
		$filecount++;	
		
    if($is_write === false){ return 0; }

	}

	return $filecount;
}

public function Count() {
	return count($this->Entries);
}

public function GetData($in_Index){
  return $this->Entries[$in_Index]->Data;
}

public function GetEntry($in_Index){
	return $this->Entries[$in_Index];
}

function GetError($in_Index){
  return $this->Entries[$in_Index]->Error;
}

public function GetErrorMsg($in_Index){
	return $this->Entries[$in_Index]->ErrorMsg;
}

public function GetName($in_Index){
	return $this->Entries[$in_Index]->Name;
}

public function GetPath($in_Index){
	return $this->Entries[$in_Index]->Path;
}

public function GetTime($in_Index){
  return $this->Entries[$in_Index]->Time;
}

public function ReadFile($in_FileName){
	$this->Entries = array();

	$this->Name = $in_FileName;
	$this->Time = filemtime($in_FileName);
	$this->Size = filesize($in_FileName);

	$oF = fopen($in_FileName, 'rb');
	$vZ = fread($oF, $this->Size);
	fclose($oF);

	$aE = explode("\x50\x4b\x05\x06", $vZ);


	$aP = unpack('x16/v1CL', $aE[1]);
	$this->Comment = substr($aE[1], 18, $aP['CL']);

	$this->Comment = strtr($this->Comment, array("\r\n" => "\n",
																		 "\r"   => "\n"));

	$aE = explode("\x50\x4b\x01\x02", $vZ);
	$aE = explode("\x50\x4b\x03\x04", $aE[0]);
	array_shift($aE);

	foreach($aE as $vZ) {
		$aI = array();
		$aI['E']  = 0;
		$aI['EM'] = '';
		$aP = unpack('v1VN/v1GPF/v1CM/v1FT/v1FD/V1CRC/V1CS/V1UCS/v1FNL', $vZ);
		$bE = ($aP['GPF'] && 0x0001) ? TRUE : FALSE;
		$nF = $aP['FNL'];

		if($aP['GPF'] & 0x0008) {
			$aP1 = unpack('V1CRC/V1CS/V1UCS', substr($vZ, -12));

			$aP['CRC'] = $aP1['CRC'];
			$aP['CS']  = $aP1['CS'];
			$aP['UCS'] = $aP1['UCS'];

			$vZ = substr($vZ, 0, -12);
		}

		$aI['N'] = substr($vZ, 26, $nF);

		if(substr($aI['N'], -1) == '/') {
			continue;
		}

		$aI['P'] = dirname($aI['N']);
		$aI['P'] = $aI['P'] == '.' ? '' : $aI['P'];
		$aI['N'] = basename($aI['N']);

		$vZ = substr($vZ, 26 + $nF);

		if(strlen($vZ) != $aP['CS']) {
			$aI['E']  = 1;
			$aI['EM'] = 'Compressed size is not equal with the value in header information.';
		} else {
			if($bE) {
				$aI['E']  = 5;
				$aI['EM'] = 'File is encrypted, which is not supported from this class.';
			} else {
				switch($aP['CM']) {
					case 0: // Stored
						break;

					case 8: // Deflated
						$vZ = gzinflate($vZ);
						break;

					case 12: // BZIP2
						if(! extension_loaded('bz2')) {
								if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
									@dl('php_bz2.dll');
								} else {
									@dl('bz2.so');
								}
						}

						if(extension_loaded('bz2')) {
								$vZ = bzdecompress($vZ);
						} else {
								$aI['E']  = 7;
								$aI['EM'] = "PHP BZIP2 extension not available.";
						}

						break;

					default:
						$aI['E']  = 6;
						$aI['EM'] = "De-/Compression method {$aP['CM']} is not supported.";
				}

				if(! $aI['E']) {
					if($vZ === FALSE) {
						$aI['E']  = 2;
						$aI['EM'] = 'Decompression of data failed.';
					} else {
						if(strlen($vZ) != $aP['UCS']) {
								$aI['E']  = 3;
								$aI['EM'] = 'Uncompressed size is not equal with the value in header information.';
						} else {
								if(crc32($vZ) != $aP['CRC']) {
										$aI['E']  = 4;
										$aI['EM'] = 'CRC32 checksum is not equal with the value in header information.';
								}
						}
					}
				}
			}
		}

		$aI['D'] = $vZ;

		$aI['T'] = mktime(($aP['FT']  & 0xf800) >> 11,
							($aP['FT']  & 0x07e0) >>  5,
							($aP['FT']  & 0x001f) <<  1,
							($aP['FD']  & 0x01e0) >>  5,
							($aP['FD']  & 0x001f),
							(($aP['FD'] & 0xfe00) >>  9) + 1980);

		$this->Entries[] = new SimpleUnzipEntry($aI);
	}

	return $this->Entries;
	}


}


class SimpleUnzipEntry{
public $Data = '';
public $Error = 0;
public $ErrorMsg = '';
public $Name = '';
public $Path = '';
public $Time = 0;

function SimpleUnzipEntry($in_Entry) {
	$this->Data     = $in_Entry['D'];
	$this->Error    = $in_Entry['E'];
	$this->ErrorMsg = $in_Entry['EM'];
	$this->Name     = $in_Entry['N'];
	$this->Path     = $in_Entry['P'];
	$this->Time     = $in_Entry['T'];
}

}

?>