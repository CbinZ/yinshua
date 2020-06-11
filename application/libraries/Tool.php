<?php

/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2019/3/20
 * Time: 11:25
 */
class Tool
{

	//sqlserver
	public function pod_sqlserver()
	{
		try {
			$db = new PDO("dblib:host=qybuganjiao.51vip.biz:47257;dbname=printerp2019","print","print");
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
			exit();
		}
		
		
		return $db;
	}
}
