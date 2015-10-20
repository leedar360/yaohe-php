<?php
/*
* author	袁绍月
* date		2015/5/28
* 绑定分类
*/
namespace Home\Controller;
use Think\Controller;
class BindingClassController extends CommonController {
	public function _before_index()
	{
		$province_id	=	I('get.province_id');
		$city_id		=	I('get.city_id');
		$province_list	=	M('Province')->select();
		$this->assign('province_list',$province_list);
		if($province_id>0)
		{
			$city_list	=	M('City')->where(array('p_id'=>$province_id))->select();
			$this->assign('city_list',$city_list);
		}
		$this->assign('province_id',$province_id);
		$this->assign('city_id',$city_id);
	}
	public function getcitylist()
	{
		$province_id	=	I('post.province_id');
		$list	=	M('City')->where(array('p_id'=>$province_id))->select();
		if(!$list)$list=array();
		echo json_encode($list);
	}
	public function ajax_list()
	{
		$city_id=	intval(I('get.city_id'));
		$map['city_id']	=	$city_id;
		$class_list	=	M('HomeClass')->where($map)->select();
		$class_id_arr=	array();
		foreach($class_list as $item)
		{
			$class_id_arr[]=$item['class_id'];
		}
		//var_dump($class_id_arr);exit;
		$this->assign('class_id_arr',$class_id_arr);
		//$this->assign('class_list',$class_list);
		$list	=	M('Classify')->order('parentid asc,order_num asc,id asc')->select();
		import("Think/Tree");
		$tree	=	new \Think\Tree();
		$arrlist=	array();
		foreach($list as $item)
		{
			$tree->setNode($item['id'],$item['parentid'],$item['title'],$item['is_hidden'],$item['order_num']);
			if($item['parentid']>0)continue;
			$arrlist[]=$item;
		}
		foreach($arrlist as $key=>$item)
		{
			$arrlist[$key]['list']	=	$tree->getIndustryClassList($item['id']);
		}
		$this->assign('arrlist',$arrlist);
		$this->display();
	}
	public function save()
	{
		$city_id=	intval(I('post.city_id'));
		if($city_id<1)
		{
			$return['msg']	=	'请选择城市';
			$this->ajaxReturn($return,'JSON');
			exit;
		}
		$idlist	=	I('post.ids');
		if(count($idlist)<1)
		{
			$return['msg']	=	'请选择记录';
			$this->ajaxReturn($return,'JSON');
			exit;
		}
		$now_id_list=array();
		$list=M('HomeClass')->field('class_id')->where(array('city_id'=>$city_id))->select();
		foreach($list as $item)
		{
			$now_id_list[]=$item['class_id'];
		}
		foreach($now_id_list as $id)
		{
			if(in_array($id,$idlist))continue;
			M('HomeClass')->where(array('city_id'=>$city_id,'class_id'=>$id))->delete();
			//echo M('HomeClass')->getlastsql().'<br>';
		}
		$data['city_id']	=	$city_id;
		foreach($idlist as $id)
		{
			if(in_array($id,$now_id_list))continue;
			$row=M('Classify')->where(array('id'=>$id))->find();
			if(!$row)continue;
			$data['class_id']	=	$id;
			$data['title']		=	$row['title'];
			$data['show_title']	=	$row['title'];
			$data['is_hidden']	=	$row['is_hidden'];
			$data['order_num']	=	$row['order_num'];
			M('HomeClass')->add($data);
		}
		//M('IndustryClass')->where(array('city_id'=>$city_id))->delete();
		/*$data['city_id']	=	$city_id;
		foreach($idlist as $id)
		{
			$row=M('Classify')->where(array('id'=>$id))->find();
			//echo M('Classify')->getlastsql().'<br>';
			if(!$row)continue;
			$data['class_id']=	$id;
			$data['title']	=	$row['title'];
			M('IndustryClass')->add($data);
			
		}*/
		$return['msg']	=	'保存成功';
		$this->ajaxReturn($return,'JSON');
		//var_dump($_POST);
	}
}
?>