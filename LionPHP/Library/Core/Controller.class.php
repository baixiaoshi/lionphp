<?php
// +----------------------------------------------------------------------
// | LionPHP [ WE ARE LION ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xiaobai<634842632@qq.com>
// +----------------------------------------------------------------------
// | Controller.class.php类，通过pathinfo模式统一调度
// +----------------------------------------------------------------------

class Controller extends Template{
	public function __construct(){
		parent::__construct();
	}

	public function run(){
		//获取方法
		$method = trim($_GET['a']);
		if(method_exists($this, $method)){
			$this->$method();
		}else{
			Debug::addMsg('控制器中没有<font color="red">'.$method.'</font>这个方法');
		}
	}
}