<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
    public function _initialize() {
    	// if (session(C('SESSIONID')) == null) {
    	// 	$this->redirect('Login/index');
    	// }
    }
    public function init() {
    	if (session(C('SESSIONID')) == null) {
    		$this->redirect('Login/logintype');
    	}
    }
}