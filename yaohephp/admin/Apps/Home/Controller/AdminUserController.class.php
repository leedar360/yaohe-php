<?php
/*
* author	袁绍月
* date		2015/6/3
* 管理员类
*/
namespace Home\Controller;
use Think\Controller;
class AdminUserController extends CommonController {
	public function index()
	{
		$role_list	=	M('Role')->select();
		$this->assign('role_list',$role_list);
		$rolearr	=	array();
		foreach($role_list as $item)
		{
			$rolearr[$item['id']]=$item['title'];
		}
		$this->assign('rolearr',$rolearr);
		//获取省份列表
		$province_list	=	M('Province')->select();
		$this->assign('province_list',$province_list);

		$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
        $model		=	D($model_name);
		$map		=	array();
		$province_id=	intval(I('get.province_id'));//省份
		$city_id	=	intval(I('get.city_id'));//城市
		$role_id	=	intval(I('get.role_id'));//角色ID
		$login_user	=	I('get.login_user');//账号
		$name		=	I('get.name');//姓名
		if($province_id>0)$map['province_id']	=	$province_id;
		if($city_id>0)$map['city_id']	=	$city_id;
		if($role_id>0)$map['role_id']	=	$role_id;
		if(!empty($login_user))
		{
			$map['login_user']	=	array('like','%'.$login_user.'%');
		}
		if(!empty($name))
		{
			$map['name']	=	array('like','%'.$name.'%');
		}
		$this->_list($model,$map);
		$this->assign('province_id',$province_id);
		$this->assign('city_id',$city_id);
		$this->assign('role_id',$role_id);
		$this->assign('login_user',$login_user);
		$this->assign('name',$name);

		if($province_id>0)
		{
			$city_list	=	M('City')->where(array('p_id'=>$province_id))->select();
			$this->assign('city_list',$city_list);
		}
		$this->display();
	}
	public function _before_add()
	{
		$role_list	=	M('Role')->select();
		$this->assign('role_list',$role_list);
	}
	public function _before_edit()
	{
		$role_list	=	M('Role')->select();
		$this->assign('role_list',$role_list);
	}
	public function update()
	{
		$where['id']		=	intval(I('post.id'));
		$data['login_user']	=	I('post.login_user');
		$data['role_id']	=	intval(I('post.role_id'));
		$data['name']		=	I('post.name');
		$data['tel']		=	I('post.tel');
		$data['remark']		=	I('post.remark');
		$login_pass	=	I('post.login_pass');
		if(!empty($login_pass))
		{
			$data['login_pass']=md5($login_pass);
		}
		$row	=	M('AdminUser')->where(array('login_user'=>$data['login_user']))->find();
		if($row && $row['id']!=$where['id'])
		{
			$this->error('用户名重复');
		}
		$flag	=	M('AdminUser')->where($where)->save($data);
		if($flag===false)
		{
			$this->error('出现异常');
		}
		$this->success('保存成功');
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