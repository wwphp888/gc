<?php
include 'request.php';
$req = new Request();
$rest = $req->callback();

if( $rest  )
{
	$xml = file_get_contents('php://input');
	file_put_contents( dirname( __FILE__ ).'/xml_2.txt', $xml."\r\n\r\n", FILE_APPEND );
	$res = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
	$r = explode('A',$res->out_trade_no );
	$uid =  $r[0];
	$con = mysql_connect("localhost","root","Yashina@@KKK1996.");
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	$out_trade_no = $res->out_trade_no;
	 
	$money = $res->total_fee / 100 ;
	$time = time();
	$ip = $_SERVER['REMOTE_ADDR'];
	
	mysql_select_db("shaizi", $con);
	mysql_query("set names 'utf8'");
	$result1 = mysql_query("select * from dd_charge where order_sn='{$out_trade_no}' ");
	$r1 = mysql_fetch_array( $result1 ); 
	if( !empty( $r1 ) && $r1['paid'] == 0 )
	{
		mysql_query("update dd_charge set paid=1 where order_sn='{$out_trade_no}' ");
		mysql_query("update dd_user set money=money+$money where id='{$uid}'");
	}
}
else
{
	echo 'error';
}
?>