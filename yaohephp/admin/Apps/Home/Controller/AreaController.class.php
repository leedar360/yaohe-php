<?php
/*
* author	袁绍月
* date		2015/5/27
* 地区类
*/
namespace Home\Controller;
use Think\Controller;
class AreaController extends CommonController {
	public function _before_index()
	{
		$province_list	=	M('Province')->select();
		$provincearr	=	array();
		foreach($province_list as $item)
		{
			$provincearr[$item['id']]=$item['title'];
		}
		$this->assign('provincearr',$provincearr);
		$this->assign('province_list',$province_list);
	}
	public function _before_ajax_list()
	{
		$province_list	=	M('Province')->select();
		$provincearr	=	array();
		foreach($province_list as $item)
		{
			$provincearr[$item['id']]=$item['title'];
		}
		$this->assign('provincearr',$provincearr);
		$city_list	=	M('City')->select();
		$cityarr	=	array();
		foreach($city_list as $item)
		{
			$cityarr[$item['id']]=$item['title'];
		}
		$this->assign('cityarr',$cityarr);
	}
	public function getcitylist()
	{
		$province_id	=	I('post.province_id');
		$list	=	M('City')->where(array('p_id'=>$province_id))->select();
		if(!$list)$list=array();
		echo json_encode($list);
	}
}
?>