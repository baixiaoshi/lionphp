<?php

// +----------------------------------------------------------------------
// | LionPHP [ WE ARE LION ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xiaobai<634842632@qq.com>
// +----------------------------------------------------------------------
header('Content-Type:text/html; charset=utf-8');
//检测PHP的版本
if(version_compare(PHP_VERSION,'5.3.0','<')) die('PHP版本要求大于5.3.0');
//定义项目名称目录,创建多个项目的时候改变这个常量
define('APP_PATH',__DIR__.'/Application/Home/');
//引入框架入口文件
require_once './LionPHP/LionPHP.php';
echo "hello world";
