<?php


//通用的数组排序
function do_sort($arr,$field,$order='desc')
{
    if(!is_array($arr) || !$arr || $field=='')
        return $arr ;
    $index_arr = array() ;
    foreach($arr as $k=>$v)
        if(isset($v[$field]))
            array_push($index_arr,$v[$field]) ;
    ($order == 'desc') ? rsort($index_arr) : sort($index_arr) ;
    $ret = array() ;
   foreach($index_arr as $index)
        foreach($arr as $k=>$v)
            if($v[$field] == $index)
                $ret[$k] = $v ;
   return $ret ;
}

/**
 * 获取目录树
 * 1. 将目录树结果集取出来
 * 2. 将数组变成自增ID作为键值的数组
 * 3. 传入到函数中
 * 4. 出来后对其中的path字段进行排序操作就可以了
 * 5. 可以按照目录树进行操作
 * @param  [type]  $arr    [description]
 * @param  integer $parent [description]
 * @param  integer $level  [description]
 * @return [type]          [description]
 */
public function get_dir_tree($arr,$parent=0,$level=0)
{   
    if(!is_array($arr) || !$arr)
        return $arr ;
        $level++ ;
    foreach($arr as $k=>$v)
    {   
        if($v['parent_id'] == $parent)
        {
            if($parent == 0)
                $arr[$k]['path'] = '0-'.$v['id'] ;
            else
                $arr[$k]['path'] = $arr[$v['parent_id']]['path'].'-'.$v['id'] ;
            $arr[$k]['level'] = $level ;
            $count = 0 ;
            foreach($arr as $k2=>$v2)
                if($v2['parent_id'] == $parent)
                    $count ++ ;

            if($count)
                $arr = self::get_dir_tree($arr,$v['id'],$level) ;
        }
    }
    return $arr;
}

/**
 * 下载excel报表
 * @param  array  $data       数组
 * @param  array  $field       字段名称
 * @param  array  $field_words 字段
 * @param  [type] $filename    输出excel名称
 * @return [type]              直接传入这些参数即可下载
 */
function download_excel($data=array(),$field=array(),$field_words=array(),$filename)
{	
	//error_reporting(E_ALL);
	//ini_set('display_errors', TRUE);
	//ini_set('display_startup_errors', TRUE);
	$word = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	include APPPATH.'libraries/PHPExcel.php';

	include APPPATH.'libraries/PHPExcel/Writer/Excel2007.php';

	$objPHPExcel = new PHPExcel();

	$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
								 ->setLastModifiedBy("Maarten Balliauw")
								 ->setTitle("Office 2007 XLSX Test Document")
								 ->setSubject("Office 2007 XLSX Test Document")
								 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("Test result file");
	if(count($field) >26) return '字段超过26个字母了,请增加字母';

	//设置表头
	for($i=0,$len=count($field);$i<$len;$i++)
	{
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($word[$i].'1',$field[$i]);
	}

	$i=2;
	foreach($data as $k=>$v)
	{
		for($j=0,$len=count($field);$j<$len;$j++)
		{	
			$index = $field_words[$j];
			if(isset($v[$index]))
			{
				$objPHPExcel->setActiveSheetIndex(0)
		           ->setCellValue($word[$j].$i,$v[$index]);
			}
		 	
	 	}
	 	$i++;
	}

	$objPHPExcel->getActiveSheet()->setTitle('Simple');
	$objPHPExcel->setActiveSheetIndex(0);

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	header('Cache-Control: max-age=1');

	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); 
	header ('Cache-Control: cache, must-revalidate');
	header ('Pragma: public');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$rst = $objWriter->save('php://output');

}







#导出类excel,csv格式,简单的导出可以使用这个函数，简单高效
function download_excel($ret,$field_words,$field)
{
	header( "Content-type:application/vnd.ms-excel" ) ;
	header( "Content-disposition:attachment;filename=download.xls" ) ;
	//$field_words = array('flowid','username','merchant_id','merchant_name','platform_id','platform_name','gameid','project_name','is_seal','statmonth','pay','settlement','financer','finance_time','status') ;
	//$field = array('流程单号','业务提交人','客商ID','可算名称','平台ID','平台名称','项目ID','项目名称','是否盖章','结算月份','充值','结算金额','财务审核人','财务审核日期','状态') ;
	//dump($field_words) ;
	//dump($field) ;
	foreach($field_words as $key =>$field_key)
	{
		$field_name = iconv('utf-8','gbk',$field_key) ;
		echo $field_name.chr(9) ;
	}


	echo chr(13) ;
	if(is_array($ret) && $ret)
		foreach($ret as $key =>$val)
		{
			foreach($field as $key2 =>$f)
			{
				$value = isset($val[$f])?iconv('utf-8','gbk',$val[$f]) : '--' ;
				echo $value.chr(9) ;
			}
			echo chr(13) ;
		}
	return true ;
}



#去除换行符可以使用如下的方法

function filter_enter($str)
{
    if($str == '') return $str ;
    #这里特别做一个说明，如果数组中的\r\n用单引号是不会生效的
    #这就是之所以我在这里记录下的原因
    str_replace(array("\r\n","\r","\n"),'',$str) ;
    #使用php换行符常亮
    str_replace(PHP_EOL,'',$str) ;
    #使用正则
    preg_replace('/\s*/','',$str) ;
}

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
