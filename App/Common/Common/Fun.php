<?php
/**
 * 常用函数库
 *
 */
class Fun {
    /**
     * 返回json对象
     */
    public static function returnJson( $code , $msg='', $target='' , $params=array(), $callback=NULL)
    {
        $msg = empty($msg)? Core_Comm_Modret::getMsg($code) :$msg;
        return Core_Comm_Modret::getRetJson($code, $msg, $target, $params,$callback);
    }

    /**
     * 输出json对象 并exit
     */
    public static function exitJson( $code , $msg='', $target='' , $params=array(), $callback=NULL)
    {
        exit( self::returnJson($code, $msg, $target, $params,$callback));
    }

    /**
     * iframe的ajax方式输出json对象 主要是有script标签
     */
    public static function iFrameExitJson( $code , $msg='', $target='' , $params=array(), $callback=NULL)
    {
        if(Core_Comm_Validator::checkCallback($callback))		//有回调函数才需要<script>标签
        {
            exit('<script>'.self::returnJson($code, $msg, $target , $params, $callback).'</script>');
        }
        else
        {
            exit( self::returnJson($code, $msg, $target, $params));
        }
    }

    /**
     * showMsg 方法
     *
     * @param string $msg	提示信息内容
     * @param string $goUrl 跳转地址
     * @param int    $time	跳转等待时间
     * @param string $type  提示类型
     * @return mixed
     */
    public static function showMsg($msg, $goUrl = -1, $time = 2 , $type = 'succeed')
    {
        if ($goUrl == -1)
        {
            $goUrl = empty($_SERVER['HTTP_REFERER']) ? self::getUrlRoot() : $_SERVER['HTTP_REFERER'];
        } elseif (!preg_match ('#^(https?|ftp)://#' , $goUrl) && !preg_match ('/^javascript/' , $goUrl))
        {
            if(!preg_match('/^\//', $goUrl))
            {
                $goUrl = '/'.$goUrl;
            }
        }
        $output = array();
        //检测是否有问号?
        $parseUrl = parse_url($goUrl);
        if((array_key_exists('query',$parseUrl)))
        {
            parse_str($parseUrl['query'], $output);
            if(isset($output['errorType']))
            {
                unset($output['errorType'] );
            }
            if(isset($output['errorMsg']))
            {
                unset($output['errorMsg'] );
            }
        }
        $output['errorType'] = $type;
        $output['errorMsg']  = $msg;
        $url = isset($parseUrl['path']) ? $parseUrl['path'].'?'.http_build_query($output) : '/';
        return Redirect::to($url);
    }

    /**
     * 获得UrlRoot
     *
     * @return string
     */
    public static function getUrlRoot()
    {
        $http = $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
        return $http . '://' . $_SERVER['HTTP_HOST'];
    }

    /**
     * 格式化字节
     *
     * @param $size - 大小(字节)
     * @return string 返回格式化后的文本
     */
    public static function formatBytes($size)
    {
        if ($size >= 1073741824)
        {
            $size = round($size / 1073741824 * 100) / 100 . ' GB';
        }
        elseif ($size >= 1048576)
        {
            $size = round($size / 1048576 * 100) / 100 . ' MB';
        }
        elseif ($size >= 1024)
        {
            $size = round($size / 1024 * 100) / 100 . ' KB';
        }
        else
        {
            $size = $size . ' Bytes';
        }
        return $size;
    }

    /**
     * AES 加密
     *
     * @param string $str 加密的字符串
     * @return string
     */
    public static function encrypt($str)
    {
        # Add PKCS7 padding.
        $block = mcrypt_get_block_size(Config::get('app.cipher'), MCRYPT_MODE_ECB);
        $len = strlen($str);
        $padding = $block - ($len % $block);
        $str .= str_repeat(chr($padding),$padding);
        return mcrypt_encrypt(Config::get('app.cipher'), Config::get('app.key'), $str, MCRYPT_MODE_ECB);
    }

    /**
     * AES 解密
     *
     * @param string $str 加密的字符串
     * @return string
     */
    public static function decrypt($str)
    {
        $str = mcrypt_decrypt(Config::get('app.cipher'), Config::get('app.key'), $str, MCRYPT_MODE_ECB);
        //To remove PKCS7 padding
        $dec_s = strlen($str);
        $padding = ord($str[$dec_s-1]);
        return substr($str, 0, -$padding);
    }

    /**
     * 对变量进行 JSON 编码
     *
     * @param $arr
     * @return string
     */
    public static function jsonEncode($arr)
    {
        //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
        array_walk_recursive($arr, function (&$item, $key) { if (is_string($item)) $item = mb_encode_numericentity($item, array (0x80, 0xffff, 0, 0xffff), 'UTF-8'); });
        return mb_decode_numericentity(json_encode($arr), array (0x80, 0xffff, 0, 0xffff), 'UTF-8');
    }

    /**
     * 微信错误码函数定义
     *
     * 约定0为成功
     * 小于0代表系统错误（可重试的错误）
     * 大于0为逻辑错误（通常为程序的bug引起，需要人工干预）详细的错误码待定
     *
     * @param int $errorCode 错误码
     * @param string $errorMessage 错误信息
     * @param array $data 返回数据[当$errorCode状态为成功时，才有值]
     * @return string 返回Json错误信息
     */
    public static function returnWeiXinErrorJson($errorCode, $errorMessage = '', $data = null)
    {
        return Core_Comm_Apiret::getRetJson($errorCode, $errorMessage, $data);
    }

    /**
     * 机构名称变色[认证员]
     *
     * @param Order $order 订单对象
     * @return string
     */
    public static function returnStyleNameByVerify(Order $order)
    {
        $currentTime = time();
        $dt = strtotime($order->created_at);
        if($currentTime - $dt >= (36*3600) && $currentTime - $dt < (48*3600))
        {
            //大于等于36,小于48[变红]
            return 'style="background:#F79999"';
        } elseif($currentTime- $dt >= (48*3600))
        {
            //大于等于48[变黄]
            return 'style="background:#FDD762"';
        }
        return '';
    }

    /**
     * 机构名称变色[审核员]
     *
     * @param Order $order 订单对象
     * @return string
     */
    public static function returnStyleNameByAudit(Order $order)
    {
        $currentTime = time();
        $dt = strtotime($order->created_at);
        if($currentTime - $dt < (45*3600))
        {
            //小于45[黄绿色]
            return 'style="background:#C5ED74"';
        } elseif($currentTime - $dt >= (45*3600) && $currentTime - $dt < (48*3600))
        {
            //大于等于45,小于48[变红]
            return 'style="background:#F79999"';
        } elseif($currentTime- $dt >= (48*3600))
        {
            //大于等于48[不变色]
        }
        return '';
    }


    /**
     * 获取请求的流水号
     *
     * @return string
     */
    public static function getRequestNo()
    {
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);
        return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * 截取中英文混合字符串
     */
     public static function  mixSub($str, $len, $charset="utf-8")
    {
        //如果截取长度小于等于0，则返回空
        if( !is_numeric($len) or $len <= 0 )
        {
            return "";
        }

        //如果截取长度大于总字符串长度，则直接返回当前字符串
        $sLen = strlen($str);
        if( $len >= $sLen )
        {
            return $str;
        }
        //判断使用什么编码，默认为utf-8
        if ( strtolower($charset) == "utf-8" )
        {
            $len_step = 3; //如果是utf-8编码，则中文字符长度为3
        }else{
            $len_step = 2; //如果是gb2312或big5编码，则中文字符长度为2
        }

        //执行截取操作
        $len_i = 0;
        //初始化计数当前已截取的字符串个数，此值为字符串的个数值（非字节数）
        $substr_len = 0; //初始化应该要截取的总字节数

        for( $i=0; $i < $sLen; $i++ )
        {
            if ( $len_i >= $len ) break; //总截取$len个字符串后，停止循环
            //判断，如果是中文字符串，则当前总字节数加上相应编码的中文字符长度
            if( ord(substr($str,$i,1)) > 0xa0 )
            {
                $i += $len_step - 1;
                $substr_len += $len_step;
            }else{ //否则，为英文字符，加1个字节
                $substr_len ++;
            }
            $len_i ++;
        }
        $result_str = substr($str,0,$substr_len );
        return $result_str;
    }

}
