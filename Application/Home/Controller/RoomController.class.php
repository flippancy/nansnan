<?php
namespace Home\Controller;
use Think\Controller;
class RoomController extends CommonController {
    public function index(){
    	$this->init();
        $this->display();
    }
    public function danmu(){
    	$this->init();
        $Wx = D('Wechat' ,'Service');
        $qrurl = $Wx->getQR(session(C("ROOMID")));
        $qrurl = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrurl;
        $danmu_color = M('User')->where(array('id' => session(C("USERID"))))->field('danmu_color')->find();

        $this->assign('qrurl',$qrurl);
        $this->assign('danmu_color',$danmu_color['danmu_color']);
    	$this->assign('roomid',session(C("ROOMID")));
        $this->assign('loginname',session(C("LOGINNAME")));
        $this->display();
    }
    public function add(){
    	$this->init();
        if(IS_POST) {
            $Room = M("Room");
            $data['name'] = I('post.name');
            $data['password'] = I('post.password');
            $result = $Room->where(array('name' => $data['name']))->find();
            if($result == null or $result == false){
                $data['salt']      = randomkeys(6);
                $data['password']  = md5($data['salt'].$data['password']);
                $data['belong_to_user'] = session(C("USERID"));
                if(false === ($Room->create($data))){
                    echo json_encode(1);
                }else{
	                $id = $Room->add($data);
	                echo json_encode(0);
                }
            }else{
				echo json_encode(2);
            }
        }else{
            $this->display();
        }
    }
    public function into(){
    	$this->init();
        if(IS_POST) {
            $Room = M("Room");
            $data['name'] = I('post.name');
            $data['password'] = I('post.password');
            $result = $Room->where(array('name' => $data['name']))->find();
            if($result != null && $result != false){
            	if(md5($result['salt'] . $data['password']) == $result['password']){
            		$Room->where(array('id' => $result['id']))->setInc('see_num');
            		session(C("ROOMID"), $result['id']);
            		echo json_encode(0);
            	}else{
            		echo json_encode(1);
            	}
            }else{
				echo json_encode(2);
            }
        }else{
            $this->display();
        }
    }
    public function intoById(){
        $this->init();
        if (IS_GET) {
            $userid = session(C("USERID"));
            $roomid = I('get.id');
            $Room = M("Room");
            $result = $Room->where(array('id' => $roomid))->find();
            if ($result['belong_to_user'] == $userid) {
                $Room->where(array('id' => $roomid))->setInc('see_num');
                session(C("ROOMID"), $roomid);
                $Wx = D('Wechat' ,'Service');
                $qrurl = $Wx->getQR(session(C("ROOMID")));
                $qrurl = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrurl;
                $danmu_color = M('User')->where(array('id' => session(C("USERID"))))->field('danmu_color')->find();

                $this->assign('danmu_color',$danmu_color['danmu_color']);
                $this->assign('qrurl',$qrurl);
                $this->assign('roomid',session(C("ROOMID")));
                $this->assign('loginname',session(C("LOGINNAME")));
                $this->display('danmu');
            }else{
                $this->error('您不是房间创建者');
            }
        }else{
            $this->error('请求操作异常');
        }
    }
    public function danmuPublic(){
        $Wx = D('Wechat' ,'Service');
        $qrurl = $Wx->getQR(session(C("ROOMID")));

        $qrurl = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrurl;
        $this->assign('qrurl',$qrurl);
        $this->assign('roomid',session(C("ROOMID")));
        $this->display();
    }
    public function delete(){
        $this->init();
        if (IS_GET) {
            $userid = session(C("USERID"));
            $roomid = I('get.id');
            $Room = M("Room");
            $result = $Room->where(array('id' => $roomid))->find();
            if ($result['belong_to_user'] == $userid) {
                $Room->where(array('id' => $roomid))->delete();
                $this->success('删除成功', U('Index/mine'));
            }else{
                $this->error('您不是房间创建者');
            }
        }else{
            $this->error('请求操作异常');
        }
    }
}