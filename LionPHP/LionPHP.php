<?php

// +----------------------------------------------------------------------
// | LionPHP [ WE ARE LION ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xiaobai<634842632@qq.com>
// +----------------------------------------------------------------------
//设置编码为UTF8
Header('Content-Type:text/html; charset=utf-8');
//设置时区(中国)
date_default_timezone_set("PRC"); 
//定义框架目录
define('LION_PATH',__DIR__.'/');
//定义框架通用函数目录
define('LION_COMMON_PATH',LION_PATH.'Common/');
//定义框架类库目录
define('LION_LIBRARY_PATH',LION_PATH.'Library/');
//定义框架核心目录
define('LION_CORE_PATH',LION_LIBRARY_PATH.'Core/');
//定义框架第三方目录
define('LION_VENDOR_PATH',LION_LIBRARY_PATH.'Vendor/');
//定义框架模板目录
define('LION_TPL_PATH',LION_LIBRARY_PATH.'Tpl/');
//定义框架配置目录
define('LION_CONFIG_PATH',LION_PATH.'Config/');
//网站根目录
define('ROOT_PATH',dirname($_SERVER['SCRIPT_FILENAME']).'/');
//运行时配置目录
define('RUNTIME_PATH',ROOT_PATH.'Application/Runtime/');
//项目权限控制目录
define('APP_COMMON_PATH',APP_PATH.'Common/');
//加载通用函数
require_once LION_COMMON_PATH.'functions.php';
//加载框架本身配置文件
$config = require_once LION_CONFIG_PATH.'config.php';
foreach($config as $key=>$val){
	define($key,$val);
}
//加载DEBUG类来处理程序的时候遇到的问题
if(DEBUG == TRUE){
	//设置显示所有的错误信息
	error_reporting(E_ALL);
	require_once LION_CORE_PATH.'Debug.class.php';
	Debug::start();
	set_error_handler(array('Debug','onError'));
}else{
	//此时开启错误日志
	ini_get('log_errors','On');
	//写入log
	ini_get('error_log',RUNTIME_PATH.'error_log');
}
Debug::addMsg('这条测试信息!');
//前端需要的常量
define('VIEW_ROOT',APP_PATH.'View/');
//模板默认目录
define('DEFAULT_TPL_PATH',APP_PATH.'View/default/');
//默认的js目录
define('JS_PATH',DEFAULT_TPL_PATH.'statics/js/');
//默认css目录
define('CSS_PATH',DEFAULT_TPL_PATH.'statics/css/');
//默认图片目录
define('IMAGE_PATH',DEFAULT_TPL_PATH.'statics/image/');


//加载进来核心的类库
$include_path = get_include_path();
$include_path .= PATH_SEPARATOR.LION_CORE_PATH;
$include_path .= PATH_SEPARATOR.LION_VENDOR_PATH;
$include_path .= PATH_SEPARATOR.LION_TPL_PATH.'Smarty/';
//应用控制器目录
$include_path .= PATH_SEPARATOR.APP_PATH.'Controller/';
set_include_path($include_path);
//自动加载类
function __autoload($classname){
	require_once $classname.'.class.php';
}
//解析处理URL
Route::parseUrl();
//如果应用目录不存在，则生成，如果存在就跳转到相应的控制器
if(!is_dir(APP_COMMON_PATH)){
	//加载框架本身通用函数
	require_once LION_COMMON_PATH.'build.php';
}


//控制器所在目录
$srcController = APP_PATH.'Controller/'.$_GET['c'].'Controller.class.php';

if(file_exists($srcController)){
	$classname = ucfirst(trim($_GET['c'])).'Controller';
	$controller = new $classname();
	$controller->run();
}

//最后调用debug来输出调试信息
if(DEBUG == TRUE){
	Debug::end();//脚本停止运行时间点
	Debug::outputMessage();
}




