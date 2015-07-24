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
        $comments = new Comment;
        $count      = $comments->order('updated_at desc')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','页');
        $Page->setConfig('prev','');
        $Page->setConfig('next','');
        $Page->setConfig('first','>>');
        $Page->setConfig('end','<<');
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $comments->order('updated_at desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('comments',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
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
    		//$this->success('编辑成功','/Index/index',1);
            $this->redirect('Comment/edit', array('id' => $id), 2, '编辑成功，页面跳转中...');
    	}
    	$this->error('编辑失败');
    }
    /**
	*动作： 删除评论
	**/
    public function delete()
    {
        $id = $_GET['id'];
    	$comment = new Comment;
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