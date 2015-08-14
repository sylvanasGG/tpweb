<?php
namespace Admin\Controller;


class AdminController extends BaseController {



    public function index(){

        $this->display('index');
    }

    public function show()
    {
        $user = M('users');
        //$username = $_POST['username'];
        $data = $user->where("username='zippo'")->select();
        var_dump($data);exit;
        $data['info'] = $username;
        $this->ajaxReturn($data);
    }

    public function unsetSession()
    {
        session('admin.success_msg',null);
        $data = array(
            'ret'=>0,
        );
        $this->ajaxReturn($data);
    }
}