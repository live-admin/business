<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<?php
	$link=mysql_connect("192.168.2.13","colourlife","colourlife!@#") or die("链接失败！".mysql_error());
	mysql_select_db('colourlife') or die('打开数据库失败'.mysql_error());

	//头一天的安装量
$result=mysql_query("SELECT  count(*)  FROM `customer_api_auth`  where DATEDIFF (now(),from_unixtime (`create_time`) )=1");
while ($row=mysql_fetch_row($result)) {
	foreach ($row as $data) {
		echo "昨日安装量：$data <br>";
	}
}
mysql_free_result($result);
//头一周的安装量
$result=mysql_query("SELECT  count(*)  FROM `customer_api_auth`  where DATEDIFF (now(),from_unixtime (`create_time`) ) between 1 and 7"); 
while ($row=mysql_fetch_row($result)) {
	foreach ($row as $data) {
		echo "一周安装量：$data <br>";
	}
}
mysql_free_result($result);
//全部的安装量
$result=mysql_query("SELECT  count(*)  FROM `customer_api_auth`");
while ($row=mysql_fetch_row($result)) {
	foreach ($row as $data) {
		echo "历史安装量：$data <br>";
	}
}
mysql_free_result($result);
//头一天的注册量
$result=mysql_query("SELECT  count(*)  FROM `customer`  where DATEDIFF (now(),from_unixtime (`create_time`) )=1");
while ($row=mysql_fetch_row($result)) {
	foreach ($row as $data) {
		echo "昨日注册量：$data <br>";
	}
}
mysql_free_result($result);
//头一周的注册量

$result=mysql_query("SELECT  count(*)  FROM `customer`  where DATEDIFF (now(),from_unixtime (`create_time`) ) between 1 and 7");
while ($row=mysql_fetch_row($result)) {
	foreach ($row as $data) {
		echo "一周注册量：$data <br>";
	}
}
mysql_free_result($result);
$result=mysql_query("SELECT  count(*)  FROM `customer`");
while ($row=mysql_fetch_row($result)) {
	foreach ($row as $data) {
		echo "历史注册量：$data <br>";
	}
}
mysql_free_result($result);
mysql_close($link);
?>
