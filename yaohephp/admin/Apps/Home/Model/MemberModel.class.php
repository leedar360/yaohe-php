<?php 
namespace Home\Model;
use Think\Model;
class MemberModel extends Model {
	//自动完成
	protected $_auto=array(array('login_pass','md5',3,'function'));
	//自动验证
	protected $_validate =array(
		array('login_user', 'require', '用户名不能为空', 1),
		array('login_user','validateuser','存在重复用户',Model::MUST_VALIDATE,'callback'),
		array('login_pass','checkPwd','密码长度小于6位',0,'callback'),
		array('login_pass_1','validatepass','密码和确认密码不相同',0,'callback')
	);
	//验证用户是否重复
	public function validateuser()
	{
		$login_user	=	I('post.login_user');
		$id	=	I('post.id');
		$row=$this->where("login_user='".$login_user."'")->field('id')->find();
		if($row && $row['id']!=$id)return false;
	}
	//验证密码长度
	function checkPwd()
	{
		$login_pass	=	I('post.login_pass');
		if(strlen($login_pass)<6)return false;
	}
	public function validatepass()
	{
		$login_pass	=	I('post.login_pass');
		$login_login_pass_1	=	I('post.login_pass_1');
		if($login_pass!=$login_login_pass_1)return false;
	}
}