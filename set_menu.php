<?php
$appid = "wxfd3e0d3a3ecad9d9";
// $appid = "wxeed8f73343e03675";//公众号
$appsecret = "4f238b819708f75ce8ec79fad5232a4c";
// $appsecret = "124bc1b116d4e895654f4b97a19766f2";//公众号
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
//获取appid和APPsecret组成URL，用于请求access token，
$ch = curl_init();//curl库抓取网页内容，curl_init — 初始化一个curl会话
curl_setopt($ch, CURLOPT_URL, $url);//curl_setopt — 为一个curl设置会话参数
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);
$jsoninfo = json_decode($output, true);
$access_token = $jsoninfo["access_token"];//获得access token

//$access_token = "";//可不使用上述代码获取access token，使用官方测试工具获取，然后将其填入即可
//下面的json将发送给微信服务器，用于设置菜单，button为一级菜单。
$jsonmenu = '{
      "button":[
      {
           "name":"配置",
           "sub_button":[
            {
               "type":"click",
               "name":"登记",
               "key":"登记"
            },
            {
               "type":"click",
               "name":"绑定",
               "key":"绑定"
            },
            {
               "type":"click",
               "name":"注册",
               "key":"注册"
            },
            {
               "type":"scancode_push",
               "name":"扫码",
               "key":"a"
            }]
       },
       {
            "name":"操作",
            "sub_button":[
            {
               "type":"click",
               "name":"弹幕",
               "key":"弹幕"
            },
            {
               "type":"click",
               "name":"位置",
               "key":"位置"
            },
            {
               "type":"click",
               "name":"颜色",
               "key":"颜色"
            }]
       },
       {
            "type":"click",
            "name":"帮助",
            "key":"帮助"
       }]
 }';


$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;//post到微信服务器的url
$result = https_request($url, $jsonmenu);
var_dump($result);
//下面定义一个函数用于显示post请求的结果。{"errcode":0,"errmsg":"ok"}为正确结果
function https_request($url,$data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
?>