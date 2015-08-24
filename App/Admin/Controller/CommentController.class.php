<?php
namespace Admin\Controller;
use Think\Controller;
use Home\Model\CommentModel as Comment;
use Home\Model\ArticleModel as Article;
class CommentController extends BaseController {
    /**
    *视图： 评论列表
    **/
    public function index()
    {
        $map = array();
        $content = $article_id = $created_at_start = $created_at_end =  '';
        $content = $_GET['content'];
        if ($content != null) {
            $map['content'] = array('like', "%$content%");
        }

        $article_id = $_GET['article_id'];
        if ($article_id != null) {
            $map['article_id'] = array('eq', $article_id);
        }

        $created_at_start = $_GET['created_at_start'];
        if ($created_at_start != null) {
            $map['created_at'] = array('egt', $created_at_start);
        }

        $created_at_end = $_GET['created_at_end'];
        if ($created_at_end != null) {
            $map['created_at'] = array('elt', $created_at_end);
        }

        if ($created_at_start != null && $created_at_end != null) {
            $map['created_at'] = array('between', array($created_at_start, $created_at_end));
        }
        $this->assign('content', $content);
        $this->assign('article_id', $article_id);
        $this->assign('created_at_start', $created_at_start);
        $this->assign('created_at_end', $created_at_end);

        //所有文章
        $mod = M('articles');
        $articles = $mod->order('updated_at desc')->select();
        $articleArr = array();
        foreach($articles as $article)
        {
            $articleArr[$article['article_id']] = $article['title'];
        }
        $comments = new Comment;
        $count      = $comments->where($map)->order('updated_at desc')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','页');
        $Page->setConfig('prev','');
        $Page->setConfig('next','');
        $Page->setConfig('first','>>');
        $Page->setConfig('end','<<');
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $comments->where($map)->order('updated_at desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('comments',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('articleArr',$articleArr);
        $this->display('index'); // 输出模板
    }
	
    /**
	*视图： 编辑评论
	**/
    public function edit($id)
    {
    	$mod = new Comment;
    	$comment = $mod->where('id='.$id)->find();
        $this->assign('comment',$comment);
    	$this->display('edit');
    }
    /**
	*动作： 编辑评论
	**/
    public function update($id)
    {
        $comment = new Comment;
    	//$article = $mod->where('id='.$id)->find();
    	$comment->nickname = $_POST['nickname'];
    	$comment->email = $_POST['email'];
        $comment->website = $_POST['website'];
    	$comment->content = $_POST['content'];
    	$comment->updated_at = date('Y-m-d H:i:s',time());
    	if($comment->where('id='.$id)->save())
    	{
    		//$this->success('编辑成功','Comment/index',1);
            $this->redirect('Comment/index', array('id' => $id), 1, '编辑成功');
    	}
    	$this->error('编辑失败');
    }
    /**
	*动作： 删除评论
	**/
    public function delete()
    {

        $comment = new Comment;
        $id = $_GET['id'];
        if($comment->where('id='.$id)->delete())
        {
            $data = array(
                'ret'=>0,
                'msg'=>'删除成功',
            );
            $this->ajaxReturn($data);
        }

    }
}