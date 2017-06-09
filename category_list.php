<?php
header('Content-type:text/html;charset=utf-8');
error_reporting(0);
include "./lib/Mysqi.php";
$db = ConnectMysqli::getIntance();
$sql = "select * from category";
$totalCount = $db->getCount($sql);
//分页数
$page = ceil($totalCount/10);
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
<div class="layui-tab layui-tab-brief main-tab-container">
    <ul class="layui-tab-title main-tab-title">
      <a href="category_list.php"><li class="layui-this">栏目列表</li></a>
      <a href="category_add.php"><li>添加栏目</li></a>
      <div class="main-tab-item">栏目管理</div>
    </ul>
    <div class="layui-tab-content">
      <div class="layui-tab-item layui-show">
      <form class="layui-form">

            <table class="list-table">
              <thead>
                <tr>
                  <th>排序</th>
                  <th>ID</th>
                  <th>栏目名称</th>
                  <!--<th>栏目级别</th>-->
                  <!--<th>更新时间</th>-->
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
<!--                <tr>
                  <td>1</td>
                  <td>1</td>
                  <td>
                    学无止境
                  </td>
                  <td>文章模型</td>
                  <td style="text-align: center;">
                    <a href="./category_add.html" class="layui-btn layui-btn-small" title="编辑"><i class="layui-icon"></i></a>
                    <a class="layui-btn layui-btn-small layui-btn-danger del_btn" category-id="1" title="删除" category-name="学无止境"><i class="layui-icon"></i></a>
                  </td>
                </tr>
                <tr>
                  <td><input name="sorts[8]" type="text" value="20" lay-verify="number" class="layui-input"></td>
                  <td>8</td>
                  <td>
                    ├─杂谈
                  </td>
                  <td>文章模型</td>
                  <td style="text-align: center;">
                    <a href="./category_add.html" class="layui-btn layui-btn-small" title="编辑"><i class="layui-icon"></i></a>
                    <a class="layui-btn layui-btn-small layui-btn-danger del_btn" category-id="8" title="删除" category-name="杂谈"><i class="layui-icon"></i></a>
                  </td>
                </tr>-->
              </tbody>
              <thead>
                <!--<tr>-->
                  <!--<th colspan="5"><button class="layui-btn layui-btn-small" lay-submit="" lay-filter="sort">排序</button></th>-->
                <!--</tr>-->
              </thead>
            </table>
           <div id="demo1"></div>
      </form>
      </div>
    </div>
</div>

<script type="text/javascript">
layui.use(['element', 'layer', 'form','laypage'], function(){
  var element = layui.element()
  ,jq = layui.jquery
  ,form = layui.form()
  ,laypage = layui.laypage;
  
  var laypage = layui.laypage
  ,layer = layui.layer;
  
   laypage({
    cont: 'demo1'
    ,pages: <?php echo $totalCount;?> //总页数
    ,groups: <?php echo $page;?> //连续显示分页数
    ,first: false
    ,last: false
    ,jump:function(obj,first){
       GetCategoryList(obj.curr);
    }
  });

  //图片预览
  jq('.list-table td .thumb').hover(function(){
    jq(this).append('<img class="thumb-show" src="'+jq(this).attr('thumb')+'" >');
  },function(){
    jq(this).find('img').remove();
  });
  //链接预览
  jq('.list-table td .link').hover(function(){
    var link = jq(this).attr('href');
    layer.tips( link, this, {
    tips: [2, '#009688'],
    time: false
  });
  },function(){
    layer.closeAll('tips');
  });

  //监听提交
  form.on('submit(sort)', function(data){
    loading = layer.load(2, {
      shade: [0.2,'#000'] //0.2透明度的白色背景
    });
    var param = data.field;
    jq.post('',param,function(data){
      if(data.code == 200){
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

  //ajax删除
  jq('.del_btn').click(function(){
    var name = jq(this).attr('category-name');
    var id = jq(this).attr('category-id');
    layer.confirm('确定删除【'+name+'】?', function(index){
      loading = layer.load(2, {
        shade: [0.2,'#000'] //0.2透明度的白色背景
      });
      jq.post('',{'id':id},function(data){
        if(data.code == 200){
          layer.close(loading);
          layer.msg(data.msg, {icon: 1, time: 1000}, function(){
            location.reload();//do something
          });
        }else{
          layer.close(loading);
          layer.msg(data.msg, {icon: 2, anim: 6, time: 1000});
        }
      });
    });
    
  });
  
 function deleteLoad(){
  //ajax删除
  jq('.del_btn').click(function(){
    var name = jq(this).attr('cat-name');
    var cat_id = jq(this).attr('cat-id');
    layer.confirm('确定删除【'+name+'】?', function(index){
      loading = layer.load(2, {
        shade: [0.2,'#000'] //0.2透明度的白色背景
      });
      jq.post('./categoryDelete.php',{'cat_id':cat_id},function(data){
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
    });
  });
} 
    function GetCategoryList(curr){
        jq.post('./categoryList.php',{'page':curr},function(data){
            var data = eval('('+data+')');
            if(data.code == 0){
                var len = data.data.length;
                var str = '';
                for(var i=0;i<len;i++){ 
                    str += '<tr>'
                        +'<td>'+data.data[i].order_sort+'</td>'
                        +'<td>'+data.data[i].cat_id+'</td>'
                        +'<td>'+data.data[i].cat_name+'</td>'
//                        +'<td>'+data.data[i].level+'</td>'
                        +'<td style="text-align: center;">'
                            +'<a href="./category_add.php?cat_id='+data.data[i].cat_id+'" class="layui-btn layui-btn-small" title="编辑"><i class="layui-icon"></i></a>'
                            +'<a class="layui-btn layui-btn-small layui-btn-danger del_btn"  cat-id="'+data.data[i].cat_id+'" title="删除" cat-name="'+data.data[i].cat_name+'"><i class="layui-icon"></i></a>'
                        +'</td>'
                    +'</tr>';
                }
                jq('tbody').html(str);
                deleteLoad();
            }else{
                layer.msg(data.msg, {icon: 2, anim: 6, time: 1000});
            }
        });
    }  
  
})
</script>
</body>
</html>