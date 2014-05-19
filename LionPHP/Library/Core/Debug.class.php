<?php
// +----------------------------------------------------------------------
// | LionPHP [ WE ARE LION ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xiaobai<634842632@qq.com>
// +----------------------------------------------------------------------
// | Debug.class.php类用于调试模式
// +----------------------------------------------------------------------

class Debug{
	//系统的错误信息
	static $info = array();
	//文件包含信息
	static $includeStr = array();
	//SQL语句执行信息
	static $sqlInfo = array();
	//定义脚本开始结束运行时间
	static $startTime;
	static $endTime;
	//定义错误数组
	static $errorArr = array(
		'E_ERROR'	=> '致命错误',
		'E_WARNING'	=> '运行时警告',
		'E_NOTICE'	=>	'运行时通知',
		'E_USER_ERROR'	=> '用户自定义错误',
		'E_USER_WARNING' => '用户自定义警告',
		'E_USER_NOTICE'	=> '用户自定义通知',
		'E_STRICT'	=> '编码错误',
		'Unknow'	=> '未知错误',
		);
	//脚本开始时间
	public static function start(){
		return self::$startTime = microtime(true);
	}

	//脚本结束时间
	public static function end(){
		return self::$endTime = microtime(true);
	}

	//脚本运行所花费的时间
	public static function spendTime(){
		return round((self::$endTime - self::$startTime),4);
	}

	//自定义出错函数
	public static function onError($errno,$errstr,$errfile,$errline){
		if(!isset(self::$errorArr[$errno])){
			$errno = 'Unknow';
		}
		$message = '【'.self::$errorArr[$errno].'】'.':<br/>';
		$message .= '在文件:'.$errfile.'中的第<font color="red">'.$errline.'</font>行<br/>';
		$message .= '错误消息:'.$errstr;
		self::addMsg($message);

	}

/**
 * 添加出错消息
 * @param String $mess 出错文本
 * @param Int $type 0代表系统,１代表包含,２代表Sql
 */
	public static function addMsg($mess,$type=0){
		switch($type){
			case 0:
				self::$info[] = $mess;//这种方式也可以加入进数组
				break;
			case 1:
				self::$includeStr[] = $mess;
				break;
			case 2:
				self::$sqlInfo[] = $mess;
				break;
		}
	}

	public static function outputMessage(){

		$messStr ='<div id="debug_box" style="position:absolute;bottom:0;border:1px dotted #333;background-color:#EEE;font-size:13px;width:90%;margin:40px auto 10px 40px;"><span id="close" style="position:absolute;top:0;right:0;cursor:pointer;">关闭</span><ul style="list-style:none;"><li>运行<font color="red">'.self::spendTime().'</font>秒</li>';
		$messStr .= '<li>【<b>包含(<font color="red">'.count(get_included_files()).'</font>)个文件</b>】</li>';
		foreach(get_included_files() as $val){
			$messStr .= '<li>'.$val.'</li>';
		}
		$messStr.='<li>【<b>系统消息(<font color="red">'.count(self::$info).'</font>)条</b>】</li>';

		foreach(self::$info as $val){
			$messStr .= '<li>'.$val.'</li>';
		}

		if(!empty(self::$sqlInfo)){
			$messStr .= '<li>【<b>SQL信息</b>】</li>';
			foreach(self::$sqlInfo as $val){
				$messStr .= '<li>'.$val.'</li>';
			}
		}
		$messStr .='</ul></div>';
		$messStr .='<script>var close=document.getElementById("close");close.onclick=function(){document.getElementById("debug_box").style.display="none";}</script>';
		echo $messStr;
	}

}