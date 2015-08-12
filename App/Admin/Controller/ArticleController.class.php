<?php
namespace Admin\Controller;
use Think\Controller;
use Home\Model\ArticleModel as Article;
use Home\Model\CommentModel as Comment;
use Common\Common\ArticleLib;
class ArticleController extends BaseController {

    public function index()
    {
        $article = new \Home\Model\ArticleModel();
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
        $this->assign('article_types',ArticleLib::$ARTICLE_TYPE);
        $this->display('index'); // 输出模板
    }

    /**
     *视图： 新增文章
     **/
    public function create()
    {
        $this->assign('article_types',ArticleLib::$ARTICLE_TYPE);
        $this->display('create');
    }
    public function upload($fileInfo)
    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传单个文件 
        $info   =   $upload->uploadOne($fileInfo);
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            return  $info['savepath'].$info['savename'];
        }
    }
    /**
     *动作： 新增文章
     **/
    public function store()
    {
        if($_FILES['article_photo']['name'])
        {
            $res = $this->upload($_FILES['article_photo']);
        }
        $article = new Article;
        $data = $_POST;
        $user = session('admin.admin');
        $data['author'] = $user['username'];
        $data['article_photo'] =$res? __ROOT__.'/Uploads/'.$res:__ROOT__.'/Public/img/111.jpg';
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        if($article->add($data))
        {
            $this->success('发表成功');
        }
        $this->error('发表失败');
    }
    /**
     *视图： 编辑文章
     **/
    public function edit($id)
    {
        $mod = new Article;
        $article = $mod->where('article_id='.$id)->find();
        $this->assign('article_types',ArticleLib::$ARTICLE_TYPE);
        $this->assign('article',$article);
        $this->display('edit');
    }
    /**
     *动作： 编辑文章
     **/
    public function update($id)
    {
        if($_FILES['article_photo']['name'])
        {
            $res = $this->upload($_FILES['article_photo']);
        }
        $article = new Article;
        if($res)$article->article_photo = __ROOT__.'/Uploads/'.$res;
        $article->article_type = $_POST['article_type'];
        $article->title = $_POST['title'];
        $article->content = $_POST['content'];
        $article->updated_at = date('Y-m-d H:i:s',time());
        if($article->where('id='.$id)->save())
        {
            //$this->success('编辑成功','/Index/index',1);
            $this->redirect('Article/index', array('id' => $id), 1, '编辑成功');
        }
        $this->error('编辑失败');
    }
    /**
     *动作： 删除文章
     **/
    public function delete()
    {
        $article = new Article;
        $comments = new Comment;
        $id = $_GET['id'];
        $comments->where('article_id='.$id)->delete();
        $article->where('article_id='.$id)->delete();
        $data = array(
            'ret'=>0,
            'msg'=>'删除成功',
        );
        $this->ajaxReturn($data);
    }
    /**
     *动作： 后台展示一篇文章
     **/
    public function show($id)
    {
        $articles = new Article;
        $article = $articles->where('id='.$id)->find();
        $comments = $articles->relation(true)->find($id);

        $this->assign('article',$article);
        $this->assign('comments',$comments['comment']);
        $this->display('show');
    }
}