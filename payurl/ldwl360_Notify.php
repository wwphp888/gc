<?php
include_once("config.php");
if ($mysql_conn){
mysql_select_db($mysql_database, $mysql_conn);
mysql_query("SET NAMES 'GBK'");//��������
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//��ʼ���ղ��� (��ע�����ִ�Сд)
//-----------------------------------------------------------------
$payNO	    =	isset($_REQUEST["ddh"])?$_REQUEST["ddh"]:"";	//֧�����Ķ�������
$Money2		=	isset($_REQUEST["PayJe"])?$_REQUEST["PayJe"]:0;			//������
$Moneys      =   floatval($Money2);
$dingdannum		=	isset($_REQUEST["PayMore"])?$_REQUEST["PayMore"]:"";//����˵��
$key		=	isset($_REQUEST["key"])?$_REQUEST["key"]:"";			//ǩ��
$key2 	= 73566894;// �ĳ��Լ�����Կ ��U֧����վƽ̨��Ա����ҳ�� �̻���Կ

//$key		=	73566894;
//$dingdannum		=	"20";
//$Moneys		=	10;

$bjsnk = strcmp($key2,$key);
//-----------------------------------------------------------------
if ($bjsnk===0)
{
/***��һ���ж� ֧����¼  �տ  �Ĵ���*****************************************************************/
$status = 0;
$rs=mysql_query("Select * From dd_charge Where id='$dingdannum' and money='$Moneys' and status='$status'");	//���Ҷ����� 
$num=mysql_num_rows($rs);
if($num>0){
$dingdanok	=	true;	//��������
//echo "��������<br>";
}
else{
$dingdanok	=	false;	//����������
//echo "����������<br>";
//ob_clean();
echo "Error";
}
if ($dingdanok==true){

$sql="select * from dd_charge where id='$dingdannum' and money='$Moneys' ";
$query=mysql_query($sql,$mysql_conn);
$rs=mysql_fetch_array($query);
$uuid = $rs['user_id'];// �û�UID
$payid = $rs['id'];// ����id
$moneyk = $rs['money'];
//echo $uuid."-".$payid;
    $sql="select * from dd_user where id='$uuid'";
    $query=mysql_query($sql,$mysql_conn);
    $rs=mysql_fetch_array($query);
    $money = $rs['money']+$Moneys;
    $time = time();
    $parent1 = $rs['parent1'];
    $parent2 = $rs['parent2'];
    $parent3 = $rs['parent3'];
    //佣金
    $sql = "Select * From dd_cash";
    $query = mysql_query($sql);
    $rs = mysql_fetch_array($query);
    $money1 = $moneyk * $rs['parent1'] *0.01;
    $money2 = $moneyk * $rs['parent2'] *0.01;
    $money3 = $moneyk * $rs['parent3'] *0.01;
    if($parent1 > 0){
        $sql = "select * from dd_user where id='".$parent1."'";
        $query = mysql_query($sql,$mysql_conn);
        $rs = mysql_fetch_array($query);
        $money11 = $rs['money']+$money1;
        $expense = $rs['expense']+$money1;
        mysql_query("Update dd_user set money='$money11',expense='$expense' Where id='".$parent1."'");
        mysql_query("INSERT INTO dd_expense (user_id,buyer_id,moneys,create_time,type,level) values ('".$parent1."','".$uuid."','".$money1."','".$time."','1','1')");
    }
    if($parent2 > 0){
        $sql = "select * from dd_user where id='".$parent2."'";
        $query = mysql_query($sql,$mysql_conn);
        $rs = mysql_fetch_array($query);
        $money22 = $rs['money']+$money2;
        $expense = $rs['expense']+$money2;
        mysql_query("Update dd_user set money='$money22',expense='$expense' Where id='".$parent2."'");
        mysql_query("INSERT INTO dd_expense (user_id,buyer_id,moneys,create_time,type,level) values ('".$parent2."','".$uuid."','".$money2."','".$time."','1','2')");
    }
    if($parent3 > 0){
        $sql = "select * from dd_user where id='".$parent3."'";
        $query = mysql_query($sql,$mysql_conn);
        $rs = mysql_fetch_array($query);
        $money33 = $rs['money']+$money3;
        $expense = $rs['expense']+$money3;
        mysql_query("Update dd_user set money='$money33',expense='$expense' Where id='".$parent3."'");
        mysql_query("INSERT INTO dd_expense (user_id,buyer_id,moneys,create_time,type,level) values ('".$parent3."','".$uuid."','".$money3."','".$time."','1','3')");
    }
mysql_query("Update dd_user set money='$money' Where id='".$uuid."'");//���»�Ա���
mysql_query("Update dd_charge set status=1,paid='$Moneys',pay_time='$time' Where id='".$payid."'");//���¶���ID
//ob_clean();
echo "Success";//�˴�����ֵ��Success�������޸ģ�����⵽���ַ���ʱ���ͱ�ʾ��ֵ�ɹ�
}
}
else
{ 
echo "Key";	//Կ�ײ���ȷ
} 
//*******************************************************************
mysql_close($mysql_conn);
}else{
echo "Errordb";		//�������ݿ�ʧ��
}
//*******************************************************************
?>
