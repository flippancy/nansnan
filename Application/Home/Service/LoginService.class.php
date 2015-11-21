<?php
namespace Home\Service;
class LoginService extends CommonService {
    public function addUser($info){
        $User = M('User')->where(array('username' => $info['username']))->find();
        if ($User != null && $User != false) {
            $reply['msg'] = "用户名已使用";
            $reply['status'] = "error";
            break;
        }

        $User = M('User')->where(array('email' => $info['email']))->find();
        if ($User != null && $User != false) {
            $reply['msg'] = "邮箱已使用";
            $reply['status'] = "error";
            break;
        }


        $data['username'] = $info['username'];
        $data['email'] = $info['email'];
        $data['loginname'] = $info['loginname'];
        $data['salt'] = randomkeys(6);
        $data['password'] = md5($data['salt'].$info['password']);
        $data['token'] = md5($data['salt'].$info['email']);
        if(false === (M('User')->create($data))){
            $reply['msg'] = "用户创建失败";
            $reply['status'] = "error";

        }else{
            $id = M('User')->add($data);
            $reply['msg'] = "用户创建成功";
            $reply['status'] = "succeed";
        }
        return $reply;
    }

    public function checkEmail(){
        $subject = "欢迎你的使用\n" + "TOKEN:"+ $token + "\n" + "by flippancy"
        if (think_send_mail($_POST['mail'], $_POST['title'], $subject, $_POST['content'], $attachment = null)) {
            $this->success('发送成功！');
        } else {
            $this->error('发送失败');
        }
    }
}
