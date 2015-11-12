<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
    	$this->init();
        $this->display();
    }
    public function menu(){
    	$this->init();
    	$this->display();
    }
    public function mine(){
    	$this->init();
    	if (session(C("USERID"))) {
    		$Room = M("Room");
    		$result = $Room->where(array('belong_to_user' => session(C("USERID"))))->select();
    	}
    	$this->assign('rooms',$result);
    	$this->display();
    }
    public function userDetail(){
        $this->init();
        if (session(C("USERID"))) {
            $User = M("User");
            $result = $User->where(array('id' => session(C("USERID"))))->find();
        }
        $this->assign('user',$result);
        $this->display();
    }
    public function setUserColor(){
        $this->init();
        if (IS_POST && session(C("USERID"))) {
            $data['danmu_color'] = I('post.color');
            $data['id'] = session(C("USERID"));
            $User = M("User")->save($data);
            echo json_encode(0);
        }else{
            echo json_encode(1);
        }
    }
}