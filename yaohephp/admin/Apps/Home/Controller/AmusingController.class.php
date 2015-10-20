<?php
/*
* author	袁绍月
* date		2015/5/27
* 好玩类
*/
namespace Home\Controller;
use Think\Controller;
class AmusingController extends CommonController {
	public function _filter ( &$map )
	{
		$start_date=I('get.start_date');
		$end_date=I('get.end_date');
		$status=I('get.status');
		$keywords=I('get.keywords');
		$fjtj=array();
		if($status!='')
		{
			$fjtj[]=" status='".$status."'";
		}
		if(!empty($start_date))
		{
			$fjtj[]=" start_date>='".$start_date."'";
		}
		if(!empty($end_date))
		{
			$fjtj[]=" end_date<='".$end_date."'";
		}
		if(!empty($keywords))
		{
			$fjtj[]=" (id='".$keywords."' or title like '%".$keywords."%')";
		}
		if(count($fjtj)>0)
		{
			$map['_string']	=	implode(' and ',$fjtj);
		}
		$this->assign('start_date',$start_date);
		$this->assign('end_date',$end_date);
		$this->assign('keywords',$keywords);
		$this->assign('status',$status);
	}
	public function insert()
	{
		$prize_title_list=	$_POST['prize_title'];
		$prize_num_list	=	$_POST['prize_num'];
		$prize_rate_list=	$_POST['prize_rate'];
		$prize_num		=	0;
		foreach($prize_num_list as $key=>$item)
		{
			$prize_num+=$item;
		}

		$data['title']		=	I('post.title');//活动名称
		$data['type']		=	I('post.type');//活动类型
		$data['describe']	=	I('post.describe');//活动描述
		$data['rule']		=	I('post.rule');//活动规则
		$data['start_date']	=	I('post.start_date');//活动开始时间
		$data['end_date']	=	I('post.end_date');//活动结束时间
		$data['addtime']	=	time();
		$data['prize_num']	=	$prize_num;
		$insert_id	=	M('Amusing')->add($data);
		if($insert_id<1)
		{
			$this->error('添加失败');
		}
		$data		=	array();
		$data['aid']=	$insert_id;
		foreach($prize_title_list as $key=>$item)
		{
			$data['title']	=	$item;
			$data['num']	=	$prize_num_list[$key];
			$data['rate']	=	$prize_rate_list[$key];
			M('Prize')->add($data);
		}
		$this->success('添加成功');
	}
	public function edit()
	{
		$id	=	intval(I('get.id'));
		if($id<1)
		{
			$this->error('参数错误');
		}
		$row=	M('Amusing')->where(array('id'=>$id))->find();
		$this->assign('row',$row);
		$list=	M('Prize')->where(array('aid'=>$id))->select();
		$this->assign('list',$list);
		$this->display();
	}
	public function update()
	{
		$where['id']	=	intval(I('post.id'));
		$prize_title_list=	$_POST['prize_title'];
		$prize_num_list	=	$_POST['prize_num'];
		$prize_rate_list=	$_POST['prize_rate'];
		$prize_num		=	0;
		foreach($prize_num_list as $key=>$item)
		{
			$prize_num+=$item;
		}
		//echo $prize_num;exit;
		$data['title']		=	I('post.title');//活动名称
		$data['type']		=	I('post.type');//活动类型
		$data['describe']	=	I('post.describe');//活动描述
		$data['rule']		=	I('post.rule');//活动规则
		$data['start_date']	=	I('post.start_date');//活动开始时间
		$data['end_date']	=	I('post.end_date');//活动结束时间
		$data['addtime']	=	time();
		$data['prize_num']	=	$prize_num;
		$flag	=	M('Amusing')->where($where)->save($data);
		//echo M('Amusing')->getlastsql();exit;
		if($flag===false)
		{
			$this->error('编辑失败');
		}
		$data		=	array();
		$prize_id_list=	$_POST['prize_id'];
		foreach($prize_title_list as $key=>$item)
		{
			$data['title']	=	$item;
			$data['num']	=	$prize_num_list[$key];
			$data['rate']	=	$prize_rate_list[$key];
			M('Prize')->where(array('id'=>$prize_id_list[$key]))->save($data);
			//echo M('Prize')->getlastsql().'<br>';
		}
		//exit;
		$this->success('编辑成功');
	}
	// 删除数据
    public function delete() {
		/*if(!in_array(CONTROLLER_NAME,$this->module_arr) || !in_array('delete',$this->method_arr[CONTROLLER_NAME]))
		{
			$this->error('您没有权限');
		}*/
		$id	=	$_REQUEST['id'];//I('post.id');
        if (!empty($id)) {
			//var_dump($id);exit;
			$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
			$model = D($model_name);
			$where['id']=	is_array($id)?array('in',$id):$id;
			$result = $model->where($where)->delete($id);
			//echo $model->getlastsql();exit;
            if (false !== $result) {
				$where	=	array();
				$where['aid']	=	is_array($id)?array('in',$id):$id;
				M('Prize')->where($where)->delete();
				$return['msg']='删除成功！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->success($return['msg']);
                //$this->ajaxsuccess('删除成功！',__CONTROLLER__);
            } else {
				$return['msg']='删除出错！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
                //$this->ajaxerror('删除出错！',__CONTROLLER__);
            }
        } else {
			$return['msg']='ID错误！';
			IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
            //$this->ajaxerror('ID错误！',__CONTROLLER__);
        }
    }
	public function view()
	{
		$id	=	intval(I('get.id'));
		if($id<1)
		{
			$this->error('参数错误');
		}
		$row=	M('Amusing')->where(array('id'=>$id))->find();
		$this->assign('row',$row);
		$map['aid']	=	$id;
		$model	=	M('PrizeList');
		$count = $model->where ( $map )->count ( '*' );
		if ($count > 0) {
			//import ( "ORG.Util.Page" );
			import("Think/Page");//导入分页类
			$p = new \Think\Page($count, C('PAGE_NUM'));
			//分页查询数据，增加关联模型，Add By 4wei.cn
			$voList = $model->where($map)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
			//echo $model->getlastsql();
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if (! is_array ( $val )) {
					$p->parameter .= "$key=" . urlencode ( $val ) . "&";
				}
			}
			
			//分页显示
			$page = $p->show ();
			
			//模板赋值显示
			$this->assign ( 'list', $voList );
			$this->assign ( "page", $page );
		}
		$this->display();
	}
}
?>