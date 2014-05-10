<?php
// +----------------------------------------------------------------------
// | LionPHP [ WE ARE LION ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xiaobai<634842632@qq.com>
// +----------------------------------------------------------------------
// | Db.class.php 数据库基础类，定义了sql的基本操作
// +----------------------------------------------------------------------

class Db{
	//sql数组
	protected $sql = array('where','in','like','limit','order','group','join','union','distinct','having');

	/**
	 * 实现链式操作,where,orwhere,in,like,limit,order by,group by,join,union,distinct,having
	 * @param  String $name      调用方法名
	 * @param  mixed $arguments 方法中的参数
	 * @return reference         返回类的引用$this
	 */
	public function __call($methodName,$args)
	{	
		if(in_array(strtolower($methodName), $this->sqls) && !is_null($arguments))
		{
			$this->sqls[$methodName] = $args;
		}else{
			Debug::addMsg("类".get_class($this)."方法".$methodName."不存在或者参数为空!");
		}
		//返回$this ，完成链式操作
		return $this;
	}

	public function combineWhere()
	{	//组合where条件的字符串
		$where = '';
		//组合where方法
		if(!empty($this->sqls['where']))
		{	//where条件的参数
			$args[0] = $this->sqls['where'][0];
			//and,或者or的标识，默认为and
			$args[1] = !empty($this->sqls['where'][1]) ? trim($this->sqls['where'][1]) : 'and';
			$where .= 'where ';
			foreach($args[0] as $k=>$v)
			{	//区别字段是数字还是字符串
				$v = is_numeric($v) ? $v : "'.$v.'";
				$where .= $k."=".$v;
				if(!end($this->sqls['where']) == $v)
				{
					$where .= $args[1];
				}
			}
		}
		
		//组合in方法,接受字符串和数组参数
		if(!empty($this->sqls['in']))
		{	
			if(is_array($this->sqls['in']))
			{	
				$where .= implode(',', $this->sqls['in']);
				
			}else{
				$where .= strval($this->sqls['in']);
			}
		}
		//组合like方法，参数可以使字符串或者数组
		if(!empty($this->sqls['like']))
		{	//获取like传递的参数
			$args[0] = $this->sqls['like'][0];
		}
	}
}