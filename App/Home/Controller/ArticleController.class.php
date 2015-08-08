<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\ArticleModel as Article;
class ArticleController extends Controller {

    public function show($id)
    {
        $articles = new Article;
        $article = $articles->where('article_id='.$id)->find();
        $comments = $articles->relation(true)->find($id);

        $this->assign('article',$article);
        $this->assign('comments',$comments['comment']);
        $this->display('showArticle');
    }

    public function showArticleFeilei($name)
    {
        $article = new Article;
        $count      = $article->where('article_type="'.$name.'"')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,3);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','页');
        $Page->setConfig('prev','');
        $Page->setConfig('next','');
        $Page->setConfig('first','>>');
        $Page->setConfig('end','<<');
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $article->where('article_type="'.$name.'"')->order('updated_at desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('articles',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display('Index/index'); // 输出模板
    }


}