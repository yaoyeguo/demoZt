<?php 
error_reporting(0);
include "./lib/Mysqi.php";
$db = ConnectMysqli::getIntance();
$category_list = $db->getAll('select * from category  order by order_sort asc');
if(!empty($_GET['imgId']) && is_numeric($_GET['imgId'])){
$sql = "select * from image where Id='{$_GET['imgId']}'";
$result = $db->getRow($sql);
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
    <title>Demo-后台管理中心</title>
    <link rel="stylesheet" href="./layui/css/layui.css">
    <link rel="stylesheet" href="./css/global.css">
    <script type="text/javascript" src="./layui/layui.js"></script>
</head>
<body>
<div class="layui-tab-brief main-tab-container">
    <ul class="layui-tab-title main-tab-title">
      <a href="image.php"><li>图片列表</li></a>
      <?php if($result){?>
      <a href="image_add.php"><li class="layui-this">更新图片</li></a>
      <?php } else {?>
      <a href="image_add.php"><li class="layui-this">添加图片</li></a>
      <?php } ?>
      <div class="main-tab-item">图片管理</div>
    </ul>
    <div class="layui-tab-content">
      <div class="layui-tab-item layui-show">
        <form class="layui-form">
          <div class="layui-tab layui-tab-card">
            <ul class="layui-tab-title">
              <li class="layui-this">添加</li>
<!--              <li>模板设置</li>
              <li>SEO设置</li>-->
            </ul>
            <?php if($result['Id']){?>
            <input type="hidden" name="imgId" value="<?php echo $result['Id'];?>">
            <?php }?>
            <div class="layui-tab-content">
              <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                  <label class="layui-form-label">图片名称</label>
                  <div class="layui-input-inline input-custom-width">
                      <input type="text" name="name" value="<?php echo $result['name']?>" lay-verify="required" autocomplete="off" placeholder="请输入栏目名称" class="layui-input">
                  </div>
                </div>
                  
                <div class="layui-form-item">
                  <label class="layui-form-label">选择栏目</label>
                  <div class="layui-input-inline input-custom-width">
                    <select name="cat_id" lay-verify="required">
                        <option value="">请选择</option>
                        <?php foreach($category_list as $key=>$val){?>
                        <option value="<?php echo $val['cat_id'];?>" <?php if($result['cat_id'] == $val['cat_id']){?> selected="selected" <?php }?>><?php echo $val['cat_name']?></option>
                        <?php }?>
                    </select>
                        
                  </div>
                  <div class="layui-form-mid layui-word-aux"></div>
                </div>
                  
                <div class="layui-form-item">
                  <label class="layui-form-label">图片描述</label>
                  <div class="layui-input-inline input-custom-width">
                    <textarea name="description" value="" lay-verify="" autocomplete="off" placeholder="请输入栏目描述" class="layui-textarea"><?php echo $result['description']?></textarea>
                  </div>
                </div>

                <div class="layui-form-item">
                  <label class="layui-form-label">图片地址</label>
                  <div class="layui-input-inline input-custom-width">
                    <input type="text" name="image_url" value="<?php echo $result['image_url']?>" lay-verify="required" autocomplete="off" placeholder="" class="layui-input">
                    <!--<div class="layui-box layui-upload-button">-->
                        <input name="file" class="layui-upload-file" type="file" id="image"> 
                        <!--<span class="layui-upload-icon"><i class="layui-icon"></i>图片</span>-->
                    <!--</div>-->
                  </div>
                  <div class="layui-form-mid layui-word-aux"></div>
                </div>
                <div class="layui-form-item">
                  <label class="layui-form-label">排序</label>
                  <div class="layui-input-inline input-custom-width">
                    <input type="sort" name="sort" value="<?php echo $result['image_url']?$result['sort']:'20';?>" lay-verify="number" autocomplete="off" placeholder="数字越小越靠前" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                  <label class="layui-form-label">是否显示</label>
                  <div class="layui-input-inline input-custom-width">
                    <input type="radio" name="is_menu" value="1" title="是" <?php if($result['is_menu'] == '1'){ ?>checked <?php } ?>><div class="layui-unselect layui-form-radio layui-form-radioed"><i class="layui-anim layui-icon"></i><span>是</span></div>
                    <input type="radio" name="is_menu" value="0" title="否" <?php if($result['is_menu'] != '1'){ ?>checked <?php } ?>><div class="layui-unselect layui-form-radio"><i class="layui-anim layui-icon"></i><span>否</span></div></div>
                    <div class="layui-form-mid layui-word-aux">
                  </div>
                </div>
              </div>
 
              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit="" lay-filter="cate_add">立即提交</button>
                </div>
              </div>
            </div>
          </div>
          
        </form>
      </div>
    </div>
</div>
    
<script type="text/javascript">
layui.use(['form','upload','jquery'], function(){
  var form = layui.form()
  ,jq = layui.jquery;
  //图片上传
  layui.upload({
    url: './imageAdd.php'
    ,elem:'#image'
    ,method:'post'
    ,before: function(input){
      loading = layer.load(2, {
        shade: [0.2,'#000'] //0.2透明度的白色背景
      });
    }
    ,success: function(res){
      layer.close(loading);
      jq('input[name=image_url]').val(res.data.src);
      layer.msg(res.msg, {icon: 1, time: 1000});
    }
  }); 
  
  //监听提交
  form.on('submit(cate_add)', function(data){
    loading = layer.load(2, {
      shade: [0.2,'#000'] //0.2透明度的白色背景
    });
    var param = data.field;
    jq.post('./imageAdd.php',param,function(data){
        var data = eval('('+data+')');
      if(data.code == 0){
        layer.close(loading);
        layer.msg(data.msg, {icon: 1, time: 1000}, function(){
          location.reload();//do something
        });
      }else{
        layer.close(loading);
        layer.msg(data.msg, {icon: 2, anim: 6, time: 1000});
      }
    });
    return false;
  });
  
})
</script>
</body>
</html>

