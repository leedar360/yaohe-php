<?php
/*
* author	袁绍月
* date		2015/5/31
* 角色类
*/
namespace Home\Controller;
use Think\Controller;
class RoleController extends CommonController {

	public function _before_add()
	{
		//获取省份列表
		$province_list	=	M('Province')->select();
		$this->assign('province_list',$province_list);
	}

	public function _before_edit()
	{
		//获取省份列表
		$province_list	=	M('Province')->select();
		$this->assign('province_list',$province_list);
	}
	/**
	* 功能：获取城市列表
	*/
	public function getcitylist()
	{
		$province_id	=	I('post.province_id');
		$list	=	M('City')->where(array('p_id'=>$province_id))->select();
		if(!$list)$list=array();
		echo json_encode($list);
	}
	
}
?>