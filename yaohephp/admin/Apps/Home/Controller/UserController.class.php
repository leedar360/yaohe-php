<?php
/*
* author	袁绍月
* date		2015/6/3
* 用户类
*/
namespace Home\Controller;
use Think\Controller;
class UserController extends CommonController {
	function index()
	{
		$list	=	array();
		$page	=	'';
		$map['type']	=	0;
		$id	=	intval(I('get.id'));
		$login_user	=	I('get.login_user');
		if($id>0)
		{
			$map['id']	=	$id;
		}
		if(!empty($login_user))
		{
			$map['_string']	=	'login_user like "%'.$login_user.'%"';
		}
		$count	=	M('Member')->where($map)->count();
		//echo $count;
		//echo M('Member')->getlastsql();
		if($count>0)
		{
			//FROM table1 LEFT JOIN table2 ON table1.field1 compopr table2.field2
			import("Think/Page");//导入分页类
			$p = new \Think\Page($count, C('PAGE_NUM'));
			$sql	=	"select ht_member.*,ht_personal.nickname from ht_member left join ht_personal on ht_member.id=ht_personal.member_id where ht_member.type=0 ";
			if($id>0)$sql.=" and ht_member.id='".$id."'";
			if(!empty($login_user))$sql.=" and login_user like '%".$login_user."%'";
			$sql	.=	"order by ht_member.id desc limit ".$p->firstRow.','.$p->listRows;
			//echo $sql;
			$list	=	M()->query($sql);
			/*foreach ( $map as $key => $val ) {
				if($key=='_string')continue;
				if (! is_array ( $val )) {
					$p->parameter .= "$key=" . urlencode ( $val ) . "&";
				}
			}*/
			$page = $p->show ();
			//echo $page;exit;
		}
		$this->assign('list',$list);
		$this->assign('page',$page);
		if($id > 0){
			$this->assign('id', $id) ;
		}
		$this->assign('login_user',$login_user) ;
		$this->display();
	}
	function disabled()
	{
		$id	=	$_REQUEST['id'];
        if (!empty($id)) {
			$model = M('Member');
			$where['id']=	is_array($id)?array('in',$id):$id;
			$data['status']=1;
			$result = $model->where($where)->save($data);
			if (false !== $result) {
				$return['msg']='禁用成功！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->success($return['msg']);
            } else {
				$return['msg']='禁用出错！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
            }
		}else {
			$return['msg']='叁数错误！';
			IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
        }
	}
	function enable()
	{
		$id	=	$_REQUEST['id'];
        if (!empty($id)) {
			$model = M('Member');
			$where['id']=	is_array($id)?array('in',$id):$id;
			$data['status']=0;
			$result = $model->where($where)->save($data);
			if (false !== $result) {
				$return['msg']='启用成功！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->success($return['msg']);
            } else {
				$return['msg']='启用出错！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
            }
		}else {
			$return['msg']='叁数错误！';
			IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
        }
	}
}
?>