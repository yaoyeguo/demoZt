<?php
error_reporting(0);
//define your token
define("TOKEN", "weixin");//改成自己的TOKEN
define('APP_ID', 'wx166c4b6840ff4b80');//改成自己的APPID
define('APP_SECRET', 'c050ecb9dd328670bce66e6e55b28f15');//改成自己的APPSECRET
 
$wechatObj = new wechatCallbackapiTest(APP_ID,APP_SECRET);
$wechatObj->Run();
 
class wechatCallbackapiTest
{
    private $fromUsername;
    private $toUsername;
    private $times;
    private $keyword;
    private $app_id;
    private $app_secret;
    
    public function __construct($appid,$appsecret)
    {
        # code...
        $this->app_id = $appid;
        $this->app_secret = $appsecret;
    }
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }
    /**
     * 运行程序
     * @param string $value [description]
     */
    public function Run()
    {
        $this->responseMsg();
        $arr[]= "您好，这是自动回复，我现在不在，有事请留言，我会尽快回复你的^_^";
        echo $this->make_xml("text",$arr);
    }
    public function responseMsg()
    {   
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];//返回回复数据
        if (!empty($postStr)){
                $access_token = $this->get_access_token();//获取access_token
                $this->createmenu($access_token);//创建菜单
                //$this->delmenu($access_token);//删除菜单
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $this->fromUsername = $postObj->FromUserName;//发送消息方ID
                $this->toUsername = $postObj->ToUserName;//接收消息方ID
                $this->keyword = trim($postObj->Content);//用户发送的消息
                $this->times = time();//发送时间
                $MsgType = $postObj->MsgType;//消息类型
                if($MsgType=='event'){
                    $MsgEvent = $postObj->Event;//获取事件类型
                    if ($MsgEvent=='subscribe') {//订阅事件
                        $arr[] = "你好，我是xxx，现在我们是好友咯![愉快][玫瑰]";
                        echo $this->make_xml("text",$arr);
                        exit;
                    }elseif ($MsgEvent=='CLICK') {//点击事件
                        $EventKey = $postObj->EventKey;//菜单的自定义的key值，可以根据此值判断用户点击了什么内容，从而推送不同信息
                        $arr[] = $EventKey;
                        echo $this->make_xml("text",$arr);
                        exit;
                    }
                }
        }else {
            echo "this a file for weixin API!";
            exit;
        }
    }
    /**
     * 获取access_token
     */
    private function get_access_token()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->app_id."&secret=".$this->app_secret;
        $data = json_decode(file_get_contents($url),true);
        if($data['access_token']){
            return $data['access_token'];
        }else{
            return "获取access_token错误";
        }
    }
    /**
     * 创建菜单
     * @param $access_token 已获取的ACCESS_TOKEN
     */
    public function createmenu($access_token)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
        $arr = array( 
            'button' =>array(
                array(
                    'name'=>urlencode("模拟考试"),
                    'type'=>'view',
                    'url'=>'#'
                ),
                
                array(
                    'name'=>urlencode("网校培训咨询"),
                    'type'=>'view',
                    'key'=>'http://119.23.214.33/tp5demo/wx/advisory.html'
                ),
                array(
                    'name'=>urlencode("更多服务"),
                    'sub_button'=>array(
                        array(
                            'name'=>urlencode("公告"),
                            'type'=>'view',
                            'url'=>'http://119.23.214.33/tp5demo/wx/demo.php'
                        ),
                        array(
                            'name'=>urlencode("常见问题"),
                            'type'=>'view',
                            'url'=>'http://119.23.214.33/tp5demo/wx/demo.php'
                        ),
                        array(
                            'name'=>urlencode("联系客服"),
                            'type'=>'view',
                            'url'=>'http://119.23.214.33/tp5demo/wx/demo.php'
                        )
                    )
                ),
                array(
                    'name' => urlencode("实操考试"),
                    'sub_button' => array(
                        array(
                            'name' => urlencode("关于我们"),
                            'type' => 'view',
                            'url' => 'http://119.23.214.33/tp5demo/wx/demo.php'
                        ),
                        array(
                            'name' => urlencode("工作信息"),
                            'type' => 'view',
                            'url' => 'http://119.23.214.33/tp5demo/wx/demo.php'
                        ),

                    )
                ), 
            )
        );
        $jsondata = urldecode(json_encode($arr));
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$jsondata);
        curl_exec($ch);
        curl_close($ch);
    }
    /**
     * 查询菜单
     * @param $access_token 已获取的ACCESS_TOKEN
     */
    
    private function getmenu($access_token)
    {
        # code...
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$access_token;
        $data = file_get_contents($url);
        return $data;
    }
    /**
     * 删除菜单
     * @param $access_token 已获取的ACCESS_TOKEN
     */
    
    private function delmenu($access_token)
    {
        # code...
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$access_token;
        $data = json_decode(file_get_contents($url),true);
        if ($data['errcode']==0) {
            # code...
            return true;
        }else{
            return false;
        }
    }
        
    /**
     *@param type: text 文本类型, news 图文类型
     *@param value_arr array(内容),array(ID)
     *@param o_arr array(array(标题,介绍,图片,超链接),...小于10条),array(条数,ID)
     */
    
    private function make_xml($type,$value_arr,$o_arr=array(0)){
        //=================xml header============
        $con="<xml>
                    <ToUserName><![CDATA[{$this->fromUsername}]]></ToUserName>
                    <FromUserName><![CDATA[{$this->toUsername}]]></FromUserName>
                    <CreateTime>{$this->times}</CreateTime>
                    <MsgType><![CDATA[{$type}]]></MsgType>";
                    
          //=================type content============
        switch($type){
          
            case "text" : 
                $con.="<Content><![CDATA[{$value_arr[0]}]]></Content>
                    <FuncFlag>{$o_arr}</FuncFlag>";  
            break;
            
            case "news" : 
                $con.="<ArticleCount>{$o_arr[0]}</ArticleCount>
                     <Articles>";
                foreach($value_arr as $id=>$v){
                    if($id>=$o_arr[0]) break; else null; //判断数组数不超过设置数
                    $con.="<item>
                         <Title><![CDATA[{$v[0]}]]></Title> 
                         <Description><![CDATA[{$v[1]}]]></Description>
                         <PicUrl><![CDATA[{$v[2]}]]></PicUrl>
                         <Url><![CDATA[{$v[3]}]]></Url>
                         </item>";
                }
                $con.="</Articles>
                     <FuncFlag>{$o_arr[1]}</FuncFlag>";  
            break;
        } //end switch
         //=================end return============
        $con.="</xml>";
        return $con;
    }
 
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];    
                
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}
?>