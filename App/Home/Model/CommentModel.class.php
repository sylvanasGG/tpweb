<?php
namespace Home\Model;
use Think\Model;
use Think\Model\RelationModel;
class CommentModel extends RelationModel{
	protected $tablePrefix = '';
    protected $tableName = 'comments';
}

?>