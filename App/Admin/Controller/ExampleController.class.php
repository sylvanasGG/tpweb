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

    public function postInsertContact()
    {
            $contactRecord = M('contacts');
//            $data['contact_time'] = $_POST['contact_time'];
//            $contactRecord->contact_time = Input::get('contact_time');
//            $contactRecord->contact_type = Input::get('contact_type');
//            $contactRecord->contact_man = Input::get('contact_man');
//            $contactRecord->contact_phone = Input::get('contact_phone');
//            $contactRecord->content = Input::get('content');
//            $contactRecord->contact_on = Input::get('contact_on');
//            $contactRecord->user_id = $this->_user->uid;
//            $contactRecord->username = $this->_user->username;
           if ($contactRecord->create($_POST))
            {

                $data = [
                    'ret'=>0,
                    'msg'=>'添加成功',
                    'data'=>$_POST
                ];
                //exit(json_encode($data));
                $this->ajaxReturn($data);
            } else
            {
                $this->ajaxReturn(array('ret'=>1,'msg'=>'添加失败'));
            }

    }

    /**
     * ¶¯×÷£ºÅúÁ¿É¾³ýÁªÏµ¼ÇÂ¼
     */
    public function deleteContact()
    {
        //É¾³ý
        if(!empty($_POST['deleteids']))
        {
            $contact = M('contacts');
            $ids = $_POST['deleteids'];
            //
            foreach($ids as $id)
            {
                $contact->where('id='.$id)->delete();
            }
            $data = array(
                'ret'=>0,
                'msg'=>'删除成功'
            );
            //exit(json_encode($data));
            $this->ajaxReturn($data);
        }
        $this->ajaxReturn(array('ret'=>1,'msg'=>'缺少ID'));

    }

    /**
     * 动作：修改联系记录
     *
     * @param int $id 联系记录ID
     * @return \Illuminate\View\View
     */
    public function putContactRecord($id)
    {
        //创建验证规则
        $rules = array(
            'contact_phone'    => 'required',
            'content'   => 'required',
        );
        //开始验证
        $validator = Validator::make(Input::all(), $rules);
        if($validator->passes())
        {
            $contactRecord = Order_ContactRecord::find($id);
            $contactRecord->order_id = Input::get('order_id');
            $contactRecord->verify_id = Input::get('verify_id');
            $contactRecord->contact_time = Input::get('contact_time');
            $contactRecord->telephone = Input::get('telephone');
            $contactRecord->contact_type = Input::get('contact_type');
            $contactRecord->contact_man = Input::get('contact_man');
            $contactRecord->contact_phone = Input::get('contact_phone');
            $contactRecord->content = Input::get('content');
            $contactRecord->contact_on = Input::get('contact_on');
            $contactRecord->user_id = $this->_user->uid;
            $contactRecord->username = $this->_user->username;
            if ($contactRecord->save())
            {
                $this->exitJson(Core_Comm_Modret::RET_SUCC,"编辑成功",'',$contactRecord->toArray());
            } else
            {
                $this->exitJson(Core_Comm_Modret::RET_SAVE_FAILED,"编辑失败");
            }
        } else
        {
            $this->exitJson(Core_Comm_Modret::RET_MISS_ARG,"缺少必要字段");
        }
    }
  
}