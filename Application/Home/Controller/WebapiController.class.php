<?php
namespace Home\Controller;
use Think\Controller;
class WebapiController extends CommonController {
    public function index(){
        if(IS_POST) {
            $User = M("User");
            $username = I('post.username');
            $password = I('post.password');
            $where['username'] = $username;
            $result = $User->where($where)->find();
            if($result != NULL && $result != false){
                if(md5($result['salt'] . $password) == $result['password']){
                    $data['lastlogin'] = date('Y-m-d H:i:s');
                    $data['ip'] = get_client_ip();
                    $User->where($result)->data($data)->save();
                    $result['msg'] = 'succeed';
                    $this->ajaxReturn($result);
                }else{
                    $error['msg'] = 'password error';
                    $this->ajaxReturn($error);
                }
            }else{
                $error['msg'] = 'username error';
                $this->ajaxReturn($error);
            }
        }
    }
    public function into(){
        if(IS_POST) {
            $Room = M("Room");
            $data['name'] = I('post.roomname');
            $data['password'] = I('post.password');
            $result = $Room->where(array('name' => $data['name']))->find();
            if($result != null && $result != false){
                if(md5($result['salt'] . $data['password']) == $result['password']){
                    $Room->where(array('id' => $result['id']))->setInc('see_num');

                    $result['msg'] = 'succeed';
                    $this->ajaxReturn($result);
                }else{
                    $error['msg'] = 'password error';
                    $this->ajaxReturn($error);
                }
            }else{
                $error['msg'] = 'roomname error';
                $this->ajaxReturn($error);
            }
        }
    }
}