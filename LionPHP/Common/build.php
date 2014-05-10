<?php
// +----------------------------------------------------------------------
// | LionPHP [ WE ARE LION ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xiaobai<634842632@qq.com>
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | 创建目录结构
// +----------------------------------------------------------------------

build_app_dir();
//创建应用目录
function build_app_dir(){
	$msg = '';
	//定义需要创建的目录数组
	$dirArr = array(
		'APP_PATH'			=>	APP_PATH,
		'APP_PATH/Common'	=>	APP_PATH.'Common',
		'APP_PATH/Config'	=>	APP_PATH.'Config',
		'APP_PATH/Controller'=>	APP_PATH.'Controller',
		'APP_PATH/Model'	=>	APP_PATH.'Model',
		'APP_PATH/View'		=>	APP_PATH.'View',
		'APP_PATH/View/default'=>APP_PATH.'View/default',
		'APP_PATH/View/default/statics'	=>APP_PATH.'View/default/statics',
		'APP_PATH/View/default/statics/js'	=>APP_PATH.'View/default/statics/js',
		'APP_PATH/View/default/statics/css'	=>APP_PATH.'View/default/statics/css',
		'APP_PATH/View/default/statics/image'	=>APP_PATH.'View/default/statics/image',
		'RUNTIME_PATH'	=>	RUNTIME_PATH,
		//创建模板编译目录
		'TEMPLATE_COMPILE_DIR'		=>	RUNTIME_PATH.'compiles/default',
		'CACHE_DIR'					=>	RUNTIME_PATH.'cache/default',

		);
	//循环创建目录
	foreach($dirArr as $val){
		if(!is_dir($val)){
			mkdir($val,0755,true);
			$msg .= '目录<b>'.$val.'</b>创建成功!<br/>';
		} 
	}
	echo $msg;
	//写入安全文件
	foreach($dirArr as $val){
		if(!is_file($val.'/index.html')) touch($val.'/index.html');
	}

}

