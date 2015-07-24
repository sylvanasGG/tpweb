<?php
namespace Admin\Model;
use Think\Model;
class AdminAccessModel extends Model{
	protected $tablePrefix = '';
    protected $tableName = 'admin_accesses';

    /**
     * 获取系统管理员信息
     *
     * @return array
     */
    public static function getSystemAdmin()
    {
        $founder = explode(',', str_replace(' ', '', Config::get('auth.founder')));
        $founders = User::whereIn('id', $founder)->get();
        return $founders;
    }

    /**
     * 检查是否是系统管理员
     *
     * @param  $user 用户对象
     * @return boolean
     */
    public static function checkIsSystemAdmin($user)
    {
        //匹配系统管理员ID
        if($user['id'] == C('SYSTEM_ADMIN'))
        {
            return true;
        }
        return false;
    }
}

?>