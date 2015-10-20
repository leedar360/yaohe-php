<?php 
namespace Home\Model;
use Think\Model;
class ShopModel extends Model {
	//自动完成
	protected $_auto=array(array('geohash_str','gethash',3,'callback'),array('register_time','time',1,'function'),array('member_id','getmemberid',1,'callback'),array('last_update_time','time',3,'function'));
	//自动验证
	protected $_validate =array(
		array('title', 'require', '用户名不能为空', 1),
		array('mobile','validateuser','存在重复登陆用户',Model::MUST_VALIDATE,'callback'),
		//array('geohash_str','gethash','密码长度小于6位',0,'callback'),
		//array('login_pass_1','validatepass','密码和确认密码不相同',0,'callback')
	);
	//验证用户是否重复
	public function validateuser()
	{
		$email_mobile	=	I('post.email_mobile');
		$row=$this->where("email_mobile='".$email_mobile."'")->field('id')->find();
		if($row)return false;
	}
	//验证密码长度
	function gethash()
	{
		import("Think/Geohash");//导入分页类
		$geohash = new \Think\Geohash();
		return $geohash->encode(I('post.long'), I('post.lat'));
	}
	function getmemberid()
	{
		$map['login_user']=I('post.mobile');
		$row	=	M('Member')->where($map)->find();
		return $row['id'];
	}
}