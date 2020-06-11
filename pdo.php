<?php
phpinfo();
exit();


try
{
	$db = new PDO("dblib:host=qybuganjiao.51vip.biz:47257;dbname=printerp2019","print","print");

	$sql = "select count(*) count from proProductionDetail";
	$res = $db->query($sql);
	while ($row = $res->fetch()){
	  print_r($row);
	}
}catch (PDOException $e)
{
	echo 'Connection failed: ' . $e->getMessage();
	exit;
}


?>