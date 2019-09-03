<?PHP 
ignore_user_abort();//关闭浏览器仍然执行
set_time_limit(0);//让程序一直执行下去
$interval=1;//每隔一定时间运行
for ($i=0; $i < 60; $i++) { 
    $msg=date("Y-m-d H:i:s");
    echo $msg;
    file_get_contents('http://www.dailaimeigw.cn/index.php?m=&c=Index&a=btclog');//记录日志
    sleep($interval);//等待时间，进行下一次操作。
}
?>