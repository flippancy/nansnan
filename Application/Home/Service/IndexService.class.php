<?php
namespace Home\Service;
class IndexService extends CommonService {
    // 获取分页数据
    public function get_page($model){
        $info = $model->count();
        $Page = new \Think\Page($info,10);
        $Page->lastSuffix=false;
        return $Page;
    }

    // 获取所有图片以及七牛地址
    public function get_photo($info, $Page = ''){
        $info = M('t_photo')->order('created_at desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        return $info;
    }
}
