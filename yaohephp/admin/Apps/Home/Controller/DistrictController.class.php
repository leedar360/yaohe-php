<?php
/*
* author	袁绍月
* date		2015/5/28
* 商圈类
*/
namespace Home\Controller;
use Think\Controller;
class DistrictController extends CommonController {
	//var $model_name='Area';
	public function ajax_list()
	{
		$model		=	M('Area');
		$map['c_id']=	intval(I('get.city_id'));
		$count = $model->where ( $map )->count ( '*' );
		//echo $model->getlastsql();
		if ($count > 0) {
			import("Think/Page");//导入分页类
			$p = new \Think\Page($count, C('PAGE_NUM'));
			$voList = $model->where($map)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
			foreach($voList as $key=>$item)
			{
				$arr	=	array();
				$list	=	M('District')->where(array('area_id'=>$item['id']))->select();
				if(!$list)$list=array();
				foreach($list as $val)
				{
					$arr[]="<a href='javascript:void(0)' onclick='open_edit_modal(".$val['id'].",\"".$val['title']."\")'>".$val['title']."</a>";
				}
				$voList[$key]['district']=implode(' | ',$arr);
			}
			//echo $model->getlastsql();
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if (! is_array ( $val )) {
					//$p->parameter .= "$key=" . urlencode ( $val ) . "&";
				}
			}
			
			//分页显示
			$page = $p->show ();
			$this->assign ( 'list', $voList );
			$this->assign ( "page", $page );
		}
		/*$voList = $model->where($map)->order('id desc')->select();
		foreach($voList as $key=>$item)
		{
			$arr	=	array();
			$list	=	M('District')->where(array('area_id'=>$item['id']))->select();
			if(!$list)$list=array();
			foreach($list as $val)
			{
				$arr[]="<a href='javascript:void(0)' onclick='open_edit_modal(".$val['id'].",\"".$val['title']."\")'>".$val['title']."</a>";
			}
			$voList[$key]['district']=implode(' | ',$arr);
		}
		//echo $model->getlastsql();
		$this->assign ( 'list', $voList );*/
		$this->display();
	}
	public function index()
	{
		$currentPage	=	intval(I('get.pageNum'));
		$this->assign('currentPage',$currentPage);
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
		$model	=	M('Area');
		$map['p_id']	=	$province_id;
		$map['c_id']	=	$city_id;
		$count = $model->where ( $map )->count ( '*' );
		//echo $model->getlastsql();
		if ($count > 0) {
			import("Think/Page");//导入分页类
			$p = new \Think\Page($count, C('PAGE_NUM'));
			$voList = $model->where($map)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
			//echo $model->getlastsql();
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if (! is_array ( $val )) {
					//$p->parameter .= "$key=" . urlencode ( $val ) . "&";
				}
			}
			
			//分页显示
			$page = $p->show ();
			$this->assign ( 'list', $voList );
			$this->assign ( "page", $page );
		}
		$this->display();
	}
	public function getcitylist()
	{
		$province_id	=	I('post.province_id');
		$list	=	M('City')->where(array('p_id'=>$province_id))->select();
		if(!$list)$list=array();
		echo json_encode($list);
	}
	public function insert() {
        $model	=	D('District');
		$data['area_id']=intval(I('post.area_id'));
		if($data['area_id']<1)
		{
			$return['msg']='请选择行政区域';
			$this->ajaxReturn($return,'JSON');
			exit;
		}
		$content=	trim(I('post.content'));//内容
		if(empty($content))
		{
			$return['msg']='内容不能为空';
			$this->ajaxReturn($return,'JSON');
			exit;
		}
		$arrlist=	explode(',',$content);
		foreach($arrlist as $title)
		{
			$data['title']=	$title;
			$model->add($data);
		}
		$return['msg']='数据保存成功！';
		$this->ajaxReturn($return,'JSON');
		exit;
    }
}
?>