<?php
// +----------------------------------------------------------------------
// | LionPHP [ WE ARE LION ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xiaobai<634842632@qq.com>
// +----------------------------------------------------------------------
// | Route.class.php类，将URL统一转换成PATHINFO模式这是我们之前 
// +----------------------------------------------------------------------

class Route{
	//解析当前的URL
	public static function parseUrl(){
		if(isset($_SERVER['PATH_INFO'])){
			$pathinfo = explode('/',trim($_SERVER['PATH_INFO']));
			//解析控制器
			$_GET['c'] = (!empty($pathinfo[1]) ? $pathinfo[1] : 'index');
			//解析动作action
			$_GET['a'] = (!empty($pathinfo[2]) ? $pathinfo[2] : 'index');
			//获取传递的参数
			
			for($i=3,$j=count($pathinfo);$i<$j;$i+=2){
				$_GET[$pathinfo[$i]] = $pathinfo[$i+1];
			}
		}else{
			//获取控制器
			$_GET['c'] = (!empty($_GET['c']) ? $_GET['c'] : 'index');
			//获取action
			$_GET['a'] = (!empty($_GET['a']) ? $_GET['a'] : 'index');
			if($_SERVER['QUERY_STRING']){
				$c = $_GET['c'];
				$a = $_GET['a'];
				unset($_GET['c']);
				unset($_GET['a']);
				//将数组转化为URL格式
				$query = http_build_query($_GET);
				$url = $_SERVER['SCRIPT_NAME']."/{$c}/{$a}/".str_replace(array('=','&'),'/',$query);
				Header("Location: {$url}");
			}
		}
	}
}