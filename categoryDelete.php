<?php
header('Content-type:text/html;charset=utf-8');
error_reporting(0);
include './lib/Mysqi.php';

if($_POST){
    $id = (int)$_POST['cat_id'];
    $db = ConnectMysqli::getIntance();
    if($db->deleteOne('category',array('cat_id'=>$id))){
        echo json_encode(array('code'=>0,'msg'=>'删除成功'));
        exit();
    }else{
        echo json_encode(array('code'=>1,'msg'=>'删除失败'));
        exit();
    }
}