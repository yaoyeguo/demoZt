<?php
error_reporting(0);
include './lib/Image.php';
include './lib/Mysqi.php';

if ($_POST) {
    $post = $_POST;
    if (empty($post['name'])) {
        echo json_encode(array('code' => 1, 'msg' => '栏目名称必填'));
        exit();
    }else{
        $post['cat_name'] = $post['name'];
        unset($post['name']);
    }
    
    if($post['sort']){
        $post['order_sort'] = $post['sort'];
        unset($post['sort']);
    }
    $post['modified_time'] = time();
    $db = ConnectMysqli::getIntance();
    if ($post['cat_id']) {
        $cat_id = $post['cat_id'];
        unset($post['cat_id']);
        if ($db->update('category', $post, 'cat_id=' . $cat_id)) {
            echo json_encode(array('code' => 0, 'msg' => '更新成功'));
            exit();
        } else {
            echo json_encode(array('code' => 1, 'msg' => '更新异常'));
            exit();
        }
    } else {
        if ($db->insert('category', $post)) {
            echo json_encode(array('code' => 0, 'msg' => '保存成功'));
            exit();
        } else {
            echo json_encode(array('code' => 1, 'msg' => '保存异常'));
            exit();
        }
    }
}
