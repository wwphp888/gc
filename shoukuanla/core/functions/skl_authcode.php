<?php 
/*
功能：加密解密
参数：$string=要加密或解密字符串，$operation='decode'是解密'encode'为加密，$key=秘钥，$expiry=有效时间(秒)
返回值：string解密失败返回空
修改时间：2019-07-09 18:58
*/
function skl_authcode($string, $operation = 'decode', $key = '', $expiry = 0) {

	$ckey_length = 4;

	$key = md5($key ? $key : 'www.shoukuanla.net');
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));

  $is_decode=$operation == 'decode' ? true : false;
	if($is_decode){   $string=str_replace(array('-','_'),array('+','/'),$string); 	}
	$keyc = $ckey_length ? ($is_decode ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $is_decode ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($is_decode) {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		//return $keyc.str_replace('=', '', base64_encode($result));
		return $keyc.str_replace(array('+','/','='),array('-','_',''), base64_encode($result));
	}

}

?>

