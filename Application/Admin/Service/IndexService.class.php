<?php
namespace Admin\Service;
class IndexService extends CommonService {
	public function add_photo($info){
       	$setting=C('UPLOAD_SITEIMG_QINIU');
        $qiniu = new \Think\Upload\Driver\Qiniu\QiniuStorage($setting['driverConfig']);
    	$Upload = new \Think\Upload($setting);


    	$info = $Upload->upload($_FILES);

        $time = date('Y-m-d H:i:s');
        $info['file']['created_at'] = $time;
        $info['file']['created_by'] = 'cjx';
        $info['file']['remark'] = '暂时什么都没有！';

        $result = M('t_photo')->data($info['file'])->add();

    	$info['file']['url'] = Qiniu_Sign($info['file']['url']);
    	return $info['file'];
	}

    public function get_photo($info){
        $info = M('t_photo')->select();
        for ($i=0; $i < count($info); $i++) {
            $info[$i]['url'] = Qiniu_Sign($info[$i]['url']);
        }
        return $info;
    }

    public function delete($file){
        $photo = M('t_photo')->where($file)->delete();
        $info = '图片删除成功！';
        if ($photo) {
            $file = $file['name'];
            $file = str_replace("/", "_", $file);
            $setting=C('UPLOAD_SITEIMG_QINIU');
            $qiniu = new \Think\Upload\Driver\Qiniu\QiniuStorage($setting['driverConfig']);
            $info = $qiniu->del($file);
        }
        return $info;
    }
}
