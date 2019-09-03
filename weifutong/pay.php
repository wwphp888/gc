<?php
// exit;
include 'request.php';
$req = new Request();
$data = array(
	'out_trade_no' => $_GET['order_id'],
	'sub_openid' => $_GET['openid'],
	'body' => 'To Pay',
	'total_fee' => $_GET['money'] * 100,
	'mch_create_ip' => $_SERVER['REMOTE_ADDR'],
);
if( empty( $_GET['order_id'] ) ||  empty( $_GET['money'] ) || empty( $_GET['openid'] )  )
{
	die('Error');
}
$res = $req->submitOrderInfo( $data );
$result = json_decode( $res, true );
$token_id  = $result['token_id'];
$url = "https://pay.swiftpass.cn/pay/jspay?token_id={$token_id}&showwxtitle=1";
header( "Location: $url");
exit;
?>