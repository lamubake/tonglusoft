<?php
class PageController extends Controller{
	//构造函数，自动新建对象
 	function  __construct() {
		
	}

	function hufen(){
		$this->template($mb.'/hufen');
	}
	
	
	
}
?>