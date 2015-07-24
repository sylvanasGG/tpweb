<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\CommentModel as Comment;
class CommentController extends Controller {

    public function create()
    {
        $comment = new Comment;
        $data = $_POST;
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        if($comment->add($data))
        {
        	$this->success('评论成功');
        }else
        {
        	$this->error('评论失败');
        }
    }

   
}