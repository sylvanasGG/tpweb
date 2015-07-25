<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\UserModel as User;
class AuthController extends Controller {

    public function login(){
        C('LAYOUT_ON',false);
        $this->display('login');
    }

    public function getLogin()
    {
        $name = $_POST['name'];
        $user = new User();
        $data = $user->where('name="'.$name.'"')->find();
        if($data && $data['password'] == md5($_POST['password']))
        {
            session('admin.admin',$data);  //设置session
            $this->redirect('Admin/index', '', 0, '...');
        }
        $this->error('用户名或密码错误，请重新登录');

    }

    public function getLoginOut()
    {
      session('admin.admin',null);
      $this->redirect('Auth/login', '', 0, '...');

    }

}