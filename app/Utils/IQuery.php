<?php 

/*
 * @version: 0.1 查询工具类范例
 * @author: wuzhihui
 * @date: 2016/9/4
 * @description:
 *
 * @version 0.2 加入新的通用方法
 * @author: wuzhihui
 * @date : 2017/4/13
 * @description:
 *
 */

namespace app\Utils;

class IQuery{
	
	//加入排序
	public function ofOrder(&$query, $request)
	{
		//$query = $query->orderBy('id', 'desc');
		$sort = $request->input('_sort');
		$order =$request->input('_order');
		if($sort != null && $sort != ''){
			if($order != null && $order == 'desc') $query = $query->orderBy($sort, 'desc');
			else $query = $query->orderBy($sort,'asc');
		}else{
			$query = $query->orderBy('id', 'desc');
		}
		return $query;
	}

	public function ofDefault(&$query, $request, $order_params, $text_params = ['name'], $table = '')
	{
		$query = $this->ofOrder2($query, $request, $order_params);
		$query = $this->ofText($query, $request, $text_params);
		$query = $this->ofStatus($query, $request, $table);
		$query = $query->paginate(10);
		return $query;
	}

	//加入排序
	public function ofOrder2(&$query, $request, $order_params)
	{
		$sort = $request->input('_sort');
		$order = $request->input('_order');

		if($sort != null && $sort != ''){
			if($order != null && $order == 'desc') $query = $query->orderBy($order_params[$sort], 'desc');
			else $query = $query->orderBy($order_params[$sort],'asc');
		}else{
			$query = $query->orderBy($order_params['id'], 'desc');
		}
		return $query;
	}

	//加入状态查询
	public function ofStatus(&$query, $request, $table = '')
	{
		if($table != '') $table = $table.'.';
		$status = $request->input('cstatus');
		if($status != null && $status != ''){
			$query = $query->where($table.'status', '=', $status);
		}
		return $query;
	}

	//加入文本查询
	public function ofText(&$query, $request, $text_params = ['name'])
	{
		$texts=  explode(' ', $request->query_text);
		$query = $query->where(function ($query) use ($request, $text_params, $texts){
			foreach($text_params as $param){
				$query->orWhere(function($query) use ($param, $texts){
					foreach($texts as $text){
						$query->where($param, 'like', '%'.$text.'%');
					}
				});
			}
		});
		return $query;
	}

	
}