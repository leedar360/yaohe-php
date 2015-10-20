<?php
/*
* author	袁绍月
* date		2015/5/25
* 城市类
*/
namespace Home\Controller;
use Think\Controller;
class CityController extends CommonController {
	public function _filter(&$map)
	{
		$province_id	=	intval(I('get.province_id'));
		if($province_id>0)
		{
			$map['p_id']=$province_id;
		}
	}
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
		$province_id	=	intval(I('get.province_id'));
		$this->assign('province_id',$province_id);
	}
	public function _ajax_filter(&$map)
	{
		$province_id	=	intval(I('get.province_id'));
		if($province_id>0)
		{
			$map['p_id']=$province_id;
		}
	}
	public function ajax_list()
	{
		/*if(!in_array(CONTROLLER_NAME,$this->module_arr))
		{
			$this->error('您没有权限');
		}*/
		$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
        $model = D($model_name);
		$map	=	array();
		if (method_exists ( $this, '_ajax_filter' )) {
			$this->_ajax_filter ( $map );
		}
		$count = $model->where ( $map )->count ( '*' );
		//echo $model->getlastsql();
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
		$province_list	=	M('Province')->select();
		$provincearr	=	array();
		foreach($province_list as $item)
		{
			$provincearr[$item['id']]=$item['title'];
		}
		$this->assign('provincearr',$provincearr);
	}
	public function status()
	{
		$id	=	intval(I('get.id'));
		$row=	M('City')->where(array('id'=>$id))->find();
		if(!$row)
		{
			$this->error('城市不存在');
		}
		if($row['status']==0)$data['status']=1;
		else $data['status']=0;
		$flag	=	M('City')->where(array('id'=>$id))->save($data);
		if($flag===false)
		{
			$this->error('出现异常');
		}
		$this->success('操作成功');
	}
}
?>