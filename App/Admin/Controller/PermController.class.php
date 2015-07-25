<?php
namespace Admin\Controller;

use Admin\Model\AdminAccessModel;
use Think\Controller;
use Admin\Model\UserModel as User;
use Admin\Model\AdminGroupModel as AdminGroup;
use Admin\Model\AdminAccessModel as AdminAccess;
class PermController extends BaseController {

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