<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Model\UserModel as User;
use Admin\Model\AdminGroupModel as AdminGroup;
class ExampleController extends BaseController {

    /**
	* 视图：用户列表
    */
    public function contact(){

        $contact = M('contacts');
        $contacts = $contact->order('updated_at asc')->select();
        $this->assign('contacts',$contacts);
        $this->display(); // 输出模板
    }

  
}