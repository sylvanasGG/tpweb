<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\ArticleModel as Article;
class IndexController extends Controller {
    public function index(){
        $ip = $_SERVER["REMOTE_ADDR"];
        $now = date("Y-m-d H:i:s",time());
        $less_minute_time = date("Y-m-d H:i:s",time()-60);
        $visitor = M('Visitors');
        $res = $visitor->where('ip_address="'.$ip.'" AND visit_start_at >="'.$less_minute_time.'"')->find();
        if(!$res)
        {
            $sum = M('sums');
            $total = $sum->where('id=1')->getField('visitor_sum');
            $visitor->add(
                array(
                    'ip_address'=>$ip,
                    'visit_start_at'=>$now,
                    'created_at'=>$now
                )
            );
            $data['visitor_sum'] = $total+1;
            $sum->where('id=1')->save($data);

        }
        $article = new Article;
        $count      = $article->order('updated_at desc')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,3);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','页');
        $Page->setConfig('prev','');
        $Page->setConfig('next','');
        $Page->setConfig('first','>>');
        $Page->setConfig('end','<<');
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $article->order('updated_at desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('articles',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display('index'); // 输出模板
    }
    
}