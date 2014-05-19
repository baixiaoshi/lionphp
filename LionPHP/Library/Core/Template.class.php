<?php
// +----------------------------------------------------------------------
// | LionPHP [ WE ARE LION ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xiaobai<634842632@qq.com>
// +----------------------------------------------------------------------
// | Template类继承自Smarty类
// +----------------------------------------------------------------------

class Template extends Smarty{

	public function __construct(){
		parent::__construct();
		//定义模板目录
		$this->template_dir = APP_PATH.'View/'.VIEW_PATH.'/'.$_GET['c'].'/';
		//定义编译目录
		$this->compile_dir = RUNTIME_PATH.'compiles/'.VIEW_PATH.'/';
		//设置缓存
		$this->caching = IS_CACHE;
		//设置缓存目录
		$this->cache_dir = RUNTIME_PATH.'cache/'.VIEW_PATH.'/';
		//设置缓存时间
		$this->cache_lifetime = CACHE_TIME;
		//左右定界符
		$this->left_delimiter = '<{';
		$this->right_delimiter = '}>';

	}

	/**
	 * 重写smarty的display方法
	 * @param  String $resource_name 文件名称
	 * @param  Int $cache_id     	缓存ID
	 * @param  Int $compile_id    编译文件ID
	 * @return void
	 */
	public function display($resource_name, $cache_id = null, $compile_id = null){
		//分配一下前端变量
		//默认模板目录
		$this->assign('VIEW_ROOT',VIEW_ROOT);
		$this->assign('DEFAULT_TPL_PATH',DEFAULT_TPL_PATH);
		$this->assign('JS_PATH',JS_PATH);
		$this->assign('CSS_PATH',CSS_PATH);
		$this->assign('IMAGE_PATH',IMAGE_PATH);
		parent::display($resource_name.TPL_SUFFIX,$cache_id,$compile_id);
	}

	/**
	 * 切换模板目录
	 * @param  String $styleName 模板文件夹名称
	 * @return void
	 */
	public function chStyle($styleName){
		//新模板的目录不存在则生成
		if(!file_exists(RUNTIME_PATH.'compiles/'.$styleName.'/') || !file_exists(RUNTIME_PATH.'cache/'.$styleName.'/')){
			mkdir(RUNTIME_PATH.'compiles/'.$styleName.'/',0755,false);
			mkdir(RUNTIME_PATH.'cache/'.$styleName.'/',0755,false);
		}
		//定义模板目录
		$this->template_dir = APP_PATH.'View/'.$styleName.'/'.$_GET['c'].'/';
		//定义编译目录
		$this->compile_dir = RUNTIME_PATH.'compiles/'.$styleName.'/';
		
		
	}

}