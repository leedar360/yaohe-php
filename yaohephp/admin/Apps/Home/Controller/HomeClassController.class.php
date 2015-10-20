<?php
/*
* author	袁绍月
* date		2015/5/28
* 绑定分类
*/
namespace Home\Controller;
use Think\Controller;
class HomeClassController extends CommonController {
	public function _filter(&$map)
	{
		$city_id	=	intval(I('get.city_id'));
		$keywords	=	I('get.keywords');
		if($city_id>0)$map['city_id']=$city_id;
		if(!empty($keywords))
		{
			$map['show_title']=array('like','%'.$keywords.'%');
		}
	}
	public function _before_index()
	{
		$province_list	=	M('Province')->select();
		$this->assign('province_list',$province_list);
		$province_id=	intval(I('get.province_id'));
		$city_id	=	intval(I('get.city_id'));
		$keywords	=	I('get.keywords');
		$this->assign('province_id',$province_id);
		$this->assign('city_id',$city_id);
		$this->assign('keywords',$keywords);
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
		if(in_array(CONTROLLER_NAME,$this->module_arr))
		{
			//$this->error('您没有权限');
		}
		$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
        $model = D($model_name);
		$map	=	array();
		$city_id	=	intval(I('get.city_id'));
		//echo $city_id;exit;
		if($city_id>0)$map['city_id']=$city_id;
		$keywords	=	I('get.keywords');
		if(!empty($keywords))
		{
			$map['show_title']=array('like','%'.$keywords.'%');
		}
		$count = $model->where ( $map )->count ( '*' );
		if ($count > 0) {
			//import ( "ORG.Util.Page" );
			import("Think/Page");//导入分页类
			$p = new \Think\Page($count, C('PAGE_NUM'));
			//分页查询数据，增加关联模型，Add By 4wei.cn
			$voList = $model->where($map)->order('order_num asc,id desc')->limit($p->firstRow.','.$p->listRows)->select();
			//echo $model->getlastsql();
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if (! is_array ( $val )) {
					$p->parameter .= "$key=" . urlencode ( $val ) . "&";
				}
			}
			
			//分页显示
			$page = $p->show ();
			
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			
			
			//模板赋值显示
			$this->assign ( 'list', $voList );
			$this->assign ( "page", $page );
			//echo $p->firstRow;
		}
		$this->display();
	}
	public function _before_ajax_list()
	{
		$list	=	M('City')->select();
		$cityarr=	array();
		foreach($list as $item)
		{
			$cityarr[$item['id']]=$item['title'];
		}
		$this->assign('cityarr',$cityarr);
	}
}
?>