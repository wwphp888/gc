<?php
//����MYSQL���ݿ�������Ϣ
#���ݿ�����
include_once '../ewmadmin/inc/config.php';
$mysql_server_name	=	$host; 	//���ݿ����������
$mysql_username		=	$user; 		// �������ݿ��û���
$mysql_password		=	$password;			// �������ݿ�����
$mysql_database		=	$db; 		// ���ݿ������
$mysql_conn = mysql_connect($mysql_server_name, $mysql_username, $mysql_password);
?>