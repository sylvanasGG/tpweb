<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Model\UserModel as User;
use Admin\Model\AdminGroupModel as AdminGroup;
class ExampleController extends BaseController {

    /**
	* 视图：用户列表
    */
    public function index(){

    	
        $this->display('index'); // 输出模板
    }

  
}