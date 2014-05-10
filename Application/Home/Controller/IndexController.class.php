<?php

class IndexController extends Controller{

	public function index(){
		$arr = array(
			'username'	=> 'baixiaoshi',
			'password'	=> '123456',
			'ages'		=> 23
			);
		echo implode(',', $arr);
	}

	public function __call($methodName,$args)
	{
		if($methodName == 'hello')
		{
			dump($args);
		}
	}


}