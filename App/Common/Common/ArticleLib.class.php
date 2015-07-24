<?php 
namespace Common\Common;

class ArticleLib{
    //游戏
    const ARTICLE_TYPE_GAME = 'game';
    //文字
    const ARTICLE_TYPE_WORD = 'word';
    //编程
    const ARTICLE_TYPE_CODE = 'code';
    //其他
    const ARTICLE_TYPE_OTHER = 'other';

    public static $ARTICLE_TYPE = array(
        self::ARTICLE_TYPE_GAME       => '游戏',
        self::ARTICLE_TYPE_WORD         => '文字',
        self::ARTICLE_TYPE_CODE  => '编程',
        self::ARTICLE_TYPE_OTHER   => '其他',
    );
}