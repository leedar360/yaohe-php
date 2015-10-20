<?php
/*
* author	袁绍月
* date		2015/6/3
* 商家用户类
*/
namespace Home\Controller;
use Think\Controller;
class ShopUserController extends CommonController {
	function index()
	{
		$list	=	array();
		$page	=	'';
		$map['type']	=	1;
		$id	=	I('get.id');
		$login_user	=	I('get.login_user');
		$this->assign('id',$id);
		$this->assign('login_user',$login_user);
		if($id!='')
		{
			$map['id']	=	$id;
		}
		if(!empty($login_user))
		{
			$map['_string']	=	'login_user like "%'.$login_user.'%"';
		}
		$map['type']=1;
		$count	=	M('Member')->where($map)->count();
		//echo M('Member')->getlastsql();
		if($count>0)
		{
			import("Think/Page");//导入分页类
			$p = new \Think\Page($count, C('PAGE_NUM'));
			$sql	=	"select m.*,s.title,s.industry_class_id,s.city_id from ht_member as m left join ht_shop as s on m.id=s.member_id where m.type=1 ";
			if($id!='')
			{
				$sql.=	" and m.id='".$id."'";
			}
			if(!empty($login_user))
			{
				$sql.=	' and m.login_user like "%'.$login_user.'%"';
			}
			$sql	.=	" order by m.id desc limit ".$p->firstRow.','.$p->listRows;
			//echo $sql;
			$list	=	M()->query($sql);
			$page = $p->show ();
		}
		$this->assign('list',$list);
		$this->assign('page',$page);
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