<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\CommentModel as Comment;
use Home\Model\ArticleModel as Article;
class CommentController extends Controller {

    public function create()
    {
        $comment = new Comment;
        $data = $_POST;
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        if($comment->add($data))
        {
            $mod = new Article;
            $article = $mod->where('article_id='.$_POST['article_id'])->find();
            $msg = '文章：['.$article['title'].'] 下有新的评论：'.$_POST['content'];
            //评论成功后给管理员发邮件
           // sendMail('77849093@qq.com','有人发表评论',$msg);
            $this->success('评论成功');
        }else
        {
        	$this->error('评论失败');
        }
        
    }
}