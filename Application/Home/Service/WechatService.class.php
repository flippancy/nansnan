<?php
namespace Home\Service;
class WechatService extends CommonService {
    public function reply($data){
        // $appID = 'wxeed8f73343e03675';
        // $appsecret = '124bc1b116d4e895654f4b97a19766f2';
        $appID = 'wxfd3e0d3a3ecad9d9';
        $appsecret = '4f238b819708f75ce8ec79fad5232a4c';

        if('text' == $data['MsgType']){
            $info = explode(" ",$data['Content']);
            switch ($info[0]) {
                case '0':
                    $reply = array("发弹幕", 'text');
                    break;
                case '1':
                    $reply = array("创建房间", 'text');
                    break;
                case '弹幕':
                    $Wx = M('Wx')->where(array('openid' => $data['FromUserName']))->find();
                    if ($Wx == null or $Wx == false) {
                        $reply = array("微信号未登记", 'text');
                    }else{
                        $this->sock($info[1], $Wx['nickname'], $Wx['roomid']);
                        $reply = array('弹幕发送成功', 'text');
                    }
                    // $content = date("Y-m-d H:i:s",time())."\nopenid:".$user_info['openid']."\nnickname:".$user_info['nickname'];
                    break;
                case '登记':
                    $Wx = M('Wx')->where(array('openid' => $data['FromUserName']))->find();
                    if ($Wx != null && $Wx != false) {
                        $reply = array("微信号已登记", 'text');
                        break;
                    }
                    $token_access_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appID."&secret=".$appsecret;
                    $access_token_json = file_get_contents($token_access_url);
                    $access_token = json_decode($access_token_json, true);

                    $user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token['access_token']."&openid=".$data['FromUserName']."&lang=zh_CN";
                    $user_info_json = file_get_contents($user_info_url);
                    $user_info = json_decode($user_info_json, true);

                    $new_wx = M("Wx"); 
                    $new_data['openid'] = $user_info['openid'];
                    $new_data['nickname'] = $user_info['nickname'];
                    $new_data['sex'] = $user_info['sex'];
                    if(false === ($new_wx->create($new_data))){
                        $reply = array("登记失败", 'text');
                    }else{
                        $id = $new_wx->add($new_data);
                        $reply = array("登记成功，登记id：".$id, 'text');
                    }
                    break;
                case '注册':
                    if ($info[1] == null or $info[1] == false or $info[2] == null or $info[2] == false) {
                        $reply = array("注册格式：注册 用户名 密码", 'text');
                        break;
                    }
                    $Wx = M('Wx')->where(array('openid' => $data['FromUserName']))->find();
                    if ($Wx == null or $Wx == false) {
                        $reply = array("微信号未登记", 'text');
                        break;
                    }
                    $User2 = M('User')->where(array('wx_id' => $Wx['id']))->find();
                    if ($User2 != null && $User2 != false) {
                        $reply = array("微信号已注册\n"."用户名：".$User2['username'], 'text');
                        break;
                    }
                    $User = M('User')->where(array('username' => $info[1]))->find();
                    if ($User != null && $User != false) {
                        $reply = array("用户名已使用", 'text');
                        break;
                    }

                    $data['username'] = $info[1];
                    $data['loginname'] = $Wx['nickname'];
                    $data['salt'] = randomkeys(6);
                    $data['wx_id'] = $Wx['id'];
                    $data['password'] = md5($data['salt'].$info[2]);
                    if(false === (M('User')->create($data))){
                        $reply = array("用户创建失败", 'text');
                    }else{
                        $id = M('User')->add($data);
                        $reply = array("用户创建成功", 'text');
                    }
                    break;
                case '绑定':
                    $reply = $this->bind($data, $info);
                    break;
                case '格式':
                    $reply = array("绑定格式：绑定 账号 密码\n"
                        ."弹幕格式：弹幕 信息\n"
                        ."注册格式：注册 用户名 密码", 'text');
                    break;
                case '位置':
                    $reply = array("位置", 'text');
                    break;
                case '颜色':
                    $reply = array("颜色", 'text');
                    break;
                case '帮助':
                    $help = "使用步骤\n"
                        ."->第一次使用需要登记账号(发送登记)\n"
                        ."->绑定房间(扫码或发送绑定信息)\n"
                        ."->发弹幕\n"
                        ."->注册用户可在官网使用更多功能\n"
                        ."发送格式查看基本格式";
                    $reply = array($help, 'text');
                    break;
                default:
                    $reply = array($info[0], 'text');
                    break;
            }
        } elseif('event' == $data['MsgType'] && 'subscribe' == $data['Event']){
            $reply = array("欢迎您关注公众号！\n"."发送帮助查看使用说明", 'text');
        } elseif('event' == $data['MsgType'] && 'SCAN' == $data['Event']){
            $reply = $this->bind_scan($data);
        } elseif('event' == $data['MsgType'] && 'CLICK' == $data['Event']){
            switch ($data['EventKey']) {
                case '弹幕':
                    $reply = array('弹幕格式：弹幕 信息', 'text');
                    break;
                case '登记':
                    $Wx = M('Wx')->where(array('openid' => $data['FromUserName']))->find();
                    if ($Wx != null && $Wx != false) {
                        $reply = array("微信号已登记", 'text');
                        break;
                    }
                    $token_access_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appID."&secret=".$appsecret;
                    $access_token_json = file_get_contents($token_access_url);
                    $access_token = json_decode($access_token_json, true);

                    $user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token['access_token']."&openid=".$data['FromUserName']."&lang=zh_CN";
                    $user_info_json = file_get_contents($user_info_url);
                    $user_info = json_decode($user_info_json, true);

                    $new_wx = M("Wx"); 
                    $new_data['openid'] = $user_info['openid'];
                    $new_data['nickname'] = $user_info['nickname'];
                    $new_data['sex'] = $user_info['sex'];
                    if(false === ($new_wx->create($new_data))){
                        $reply = array("登记失败", 'text');
                    }else{
                        $id = $new_wx->add($new_data);
                        $reply = array("登记成功，登记id：".$id, 'text');
                    }
                    break;
                case '注册':
                    $reply = array("注册格式：注册 用户名 密码", 'text');
                    break;
                case '绑定':
                    $reply = array("绑定格式：绑定 账号 密码", 'text');
                    break;
                case '位置':
                    $reply = array("位置", 'text');
                    break;
                case '颜色':
                    $reply = array("颜色", 'text');
                    break;
                case '帮助':
                    $help = "使用步骤\n"
                        ."->第一次使用需要登记账号(发送登记)\n"
                        ."->绑定房间(扫码或发送绑定信息)\n"
                        ."->发弹幕\n"
                        ."->注册用户可在官网使用更多功能\n"
                        ."发送格式查看基本格式";
                    $reply = array($help, 'text');
                    break;
                default:
                    break;
            }
        } else {
            exit;
        }
        return $reply;
    }

    public function sock($message, $nickname, $roomid, $color = 'black', $position = '0'){
        $client = stream_socket_client('tcp://0.0.0.0:7273');
        if(!$client)exit("can not connect");
        $new_message = array(
            'type'=>'message',
            'loginname'=>$nickname,
            'roomid'=>$roomid,
            'message'=>$message,
            'color'=>$color,
            'position'=>$position,
        );
        $data = json_encode($new_message);
        fwrite($client, "$data"."\n");
    }

    public function getQR($roomid){
        $appID = 'wxfd3e0d3a3ecad9d9';
        $appsecret = '4f238b819708f75ce8ec79fad5232a4c';
        $token_access_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appID."&secret=".$appsecret;
        $access_token_json = file_get_contents($token_access_url);
        $access_token = json_decode($access_token_json, true);

        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token['access_token'];
        $data = '{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$roomid.'}}}';
        $qr = $this->do_post_request($url, $data);
        $qr = json_decode($qr, true);
        return $qr['ticket'];
    }


    public function do_post_request($url, $data, $optional_headers = null)
    {
        $params = array('http' => array(
            'method' => 'POST',
            'content' => $data
        ));
        if ($optional_headers !== null) {
           $params['http']['header'] = $optional_headers;
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($url, 'rb', false, $ctx);
        if (!$fp) {
           // throw new Exception("Problem with $url, $php_errormsg");
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            // throw new Exception("Problem reading data from $url, $php_errormsg");
        }
        return $response;
    }

    public function bind($data, $info){
        $Room = M("Room");
        $Wx = M('Wx')->where(array('openid' => $data['FromUserName']))->find();
        if ($Wx == null or $Wx == false) {
            $reply = array("微信号没有登记", 'text');
        }else{
            $result = $Room->where(array('name' => $info[1]))->find();
            if ($result == null or $result == false) {
                $reply = array('找不到房间', 'text');
            }else{
                if(md5($result['salt'] . $info[2]) == $result['password']){
                    $new_data['roomid'] = $result['id'];
                    M('Wx')->where(array('openid' => $data['FromUserName']))->save($new_data);
                    $reply = array('绑定房间成功', 'text');
                }else{
                    $reply = array('错误密码', 'text');
                }
            }
        }
        return $reply;
    }

    public function bind_scan($data){
        $Room = M("Room");
        $Wx = M('Wx')->where(array('openid' => $data['FromUserName']))->find();
        if ($Wx == null or $Wx == false) {
            $reply = array("微信号没有登记", 'text');
        }else{
            $result = $Room->where(array('id' => $data['EventKey']))->find();
            if ($result == null or $result == false) {
                $reply = array('找不到房间', 'text');
            }else{
                $new_data['roomid'] = $result['id'];
                M('Wx')->where(array('openid' => $data['FromUserName']))->save($new_data);
                $reply = array('绑定房间成功', 'text');
            }
        }
        return $reply;
    }
}
