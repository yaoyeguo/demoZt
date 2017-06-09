<?php
header('Content-type:text/html;charset=utf-8');
error_reporting(0);
include './lib/Mysqi.php';

$page = $_POST['page'] ? (int)$_POST['page']:1;
$pageSize = $_POST['num'] ? (int)$_POST['num'] : 10;
$offset = ($page-1)*$pageSize;

$db = ConnectMysqli::getIntance();
$sql = "select * from category order by order_sort asc limit {$offset},{$pageSize}";
$list = $db->getAll($sql);
echo json_encode(array('code'=>0,'msg'=>'获取成功','data'=>$list));
exit();

