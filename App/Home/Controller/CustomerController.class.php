<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\ArticleModel as Article;
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 2015/8/24
 * Time: 9:56
 */

class CustomerController extends Controller {

    public function create()
    {
        sendMail('77849093@qq.com','客户发送需求','['.$_POST["customer_phone"].']'.'['.$_POST["customer_email"].']'.'需求：'.$_POST['customer_content']);
        $item = array(
            'ret' => 0,
        );
        $this->ajaxReturn($item);
    }

}