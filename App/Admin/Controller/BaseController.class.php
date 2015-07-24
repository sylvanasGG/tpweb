<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Model\AdminAccessModel as AdminAccess;
class BaseController extends Controller {

    protected $_menus = '';
    protected $_allowAccess = '';
    public function __construct()
    {
        parent::__construct();
        //获取配置文件中的menu目录
        //验证是否admin登录
        if(!session('admin.admin'))
        {
            $this->redirect('Auth/login','', 2, '请先登录...');
        }
        $this->_menus = C("LAYOUT_MENU");
        $this->allowList();
        $this->assign('menus',$this->getPermMenuList());
    }

    public function getMenuList()
    {
        // 加载Menu
        $menu = $this->_menus;

        return $menu;
    }

    /**
     * 获取允许访问的菜单列表
     */
    public function getPermMenuList()
    {
        $menuList = $this->_menus;
            $user = session('admin.admin');
        if(AdminAccess::checkIsSystemAdmin($user)) {
            return $menuList;
        }
            if ($user['cp_group_id']) {
                foreach ($menuList as $key => $topMenu) {
                    $itemExists = 0;
                    foreach ($topMenu['treeViewMenu'] as $menuKey => $menu) {
                        if (array_key_exists($menu['actionName'], $this->_allowAccess)) {
                            $itemExists = 1;
                        } else {
                            unset($menuList[$key]['treeViewMenu'][$menuKey]);
                        }
                    }
                    if (!$itemExists) unset($menuList[$key]);
                }
            }

        return $menuList;

    }

    public function allowList()
    {
        $user = session('admin.admin');
        $user['custom_access'] = ! empty($user['custom_access']) ? unserialize($user['custom_access']) : array();
        if ($user['cp_group_id'] > 0)
        {
            //获取用户所在管理组的权限
            $adminAccess = new AdminAccess();
            $accessList = $adminAccess->where('cp_group_id='.$user['cp_group_id'])->select();
            //权限菜单
            $menuList = $this->_menus;
            foreach ($menuList as $topMenu)
            {
                foreach ($topMenu['treeViewMenu'] as $menu)
                {
                    $adminAccess = new AdminAccess();
                    $adminAccess = $adminAccess->where('cp_group_id='.$user['cp_group_id'].' AND access="'.$menu['actionName'].'"')->find();
                    if ($adminAccess &&! in_array($adminAccess['access'], $user['custom_access']) && $menu['auth'])
                    {
                        $menu['auth'] = is_array($menu['auth']) ? $menu['auth'] : (array)$menu['auth'];
                        foreach ($menu['auth'] as $auth)
                        {
                            $accessList[] = array('cp_group_id' => $adminAccess['cp_group_id'], 'access' => $auth);
                        }
                    }
                }
            }

            //保存访问权限
            foreach ($accessList as $access)
            {
                if (empty($user['custom_access']))
                {
                    $this->_allowAccess[$access['access']] = true;
                } elseif ( !in_array($access['access'], $user['custom_access']))
                {
                    $this->_allowAccess[$access['access']] = true;
                }
            }
            return $this->_allowAccess;
        }

        return true;
    }



}