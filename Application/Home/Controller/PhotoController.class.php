<?php
namespace Home\Controller;
use Think\Controller;
class PhotoController extends CommonController {
    public function index(){
        $this->init();
        $Service = D('Photo','Service');
        $Page = $Service->get_page(M('photo'));
        $info = $Service->get_photo($info, $Page);
        $show = $Page->show();

        $this->assign('roomid',session(C("ROOMID")));
        $this->assign('loginname',session(C("LOGINNAME")));
        $this->assign('info',$info);
        $this->assign('page',$show);
        $this->display();
    }

    public function upload(){
        $this->init();
        $Service = D('Photo','Service');
        if (IS_POST) {
            $data = $Service->add_photo($info);
            $this->ajaxReturn($data);
        }
    }

    public function delete(){
        $this->init();
        $Service = D('Photo','Service');
        if (IS_GET) {
            $data = I('get.');
            $data = $Service->delete($data);
            $this->success($data);
        }
    }

    public function update(){
        $this->init();
        if(IS_POST){
            $data = I('post.');
            if(M('photo')->where("id=%d",$data['id'])->field('remark')->save($data)){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }
    }
}