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
            $data['type'] = I('post.type');
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
                    if($result['type'] == 'danmu'){
                        // 进入弹幕房间
                        echo json_encode(0);
                    }elseif($result['type'] == 'chat'){
                        // 进入聊天室
                        echo json_encode(3);
                    }else{
                        // 进入主题房间
                        echo json_encode(4);
                    }
            	}else{
                    // 密码错误
                    echo json_encode(1);
            	}
            }else{
                // 找不到房间
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
                $danmu_color = M('User')->where(array('id' => session(C("USERID"))))->field('danmu_color')->find();

                $this->assign('danmu_color',$danmu_color['danmu_color']);
                $this->assign('roomid',session(C("ROOMID")));
                $this->assign('loginname',session(C("LOGINNAME")));
                if($result['type'] == 'danmu'){
                    // 进入弹幕房间
                    $Wx = D('Wechat' ,'Service');
                    $qrurl = $Wx->getQR(session(C("ROOMID")));
                    $qrurl = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrurl;
                    $this->assign('qrurl',$qrurl);
                    $this->display('danmu');
                }elseif($result['type'] == 'chat'){
                    // 进入聊天室
                    $this->display('chat');
                }else{
                    // 进入主题房间
                    $Service = D('Photo','Service');
                    $Page = $Service->get_page(M('photo'));
                    $info = $Service->get_photo($info, $Page);
                    $show = $Page->show();

                    $this->assign('roomid',session(C("ROOMID")));
                    $this->assign('loginname',session(C("LOGINNAME")));
                    $this->assign('info',$info);
                    $this->assign('page',$show);

                    $this->display('theme');
                }
            }else{
                $this->error('您不是房间创建者');
            }
        }else{
            $this->error('请求操作异常');
        }
    }
    public function intoThemeById(){
        $this->init();
        if (IS_GET) {
            $userid = session(C("USERID"));
            $roomid = I('get.id');
            $Room = M("Room");
            $result = $Room->where(array('id' => $roomid))->find();
            $Room->where(array('id' => $roomid))->setInc('see_num');
            session(C("ROOMID"), $roomid);
            $danmu_color = M('User')->where(array('id' => session(C("USERID"))))->field('danmu_color')->find();

            $this->assign('danmu_color',$danmu_color['danmu_color']);
            $this->assign('roomid',session(C("ROOMID")));
            $this->assign('loginname',session(C("LOGINNAME")));
            // 进入主题房间
            if($result['type'] == 'theme'){
                $Service = D('Photo','Service');
                $Page = $Service->get_page(M('photo'));
                $info = $Service->get_photo($info, $Page);
                $show = $Page->show();

                $this->assign('roomid',session(C("ROOMID")));
                $this->assign('loginname',session(C("LOGINNAME")));
                $this->assign('info',$info);
                $this->assign('page',$show);

                $this->display('theme');
            }else{
                $this->error('不是主题房间');
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
    public function send(){
        $this->init();
        $danmu_color = M('User')->where(array('id' => session(C("USERID"))))->field('danmu_color')->find();

        $this->assign('danmu_color',$danmu_color['danmu_color']);
        $this->assign('roomid',session(C("ROOMID")));
        $this->assign('loginname',session(C("LOGINNAME")));
        $this->display();
    }
    public function game(){
        $this->init();

        $this->assign('roomid',session(C("ROOMID")));
        $this->assign('userid',session(C("USERID")));
        $this->assign('loginname',session(C("LOGINNAME")));
        $this->display();
    }
    public function chat(){
        $this->init();

        $this->assign('roomid',session(C("ROOMID")));
        $this->assign('loginname',session(C("LOGINNAME")));
        $this->display();
    }
    public function theme(){
        $this->init();
        $danmu_color = M('User')->where(array('id' => session(C("USERID"))))->field('danmu_color')->find();

        $Service = D('Photo','Service');
        $Page = $Service->get_page(M('photo'));
        $info = $Service->get_photo($info, $Page);
        $show = $Page->show();

        $this->assign('roomid',session(C("ROOMID")));
        $this->assign('loginname',session(C("LOGINNAME")));
        $this->assign('info',$info);
        $this->assign('page',$show);

        $this->assign('danmu_color',$danmu_color['danmu_color']);
        $this->assign('roomid',session(C("ROOMID")));
        $this->assign('loginname',session(C("LOGINNAME")));
        $this->display();
    }

    // like+1
    public function likes(){
        $this->init();
        if (IS_POST) {
            $id = I('post.id');
            $result['status'] = M('Photo')->where(array('id' => $id))->setInc('likes');
            if ($result['status']) {
                $result['msg'] = "点赞成功";
            }else{
                $result['msg'] = "点赞失败";
            }

            $this->ajaxReturn($result);
        }
    }
}