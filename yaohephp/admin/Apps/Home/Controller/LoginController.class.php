<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
	/*$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎登陆<b>ThinkPHP</b>！</p></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');*/
		$this->display();
    }
	public function captcha()
	{
		$Verify =     new \Think\Verify();
		$Verify->length   = 4;
		$Verify->useNoise = false;
		$Verify->entry();
	}
	function auth()
	{

		$login_user	=	I('post.login_user');
		$login_pass	=	I('post.login_pass');
		$vcode		=	I('post.vcode');
		$verify = new \Think\Verify();
		//$flag	=	$verify->check($vcode);
		$flag = true;
		if($flag==false)
		{
			$this->error('验证码错误','/?c=Login');
		}
		$row	=	M('AdminUser')->where(array('login_user'=>$login_user))->find();
		if(!$row)
		{
			$this->error('用户不存在','/?c=Login');
		}
		if($row['status']==1)
		{
			$this->error('用户被禁用','/?c=Login');
		}
		if(md5($login_pass)!=$row['login_pass'])
		{
			$this->error('密码错误','/?c=Login');
		}
		$data['last_login_time']	=	time();
		$data['login_num']	=	$row['login_num']+1;
		M('AdminUser')->where(array('id'=>$row['id']))->save($data);
		$_SESSION['ht_admin']['id']	=	$row['id'];
		$_SESSION['ht_admin']['username']=$row['login_user'];
		$_SESSION['ht_admin']['role_id']=$row['role_id'];
		$this->success('恭喜进入系统后台','/');
		//import("Think/Cache");
		$options = array('host' => C('MEMCACHE_HOST'), 'port' => C('MEMCACHE_PORT'), 'timeout' => 14400,
		'persistent' => false);
		$Cache = \Think\Cache::getInstance('memcache', $options);
		$Cache->set(session_id(),time());
		$Cache->set('id_'.session_id(),$row['id'],14400);
		$Cache->set('name_'.session_id(),$row['login_user'],14400);
		$Cache->set('role_id_'.session_id(),$row['role_id'],14400);
		//echo $Cache->get('id_'.session_id());exit;
		//$Cache->set('name_''.$row['id'],$row['login_user']);
		//$Cache->set('role_id_'.$row['id'],$row['role_id']);
		//var_dump($flag);exit;
		/*if($login_user!='yeeuo' || $login_pass!='yeeuo!@#$%^')
		{
			$this->error('用户名或密码错误');
		}
		$_SESSION['yeeuo_admin']['username']='admin';
		$this->success('恭喜您进入系统后台','/');*/
	}
	public function logout()
	{
		session_destroy();
		$this->success('退出成功','/?c=Login');
	}
}