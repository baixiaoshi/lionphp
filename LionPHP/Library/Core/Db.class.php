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
	//where条件
	protected $where = '';
	//sql字符串
	protected $sqlstr = '';

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
			Debug::addMsg("类".get_class($this)."方法".$methodName."不存在或者参数为空!",2);
		}
		//返回$this ，完成链式操作
		return $this;
	}
	/**
	 * 组合所有的where条件
	 * @return String
	 */
	public function combine_Where()
	{	//组合where条件的字符串
		$this->where = '';
		//组合where方法
		if(!empty($this->sqls['where']))
		{	//where条件的参数
			$args[0] = $this->sqls['where'][0];
			//and,或者or的标识，默认为and
			$args[1] = !empty($this->sqls['where'][1]) ? trim($this->sqls['where'][1]) : 'and';
			$this->where .= 'where ';
			foreach($args[0] as $k=>$v)
			{	//区别字段是数字还是字符串
				$v = is_numeric($v) ? $v : "'.$v.'";
				$this->where .= $k."=".$v;
				if(!end($this->sqls['where']) == $v)
				{
					$this->where .= $args[1];
				}
			}
		}
		
		//组合in方法,接受字符串和数组参数
		if(!empty($this->sqls['in']))
		{	
			if(is_array($this->sqls['in']))
			{	
				$this->where .= implode(',', $this->sqls['in']);	
			}
			else
			{
				$this->where .= strval($this->sqls['in']);
			}
		}
		//组合like方法，参数可以使字符串或者数组
		if(!empty($this->sqls['like']))
		{	//获取like传递的参数
			$args[0] = $this->sqls['like'][0];
			//是and或者or的标识，默认为and
			$args[1] = isset($this->sqls['like'][1]) ? trim($this->sqls['like'][1]) : 'and';
			//如果传递的第一个参数是数组
			if (is_array($args[0]))
			{
				//组合like语句
				$this->where .= implode($args[1], $args[0]);
			}
			//如果传递的第一个参数是字符串
			if(is_string(trim($args[0])))
			{
				$this->where .= 'like '.$args[0];
			}
		}
	}
	/**
	 * 组合limit条件
	 * limit(10)  limit(10,20)
	 * @return $this
	 */
	public function limit()
	{
		//赋值limit的条件
		$args = $this->sqls['limit'];
		//如果是一个数字数组
		if(is_array($args) && is_numeric($args[0]) && is_numeric($args[1]))
		{
			$this->sqlstr .= 'limit '.intval($args[0]).','.intval($args[1]);
			return $this;
		}
		//如果单个数字
		if (is_numeric($args)) {
			$this->sqlstr .= 'limit 0,'.intval($args);
			return $this;
		}

		//输出出错信息
		Debug::addMsg('方法limit的参数格式不正确!例子:limit(10)或者limit(10,20)',2);
	}
	/**
	 * 组合order条件
	 * @return $this
	 */
	public function order()
	{
		//获取参数字符串
		$args[0] = is_array($this->sqls['order']) ? trim($this->sqls['order'][0]) : trim($this->sqls['order']);
		//获取asc ,desc标志
		$args[1] = isset($this->sqls['order'][2]) ? trim($this->sqls['order'][2]) : 'asc';
		$this->sqlstr .= 'order by'.$args[0].' '.$args[1];
		return $this;
	}

}