<?php
namespace Admin\Controller;

use Admin\Model\VisitorModel;
use Think\Controller;
use Admin\Model\UserModel as User;
use Admin\Model\AdminGroupModel as AdminGroup;
class VisitorController extends BaseController {

    /**
	* 视图：用户列表
    */
    public function index(){
        $visitors = M('Visitors');
    	$count      = $visitors->order('created_at desc')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','页');
        $Page->setConfig('prev','');
        $Page->setConfig('next','');
        $Page->setConfig('first','>>');
        $Page->setConfig('end','<<');
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $visitors->order('created_at desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        //访问总次数
        $sum = M('sums');
        $total = $sum->where('id=1')->getField('visitor_sum');
        $this->assign('visitors',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('sum',$total);
        $this->display('index'); // 输出模板
    }

    public function showAdd()
    {
        $group = new AdminGroup;
        $groupAll  = $group->order('cp_group_id asc')->select();
        $this->assign('groupAll',$groupAll);
        $this->display('add');
    }

    /**
     *动作： 新增用户
     **/
    public function postAdd()
    {
        $user = new User;
        $data['name'] =$_POST['name'];
        $data['password'] =md5($_POST['password']);
        $data['email'] = $_POST['email'];
        $data['cp_group_id'] = $_POST['cp_group_id'];
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        if($user->add($data))
        {
            $this->success('添加成功');
        }
        $this->error('添加失败');
    }

    /**
     * 视图：编辑用户
     *
     * @param $id
     */
    public function showEdit($id)
    {
        $mod = new User(); // 实例化User对象
        $user = $mod->where('id = '.$id)->find();
        $group = new AdminGroup;
        $groupAll  = $group->order('cp_group_id asc')->select();
        $this->assign('groupAll',$groupAll);
        $this->assign('user',$user);
        $this->display('edit');
    }

    /**
     * 动作：编辑用户
     *
     * @param $id
     */
    public function postEdit($id)
    {
        $user = new User(); // 实例化User对象
        $user->name = $_POST['name'];
        $user->email = $_POST['email'];
        if($_POST['password'])
        {
            $user->password = md5($_POST['password']);
        }
        $user->cp_group_id = $_POST['cp_group_id'];
        if($user->where('id='.$id)->save())
        {
            //$this->success('编辑成功','/Index/index',1);
            $this->redirect('User/showEdit', array('id' => $id), 2, '编辑成功，页面跳转中...');
        }
        $this->error('编辑失败');
    }

    /**
     * 动作：删除用户
     */
    public function deleteVisitor()
    {
        $id = $_GET['id'];
        $visitor = M('Visitors');
        if($visitor->where('id='.$id)->delete())
        {
            $sum = M('sums');
            $total = $sum->where('id=1')->getField('visitor_sum');
            if($total>0)
            {
                $data['visitor_sum'] = $total-1;
                $sum->where('id=1')->save($data);
            }

            $data = array(
                'ret'=>0,
                'msg'=>'删除成功',
            );
            $this->ajaxReturn($data);
        }
    }

    /**
     * 视图：修改个人资料
     */
    public function getPersonalInfo()
    {
        $user = new User();
        $id = $_GET['id'];
        $user = $user->where('id='.$id)->find();
        $this->assign('user',$user);
        $this->display('personalInfo');
    }

    /**
     * 动作：修改个人资料
     */
    public function postPersonalInfo($id)
    {
        $user = new User(); // 实例化User对象
        $user->name = $_POST['name'];
        $user->email = $_POST['email'];
        if($_POST['password'])
        {
            $user->password = md5($_POST['password']);
        }
        if($user->where('id='.$id)->save())
        {
            $_arr = is_object($user) ? get_object_vars($user) :$user;
            session('admin.admin',$_arr);
            //$this->success('编辑成功','/Index/index',1);
            $this->redirect('User/showEdit', array('id' => $id), 1, '编辑成功,...');
        }
        $this->error('编辑失败');
    }

   
}