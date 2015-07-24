<?php
namespace Home\Model;
use Think\Model;
use Think\Model\RelationModel;
class ArticleModel extends RelationModel{
	protected $tablePrefix = '';
    protected $tableName = 'articles';

    protected $_link = array(
        'comment' => array(
                'mapping_type'  => self::HAS_MANY,
                'class_name'    => 'Comment',
                'foreign_key'   => 'article_id',
                'mapping_name'  => 'comment',
            ),
        );
}

?>