<?php

error_reporting(0);
include './lib/Image.php';
include './lib/Mysqi.php';

//图片路径URL
if ($_SERVER['REMOTE_ADDR'] == '::1') {
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
}
define('IMAGE_URL', './upload/images/');

//上传图片
if ($_FILES['file']) {
    $upload = new UploadFile();
    //设置上传文件大小
    $upload->maxSize = 1024 * 1024 * 2; //最大2M
    //设置上传文件类型
    $upload->allowExts = explode(',', 'jpg,gif,png,bmp');
    //设置附件上传目录
    $upload->savePath = './upload/images/';
    $upload->saveRule = time() . rand(10000, 99999);
    if (!$upload->upload()) {
        //捕获上传异常
        $msg = $this->error($upload->getErrorMsg());
        echo json_encode(array('code' => 1, 'msg' => $msg));
        exit();
    } else {
        //取得成功上传的文件信息
        $imageInfo = $upload->getUploadFileInfo();
        echo json_encode(array('code' => 0, 'msg' => '上传成功', 'data' => array('src' => IMAGE_URL . $imageInfo[0]['savename'])));
        exit();
    }
}

//添加图片信息
if ($_POST) {
    unset($_POST['file']);
    $post = $_POST;

    if (empty($post['name'])) {
        echo json_encode(array('code' => 1, 'msg' => '图片名称必填'));
        exit();
    }
    if (empty($post['cat_id'])) {
        echo json_encode(array('code' => 1, 'msg' => '栏目分类必填'));
        exit();
    }
    
    if (empty($post['image_url'])) {
        echo json_encode(array('code' => 1, 'msg' => '图片未上传'));
        exit();
    }
    $db = ConnectMysqli::getIntance();
    if ($post['imgId']) {
        $imgId = $post['imgId'];
        unset($post['imgId']);
        if ($db->update('image', $post, 'Id=' . $imgId)) {
            echo json_encode(array('code' => 0, 'msg' => '更新成功'));
            exit();
        } else {
            echo json_encode(array('code' => 1, 'msg' => '更新异常'));
            exit();
        }
    } else {
        if ($db->insert('image', $post)) {
            echo json_encode(array('code' => 0, 'msg' => '保存成功'));
            exit();
        } else {
            echo json_encode(array('code' => 1, 'msg' => '保存异常'));
            exit();
        }
    }
}

