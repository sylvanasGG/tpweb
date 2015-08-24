<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Model\UserModel as User;
use Admin\Model\AdminGroupModel as AdminGroup;
use Admin\Model\AdminAccessModel as AdminAccess;
class UserController extends BaseController {

    /**
	* 视图：用户列表
    */
    public function index(){
        $map = array();
        $username = $cp_group_id =  '';
        $username = $_GET['username'];
        if ($username != null) {
            $map['username'] = array('like', "%$username%");
        }

        $cp_group_id = $_GET['cp_group_id'];
        if ($cp_group_id != null) {
            $map['cp_group_id'] = array('eq', $cp_group_id);
        }

        $this->assign('username', $username);
        $this->assign('cp_group_id', $cp_group_id);

    	$users = new User;
    	$count      = $users->where($map)->order('created_at desc')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','页');
        $Page->setConfig('prev','');
        $Page->setConfig('next','');
        $Page->setConfig('first','>>');
        $Page->setConfig('end','<<');
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $users->where($map)->order('created_at desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $group = new AdminGroup;
        $groupAll  = $group->order('cp_group_id asc')->select();
        $this->assign('groupAll',$groupAll);
        $this->assign('users',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
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
        $data['username'] =$_POST['username'];
        $data['password'] =md5($_POST['password']);
        $data['email'] = $_POST['email'];
        $data['cp_group_id'] = $_POST['cp_group_id'];
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        if($user->add($data))
        {
            session('admin.success_msg','添加成功');
            //$this->success('评论成功');
            $this->redirect('User/index','', 0, '');
            //$this->success('添加成功');
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
    public function deleteUser()
    {
        $id = $_GET['id'];
        $user = new User();
        if($user->where('id='.$id)->delete())
        {
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

    /**
     * 视图：个人用户权限分配
     *
     */
    public function showPersonalPerm()
    {
        $id = $_GET['id'];
        $users = new User();
        $user = $users->where('id='.$id)->find();
        $cp_group_id = $_GET['cp_group_id'] ? $_GET['cp_group_id']  : $user['cp_group_id'];
        //获取菜单权限列表
        $menuList = $this->getMenuList();
        //查询所有职务
        $group = new AdminGroup;
        $groupAll  = $group->order('cp_group_id asc')->select();
        //反序列化权限
        $user['custom_access'] = ! empty($user['custom_access']) ? unserialize($user['custom_access']) : array();
        //获取职务权限
        $adminAccess = new AdminAccess();
        $adminAccessList = $adminAccess->where('cp_group_id='.$cp_group_id)->select();
        $groupAccess = array();
        foreach ($adminAccessList as $adminAccess)
        {
            $groupAccess[] = $adminAccess['access'];
        }
        $this->assign('groupAccess', $groupAccess);
        $this->assign('menuList', $menuList);
        $this->assign('groupAll', $groupAll);
        $this->assign('user', $user);
        $this->assign('cp_group_id', $cp_group_id);
        $this->display('personalPerm');
    }

    /**
     * 动作：个人用户权限分配
     */
    public function postPersonalPerm($id)
    {
        $accessNew = $_POST['access_new'] ? $_POST['access_new'] : array();
        $cpgroupidNew = $_POST['cp_group_id_new'];

        //获取职务权限
        $adminAccess = new AdminAccess();
        $adminAccessList = $adminAccess->where('cp_group_id='.$cpgroupidNew)->select();
        $groupAccess = array();
        foreach ($adminAccessList as $adminAccess)
        {
            $groupAccess[] = $adminAccess['access'];
        }
        // 序列化自定义权限
        $customaccess = serialize(array_diff($groupAccess, $accessNew));

        $user = new User();
        $data['cp_group_id'] = $cpgroupidNew;
        $data['custom_access'] = $customaccess;
        $user->where('id='.$id)->save($data);

        $this->success('保存成功');
    }

    public function showGroupsList()
    {
        $group = new AdminGroup;
        $groupAll  = $group->order('cp_group_id asc')->select();
        $this->assign('groupAll',$groupAll);
        $this->display('groupsList');
    }

    public function postGroupsList()
    {
        //新增职务
        if($_POST['new_cp_group_name'])
        {
            $new_cp_group_name = $_POST['new_cp_group_name'];
            $adminGroup = new AdminGroup();
            if (in_array($new_cp_group_name, array('系统管理员')) || $adminGroup->where('cp_group_name="'.$new_cp_group_name.'"')->find())
            {
                $this->redirect('Perm/showGroupsList', '', 2, '该团队职务已经存在...');
            }
            $data['cp_group_name'] = strip_tags($new_cp_group_name);
            $adminGroup->add($data);
        }
        //更新职务
        if($_POST['name'])
        {
            foreach($_POST['name'] as $cp_group_id => $cp_group_name)
            {
                $adminGroup = new AdminGroup();
                $adminGroup->cp_group_name = $cp_group_name;
                $adminGroup->where('cp_group_id='.$cp_group_id)->save();
            }
        }
        //删除职务
        if($_POST['delete'])
        {
            $adminAccess = new AdminAccess();
            $ids = $_POST['delete'];
            //$adminAccess->where('id='.$id)->delete();
            $adminAccess->where(array('cp_group_id'=>array('in',$ids)))->delete();
            $user = new User();
            $user->where(array('cp_group_id'=>array('in',$ids)))->delete();
            //User::whereIn('cp_group_id', $request->input('delete'))->delete();
            $adminGroup = new AdminGroup();
            $adminGroup->where(array('cp_group_id'=>array('in',$ids)))->delete();
        }
        $this->success('保存成功');
    }


    public function showGroupPerm()
    {
        $cp_group_id = $_GET['id'];
        $menuList = $this->getMenuList();
        $group = new AdminGroup;
        $groupAll  = $group->order('cp_group_id asc')->select();
        //获取职务权限
        $adminAccess = new AdminAccess();
        $adminAccessList = $adminAccess->where('cp_group_id='.$cp_group_id)->select();
        $groupAccess = array();
        foreach ($adminAccessList as $adminAccess)
        {
            $groupAccess[] = $adminAccess['access'];
        }
        $this->assign('groupAll',$groupAll);
        $this->assign('menuList',$menuList);
        $this->assign('id',$cp_group_id);
        $this->assign('groupAccess', $groupAccess);
        $this->display('groupPerm');
    }

    public function postGroupPerm($id)
    {
        $adminAccess = new AdminAccess();
        $adminAccess->where('cp_group_id='.$id)->delete();

        if(!empty($_POST['perm_allow']))
        {
            $perm_allow = $_POST['perm_allow'];
            foreach($perm_allow as $access)
            {
                $adminAccess = new AdminAccess();
                $data['cp_group_id'] = $id;
                $data['access'] = $access;
                $data['created_at'] = date('Y-m-d H:i:s',time());
                $data['updated_at'] = date('Y-m-d H:i:s',time());
                $adminAccess->add($data);
            }
        }
        $this->success('保存成功');
    }

   
}