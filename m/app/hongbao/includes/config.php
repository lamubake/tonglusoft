<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
date_default_timezone_set("asia/shanghai");
define("DBHOST","localhost:3306");//��������ַ
define("DBUSER","root");//���ݿ��û���
define("DBPASS","123123!@");//���ݿ��½����
define("DBDATA","testfenxiao");//���ݿ�����
$db=mysql_connect(DBHOST,DBUSER,DBPASS) or die("���ݿ����Ӵ����������Ա��ϵ");
mysql_query("SET NAMES 'GBK'");
mysql_select_db(DBDATA,$db);

define('APPID',"wx1559595cd8992e28");//APPID
define('APPSECRET',"1afe9ca4b0b5882ea2c81ff2a554bfb3");//APPSECRET
define('PARTNERKEY',"10000000000000000000000000001234");//��Կ
define('MCHID',"1249974901");//�̻���
define('URL',"http://nxn.8208111.com");//������ַ ���Ҫ��/
?>