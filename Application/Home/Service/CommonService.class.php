<?php
namespace Home\Service;
class CommonService{
    // 获取分页数据
    public function get_page($model){
        $info = $model->count();
        $Page = new \Think\Page($info,10);
        $Page->lastSuffix=false;
        return $Page;
    }
}

