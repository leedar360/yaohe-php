<?php 
namespace Home\Model;
use Think\Model;
class AdminUserModel extends Model {
	//自动完成
	protected $_auto=array(array('login_pass','md5',1,'function'),array('addtime','time',1,'function'));
	//自动验证
	protected $_validate =array(
		array('login_user', 'require', '用户名不能为空', 1),
		array('login_user','validateuser','存在重复用户',Model::MUST_VALIDATE,'callback')
	);
	//验证用户是否重复
	public function validateuser()
	{
		$login_user	=	I('post.login_user');
		$id	=	I('post.id');
		$row=$this->where("login_user='".$login_user."'")->field('id')->find();
		if($row && $row['id']!=$id)return false;
	}
}