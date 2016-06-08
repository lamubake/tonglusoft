<?php
class MD5SignUtil 
{
	function sign($content,$key) 
	{
		try 
		{
			if (null == $key) 
			{
				throw new SDKRuntimeException("�Ƹ�ͨǩ��key����Ϊ�գ�"."<br>");
			}
			if (null == $content) 
			{
				throw new SDKRuntimeException("�Ƹ�ͨǩ�����ݲ���Ϊ��"."<br>");
			}
			$signStr = $content ."&key=".$key;
			return strtoupper(md5($signStr));
		}
		catch (SDKRuntimeException $e) 
		{
			die($e->errorMessage());
		}
	}
}
?>