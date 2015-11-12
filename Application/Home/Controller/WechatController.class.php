<?php
namespace Home\Controller;
use Think\Controller;
class WechatController extends CommonController {
    public function index(){
		/* 加载微信SDK */
		import('Com.ThinkWechat');
		$Wechat = D('Wechat' ,'Service');
		$weixin = new \ThinkWechat('chenjianxiang');

		$data = $weixin->request();
		
		list($content, $type) = $Wechat->reply($data);

		$weixin->response($content, $type);

        $this->display();
    }
}