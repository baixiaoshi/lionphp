<?php

class IndexController extends Controller{

	public function index(){
		

		$str = " hello world do\r\n you work??" ;
		
		//$ret =  str_replace(array("\r\n","\n","\r"),'',$str) ;
		//$ret = str_replace(PHP_EOL,'',$str) ;
		$ret = preg_replace('/\s*/','',$str) ;
		$fp = fopen('d:/log.txt','a+') ;
		if($fp !== false)
		{
			fwrite($fp, $str) ;
			fwrite($fp, '@@@@@@@@@') ;
			fwrite($fp, $ret) ;
		}
		fclose($fp) ;
		//echo preg_replace('/\s/', '', $str) ;

	}

	public function __call($methodName,$args)
	{
		if($methodName == 'hello')
		{
			dump($args);
		}
	}

}