<?php

/**

*	΢�����������̳�

*

*	http://www.besttool.cn

*

*	Q Q : 2045003697

*

*	΢��: dragondean

*

================================================================================



	ʹ��Э�飺

	������������ϵ�ۺ󣬲�Ҫ����Ķ�����Դ���룬һ���Ķ����������ۺ��Ϸ���

	��������ǳ���ʹ��Ȩ����Ȩ�鿪�������С�δ��ͬ�ⲻ��ת�ۻ����޸ĺ��ٴ�����

	ʹ�ñ���������Ϊ���ܱ�Э��

	

================================================================================



	=> ��ȫ��ʾ��

	=> Ϊ�˹���Ա��½���㣬����Ա��½����δ����ͼ����֤�롣

	=> Ϊ�˰�ȫ�������������װ�����Ժ�֮�󽫴��ļ�����Ϊһ�����˲²���������

	=> ����bst151209.php����Ҫʹ��������ţ����ܵ��·��ʲ��ˣ�





*/

if (get_magic_quotes_gpc()){

	function stripslashes_deep($value){

		$value = is_array($value) ?

		array_map('stripslashes_deep', $value) :

		stripslashes($value);

		return $value;

	}

	$_REQUEST = array_map('stripslashes_deep', $_REQUEST);

	$_POST = array_map('stripslashes_deep', $_POST);

	$_GET = array_map('stripslashes_deep', $_GET);

	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);

}



$_GET['m'] = 'Admin';

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('PHP �汾������ڵ���5.3.0 !');



define('DIR_SECURE_CONTENT', 'powered by http://www.dragondean.cn');



// ��������ģʽ ���鿪���׶ο��� ����׶�ע�ͻ�����Ϊfalse

define('APP_DEBUG', true);



define('APP_PATH','./Application/');

require './#DFrame/DFrame.php';