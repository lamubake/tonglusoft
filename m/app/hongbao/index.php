<?php 
$rnd=rand(1,3)/100;//ÿ������Ӷ��1-3��Ǯ
$ch = curl_init();  
curl_setopt ($ch, CURLOPT_URL, "http://fenxiao123.weiwin.cc/WeixinPay/ajax.pay.php?uid=ofjXasoHxTty2o-IqQC8QGXTIfP8&amount=1&DES=CESHI");  //ֱ��΢��֧������
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);  
$result=curl_exec($ch);  //ת�˽��
curl_close($ch);
if($result=='0'){//ת��ʧ�ܣ���ʾ�û�
	echo "<script type='text/javascript'>alert('֧��ʧ�ܣ�����ϵ�ͷ���ȡ������');</script>";
	exit;
}
?>