<?php
// +----------------------------------------------------------------------
// | LionPHP [ WE ARE LION ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xiaobai<634842632@qq.com>
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | 这个类主要写框架本身用到的函数
// +----------------------------------------------------------------------

//输出友好的变量

function dump($vars){
	echo '<pre>';
		print_r($vars);
	echo '</pre>';
}

