<?php
/*
* author	袁绍月
* date		2015/5/28
* 分类
*/
namespace Home\Controller;
use Think\Controller;
class ClassifyController extends CommonController {
	public function getclassifylist()
	{
		$class_list	=	M('Classify')->where(array('parentid'=>0))->select();
		echo json_encode($class_list);
		//$this->assign('class_list',$class_list);
	}
	public function ajax_list()
	{
		import("Think/Tree");
		$tree	=	new \Think\Tree();
		$list	=	M('Classify')->order('parentid asc,order_num asc,id asc')->select();
		$this->assign('list',$list);
		foreach($list as $item)
		{
			$tree->setNode($item['id'],$item['parentid'],$item['title'],$item['is_hidden'],$item['order_num']);
		}
		$this->assign('html',$tree->getClassifyList());
		$this->display();
	}
	public function _before_insert()
	{
		$parentid	=	I('post.parentid');
		$title		=	I('post.title');
		$map['parentid']=	$parentid;
		$map['title']	=	$title;
		$row	=	M('Classify')->where($map)->find();
		if($row)
		{
			$return['msg']='同级分类下分类名称不能重复';
			$this->ajaxReturn($return,'JSON');
		}
	}
	public function _before_update()
	{
		$model	=	M('Classify');
		$id	=	I('post.id');
		$parentid	=	I('post.parentid');
		$title		=	I('post.title');
		$map['parentid']=	$parentid;
		$map['title']	=	$title;
		$row	=	M('Classify')->where($map)->find();
		if($row && $row['id']!=$id)
		{
			$return['msg']='同级分类下分类名称不能重复';
			$this->ajaxReturn($return,'JSON');
		}
	}
}
?>