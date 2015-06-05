<?php



/**
 * 获得当前的域名
 *
 * @return  string
 */
function get_domain()
{
    /* 协议 */
    $protocol = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';

    /* 域名或IP地址 */
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
    {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    }
    elseif (isset($_SERVER['HTTP_HOST']))
    {
        $host = $_SERVER['HTTP_HOST'];
    }
    else
    {
        /* 端口 */
        if (isset($_SERVER['SERVER_PORT']))
        {
            $port = ':' . $_SERVER['SERVER_PORT'];

            if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
            {
                $port = '';
            }
        }
        else
        {
            $port = '';
        }

        if (isset($_SERVER['SERVER_NAME']))
        {
            $host = $_SERVER['SERVER_NAME'] . $port;
        }
        elseif (isset($_SERVER['SERVER_ADDR']))
        {
            $host = $_SERVER['SERVER_ADDR'] . $port;
        }
    }

    return $protocol . $host;
}

/**
 * 验证输入的邮件地址是否合法
 *
 * @param   string      $email      需要验证的邮件地址
 *
 * @return bool
 */
function is_email($user_email)
{
    $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,5}\$/i";
    if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false)
    {
        if (preg_match($chars, $user_email))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}


/**
 * 递归方式的对变量中的特殊字符去除转义
 *
 * @access  public
 * @param   mix     $value
 *
 * @return  mix
 */
function stripslashes_deep($value)
{
    if (empty($value))
    {
        return $value;
    }
    else
    {
        return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
    }
}


/**
 * 获取服务器的ip
 *
 * @access      public
 *
 * @return string
 **/
function real_server_ip()
{
    static $serverip = NULL;

    if ($serverip !== NULL)
    {
        return $serverip;
    }

    if (isset($_SERVER))
    {
        if (isset($_SERVER['SERVER_ADDR']))
        {
            $serverip = $_SERVER['SERVER_ADDR'];
        }
        else
        {
            $serverip = '0.0.0.0';
        }
    }
    else
    {
        $serverip = getenv('SERVER_ADDR');
    }

    return $serverip;
}


/**
 * 获得用户操作系统的换行符
 *
 * @access  public
 * @return  string
 */
function get_crlf()
{
/* LF (Line Feed, 0x0A, \N) 和 CR(Carriage Return, 0x0D, \R) */
    if (stristr($_SERVER['HTTP_USER_AGENT'], 'Win'))
    {
        $the_crlf = "\r\n";
    }
    elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Mac'))
    {
        $the_crlf = "\r"; // for old MAC OS
    }
    else
    {
        $the_crlf = "\n";
    }

    return $the_crlf;
}



	/**
 * 危险 HTML代码过滤器
 *
 * @param   string  $html   需要过滤的html代码
 *
 * @return  string
 */
function html_filter($html)
{
    $filter = array(
        "/\s/",
        "/<(\/?)(script|i?frame|style|html|body|title|link|\?|\%)([^>]*?)>/isU",//object|meta|
        "/(<[^>]*)on[a-zA-Z]\s*=([^>]*>)/isU",
        );

    $replace = array(
        " ",
        "&lt;\\1\\2\\3&gt;",
        "\\1\\2",
        );

    $str = preg_replace($filter,$replace,$html);
    return $str;
}

	/**
	 * 将数组转化为以某个字段为键值的数组，最多支持三维数组
	 * 我们要巧妙的利用多维数组来解决问题，数据就是数据结构
	 * @param  [type] $array 数组
	 * @param  [type] $keys  组合自己的键值数组
	 * @return [type]        [description]
	 */
	function array_compare_key($array,$keys)
	{
		if(!is_array($array) || !$array || !is_array($keys) || !$keys)
			return $array ;
		$deep = count($keys) ;
		$return = array() ;
		switch($deep)
		{
			case 1:
				foreach($array as $k=>$v)
				{
					if(isset($v[$keys[0]]))
					{
						$return[$v[$keys[0]]] = $v;
					}
				}
				break;
			case 2:
				foreach($array as $k=>$v)
				{
					if(isset($v[$keys[0]]) && isset($v[$keys[1]]))
					{
						$return[$v[$keys[0]]][$v[$keys[1]]] = $v ;
					}
				}
			case 3:
				foreach($array as $k=>$v)
				{
					if(isset($v[$keys[0]]) && isset($v[$keys[1]]) && isset($v[$keys[2]]))
					{
						$return[$v[$keys[0]]][$v[$keys[1]]][$v[$keys[2]]] = $v ;
					}
				}
		}
		return $return ;
	}
}


#打印数组
function dd($arr)
{	
	if(!is_array($arr) || !$arr)
		return false ;
	echo '<pre>';
		print_r($arr) ;
	echo '</pre>';
}



#打印原型格式到文件中，这个非常的有帮助
function put($arr,$path)
{	
	if(!is_array($arr) || !$arr || !$path)
		return false ;
	ob_start() ; #开启ob缓存
	echo '<pre>' ;
		print_r($arr) ;
	echo '</pre>' ;
	$content = ob_get_contents() ;
	file_put_contents($path,$content."\r\n",FILE_APPEND) ;
	ob_end_flush() ; //结束ob缓存
}



#判断请求是否来自浏览器
function is_from_browser()
{
	static $ret_val = null ;
	if($ret_val === null)
	{
		$ret_val = false ;
		$ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '' ;
		if($ua)
		{
			if(strpos($ua, 'mozilla') !== false  && strpos($ua, 'msie') !== false && strpos($ua, 'gecko') !== false)
			{
				$ret_val = false ;
			}
			elseif (strpos($ua, 'opera'))
			{
				$ret_val = true ;
			}
		}
		return $ret_val ;
	}
}


/**
 *    所有类的基础类
 *
 *    @author    Garbin
 *    @usage    none
 */
class Object
{
    var $_errors = array();
    var $_errnum = 0;
    function __construct()
    {
        $this->Object();
    }
    function Object()
    {
        #TODO
    }
    /**
     *    触发错误
     *
     *    @author    Garbin
     *    @param     string $errmsg
     *    @return    void
     */
    function _error($msg, $obj = '')
    {
        if(is_array($msg))
        {
            $this->_errors = array_merge($this->_errors, $msg);
            $this->_errnum += count($msg);
        }
        else
        {
            $this->_errors[] = compact('msg', 'obj');
            $this->_errnum++;
        }
    }

    /**
     *    检查是否存在错误
     *
     *    @author    Garbin
     *    @return    int
     */
    function has_error()
    {
        return $this->_errnum;
    }

    /**
     *    获取错误列表
     *
     *    @author    Garbin
     *    @return    array
     */
    function get_error()
    {
        return $this->_errors;
    }
}