<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends CommonController {
    public function index(){
        $this->display();
    }
    public function logintype(){
        $this->display();
    }
    public function loginPerson(){
        if(IS_POST) {
            $User = M("User");
            $username = I('post.username');
            $password = I('post.password');
            if(!empty($username) && !empty($password)){
                $where['username'] = $username;
                $result = $User->where($where)->find();
                if($result != NULL && $result != false){
                    if(md5($result['salt'] . $password) == $result['password']){
                        session(C("LOGINNAME"), $result['loginname']);
                        session(C("SESSIONID"), session_id());
                        session(C("USERID"), $result['id']);
                        $data['lastlogin'] = date('Y-m-d H:i:s');
                        $data['ip'] = get_client_ip();
                        $User->where($result)->data($data)->save();
                        echo json_encode(0);
                    }
                    else{
                        echo json_encode(1);
                    }
                }else{
                    echo json_encode(2);
                }
            }
            else{
                echo json_encode(2);
            }
        }else{
            $this->display();
        }
    }
	public function loginPublic(){
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
	public function loginWeixin(){
		if (IS_POST) {
			# code...
		}else{
			$this->display();
		}
	}
    public function logout(){
    	$this->init();
    	session(null);
    	$this->success("成功退出！",'logintype');
    }
    public function register(){
        if (IS_POST) {
            $info = I('post.');
            $loginService = D('Login' ,'Service');
            $reply = $loginService->addUser($info);
            $this->ajaxReturn($reply);
        }else{
            $this->display();
        }
    }
}