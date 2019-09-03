<?php

$file_name = "kaixinnongchang.apk";
$file_dir = "http://www.176lzy.com/app/";
$file = @ fopen($file_dir . $file_name,"r");
if (!$file) {
echo "error";
} else {
Header("Content-type: application/octet-stream");
Header("Content-Disposition: attachment; filename=" . $file_name);
while (!feof ($file)) {
echo fread($file,50000);
}
fclose ($file);
} 
?>