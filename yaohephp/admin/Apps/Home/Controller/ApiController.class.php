<?php
/*
* author	袁绍月
* date		2015/6/1
* Api
*/
namespace Home\Controller;
use Think\Controller;
header("Content-type: text/html; charset=utf-8");
class ApiController extends Controller {
	public function setshopservice()
	{
		$list	=	M('ShopService')->select();
		foreach($list as $item)
		{
			$shop=	$this->getShop($item['member_id']);
			$data['province_id']	=	$shop['province_id'];
			$data['area_id']	=	$shop['area_id'];
			$data['district_id']	=	$shop['district_id'];
			$data['one_id']	=	$shop['one_id'];
			$data['industry_class_id']	=	$shop['industry_class_id'];
			M('ShopService')->where(array('id'=>$item['id']))->save($data);
		}
	}
	/**
	* 功能：获取城市列表
	*/
	public function getallcity()
	{
		$list	=	M('City')->field('id,title')->where(array('status'=>0))->select();
		if(!$list)$list=array();
		$this->json_ok($list);
	}
	/**
	* 功能：获取开通的城市及热门城市
	*/
	public function getcitylist()
	{
		$hotcity=	array();//热门城市
		$citylist=	array();//排列城市
		$list	=	M('City')->field('id,title,letter,is_hot')->where(array('status'=>0))->select();
		foreach($list as $item)
		{
			if($item['is_hot']>0)
			{
				array_push($hotcity,$item);
			}
			$citylist[strtolower($item['letter'])][]=$item;
		}
		$data['hotcity']	=	$hotcity;
		$data['citylist']	=	$citylist;
		$this->json_ok($data);
	}
	/**
	* 功能：注册发送短消息
	*/
	public function register_sms()
	{
		$login_user	=	I('post.login_user');
		if(empty($login_user))
		{
			$this->json_error('手机号不能为空');
		}
		$row	=	M('Member')->where(array('login_user'=>$login_user))->find();
		if($row && empty($row['code']))
		{
			$this->json_error('该手机号已经被使用');
		}
		$code	=	getRandomNum(6);
		if($row)
		{
			$map['id']	=	$row['id'];
			$data['code']=	$code;
			M('Member')->where($map)->save($data);
		}
		else
		{
			$data['login_user']	=	$login_user;
			$data['code']		=	$code;
			M('Member')->add($data);
		}
		//$mobile	=	I('post.mobile');
		$_account = 'cf_zcsd';
		$_password = '69wZ74';
		$_url = 'http://106.ihuyi.cn/webservice/sms.php?method=Submit&format=json';
		$data = array(
			'account' => $_account,
			'password' => $_password,
			'mobile' => $login_user,
			'content' => '亲爱的客户大大，您正在注册成为吆喝会员，动态验证码是【'.$code.'】请笑纳！',
		);
		$res_json = curlPost($_url, $data, 5);
		$res = json_decode($res_json,1);
		if($res['code']==2)
		{
			$this->json_ok(true);
		}
		$this->json_error($res['msg']);
	}
	/**
	* 功能：注册
	*/
	public function register()
	{
		$data['login_user']	=	I('post.login_user');
		$code	=	I('post.code');
		$login_pass	=	I('post.login_pass');
		if(empty($data['login_user']))
		{
			$this->json_error('请填写手机号');
		}
		if(empty($login_pass))
		{
			$this->json_error('请填写密码');
		}
		$data['login_pass']	=	md5($login_pass);
		$data['type']		=	0;
		/*$row	=	M('Member')->where(array('login_user'=>$data['login_user']))->find();
		if(!$row)
		{
			$this->json_error('验证码错误');
		}*/
		$row	=	M('Member')->where(array('login_user'=>$data['login_user']))->find();
		if($row)
		{
			$this->json_error('用户已经注册过了');
		}
		if(empty($row['code']))
		{
			//$this->json_error('用户已经注册过了');
		}
		if($code!=$row['code'])
		{
			//$this->json_error('验证码不正确');
		}

		$map['id']	=	$row['id'];
		$data['code']=	'';
		//var_dump($data);exit;
		//$insert_id	=	M('Member')->where($map)->save($data);
		$insert_id	=	M('Member')->where($map)->add($data);
		//echo M('Member')->getlastsql();exit;
		if($insert_id<1)
		{
			$t_data['member_id']=$insert_id;
			$t_data['nickname']='吆喝'.$insert_id;
			M('Personal')->add($t_data);
			$this->json_error('系统繁忙，请稍侯重试');
		}
		$data	=	array();
		$data['member_id']=$map['id'];
		$this->json_ok($data);
	}
	/**
	* 功能：商家注册
	*/
	public function shopreg()
	{
		$map['login_user']	=	I('post.login_user');
		$row	=	M('Member')->where($map)->find();
		if(!$row)
		{
			$this->json_error('用户不存在');
		}
		if($row['type']==1)
		{
			$this->json_error('用户已经成为商家');
		}
		$data['type']	=	1;
		$flag	=	M('Member')->where($map)->save($data);
		if($flag===false)
		{
			$this->json_error('服务器忙，请稍侯尝试');
		}
		$this->json_ok(true);
	}
	/**
	* 功能：登陆
	*/
	public function login()
	{
		$map['login_user']	=	I('post.login_user');
		$login_pass	=	I('post.login_pass');
		$row	=	M('Member')->where($map)->find();
		if(!$row)
		{
			$this->json_error('用户尚未注册');
		}
		if(!empty($row['code']))
		{
			$this->json_error('用户尚未注册成功');
		}
		if($row['login_pass']!=md5($login_pass))
		{
			$this->json_error('密码错误');
		}
		$map	=	array();
		$map['id']	=	$row['id'];
		$data['login_num']=$row['login_num']+1;
		$data['last_login_time']=time();
		$flag	=	M('Member')->where($map)->save($data);
		if($flag===false)
		{
			$this->json_error('服务器忙，请稍侯尝试');
		}
		$this->json_ok($row);
	}
	/**
	* 功能：获取区域
	*/
	public function getAreaList()
	{
		$city_id=	intval(I('post.city_id'));//城市ID
		$list	=	M('Area')->field('id,title')->where('c_id='.$city_id)->select();//获取区域列表
		$this->json_ok($list);
	}
	/**
	* 功能：获取商圈
	*/
	public function getDistrictList()
	{
		$area_id=	intval(I('post.area_id'));//区域ID
		$list	=	M('District')->field('id,title')->where('area_id='.$area_id)->select();//获取区域列表
		$this->json_ok($list);
	}
	/**
	* 功能：获取分类
	*/
	public function getClassifyList()
	{
		$parentid=	intval(I('post.parent_id'));
		if($parentid>0)
		{
			$list	=	M('Classify')->field('id,title')->where('parentid='.$parentid.' and is_hidden=0')->order('order_num asc,id asc')->select();
		}
		else
		{
			$list	=	M('Classify')->field('id,title')->where('parentid=0 and is_hidden=0')->order('order_num asc,id asc')->select();
		}
		$this->json_ok($list);
	}
	/**
	* 功能：商家资料填写
	*/
	public function shopbase()
	{
		$filelist	=	$this->uploads();

		$member_id					=	intval(I('post.member_id'));//商家ID
		$data['member_id']			=	$member_id;
		//$data['province_id']		=	intval(I('post.province_id'));//省份ID
		$data['city_id']			=	intval(I('post.city_id'));//城市ID
		$data['title']				=	I('post.title');//店铺名称
		$data['one_id']				=	I('post.one_id');//一级分类ID
		$data['industry_class_id']	=	I('post.industry_class_id');//二级分类ID
		$data['area_id']			=	intval(I('post.area_id'));//区域ID
		$data['district_id']		=	I('post.district_id');//商圈ID
		$data['address']			=	I('post.address');//详细地址
		$data['lng']				=	I('post.long');//经度
		$data['lat']				=	I('post.lat');//纬度
		$data['business_time']		=	I('post.business_time');//营业时间
		$data['subscribe_tel']		=	I('post.subscribe_tel');//预约电话
		$data['content']			=	I('post.content');//店铺介绍
		$data['shopkeeper_name']	=	I('post.shopkeeper_name');//店主姓名
		$city	=	M('city')->where(array('id'=>$data['city_id']))->find();
		$data['province_id']		=	$city['p_id'];

		M('Member')->where(array('id'=>$member_id))->save(array('type'=>1));
		//$data['type']				=	1;
		foreach($filelist as $item)
		{
			$data[$item['key']]		=	$item['savepath'].$item['savename'];
		}
		$row	=	M('Member')->where(array('id'=>$member_id))->find();
		if(!$row)
		{
			$this->json_error('商家不存在');
		}
		if(!empty($row['code']))
		{
			$this->json_error('用户尚未注册成功');
		}
		if($row['type']!=1)
		{
			//$this->json_error('用户不是店铺');
		}
		$row	=	M('Shop')->where(array('member_id'=>$member_id))->find();
		//var_dump($row);exit;
		if(!$row)
		{
			$flag=	M('Shop')->add($data);
			if($flag<1)
			{
				$this->json_error('保存资料失败');
			}
		}
		else
		{
			$flag=	M('Shop')->where(array('member_id'=>$member_id))->save($data);
			if($flag===false)
			{
				$this->json_error('保存资料失败');
			}
		}
		//echo M('Shop')->getlastsql();//exit;
		$this->json_ok(true);
	}
	/**
	* 功能：写入吆喝
	*/
	public function addcall()
	{
		/*$member_id			=	intval(I('post.member_id'));//商家ID
		$data['member_id']	=	$member_id;
		$data['content']	=	I('post.content');//吆喝内容
		$data['type']		=	I('post.type');//引用内容
		$data['c_id']		=	I('post.c_id');//引用内容ID
		$data['city_id']	=	intval(I('post.city_id'));
		$data['addtime']	=	time();*/
		$province	=	M('Province')->where(array('id'=>intval(I('post.city_id'))))->find();
		$member_id	=	intval(I('post.member_id'));//商家ID
		$type		=	I('post.type');//0优惠券 1会员卡 2活动 3新品 4吆喝
		$content	=	I('post.content');
		$city_id	=	intval(I('post.city_id'));
		$c_id		=	I('post.c_id');//引用内容ID
		$filelist	=	$this->uploads();
		$new_data	=	array();
		foreach($filelist as $item)
		{
			$data[$item['key']]		=	$item['savepath'].$item['savename'];
			$new_data[$item['key']]	=	$item['savepath'].$item['savename'];
		}
		switch($type)
		{
			case 2://活动
				$activity	=	M('Activity')->where(array('id'=>$c_id))->find();
				if(!$activity)
				{
					$this->json_error('活动不存在');
				}
				$new_data['title']	=	$activity['title'];
				//$data['one_id']				=	$activity['one_id'];
				//$data['industry_class_id']	=	//$activity['industry_class_id'];
				//$type	=	2;
			break;
			case 3://新品
				$product	=	M('NewProduct')->where(array('id'=>$c_id))->find();
				if(!$product)
				{
					$this->json_error('新品不存在');
				}
				$new_data['title']	=	'';//$activity['title'];
				//$data['one_id']				=	$product['one_id'];
				//$data['industry_class_id']	=	//$product['industry_class_id'];
				//$type	=	3;
			break;
			case 1://卡
				$card	=	M('Card')->where(array('id'=>$c_id))->find();
				if(!$card)
				{
					$this->json_error('卡不存在');
				}
				$new_data['title']	=	$card['title'];
				//$data['one_id']				=	$card['one_id'];
				//$data['industry_class_id']	=	$card['industry_class_id'];
				//$type	=	1;
			break;
			case 0://券
				$coupon	=	M('Coupon')->where(array('id'=>$c_id))->find();
				if(!$coupon)
				{
					$this->json_error('券不存在');
				}
				$new_data['title']	=	$coupon['title'];
				//$data['one_id']				=	$coupon['one_id'];
				//$data['industry_class_id']	=	$coupon['industry_class_id'];
				//$type	=	0;
			break;
			default:
				//$type	=	4;
		}
		$shop=	$this->getShop($member_id);
		//写入纯吆喝
		if($type==4)
		{		
			//$data['province_id']=	$province['id'];
			//$data['city_id']	=	$city_id;
			$data['member_id']	=	$member_id;
			$data['content']	=	$content;
			//纯吆喝时候是不加title的
			//$data['title']		=	$content;
			$data['c_id']		=	$c_id;
			$data['province_id']=	$shop['province_id'];//省份
			$data['city_id']	=	$shop['city_id'];//城市ID
			$data['area_id']	=	$shop['area_id'];//行政区域
			$data['district_id']=	$shop['district_id'];//商圈ID
			$data['one_id']		=	$shop['one_id'];//一级分类
			$data['addtime']	=	time();//添加时间
			$data['industry_class_id']=$shop['industry_class_id'];//二级分类
			$data['type']		=	$type;
			//$data['type']		=	$type;
			//$data['member_id']	=	$member_id;
			//$data['member_id']	=	$member_id;
			$insert_id	=	M('Call')->add($data);
			if($insert_id<1)
			{
				$this->json_error('吆喝发布失败');
			}
		}else{
			$insert_id	=	$c_id;
		}
		//echo $insert_id.'=='.$member_id;exit;
		//$data	=	array();
		$new_data['province_id']=	$province['id'];
		$new_data['service_id']	=	$insert_id;
		$new_data['member_id']	=	$member_id;
		$new_data['city_id']	=	intval(I('post.city_id'));
		$new_data['type']		=	$type;
		$new_data['member_id']	=	$member_id;
		$new_data['content']	=	$content;//I('post.title');
		//纯吆喝时候是不加title的
		//$data['title']			=	$content;
		$new_data['addtime']	=	time();
		$new_data['province_id']=	$shop['province_id'];//省份
		$new_data['city_id']	=	$shop['city_id'];//城市ID
		$new_data['area_id']	=	$shop['area_id'];//行政区域
		$new_data['district_id']=	$shop['district_id'];//商圈ID
		$new_data['one_id']		=	$shop['one_id'];//一级分类
		$new_data['addtime']	=	time();//添加时间
		$new_data['industry_class_id']=$shop['industry_class_id'];//二级分类
		M('ShopService')->add($new_data);
		$this->json_ok(true);
	}
	/**
	* 功能：发布优惠券
	*/
	public function addcoupon()
	{
		//$data['content']	=	serialize($_REQUEST);
		//M('Shuju')->add($data);
		$data	=	array();
		$member_id			=	intval(I('post.member_id'));//商家ID
		$shop				=	$this->getShop($member_id);//获取商家
		$data['member_id']	=	$member_id;
		$data['type']		=	intval(I('post.type'));//类别 0满减券 1满赠券 2代金券 3折扣券
		$data['title']		=	I('post.title');//标题
		$data['content']	=	I('post.content');//使用范围
		$data['term_start']	=	I('post.term_start');//0条件开始 1条件开始  2条件  3折扣
		$data['term_end']	=	I('post.term_end');//0条件结束 1条件结束
		$data['valid_start']=	I('post.valid_start');//有效开始时间
		$data['valid_end']	=	I('post.valid_end');//有效截止时间
		$data['num']		=	I('post.num');//发行量
		$data['use']		=	I('post.use');//0单次 1多次
		$data['province_id']=	$shop['province_id'];//省份
		$data['city_id']	=	$shop['city_id'];//城市ID
		$data['area_id']	=	$shop['area_id'];//行政区域
		$data['district_id']=	$shop['district_id'];//商圈ID
		$data['one_id']		=	$shop['one_id'];//一级分类
		$data['addtime']	=	time();//添加时间
		$data['industry_class_id']	=	$shop['industry_class_id'];//二级分类
		if($member_id<1)
		{
			$this->json_error('参数错误');
		}
		$filelist	=	$this->uploads();
		foreach($filelist as $item)
		{
			$data[$item['key']]		=	$item['savepath'].$item['savename'];
		}
		$flag	=	M('Coupon')->add($data);
		if($flag<1)
		{
			$this->json_error('优惠券发布失败');
		}
		$data	=	array();
		$data['service_id']	=	$flag;
		$data['city_id']	=	$shop['city_id'];
		$data['type']		=	0;
		$data['member_id']	=	$member_id;
		$data['title']		=	I('post.title');
		$data['content']	=	I('post.content');
		$data['addtime']	=	time();
		$data['province_id']=	$shop['province_id'];//省份
		$data['city_id']	=	$shop['city_id'];//城市ID
		$data['area_id']	=	$shop['area_id'];//行政区域
		$data['district_id']=	$shop['district_id'];//商圈ID
		$data['one_id']		=	$shop['one_id'];//一级分类
		$data['addtime']	=	time();//添加时间
		$data['industry_class_id']	=	$shop['industry_class_id'];//二级分类
		M('ShopService')->add($data);
		$this->json_ok(true);
	}
	/**
	* 功能：发布卡
	*/
	public function addcard()
	{
		$member_id			=	intval(I('post.member_id'));//商家ID
		if($member_id<1)
		{
			$this->json_error('参数错误');
		}
		$shop				=	$this->getShop($member_id);//获取商家
		$data['member_id']	=	$member_id;
		$data['title']		=	I('post.title');//标题
		$data['discount']	=	I('post.discount');//折扣
		$data['content']	=	I('post.content');//使用范围
		$data['num']		=	I('post.num');//发行量
		$data['addtime']	=	time();//添加时间
		$data['province_id']=	$shop['province_id'];//省份
		$data['city_id']	=	$shop['city_id'];//城市ID
		$data['area_id']	=	$shop['area_id'];//行政区域
		$data['district_id']=	$shop['district_id'];//商圈ID
		$data['one_id']		=	$shop['one_id'];//一级分类
		$data['industry_class_id']	=	$shop['industry_class_id'];//二级分类
		$data['valid_start']=	I('post.valid_start');//有效开始时间
		$data['valid_end']	=	I('post.valid_end');//有效截止时间
		$filelist	=	$this->uploads();
		foreach($filelist as $item)
		{
			$data[$item['key']]		=	$item['savepath'].$item['savename'];
		}
		$flag	=	M('Card')->add($data);
		if($flag===false)
		{
			$this->json_error('优惠券发布失败');
		}
		$data	=	array();
		$data['service_id']	=	$flag;
		$data['type']		=	1;
		$data['city_id']	=	$shop['city_id'];
		$data['member_id']	=	$member_id;
		$data['title']		=	I('post.title');
		$data['content']	=	I('post.content');//使用范围
		$data['addtime']	=	time();
		$data['province_id']=	$shop['province_id'];//省份
		$data['city_id']	=	$shop['city_id'];//城市ID
		$data['area_id']	=	$shop['area_id'];//行政区域
		$data['district_id']=	$shop['district_id'];//商圈ID
		$data['one_id']		=	$shop['one_id'];//一级分类
		$data['addtime']	=	time();//添加时间
		$data['industry_class_id']	=	$shop['industry_class_id'];//二级分类
		M('ShopService')->add($data);
		$this->json_ok(true);
	}
	/**
	* 功能：发布活动
	*/
	public function addactivity()
	{
		$member_id			=	intval(I('post.member_id'));//商家ID
		if($member_id<1)
		{
			$this->json_error('参数错误');
		}
		$shop				=	$this->getShop($member_id);//获取商家
		$data['member_id']	=	$member_id;
		$data['title']		=	I('post.title');//标题
		$data['content']	=	I('post.content');//活动内容
		$data['start_date']	=	I('post.start_date');//开始日期
		$data['end_date']	=	I('post.end_date');//截止日期
		$data['address']	=	I('post.address');//详细地址
		$data['long']		=	I('post.long');//经度
		$data['lat']		=	I('post.lat');//纬度
		$data['addtime']	=	time();//添加时间
		$data['province_id']=	$shop['province_id'];//省份
		$data['city_id']	=	$shop['city_id'];//城市ID
		$data['area_id']	=	$shop['area_id'];//行政区域
		$data['district_id']=	$shop['district_id'];//商圈ID
		$data['one_id']		=	$shop['one_id'];//一级分类
		$data['industry_class_id']	=	$shop['industry_class_id'];//二级分类
		$filelist	=	$this->uploads();
		foreach($filelist as $item)
		{
			$data[$item['key']]		=	$item['savepath'].$item['savename'];
		}
		$flag	=	M('Activity')->add($data);
		if($flag===false)
		{
			$this->json_error('活动发布失败');
		}
		$data	=	array();
		$data['service_id']	=	$flag;
		$data['type']		=	2;
		$data['city_id']	=	$shop['city_id'];
		$data['member_id']	=	$member_id;
		$data['title']		=	I('post.title');
		$data['content']	=	I('post.content');//活动内容
		$data['addtime']	=	time();
		$data['province_id']=	$shop['province_id'];//省份
		$data['city_id']	=	$shop['city_id'];//城市ID
		$data['area_id']	=	$shop['area_id'];//行政区域
		$data['district_id']=	$shop['district_id'];//商圈ID
		$data['one_id']		=	$shop['one_id'];//一级分类
		$data['addtime']	=	time();//添加时间
		$data['industry_class_id']	=	$shop['industry_class_id'];//二级分类
		M('ShopService')->add($data);
		$this->json_ok(true);
	}

	/**
	* 功能：发布新品
	*/
	public function addproduct()
	{
		$member_id			=	intval(I('post.member_id'));//商家ID
		if($member_id<1)
		{
			$this->json_error('参数错误');
		}
		$shop				=	$this->getShop($member_id);//获取商家
		$data['member_id']	=	$member_id;
		$data['title']		=	I('post.title');//标题
		$data['price']		=	I('post.price');//价格
		$data['day']		=	I('post.day');//日期
		$data['addtime']	=	time();//添加时间
		$data['province_id']=	$shop['province_id'];//省份
		$data['city_id']	=	$shop['city_id'];//城市ID
		$data['area_id']	=	$shop['area_id'];//行政区域
		$data['district_id']=	$shop['district_id'];//商圈ID
		$data['one_id']		=	$shop['one_id'];//一级分类
		$data['industry_class_id']	=	$shop['industry_class_id'];//二级分类
		$filelist	=	$this->uploads();
		foreach($filelist as $item)
		{
			$data[$item['key']]		=	$item['savepath'].$item['savename'];
		}
		$flag	=	M('NewProduct')->add($data);
		if($flag===false)
		{
			$this->json_error('新品发布失败');
		}
		$data	=	array();
		$data['service_id']	=	$flag;
		$data['type']		=	3;
		$data['city_id']	=	$shop['city_id'];
		$data['member_id']	=	$member_id;
		$data['title']		=	I('post.title');
		$data['addtime']	=	time();
		$data['province_id']=	$shop['province_id'];//省份
		$data['city_id']	=	$shop['city_id'];//城市ID
		$data['area_id']	=	$shop['area_id'];//行政区域
		$data['district_id']=	$shop['district_id'];//商圈ID
		$data['one_id']		=	$shop['one_id'];//一级分类
		$data['addtime']	=	time();//添加时间
		$data['industry_class_id']	=	$shop['industry_class_id'];//二级分类
		M('ShopService')->add($data);
		$this->json_ok(true);
	}
	/**
	* 功能：获取首页分类
	*/
	public function getHomeTypeList()
	{
		$city_id	=	I('get.city_id');//城市ID
		$map['city_id']	=	$city_id;
		$map['is_hidden']=	0;
		$list	=	M('HomeClass')->where($map)->order('order_num asc,id desc')->select();
		//echo M('HomeClass')->getlastsql();
		if(!$list)$list=array();
		$this->json_ok($list);
	}
	/**
	* 功能：获取首页轮换图
	*/
	public function getHomeRotateList()
	{
		$city_id	=	I('get.city_id');//城市ID
		$map['city_id']	=	$city_id;
		$map['status']	=	1;
		$list	=	M('Rotate')->field('id,type,content_id,link_url,title,img,service_id')->where($map)->order('order_num asc,id desc')->select();
		if(!$list)$list=array();
		$this->json_ok($list);
	}
	/**
	* 功能：获取某商家单项服务
	*/
	public function getShopServiceList()
	{
		$page	=	I('post.page');
		if($page<1)$page=1;
		$shop_id=	I('post.shop_id');
		$shop	=	M('Shop')->where(array('id'=>$shop_id))->find();
		if(!$shop)
		{
			$this->json_error('商家不存在');
		}
		$map['member_id']=	$shop['member_id'];
		$type	=	I('post.type');
		switch($type)
		{
			case 1://会员卡
				$list	=	M('Card')->where($map)->order('id desc')->limit(($page-1)*20,20)->select();
			break;
			case 2://活动
				$list	=	M('Activity')->where($map)->order('id desc')->limit(($page-1)*20,20)->select();
			break;
			case 3://新品
				$list	=	M('NewProduct')->where($map)->order('id desc')->limit(($page-1)*20,20)->select();
			break;
			default://优惠券
				$list	=	M('Coupon')->where($map)->order('id desc')->limit(($page-1)*20,20)->select();
		}
		if(!$list)$list=array();
		$arr	=	array();
		foreach($list as $item)
		{
			$arr1['id']		=	$item['id'];
			$arr1['title']	=	$item['title'];
			$arr1['addtime']=	date("Y-m-d",$item['addtime']);
			if(!empty($item['img6']))$arr1['img']=	$item['img6'];
			if(!empty($item['img5']))$arr1['img']=	$item['img5'];
			if(!empty($item['img4']))$arr1['img']=	$item['img4'];
			if(!empty($item['img3']))$arr1['img']=	$item['img3'];
			if(!empty($item['img2']))$arr1['img']=	$item['img2'];
			if(!empty($item['img1']))$arr1['img']=	$item['img1'];
			if(!isset($arr1['img']))$arr1['img'] =	'';
			$arr[]=$arr1;
		}
		if(count($arr)<1)$arr=array(array('id'=>''));
		$this->json_ok($arr);
	}
	/**
	* 功能：获取商家详情页
	*/
	public function getShopInfo()
	{
		$id	=	intval(I('get.id'));
		$member_id	=	intval(I('get.member_id'));//会员ID
		$row=	M('Shop')->field('id,title as full_name,fans_num,star,content,address,subscribe_tel,business_time')->where(array('id'=>$id))->find();
		//echo M('Shop')->getlastsql();
		if(!$row)
		{
			$this->json_error('商家不存在');
		}
		$row['content']	=	str_replace(array("\r","\n","\t"),array('','',''),strip_tags(htmlspecialchars_decode($row['content'])));
		$map['shop_id']	=	$id;
		$map['member_id']=	$member_id;
		$record	=	M('ShopFans')->where($map)->find();
		if(!$record)
		{
			$row['follow']=	0;
		}
		else
		{
			$row['follow']=	1;
		}
		//获取商家服务
		$map	=	array();
		$map['member_id']=$member_id;
		//优惠券数量
		$map['type']=	0;
		$counponnum	=	M('ShopService')->where($map)->count();
		//会员卡数量
		$map['type']=	1;
		$cardnum	=	M('ShopService')->where($map)->count();
		//活动数量
		$map['type']=	2;
		$activitynum=	M('ShopService')->where($map)->count();
		//新品数量
		$map['type']=	3;
		$newproductnum=	M('ShopService')->where($map)->count();
		$row['counponnum']		=	$counponnum;
		$row['cardnum']			=	$cardnum;
		$row['activitynum']		=	$activitynum;
		$row['newproductnum']	=	$newproductnum;
		
		$map	=	array();
		$map['member_id']=$member_id;
		//$map['_string']=" type<4";
		$calllist=	M('ShopService')->where(array('member_id'=>$member_id))->order('id desc')->limit(0,3)->select();
		if(!$calllist)$calllist=array();
		//$new_calllist[] = '';
		foreach($calllist as $key=>$item)
		{
			switch($item['type'])
			{
				case 1://会员卡
					$service	=	M('Card')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 2://活动
					$service	=	M('Activity')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 3://新品
					$service	=	M('NewProduct')->field('img1,img2,img3,img4,img5,img6,title as content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 0://优惠券
					$service	=	M('Coupon')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 4://吆喝
					$service	=	M('Call')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
			}
			if(empty($item['content']))$item['content']=$service['content'];
			if(!empty($item['img6']))$item['img']	=	$item['img6'];
			if(!empty($item['img5']))$item['img']	=	$item['img5'];
			if(!empty($item['img4']))$item['img']	=	$item['img4'];
			if(!empty($item['img3']))$item['img']	=	$item['img3'];
			if(!empty($item['img2']))$item['img']	=	$item['img2'];
			if(!empty($item['img1']))$item['img']	=	$item['img1'];
			if(!isset($item['img']))$item['img']='';
			if(empty($item['img']))
			{
				if(!empty($service['img6']))$item['img']=	$service['img6'];
				if(!empty($service['img5']))$item['img']=	$service['img5'];
				if(!empty($service['img4']))$item['img']=	$service['img4'];
				if(!empty($service['img3']))$item['img']=	$service['img3'];
				if(!empty($service['img2']))$item['img']=	$service['img2'];
				if(!empty($service['img1']))$item['img']=	$service['img1'];
				if(!isset($item['img']))$item['img']='';
			}
			$new_calllist[]=$item;
		}
		if(count($new_calllist)<1)$new_calllist=array(array('id'=>''));
		$arr['row']=$row;
		//$arr['service']=$new_servicelist;
		$arr['call']=$new_calllist;
		$this->json_ok($arr);
	}
	/**
	* 功能：取有关注
	*/
	public function cancelFollow()
	{
		$id			=	intval(I('get.id'));//店铺ID
		$member_id	=	intval(I('get.member_id'));//会员ID
		//TODO 
		/*
		 * 1.use the memeber id to query the shop, if the shop id is no exist, and the member id is only 
		 * the member, not the shop owner, so here  will show the message "您未关注过"
		 * 
		 * 2. set the member_id= 4000000(member not exist),id=3(shop is exist), will show the message the 商家不存在
		 * 
		 * 3. set the member_id=40(exist), id=200000000000000(shop not exist),show the message "您未关注过"
		 * @var unknown 
		 */
		$row	=	$this->getShopById($id);
		if(!$row)
		{
			$this->json_error('商家不存在');
		}
		$member	=	M('Member')->where(array('id'=>$member_id))->find();
		if(!$member)
		{
			$this->json_error('会员不存在');
		}
		$map['shop_id']	=	$id;
		$map['member_id']=	$member_id;
		$fans	=	M('ShopFans')->where($map)->find();
		if(!$fans)
		{
			$this->json_error('您未关注过');
		}
		M('ShopFans')->where(array('id'=>$fans['id']))->delete();
		M('Shop')->where(array('id'=>$id))->setDec('fans_num');
		$this->json_ok(true);
	}
	/**
	* 功能：店铺加关注
	*/
	public function shopFollow()
	{
		$id			=	intval(I('post.id'));//店铺ID
		$member_id	=	intval(I('post.member_id'));//会员ID
		$row=	M('Shop')->where(array('id'=>$id))->find();
		if(!$row)
		{
			$this->json_error('商家不存在');
		}
		$member	=	M('Member')->where(array('id'=>$member_id))->find();
		if(!$member)
		{
			$this->json_error('会员不存在');
		}
		if($member['type']>0)
		{
			$this->json_error('您是商家的角色，不允许关注');
		}
		$map['shop_id']	=	$id;
		$map['member_id']=	$member_id;
		$fans	=	M('ShopFans')->where($map)->find();
		if($fans)
		{
			$this->json_error('您已经关注过了');
		}
		$shop	=	M('Shop')->field('member_id')->where(array('id'=>$id))->find();
		$data['shop_id']	=	$id;
		$data['member_id']	=	$member_id;
		$data['follow_time']=	time();
		$data['to_member_id']=	$shop['member_id'];
		$fans_id	=	M('ShopFans')->add($data);
		if($fans_id<1)
		{
			$this->json_error('加关注失败01');
		}
		$flag	=	M('Shop')->where(array('id'=>$id))->setInc('fans_num');
		if($flag===false)
		{
			$this->json_error('加关注失败02');
		}
		$this->json_ok(true);
	}
	/**
	* 功能：获取优惠券
	*/
	public function getCoupon()
	{
		$member_id	=	intval(I('post.member_id'));//会员ID
		$id	=	intval(I('post.id'));//优惠券ID
		$row=	M('Coupon')->field('id,member_id,type,title,content,img1,img2,img3,img4,img5,img6,valid_start,valid_end,num,use,draw_num,status,addtime')->where(array('id'=>$id))->find();
		if(!$row)
		{
			$this->json_error('优惠券不存在');
		}
		if($row['status']!=1)
		{
			//$this->json_error('优惠券未上线');
		}
		$shop=	$this->getShop($row['member_id']);
		if(!$shop)
		{
			$this->json_error('店铺不存在');
		}
		$map['coupon_id']	=	$id;//优惠券ID
		$map['member_id']	=	$member_id;//会员ID
		$membercoupon		=	M('MemberCoupon')->where($map)->find();
		if($membercoupon)
		{
			$row['give']	=	1;
		}
		else
		{
			$row['give']	=	0;
		}
		$row['shop_id']		=	$shop['id'];//店铺ID
		$row['shop_name']	=	$shop['title'];//店铺名字
		$row['shop_subscribe_tel']=	$shop['subscribe_tel'];//店铺电话
		$row['shop_address']=	$shop['address'];//店铺地址
		$row['shop_fans_num']=	$shop['fans_num'];//店铺粉丝数量
		$this->json_ok($row);
	}
	/**
	* 功能：领取优惠券
	*/
	public function giveCoupon()
	{
		$id	=	intval(I('post.id'));//优惠券ID
		$member_id=intval(I('post.member_id'));//会员ID
		
		$coupon=	M('Coupon')->field('id,type,valid_start,valid_end,draw_num,num')->where(array('id'=>$id))->find();
		if(!$coupon)
		{
			$this->json_error('优惠券不存在');
		}
		if($coupon['draw_num']>=$coupon['num'])
		{
			$this->json_error('优惠券已发放完毕');
		}
		$now_date	=	date("Y-m-d");//现在日期
		if($coupon['valid_start']>$now_date || $coupon['valid_end']<$now_date)
		{
			$this->json_error('优惠券领取时间未到或者已经过期');
		}

		$map['coupon_id']	=	$id;//优惠券ID
		$map['member_id']	=	$member_id;//会员ID
		$row	=	M('MemberCoupon')->where($map)->find();
		if($row)
		{
			$this->json_error('您已经领取过了');
		}
		$data['coupon_id']	=	$id;//优惠券ID
		$data['member_id']	=	$member_id;//会员ID
		$data['card_number']=	$this->getCouponCardNumber();//获取新增的优惠券卡号
		$data['type']		=	$coupon['type'];//优惠券类型
		$data['draw_time']	=	time();//领取时间
		$data['valid_start']=	$coupon['valid_start'];
		$data['valid_end']	=	$coupon['valid_end'];
		$insert_id	=	M('MemberCoupon')->add($data);
		if($insert_id<1)
		{
			$this->json_error('领取优惠券失败01');
		}
		$flag	=	M('Coupon')->where(array('id'=>$id))->setInc('draw_num');
		if($flag===false)
		{
			$this->json_error('领取优惠券失败02');
		}
		$this->json_ok(true);
	}
	/**
	* 功能：获取会员卡
	*/
	public function getCard()
	{
		$member_id=intval(I('post.member_id'));//会员ID
		$id	=	intval(I('post.id'));//会员卡ID
		$row=	M('Card')->field('id,member_id,title,content,discount,img1,img2,img3,img4,img5,img6,valid_start,valid_end,status,num,draw_num')->where(array('id'=>$id))->find();
		//echo M('Card')->getlastsql();exit;
		if(!$row)
		{
			$this->json_error('会员卡不存在');
		}
		if($row['status']!=1)
		{
			//$this->json_error('会员卡未上线');
		}
		$shop=	$this->getShop($row['member_id']);
		if(!$shop)
		{
			$this->json_error('店铺不存在');
		}

		$map['card_id']	=	$id;//会员卡ID
		$map['member_id']=	$member_id;//会员ID
		$membercard		=	M('MemberCard')->where($map)->find();
		if($membercard)
		{
			$row['give']	=	1;
		}
		else
		{
			$row['give']	=	0;
		}
		$row['shop_id']		=	$shop['id'];//店铺ID
		$row['shop_name']	=	$shop['title'];//店铺名字
		$row['shop_subscribe_tel']=	$shop['subscribe_tel'];//店铺电话
		$row['shop_address']=	$shop['address'];//店铺地址
		$row['shop_fans_num']=	$shop['fans_num'];//店铺粉丝数量
		$this->json_ok($row);
	}
	/**
	* 功能：领取会员卡
	*/
	public function giveCard()
	{
		$id	=	intval(I('get.id'));//会员卡ID 
		$member_id=intval(I('get.member_id'));//会员ID
		
		$card=	M('Card')->field('id,valid_start,valid_end,draw_num,num')->where(array('id'=>$id))->find();
		if(!$card)
		{
			$this->json_error('会员卡不存在');
		}
		if($card['draw_num']>=$card['num'])
		{
			$this->json_error('会员卡已发放完毕');
		}
		$now_date	=	date("Y-m-d");//现在日期
		if($card['valid_start']>$now_date || $card['valid_end']<$now_date)
		{
			$this->json_error('会员卡领取时间未到或者已经过期');
		}

		$map['card_id']	=	$id;//会员卡ID
		$map['member_id']	=	$member_id;//会员ID
		$row	=	M('MemberCard')->where($map)->find();
		if($row)
		{
			$this->json_error('您已经领取过了');
		}
		$data['card_id']	=	$id;//ID
		$data['member_id']	=	$member_id;//会员ID
		$data['card_number']=	$this->getMemberCardNumber();//获取新增的号
		$data['draw_time']	=	time();//领取时间
		$data['valid_start']=	$card['valid_start'];
		$data['valid_end']	=	$card['valid_end'];
		$insert_id	=	M('MemberCard')->add($data);
		if($insert_id<1)
		{
			$this->json_error('领取会员卡失败01');
		}
		$flag	=	M('Card')->where(array('id'=>$id))->setInc('draw_num');
		if($flag===false)
		{
			$this->json_error('领取会员卡失败02');
		}
		$this->json_ok(true);
	}
	/**
	* 功能：获取活动
	*/
	public function getActivity()
	{
		$id	=	intval(I('post.id'));//活动ID
		$row=	M('Activity')->field('id,member_id,title,content,img1,img2,img3,img4,img5,img6,start_date,end_date,status,address,`long` as _long,lat,addtime')->where(array('id'=>$id))->find();
		//echo M('Activity')->getlastsql();exit;
		if(!$row)
		{
			$this->json_error('活动不存在');
		}
		if($row['status']!=1)
		{
			//$this->json_error('活动未上线');
		}
		$shop=	$this->getShop($row['member_id']);
		if(!$shop)
		{
			$this->json_error('店铺不存在');
		}
		$row['addtime']		=	date("Y-m-d H:i");
		$row['shop_id']		=	$shop['id'];//店铺ID
		$row['shop_name']	=	$shop['title'];//店铺名字
		$row['shop_subscribe_tel']=	$shop['subscribe_tel'];//店铺电话
		$row['shop_address']=	$shop['address'];//店铺地址
		$row['shop_fans_num']=	$shop['fans_num'];//店铺粉丝数量
		$this->json_ok($row);
	}
	/**
	* 功能：获取新品
	*/
	public function getNewProduct()
	{
		$id	=	intval(I('post.id'));//新品ID
		$row=	M('NewProduct')->field('id,member_id,title,price,img1,img2,img3,img4,img5,img6,day,status,day,addtime')->where(array('id'=>$id))->find();
		//echo M('NewProduct')->getlastsql();exit;
		if(!$row)
		{
			$this->json_error('新品不存在');
		}
		if($row['status']!=1)
		{
			//$this->json_error('新品未上线');
		}
		$shop=	$this->getShop($row['member_id']);
		if(!$shop)
		{
			$this->json_error('店铺不存在');
		}
		$row['addtime']		=	date('m-d H:i',$row['addtime']);
		$row['shop_id']		=	$shop['id'];//店铺ID
		$row['shop_name']	=	$shop['title'];//店铺名字
		$row['shop_subscribe_tel']=	$shop['subscribe_tel'];//店铺电话
		$row['shop_address']=	$shop['address'];//店铺地址
		$row['shop_fans_num']=	$shop['fans_num'];//店铺粉丝数量
		$this->json_ok($row);
	}
	/**
	* 功能：获取吆喝详情
	*/
	public function getCall()
	{
		//,zan_num,comment_num,collection_num
		$id	=	intval(I('get.id'));
		/*$row=	M('ShopService')->field('id,member_id,service_id,title,zan_num,comment_num,collection_num,addtime,type')->where(array('id'=>$id))->find();*/
		$row=	M('ShopService')->where(array('id'=>$id))->find();
		if(!$row)
		{
			$this->json_error('吆喝不存在');
		}
		$shop=	$this->getShop($row['member_id']);
		if(!$shop)
		{
			$this->json_error('店铺不存在');
		}
		switch($row['type'])
		{
			case 4://纯吆喝
				$record	=	M('Call')->field('id,content,img1,img2,img3,img4,img5,img6')->where(array('id'=>$row['service_id']))->find();
				//echo M('Call')->getlastsql();
			break;
			case 3://新品
				$record	=	M('NewProduct')->field('id,title,price,day,img1,img2,img3,img4,img5,img6,title')->where(array('id'=>$row['service_id']))->find();
			break;
			case 2://活动
				$record	=	M('Activity')->field('id,title,content,img1,img2,img3,img4,img5,img6,title')->where(array('id'=>$row['service_id']))->find();
			break;
			case 1://会员卡
				$record	=	M('Card')->field('id,title,content,img1,img2,img3,img4,img5,img6,title')->where(array('id'=>$row['service_id']))->find();
				//$coupon	=	M('Coupon')->field('id')->where(array('id'=>$row['c_id']))->find();
			break;
			default://优惠券
				$record	=	M('Coupon')->field('id,title,content,img1,img2,img3,img4,img5,img6,title')->where(array('id'=>$row['service_id']))->find();
		}
		/*if(isset($card))$row['card_id']=$card['id'];
		else $row['card_id']=0;
		if(isset($coupon))$row['coupon_id']=$coupon['id'];
		else $row['coupon_id']=0;
		$row['shop_service_id']		=	$record['id'];
		$row['zan_num']				=	$record['zan_num'];
		$row['comment_num']			=	$record['comment_num'];
		$row['collection_num']		=	$record['collection_num'];*/

		if(!empty($record['img6']))$row['s_img']=$record['img6'];
		if(!empty($record['img5']))$row['s_img']=$record['img5'];
		if(!empty($record['img4']))$row['s_img']=$record['img4'];
		if(!empty($record['img3']))$row['s_img']=$record['img3'];
		if(!empty($record['img2']))$row['s_img']=$record['img2'];
		if(!empty($record['img1']))$row['s_img']=$record['img1'];

		if(!isset($row['s_img']))
		{
			if(!empty($row['img6']))$row['s_img']=$row['img6'];
			if(!empty($row['img5']))$row['s_img']=$row['img5'];
			if(!empty($row['img4']))$row['s_img']=$row['img4'];
			if(!empty($row['img3']))$row['s_img']=$row['img3'];
			if(!empty($row['img2']))$row['s_img']=$row['img2'];
			if(!empty($row['img1']))$row['s_img']=$row['img1'];
		}
		if(!isset($row['s_img']))$row['s_img']='';
		$row['s_title']				=	$row['title'];
		if(empty($row['s_title']))$row['s_title']=	$record['title'];
		/*$row['img1']				=	$record['img1'];
		$row['img2']				=	$record['img2'];
		$row['img3']				=	$record['img3'];
		$row['img4']				=	$record['img4'];
		$row['img5']				=	$record['img5'];
		$row['img6']				=	$record['img6'];*/
		$row['shop_service_id']		=	$row['service_id'];
		if(!isset($record['content'])){
			$row['content']				=	$record['title'];
		}else{
			$row['content']				=	$record['content'];
		}
		//$row['content']				=	$record['content'];
		$row['addtime']				=	date('m-d H:i');
		$row['shop_id']				=	$shop['id'];//店铺ID
		$row['shop_name']			=	$shop['title'];//店铺名字
		$row['shop_subscribe_tel']	=	$shop['subscribe_tel'];//店铺电话
		$row['shop_address']		=	$shop['address'];//店铺地址
		$row['shop_fans_num']		=	$shop['fans_num'];//店铺粉丝数量
		$this->json_ok($row);
	}
	/**
	* 功能：获取首页吆喝列表
	*/
	public function getHomeCallList()
	{
		$page	=	intval(I('get.page'));
		if($page<1)$page=1;
		$city_id=	intval(I('get.city_id'));
		$member_id=	intval(I('get.member_id'));
		//$field	=	'id,member_id,content,img1,type,fans_num,zan_num,comment_num';
		//$field	=	'id,member_id,type,zan_num,comment_num,collection_num';
		//$list	=	M('Call')->field($field)->where(array('city_id'=>$city_id))->order('id desc')->limit(($page-1)*20,20)->select();
		$list	=	M('ShopService')->where(array('city_id'=>$city_id))->order('id desc')->limit(($page-1)*20,20)->select();
		$count	=	M('ShopService')->where(array('city_id'=>$city_id))->count('*');
		//echo M('ShopService')->getlastsql();exit;
		if(!$list)$list=array();
		$arr	=	array();
		foreach($list as $key=>$item)
		{
			switch($item['type'])
			{
				case 1://会员卡
					$service	=	M('Card')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 2://活动
					$service	=	M('Activity')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 3://新品
					$service	=	M('NewProduct')->field('img1,img2,img3,img4,img5,img6,title as content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 0://优惠券
					$service	=	M('Coupon')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 4://纯吆喝
					$service	=	M('Call')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
			}
			//$item['content']	=	$service['content'];
			if(!empty($service['img6']))$item['s_img']=	$service['img6'];
			if(!empty($service['img5']))$item['s_img']=	$service['img5'];
			if(!empty($service['img4']))$item['s_img']=	$service['img4'];
			if(!empty($service['img3']))$item['s_img']=	$service['img3'];
			if(!empty($service['img2']))$item['s_img']=	$service['img2'];
			if(!empty($service['img1']))$item['s_img']=	$service['img1'];

			if(!empty($item['img6']))$item['img']	=	$item['img6'];
			if(!empty($item['img5']))$item['img']	=	$item['img5'];
			if(!empty($item['img4']))$item['img']	=	$item['img4'];
			if(!empty($item['img3']))$item['img']	=	$item['img3'];
			if(!empty($item['img2']))$item['img']	=	$item['img2'];
			if(!empty($item['img1']))$item['img']	=	$item['img1'];

			if(!isset($item['s_img']))$item['s_img']='';
			if(!isset($item['img']))$item['img']='';
			if(empty($item['s_content']))$item['s_content']=$service['content'];
			//$list[$key]['img']		=	$service['img1'];
			$row=	M('Shop')->field('id,title,star,fans_num')->where(array('member_id'=>$item['member_id']))->find();
			$item['shop_id']		=	$row['id'];
			$item['shop_name']		=	$row['title'];
			$item['shop_star']		=	$row['star'];
			$item['shop_fans_num']	=	$row['fans_num'];
			$row	=	M('ShopFans')->where(array('shop_id'=>$row['id'],'member_id'=>$member_id))->find();
			if($row)
			{
				$item['guanzhu']=1;
			}
			else
			{
				$item['guanzhu']=0;
			}
			$arr[]=$item;
		}
		if(count($arr)<1)$arr=array(array('id'=>''));
		$this->json_ok_page($arr, $page, $count);
		//$this->json_ok($arr);
	}
	/**
	* 功能：获取某个分类下的吆喝
	*/
	public function getTypeCallList()
	{
		$page	=	intval(I('get.page'));
		if($page<1)$page=1;
		$city_id=	intval(I('get.city_id'));
		$one_id	=	intval(I('get.one_id'));

		$classMap['id'] = $one_id ;
		$typeList	=	M('Classify')->where($classMap)->find();
		if($typeList['parentid'] == 0){
			$map['one_id']	=	$one_id;
		}else{
			$map['industry_class_id']	=	$one_id;
		}

		$map['city_id']	=	$city_id;

		//$field	=	'id,member_id,content,img1,type,fans_num,zan_num,comment_num';
		//$field	=	'id,member_id,title,type,addtime,zan_num,comment_num,collection_num,img1,img2,img3,img4,img5,img6';
		$list	=	M('ShopService')->where($map)->order('id desc')->limit(($page-1)*20,20)->select();
		$count	=	M('ShopService')->where($map)->count('*');
		//$list	=	M('Call')->field($field)->where($map)->order('id desc')->select();
		if(!$list)$list=array();
		$arr	=	array();
		foreach($list as $item)
		{
			switch($item['type'])
			{
				case 1://会员卡
					$service	=	M('Card')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 2://活动
					$service	=	M('Activity')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 3://新品
					$service	=	M('NewProduct')->field('img1,img2,img3,img4,img5,img6,title as content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 0://优惠券
					$service	=	M('Coupon')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
			}
			if(!empty($service['img6']))$item['s_img']=	$service['img6'];
			if(!empty($service['img5']))$item['s_img']=	$service['img5'];
			if(!empty($service['img4']))$item['s_img']=	$service['img4'];
			if(!empty($service['img3']))$item['s_img']=	$service['img3'];
			if(!empty($service['img2']))$item['s_img']=	$service['img2'];
			if(!empty($service['img1']))$item['s_img']=	$service['img1'];

			if(!empty($item['img6']))$item['img']	=	$item['img6'];
			if(!empty($item['img5']))$item['img']	=	$item['img5'];
			if(!empty($item['img4']))$item['img']	=	$item['img4'];
			if(!empty($item['img3']))$item['img']	=	$item['img3'];
			if(!empty($item['img2']))$item['img']	=	$item['img2'];
			if(!empty($item['img1']))$item['img']	=	$item['img1'];

			if(!isset($item['s_img']))$item['s_img']='';
			if(!isset($item['img']))$item['img']='';
			if(empty($item['s_content']))$item['s_content']=$service['content'];
			//$item['img1']=$item['img'];
			//$item['img1']=$service['img1'];
			$item['content']=$service['content'];
			$row=	M('Shop')->field('id,title,star,fans_num')->where(array('member_id'=>$item['member_id']))->find();
			$item['shop_id']=$row['id'];
			$item['shop_name']=$row['title'];
			$item['shop_star']=$row['star'];
			$item['shop_fans_num']=$row['fans_num'];
			$arr[]=$item;
		}
		if(count($arr)<1)$arr=array(array('id'=>''));
		$this->json_ok_page($arr, $page, $count);
		//$this->json_ok($arr);
	}
	/**
	* 功能：获取关注商家的吆喝列表
	*/
	public function getFollowShopList()
	{
		$city_id	=	intval(I('get.city_id'));//城市
		$member_id	=	intval(I('get.member_id'));//会员ID
		$list		=	M('ShopFans')->field('to_member_id')->where(array('member_id'=>$member_id))->select();
		if(!$list)
		{
			$this->json_ok(array());
		}
		$shop_id_arr=	array();
		foreach($list as $item)
		{
			$shop_id_arr[]	=	$item['to_member_id'];
		}
		$map['member_id']=	array('in',implode(',',$shop_id_arr));
		$map['city_id']	=	$city_id ;
		$field	=	'id,member_id,service_id,title,type,collection_num,zan_num,comment_num,addtime,img1,img2,img3,img4,img5,img6';
		//$list	=	M('Call')->field($field)->where($map)->order('id desc')->select();
		$list	=	M('ShopService')->field($field)->where($map)->order('id desc')->select();
		//echo M('ShopService')->getlastsql();exit;
		//var_dump($list);exit;
		if(!$list)$list=array();
		$arr	=	array();
		foreach($list as $item)
		{
			switch($item['type'])
			{
				case 1://会员卡
				$row	=	M('Card')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
					break;
				case 2://活动
				$row	=	M('Activity')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
					break;
				case 3://新品
				$row	=	M('NewProduct')->field('img1,title as content')->where(array('id'=>$item['service_id']))->find();
					break;
				case 0://优惠券
				$row	=	M('Coupon')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
					break;
				case 4://吆喝
				$row	=	M('Call')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
					break;
				default:
					$row['content']	=	'';
					$row['img1']	=	'';
			}
			if(empty($item['content']))$item['content']=	$row['content'];
			//$item['img1']	=	$row['img1'];

			if(!empty($item['img6']))$item['img']	=	$row['img6'];
			if(!empty($item['img5']))$item['img']	=	$row['img5'];
			if(!empty($item['img4']))$item['img']	=	$row['img4'];
			if(!empty($item['img3']))$item['img']	=	$row['img3'];
			if(!empty($item['img2']))$item['img']	=	$row['img2'];
			if(!empty($item['img1']))$item['img']	=	$row['img1'];
			if(!isset($item['img']))
			{
				if(!empty($service['img6']))$item['img']=	$item['img6'];
				if(!empty($service['img5']))$item['img']=	$item['img5'];
				if(!empty($service['img4']))$item['img']=	$item['img4'];
				if(!empty($service['img3']))$item['img']=	$item['img3'];
				if(!empty($service['img2']))$item['img']=	$item['img2'];
				if(!empty($service['img1']))$item['img']=	$item['img1'];
			}
			if(!isset($item['img']))$item['img']='';
			$item['img1']=$item['img'];
			$item['addtime']=	date("Y-m-d H:i:s",$item['addtime']);
			$row=	M('Shop')->field('id,title,star,fans_num')->where(array('member_id'=>$item['member_id']))->find();
			$item['shop_id']=$row['id'];
			$item['shop_name']=$row['title'];
			$item['shop_star']=$row['star'];
			$item['shop_fans_num']=$row['fans_num'];
			$arr[]=$item;
			/*$list[$key]['content']=	$row['content'];
			$list[$key]['img1']	=	$row['img1'];
			$list[$key]['addtime']=	date("Y-m-d H:i:s",$item['addtime']);*/
		}
		if(count($arr)<1)
		{
			$arr	=	array(array('id'=>''));
		}
		$this->json_ok($arr);
	}
	/**
	* 功能：获取附近商家
	*/
	public function getNearbyList()
	{
		$page	=	intval(I('post.page'));//当前页数
		if($page<1)$page=1;
		$long	=	I('get._long');//经度
		$lat	=	I('get.lat');//纬度

		$city_id=	intval(I('post.city_id'));//城市ID
		$one_id	=	intval(I('post.one_id'));//第一级分类
		$industry_class_id=intval(I('post.industry_class_id'));//第二级分类
		$district_id=intval(I('post.district_id'));//商圈ID
		$rice	=	intval(I('post.rice'));//多少米
		$map['city_id']	=	$city_id;
		$map['status']	=	1;
		if($one_id>0)$map['one_id']=$one_id;
		if($industry_class_id>0)$map['industry_class_id']=$industry_class_id;
		if($district_id>0)$map['district_id']=$district_id;
		switch($rice)
		{
			case 500://500米
				$rice=0.05;
			break;
			case 1000://1000米
				$rice=0.1;
			break;
			case 3000://3000米
				$rice=0.3;
			break;
			case 5000://5000米
				$rice=0.5;
			break;
			default:
				$rice=0;
		}
		if($rice>0)
		{
			$squares = returnSquarePoint($long, $lat,$rice);
			$map['_string']	=	"lat<>0 and lat>{$squares['right-bottom']['lat']} and lat<{$squares['left-top']['lat']} and lng>{$squares['left-top']['lng']} and lng<{$squares['right-bottom']['lng']}";
		}
		$list	=	M('Shop')->field('id,lng,lat')->where($map)->select();
		//echo M('Shop')->getlastsql();
		if(!$list)$list=array();
		$shoplist	=	array();
		//var_dump($list);exit;
		foreach($list as $key=>$item)
		{
			$range	=	getDistance($long,$lat,$item['lng'],$item['lat']);//得到具体的距离
			$shoprange[]=array('id'=>$item['id'],'range'=>$range);
			$shoplist[]=$range;
			//$keyid	=	$item['id'];
		}
//		$base_num = $shoplist[$keyid];
		/*foreach($shoplist as $shop_id=>$range)
		{
		}*/
		//var_dump($shoplist);exit;
		sort($shoplist);
		$new_shoplist	=	array();
		foreach($shoplist as $item)
		{
			foreach($shoprange as $key=>$val)
			{
				if($item==$val['range'])
				{
					$new_shoplist[]	=	array('shop_id'=>$val['id'],'range'=>$item);
					unset($shoprange[$key]);
					break;
				}				
			}
		}
		//var_dump($new_shoplist);exit;
		//var_dump($shoplist);exit;
		$arr	=	array();
		$list1	=	array_slice($new_shoplist,($page-1)*20,20);
		//var_dump($list1);exit;
		foreach($list1 as $shop_id=>$item)
		{
			$field	=	'id,member_id,title as full_name,star,fans_num,area_id,district_id';
			$shop	=	M('Shop')->field($field)->where(array('id'=>$item['shop_id']))->find();
//			$item['full_name']=strip_tags(htmlspecialchars_decode($item['full_name']));
			$member	=	M('Member')->where(array('id'=>$shop['member_id']))->find();
			$shop['face']=$member['face'];
			//echo M('Shop')->getlastsql().'<br>';
			$shop['range']=$item['range'];
			$arr[]	=	$shop;
		}
		//var_dump($arr);exit;
		if(count($arr)<1)$arr=array(array('id'=>''));
		$this->json_ok($arr);
	}
	/**
	* 功能：获取店铺的点评列表
	*/
	public function getShopCommentList()
	{
		$where['shop_id']	=	intval(I('post.shop_id'));
		$page	=	intval(I('post.page'));
		if($page<1)$page=1;
		$list	=	M('ShopComment')->where($where)->limit(($page-1)*20,20)->order('id desc')->select();
		if(!$list)
		{
			$this->json_ok(array(array('id'=>'')));
		}
		$arr	=	array();
		foreach($list as $item)
		{
			$row	=	M('Personal')->field('nickname')->where(array('member_id'=>$item['member_id']))->find();
			if(!$row)
			{
				$member	=	M("Member")->where(array('id'=>$item['member_id']))->find();
				$item['nickname']=$member['login_user'];
			}
			else
			{
				$item['nickname']=$row['nickname'];
			}
			$arr[]=$item;
		}
		$this->json_ok($arr);
	}
	/**
	* 功能：店铺点评
	*/
	public function shopComment()
	{
		$data['member_id']	=	intval(I('post.member_id'));
		$data['shop_id']	=	intval(I('post.shop_id'));
		$data['star']		=	I('post.star');//几颗星
		$data['content']	=	I('post.content');//评论内容
		$data['is_anonymous']=	I('post.is_anonymous');//是否匿名
		$shop	=	M('Shop')->where(array('id'=>$data['shop_id']))->find();
		$data['title']		=	$shop['title'];//店铺名称
		$data['addtime']	=	time();
		$data['type']		=	intval(I('post.type'));//0评论 1回复
		$data['parentid']	=	intval(I('post.parentid'));
		$person	=	M('Personal')->where(array('member_id'=>$data['member_id']))->find();
		$member	=	M('Member')->where(array('id'=>$data['member_id']))->find();
		if($data['type']>0)
		{
			$row	=	M('ShopComment')->where(array('id'=>$data['parentid']))->find();
			if(!$row)
			{
				$this->json_error('评论不存在');
			}
			$data['to_member_id']	=	$row['member_id'];
			if(!$person)
			{
				$comment_title	=	'吆喝'.substr_replace($member['login_user'],'****',3,4);
			}
			else
			{
				$comment_title	=	$person['nickname'];
			}
			$person1	=	M('Personal')->where(array('member_id'=>$row['member_id']))->find();
			$member1	=	M('Member')->where(array('id'=>$row['member_id']))->find();
			if(!$person1)
			{
				$comment_title	.=	'回复吆喝'.substr_replace($member1['login_user'],'****',3,4);
			}
			else
			{
				$comment_title	.=	'回复'.$person1['nickname'];
			}
		}
		else
		{
			if(!$person)
			{
				$comment_title	=	'吆喝'.substr_replace($member['login_user'],'****',3,4);
			}
			else
			{
				$comment_title	=	$person['nickname'];
			}
		}
		$data['comment_title']	=	$comment_title;
		M('ShopComment')->add($data);
		//获取评价总星数
		$star_total	=	M('ShopComment')->where(array('shop_id'=>$data['shop_id']))->sum('star');
		//获取评价总次数
		$comment_total=	M('ShopComment')->where(array('shop_id'=>$data['shop_id']))->count();
		$star	=	floor($star_total/$comment_total);
		M('Shop')->where(array('id'=>$data['shop_id']))->setInc('comment_num');
		M('Shop')->where(array('id'=>$data['shop_id']))->save(array('star'=>$star));
		$this->json_ok(true);
	}

	/**
	 * 功能：店铺点评
	 */
	public function sms()
	{
		$data['member_id']	=	intval(I('post.member_id'));
		$data['to_member_id']	=	intval(I('post.to_member_id'));
		$data['content']	=	I('post.content');//消息内容
		$data['addtime']	=	time();
		$data['parentid']	=	intval(I('post.parentid'));
		M('Sms ')->add($data);
		$this->json_ok(true);
	}
	/**
	* 功能：店铺点赞
	*/
	public function shopPraise()
	{
		$data['member_id']	=	intval(I('post.member_id'));
		$data['shop_id']	=	intval(I('post.shop_id'));
		$data['addtime']	=	time();
		$map['member_id']	=	$data['member_id'];
		$map['shop_id']		=	$data['shop_id'];
		$row	=	M('ShopZan')->where($map)->find();
		if($row)
		{
			$this->json_error('您已经点过赞了');
		}
		M('ShopZan')->add($data);
		M('Shop')->where(array('id'=>$data['shop_id']))->setInc('zan_num');
		$this->json_ok(true);
	}
	/**
	* 功能：吆喝收藏
	*/
	public function callCollection()
	{
		$row	=	M('ShopServiceCollection')->where(array('shop_service_id'=>intval(I('post.call_id'))))->find();
		if($row)
		{
			$this->json_error('您已经收藏过了');
		}
		$data['shop_service_id']=	intval(I('post.call_id'));//吆喝ID
		$data['member_id']		=	intval(I('post.member_id'));//会员ID
		$data['addtime']		=	time();
		M('ShopServiceCollection')->add($data);
		M('ShopService')->where(array('id'=>$data['shop_service_id']))->setInc('collection_num');
		$this->json_ok(true);
	}
	/**
	* 功能：删除吆喝收藏
	*/
	public function delCallCollection()
	{
		$id	=	I('post.id');//收藏ID
		$member_id=I('post.member_id');//会员ID
		$row	=	M('ShopServiceCollection')->where(array('id'=>$id))->find();
		if(!$row)
		{
			$this->json_error('收藏不存在');
		}
		if($row['member_id']!=$member_id)
		{
			$this->json_error('收藏不是您的');
		}
		M('ShopServiceCollection')->where(array('id'=>$id))->delete();
		$this->json_ok(true);
	}
	/**
	* 功能：获取吆喝点评列表
	*/
	public function getCallCommentList()
	{
		$shop_service_id	=	intval(I('get.call_id'));
		$list	=	M('ShopServiceComment')->field('id,member_id,content,is_anonymous,addtime')->where(array('shop_service_id'=>$shop_service_id))->order('id desc')->select();
		//echo M('ShopServiceComment')->getlastsql();
		if(!$list)$this->json_ok(array(array('id'=>'')));
		$arr	=	array();
		foreach($list as $item)
		{
			$member=M('Member')->field('id,face,type')->where(array('id'=>$item['member_id']))->find();
			if(!$member)continue;
			$item['face']=$member['face'];
			$item['addtime']=date("Y-m-d H:i");
			if($member['type']==0)
			{
				$person=M('Personal')->where(array('member_id'=>$member['id']))->find();
				if(!$person)
				{
					$item['nickname']='吆喝'.$member['id'];
				}
				else
				{
					$item['nickname']=$person['nickname'];
				}
			}
			else
			{
				$shop=$this->getShop($member['id']);
				$item['nickname']=$shop['title'];
			}
			$arr[]=$item;
		}
		$this->json_ok($arr);
	}
	/**
	* 功能：吆喝点评
	*/
	public function callComment()
	{
		$data['member_id']	=	intval(I('get.member_id'));
		$data['shop_service_id']=intval(I('get.call_id'));
		$data['content']	=	I('get.content');//评论内容
		$data['is_anonymous']=	I('get.is_anonymous');//是否匿名
		$data['type']		=	I('get.type');//0优惠券 1会员卡 2活动 3新品 4吆喝
		$data['parentid']	=	intval(I('get.parentid'));//回复ID
		if($data['type']>4)
		{
			$this->json_error('类型不正确');
		}
		if($data['parentid']>0)
		{
			$data['h_type']	=	1;//回复
			$row	=	M('ShopServiceComment')->where(array('id'=>$data['parentid']))->find();
			if(!$row)
			{
				$this->json_error('回复的评论不存在');
			}
			$data['to_member_id']	=	$row['member_id'];
		}
		else
		{
			$data['h_type']	=	0;//评论
		}
		$shop_service=M('ShopService')->where(array('id'=>$data['shop_service_id']))->find();
		if(!$shop_service)
		{
			$this->json_error('吆喝不存在');
		}
		$shop	=	$this->getShop($shop_service['member_id']);
		if(!$shop)
		{
			$this->json_error('店铺不存在');
		}
		$data['shop_id']	=	$shop['id'];
		$data['shop_title']	=	$shop['title'];
		/*$service	=	M('ShopService')->where(array('id'=>$data['call_id']))->find();
		if(!$service)
		{
			$this->json_error('吆喝不存在');
		}*/
		switch($shop_service['type'])
		{
			case 0://券
				$row	=	M('Coupon')->field('title')->where(array('id'=>$shop_service['service_id']))->find();
				if(!$row)
				{
					$this->json_error('内容不存在');
				}
				$data['title']	=	$row['title'];
				break;
			case 1://卡
				$row	=	M('Card')->field('title')->where(array('id'=>$shop_service['service_id']))->find();
				if(!$row)
				{
					$this->json_error('内容不存在');
				}
				$data['title']	=	$row['title'];
				break;
			case 2://活动
				$row	=	M('Activity')->field('title')->where(array('id'=>$shop_service['service_id']))->find();
				if(!$row)
				{
					$this->json_error('内容不存在');
				}
				$data['title']	=	$row['title'];
				break;
			case 3://新品
				$row	=	M('NewProduct')->field('title')->where(array('id'=>$shop_service['service_id']))->find();
				if(!$row)
				{
					$this->json_error('内容不存在');
				}
				$data['title']	=	$row['title'];
				break;
			case 4://吆喝
				$row	=	M('Call')->field('content')->where(array('id'=>$shop_service['service_id']))->find();
				if(!$row)
				{
					$this->json_error('内容不存在');
				}
				$data['title']	=	'';
				break;
			default:
				$this->json_error('非法吆喝');
		}
		$data['addtime']	=	time();
		M('ShopServiceComment')->add($data);
		//M('Call')->where(array('id'=>$data['call_id']))->setInc('comment_num');
		M('ShopService')->where(array('id'=>$data['shop_service_id']))->setInc('comment_num');
		$this->json_ok(true);
	}
	/**
	* 功能：吆喝点赞
	*/
	public function callPraise()
	{
		$data['member_id']		=	intval(I('post.member_id'));
		$data['shop_service_id']=	intval(I('post.call_id'));
		$row	=	M('ShopServiceZan')->where($data)->find();
		//echo M('ShopServiceZan')->getlastsql();exit;
		if($row)
		{
			$this->json_error('您已经点过赞了');
		}
		$data['addtime']		=	time();
		M('ShopServiceZan')->add($data);
		M('ShopService')->where(array('id'=>$data['shop_service_id']))->setInc('zan_num');
		$this->json_ok(true);
	}
	/**
	* 功能：上传会员头像
	*/
	public function uploadFace()
	{
		$where['id']=	intval(I('post.member_id'));
		$filelist	=	$this->uploads();
		foreach($filelist as $item)
		{
			$data[$item['key']]	=	$item['savepath'].$item['savename'];
		}
		/*if(!isset($data))
		{
			$this->json_error('请上传头像');
		}*/
		$flag	=	M('Member')->where($where)->save($data);
		if($flag===false)
		{
			$this->json_error('上传头像失败');
		}
		$this->json_ok(array('face'=>$data['face']));
	}
	/**
	* 功能：我的关注
	 *
	 * 2015-11-04 修改
	 * 我的关注里显示所有的关注，不限制城市
	*/
	public function myFollowList()
	{
		$classify_arr	=	array();
		$classify_list	=	M('Classify')->select();
		foreach($classify_list as $item)
		{
			$classify_arr[$item['id']]=$item['title'];
		}
		$member_id_get	=	intval(I('get.member_id')) ;
		//$city_id	=	intval(I('get.city_id')) ;
		$where['member_id']=	intval(I('get.member_id'));
		$list	=	 M()->table('ht_shop_fans sf, ht_shop sp ')->where('sf.shop_id = sp.id and sf.member_id='.$member_id_get)->field('sf.to_member_id')->order('sf.follow_time')->select();
		//$list = M()->table('ht_shop_service s, ht_shop_service_collection n')->where('s.id = n.shop_service_id and n.member_id='.$member_id)->field('n.id,s.service_id,s.member_id,s.type,n.addtime')->order('n.id desc' )->select();

		if(!$list)
		{
			$arr	=	array(array('id'=>''));
			$this->json_ok($arr);
		}
		foreach($list as $item)
		{
			$member_id[]=$item['to_member_id'];
		}

		//TODO  由于前期推广，所以先把状态控制先取消
		//$map['status']		=	1;
		$map['member_id']	=	array('in',implode(',',$member_id));
		$calllist			=	M('Shop')->field('id,member_id,one_id,industry_class_id,title,one_id')->where($map)->order('id desc')->select();
		$arr	=	array();
		foreach($calllist as $key=>$item)
		{
			$row	=	M('Member')->where(array('id'=>$item['member_id']))->find();
			$item['class_title']=$classify_arr[$item['one_id']];
			$item['face']=$row['face'];
			$item['username']=$row['login_user'];
			$arr[]	=	$item;
			//$calllist[$key]['face']=$row['face'];
		}
		if(count($arr)<1)
		{
			$arr=array(array('id'=>''));
		}
		$this->json_ok($arr);
	}

	/**
	 * 功能：导航栏里的关注商家
	 * 这里需要城市
	 */
	public function followList()
	{
		$classify_arr	=	array();
		$classify_list	=	M('Classify')->select();
		foreach($classify_list as $item)
		{
			$classify_arr[$item['id']]=$item['title'];
		}
		$member_id_get	=	intval(I('get.member_id')) ;
		$city_id	=	intval(I('get.city_id')) ;
		$where['member_id']=	intval(I('get.member_id'));
		$list	=	 M()->table('ht_shop_fans sf, ht_shop sp ')->where('sf.shop_id = sp.id and sf.member_id='.$member_id_get.' and sp.city_id='.$city_id)->field('sf.to_member_id')->order('sf.follow_time')->select();
		//$list = M()->table('ht_shop_service s, ht_shop_service_collection n')->where('s.id = n.shop_service_id and n.member_id='.$member_id)->field('n.id,s.service_id,s.member_id,s.type,n.addtime')->order('n.id desc' )->select();

		if(!$list)
		{
			$arr	=	array(array('id'=>''));
			$this->json_ok($arr);
		}
		foreach($list as $item)
		{
			$member_id[]=$item['to_member_id'];
		}

		//TODO  由于前期推广，所以先把状态控制先取消
		//$map['status']		=	1;
		$map['member_id']	=	array('in',implode(',',$member_id));
		$calllist			=	M('Shop')->field('id,member_id,one_id,industry_class_id,title,one_id')->where($map)->order('id desc')->select();
		$arr	=	array();
		foreach($calllist as $key=>$item)
		{
			$row	=	M('Member')->where(array('id'=>$item['member_id']))->find();
			$item['class_title']=$classify_arr[$item['one_id']];
			$item['face']=$row['face'];
			$item['username']=$row['login_user'];
			$arr[]	=	$item;
			//$calllist[$key]['face']=$row['face'];
		}
		if(count($arr)<1)
		{
			$arr=array(array('id'=>''));
		}
		$this->json_ok($arr);
	}

	/**
	 * 判断会员是否已经关注店铺
	 */
	public function isFollowed(){
		$member_id=	intval(I('get.member_id'));
		$id =	intval(I('get.id')) ;

		$map['shop_id']	=	$id;
		$map['member_id']=	$member_id;
		$fans	=	M('ShopFans')->where($map)->find();

		if($fans)
		{
			$this->json_error('您已经关注过了');
		}else{
			$this->json_ok(true);
		}
	}
	/**
	* 功能：我的收藏
	*/
	public function myCollection()
	{
		$member_id=	intval(I('get.member_id'));
		$list = M()->table('ht_shop_service s, ht_shop_service_collection n')->where('s.id = n.shop_service_id and n.member_id='.$member_id)->field('s.id,s.service_id,s.member_id,s.type,n.addtime')->order('n.id desc' )->select();
		//echo M()->getlastsql();
		if(!$list)
		{
			$list=array(array('id'=>''));
			$this->json_ok($list);
		}
		$arr	=	array();
		foreach($list as $key=>$item)
		{
			switch($item['type'])
			{
				case 0://券
					$row	=	M('Coupon')->field('content,img1,img2,img3,img4,img5,img6')->where(array('id'=>$item['service_id']))->find();
					if(!$row)
					{
						continue;
						$this->json_error('内容不存在1');
					}
					$item['content']	=	$row['content'];
					break;
				case 1://卡
					$row	=	M('Card')->field('content,img1,img2,img3,img4,img5,img6')->where(array('id'=>$item['service_id']))->find();
					if(!$row)
					{
						continue;
						$this->json_error('内容不存在2');
					}
					$item['content']	=	$row['content'];
					break;
				case 2://活动
					$row	=	M('Activity')->field('content,img1,img2,img3,img4,img5,img6')->where(array('id'=>$item['service_id']))->find();
					//echo M('Activity')->getlastsql().'<br />';
					if(!$row)
					{
						continue;
						$this->json_error('内容不存在4');
					}
					$item['content']	=	$row['content'];
					break;
				case 3://新品
					$row	=	M('NewProduct')->field('content,img1,img2,img3,img4,img5,img6')->where(array('id'=>$item['service_id']))->find();
					if(!$row)
					{
						continue;
						$this->json_error('内容不存在3');
					}
					$item['content']	=	$row['content'];
					break;
				case 4://吆喝
					$row	=	M('Call')->field('content,img1,img2,img3,img4,img5,img6')->where(array('id'=>$item['service_id']))->find();
					if(!$row)
					{
						continue;
						$this->json_error('内容不存在5');
					}
					$item['content']	=	$row['content'];
					break;
			}
			if(!empty($row['img6']))$item['img1']=$row['img6'];
			if(!empty($row['img5']))$item['img1']=$row['img5'];
			if(!empty($row['img4']))$item['img1']=$row['img4'];
			if(!empty($row['img3']))$item['img1']=$row['img3'];
			if(!empty($row['img2']))$item['img1']=$row['img2'];
			if(!empty($row['img1']))$item['img1']=$row['img1'];
			if(!isset($item['img1']))$item['img1']='';
			$shop	=	$this->getShop($item['member_id']);
			$item['shop_name']=$shop['title'];
			$item['addtime']=date("Y-m-d H:i:s",$item['addtime']);
			if(empty( $item['title'])){
				$item['title'] = $row['title'] ;
			}
			$arr[]	=	$item;
		}
		if(count($arr)<1)$arr=array(array('id'=>''));
		$this->json_ok($arr);
	}
	/**
	* 功能：删除我的收藏
	*/
	public function delMyCollection()
	{
		$member_id=	intval(I('post.member_id'));
		$id	=	intval(I('post.id'));
		$row	=	M('ShopServiceCollection')->where(array('shop_service_id'=>$id,'member_id'=>$member_id))->find();
		if(!$row)
		{
			$this->json_error('收藏不存在');
		}
		if($row['member_id']!=$member_id)
		{
			$this->json_error('收藏不是您的');
		}
		M('ShopService')->where(array('id'=>$row['shop_service_id']))->setDec('collection_num');
		M('ShopServiceCollection')->where(array('id'=>$row['id']))->delete();
		$this->json_ok(true);
	}
	/**
	* 功能：获取我的优惠(即优惠券多少张，会员卡多少张)
	*/
	public function myDiscount()
	{
		$member_id	=	intval(I('post.member_id'));
		$map['member_id']	=	$member_id;
		$coupon_num	=	M('MemberCoupon')->where($map)->count();
		$card_num	=	M('MemberCard')->where($map)->count();
		$row['coupon_num']=$coupon_num;
		$row['card_num']=$card_num;
		$this->json_ok($row);
	}
	/**
	* 功能：获取可使用优惠券
	*/
	public function plyCoupon()
	{
		$date	=	date("Y-m-d");
		$member_id	=	intval(I('post.member_id'));
		$list = M()->table('ht_coupon c, ht_member_coupon m')->where('c.id = m.coupon_id and m.member_id='.$member_id." and m.valid_start<='".$date."' and m.valid_end>='".$date."'")->field('c.id,c.img1,c.title,c.content,c.member_id,c.type,m.id as member_coupon_id,m.valid_start,m.valid_end,m.use_number')->order('m.id desc' )->select();
		//echo M()->getlastsql();exit;
		if(!$list)$list=array();
		foreach($list as $key=>$item)
		{
			$shop=$this->getShop($item['member_id']);
			$list[$key]['shop_id']=$shop['id'];
			$list[$key]['shopname']=$shop['title'];
		}
		if(count($list)<1)$list=array(array('id'=>''));
		$this->json_ok($list);
	}
	/**
	* 功能：获取不可使用优惠券
	*/
	public function plyNoCoupon()
	{
		$date	=	date("Y-m-d");
		$member_id	=	intval(I('post.member_id'));
		$list = M()->table('ht_coupon c, ht_member_coupon m')->where('c.id = m.coupon_id and m.member_id='.$member_id." and (valid_start<'".$date."' || valid_end>'".$date."')")->field('c.id,c.img1,c.title,c.content,c.member_id,c.type,m.id as member_coupon_id,m.valid_start,m.valid_end,m.use_number')->order('m.id desc' )->select();
		if(!$list)$list=array();
		foreach($list as $key=>$item)
		{
			$shop=$this->getShop($item['member_id']);
			$list[$key]['shop_id']=$shop['id'];
			$list[$key]['shopname']=$shop['title'];
		}
		if(count($list)<1)$list=array(array('id'=>''));
		$this->json_ok($list);
	}
	/**
	* 功能：获取优惠券使用详情
	*/
	public function plyCouponDetail()
	{
		$coupon_id	=	intval(I('get.coupon_id'));//优惠券ID
		$member_coupon_id=intval(I('get.member_coupon_id'));//会员优惠券ID
		$coupon	=	M('Coupon')->field('id,title,content,img1,valid_start,valid_end,member_id')->where(array('id'=>$coupon_id))->find();
		$member_coupon	=	M('MemberCoupon')->field('id,card_number')->where(array('id'=>$member_coupon_id))->find();
		$coupon['card_number']	=	$member_counon['card_number'];
		
		$shop=$this->getShop($coupon['member_id']);
		$coupon['shop_id']=$shop['id'];
		$coupon['shopname']=$shop['title'];
		//获取使用详情记录
		$list	=	M('MemberCouponDetail')->where(array('member_coupon_id'=>$member_coupon_id))->order('id desc')->select();
		foreach($list as $key=>$item)
		{
			$list[$key]['ply_time']=date("Y-m-d",$item['ply_time']);
		}
		$this->json_ok(array('coupon'=>$coupon,'list'=>$list));
	}
	/**
	* 功能：会员使用优惠券
	*/
	public function memberPlyCoupon()
	{
		$member_id	=	intval(I('post.member_id'));
		$card_number=	I('post.card_number');
		$row	=	M('MemberCoupon')->where(array('card_number'=>$card_number))->find();
		if(!$row)
		{
			$this->json_error('卡号不存在');
		}
		if($member_id!=$row['member_id'])
		{
			$this->json_error('此卡不属于该会员');
		}
		$data['member_coupon_id']=$row['id'];
		$data['ply_time']	=	time();
		$flag	=	M('MemberCouponDetail')->add($data);
		if($flag===false)
		{
			$this->json_error('写入失败');
		}
		$this->json_ok(true);
	}
	/**
	* 功能：获取我的会员卡
	*/
	public function getMyMemberCardList()
	{
		$member_id	=	intval(I('post.member_id'));
		$list = M()->table('ht_member_card m, ht_card c')->where('c.id = m.card_id and m.member_id='.$member_id)->field('c.id,c.img1,c.title,c.discount,c.member_id,m.id as member_card_id')->order('m.id desc' )->select();
		if(!$list)$list=array(array('id'=>''));
		$this->json_ok($list);
	}
	/**
	* 功能：获取我的会员卡使用详情
	*/
	public function getMyMemberCardDetail()
	{
		$card_id	=	intval(I('get.card_id'));//会员卡ID
		$member_card_id=intval(I('get.member_card_id'));//会员的会员卡ID
		$card	=	M('Coupon')->field('id,title,img1')->where(array('id'=>$card_id))->find();
		$member_card=	M('MemberCard')->field('id,card_number')->where(array('id'=>$member_card_id))->find();
		$coupon['card_number']	=	$member_counon['card_number'];
		//获取使用详情记录
		$list	=	M('MemberCardDetail')->where(array('member_card_id'=>$member_card_id))->order('id desc')->select();
		foreach($list as $key=>$item)
		{
			$list[$key]['ply_time']=date("Y-m-d",$item['ply_time']);
		}
		$this->json_ok(array('card'=>$card,'list'=>$list));
	}
	/**
	* 功能：会员使用会员卡
	*/
	public function memberPlyCard()
	{
		$member_id	=	intval(I('post.member_id'));
		$card_number=	I('post.card_number');
		$row	=	M('MemberCard')->where(array('card_number'=>$card_number))->find();
		if(!$row)
		{
			$this->json_error('卡号不存在');
		}
		if($member_id!=$row['member_id'])
		{
			$this->json_error('此卡不属于该会员');
		}
		$data['member_card_id']=$row['id'];
		$data['ply_time']	=	time();
		$flag	=	M('MemberCardDetail')->add($data);
		if($flag===false)
		{
			$this->json_error('写入失败');
		}
		$this->json_ok(true);
	}
	/**
	* 功能：意见反馈
	*/
	public function addOpinion()
	{
		$data['member_id']	=	intval(I('post.member_id'));
		$data['tel']	=	I('post.tel');
		$data['content']	=	I('post.content');
		$data['city_id']	=	intval(I('post.city_id'));
		$data['terminal']	=	intval(I('post.terminal'));
		//$data['addtime']	=	time();
		$insert_id	=	M('Opinion')->add($data);
		if($insert_id===false)
		{
			$this->json_error('添加失败');
		}
		$filelist	=	$this->uploads();
		$data		=	array();
		$data['opinion_id']=$insert_id;
		foreach($filelist as $item)
		{
			$data['img_url']	=	$item['savepath'].$item['savename'];
			M('OpinionImg')->add($data);
		}
		$this->json_ok(true);
	}
	/**
	* 功能：举报
	*/
	public function addReport()
	{
		$data['member_id']	=	intval(I('post.member_id'));
		$data['shop_id']	=	intval(I('post.shop_id'));
		$data['reason']		=	intval(I('post.reason'));
		$data['content']	=	I('post.content');
		$data['terminal']	=	intval(I('post.terminal'));
		$shop	=	M('Shop')->field('member_id')->where(array('id'=>$data['shop_id']))->find();
		if(!$shop)
		{
			$this->json_error('店铺不存在');
		}
		$data['to_member_id']=	$shop['member_id'];
		$flag	=	M('Report')->add($data);
		if($flag===false)
		{
			$this->json_error('举报失败');
		}
		$this->json_ok(true);
	}
	/**
	* 功能：修改密码
	*/
	public function upPass()
	{
		$member_id	=	intval(I('post.member_id'));//会员ID
		$old_pass	=	I('post.old_pass');//老密码
		$new_pass	=	I('post.new_pass');//新密码
		$row	=	M('Member')->where(array('id'=>$member_id))->find();
		if(!$row)
		{
			$this->json_error('会员不存在');
		}
		if($row['login_pass']!=md5($old_pass))
		{
			$this->json_error('原密码不正确');
		}
		$data['login_pass']	=	md5($new_pass);
		$flag	=	M('Member')->where(array('id'=>$member_id))->save($data);
		if($flag===false)
		{
			$this->json_error('修改密码失败');
		}
		$this->json_ok(true);
	}
	/**
	* 功能：忘记密码
	*/
	public function forgetpass()
	{
		$login_user	=	I('post.login_user');
		$code		=	getRandomNum(6);
		//if($row)
		//{
			$map['login_user']	=	$login_user;
			$data['forgetcode']	=	$code;
			M('Member')->where($map)->save($data);
		//}
		$_account = 'cf_zcsd';
		$_password = '69wZ74';
		$_url = 'http://106.ihuyi.cn/webservice/sms.php?method=Submit&format=json';
		$data = array(
			'account' => $_account,
			'password' => $_password,
			'mobile' => $login_user,
			'content' => '亲爱的客户大大，您的账户正在进行密码重置，这是给您的验证码【'.$code.'】，请勿向任何人提供您收到的短信校验码',
		);
		$res_json = curlPost($_url, $data, 5);
		$res = json_decode($res_json,1);
		if($res['code']==2)
		{
			$this->json_ok(true);
		}
		$this->json_error($res['msg']);
	}
	/**
	* 功能：验证码是否通过
	*/
	public function validatecode()
	{
		$login_user	=	I('post.login_user');
		$code		=	I('post.code');
		$map['login_user']	=	$login_user;
		$row	=	M('Member')->where($map)->find();
		if(!$row)
		{
			$this->json_error('会员不存在');
		}
		if($row['forgetcode']!=$code)
		{
			$this->json_error('验证码不正确');
		}
		$this->json_ok(true);
	}
	/**
	* 功能：重置密码
	*/
	public function resetpass()
	{
		$login_user	=	I('post.login_user');
		$code		=	I('post.code');
		$new_pass	=	I('post.login_pass');
		$member		=	M('Member')->where(array('login_user'=>$login_user))->find();
		if(!$member)
		{
			$this->json_error('会员不存在');
		}
		if($code!=$member['forgetcode'])
		{
			$this->json_error('提供的验证码不正确');
		}
		$data['login_pass']	=	md5($new_pass);
		$flag	=	M('Member')->where(array('login_user'=>$login_user))->save($data);
		if($flag===false)
		{
			$this->json_error('服务器异常');
		}
		$this->json_ok(true);
	}
	/**
	* 功能：获取我发布的吆喝列表
	*/
	public function getMyCallList()
	{
		$member_id=	intval(I('post.member_id'));
		$page	=	intval(I('post.page'));
		if($page<1)$page=1;
		$list	=	M('ShopService')->field('id,title,img1,img2,img3,img4,img5,img6,type,service_id,zan_num,comment_num,collection_num')->where(array('member_id'=>$member_id))->order('id desc')->limit(($page-1)*20,20)->select();
		if(!$list)$list=array();
		foreach($list as $item)
		{switch($item['type'])
			{
				case 1://会员卡
					$service	=	M('Card')->field('content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 2://活动
					$service	=	M('Activity')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 3://新品
					$service	=	M('NewProduct')->field('img1,img2,img3,img4,img5,img6,title as content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 0://优惠券
					$service	=	M('Coupon')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 4://纯吆喝
					$service	=	M('Call')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
			}
			if(empty($item['content']))$item['content']=$service['content'];
			if(!empty($item['img6']))$item['img']	=	$item['img6'];
			if(!empty($item['img5']))$item['img']	=	$item['img5'];
			if(!empty($item['img4']))$item['img']	=	$item['img4'];
			if(!empty($item['img3']))$item['img']	=	$item['img3'];
			if(!empty($item['img2']))$item['img']	=	$item['img2'];
			if(!empty($item['img1']))$item['img']	=	$item['img1'];
			if(!isset($item['img']))
			{
				if(!empty($service['img6']))$item['img']=	$service['img6'];
				if(!empty($service['img5']))$item['img']=	$service['img5'];
				if(!empty($service['img4']))$item['img']=	$service['img4'];
				if(!empty($service['img3']))$item['img']=	$service['img3'];
				if(!empty($service['img2']))$item['img']=	$service['img2'];
				if(!empty($service['img1']))$item['img']=	$service['img1'];
			}
			if(!isset($item['img']))$item['img']='';
		}
		$this->json_ok($list);
	}
	/**
	* 功能：获取我发布的优惠券
	*/
	public function getMyCouponList()
	{
		$member_id=	intval(I('post.member_id'));
		$page	=	intval(I('post.page'));
		if($page<1)$page=1;
		$list	=	M('Coupon')->field('id,type,title,content,img1')->where(array('member_id'=>$member_id))->order('id desc')->limit(($page-1)*20,20)->select();
		if(!$list)$list=array(array('id'=>''));
		$this->json_ok($list);
	}
	/**
	* 功能：获取我发布的会员卡
	*/
	public function getMyCardList()
	{
		$member_id=	intval(I('post.member_id'));
		$page	=	intval(I('post.page'));
		if($page<1)$page=1;
		$list	=	M('Card')->field('id,type,title,discount,content,img1')->where(array('member_id'=>$member_id))->order('id desc')->limit(($page-1)*20,20)->select();
		if(!$list)$list=array(array('id'=>''));
		$this->json_ok($list);
	}
	/**
	* 功能：获取我发布的新品列表
	*/
	public function getMyNewProductList()
	{
		$member_id=	intval(I('post.member_id'));
		$page	=	intval(I('post.page'));
		if($page<1)$page=1;
		$list	=	M('Card')->field('id,title,price,day,img1')->where(array('member_id'=>$member_id))->order('id desc')->limit(($page-1)*20,20)->select();
		if(!$list)$list=array(array('id'=>''));
		$this->json_ok($list);
	}
	/**
	* 功能：获取我发布的活动列表
	*/
	public function getMyActivityList()
	{
		$member_id=	intval(I('post.member_id'));
		$page	=	intval(I('post.page'));
		if($page<1)$page=1;
		$list	=	M('Activity')->field('id,title,content,img1')->where(array('member_id'=>$member_id))->order('id desc')->limit(($page-1)*20,20)->select();
		if(!$list)$list=array(array('id'=>''));
		$this->json_ok($list);
	}
	/**
	* 功能：修改用户昵称
	*/
	public function upNickname()
	{
		$member_id	=	intval(I('post.member_id'));//会员ID
		$nickname	=	I('post.nickname');//昵称
		$row	=	M('Member')->where(array('id'=>$member_id))->find();
		if(!$row)
		{
			$this->json_error('会员不存在');
		}
		$data['nickname']	=	$nickname;
		$row	=	M('Personal')->where(array('member_id'=>$member_id))->find();
		if(!$row)
		{
			$data['member_id']=$member_id;
			M('Personal')->add($data);
		}
		else
		{
			M('Personal')->where(array('member_id'=>$member_id))->save($data);
		}
		$this->json_ok(true);
	}
	/**
	* 功能：修改性别
	*/
	public function upSex()
	{
		$member_id	=	intval(I('post.member_id'));//会员ID
		$sex	=	intval(I('post.sex'));//0男 1女
		$row	=	M('Member')->where(array('id'=>$member_id))->find();
		if(!$row)
		{
			$this->json_error('会员不存在');
		}
		$data['sex']	=	$sex;
		$row	=	M('Personal')->where(array('member_id'=>$member_id))->find();
		if(!$row)
		{
			$data['member_id']=$member_id;
			M('Personal')->add($data);
		}
		else
		{
			M('Personal')->where(array('member_id'=>$member_id))->save($data);
		}
		$this->json_ok(true);
	}
	/**
	* 功能：获取我的粉丝列表
	*/
	public function getMyFansList()
	{
		$member_id=	intval(I('post.member_id'));
		$page	=	intval(I('post.page'));
		if($page<1)$page=1;
		$count	=	M()->table('ht_member m, ht_shop_fans f')->where('m.id = f.to_member_id and f.to_member_id='.$member_id)->field('f.member_id')->count('*');
		$list = M()->table('ht_member m, ht_shop_fans f')->where('m.id = f.to_member_id and f.to_member_id='.$member_id)->field('f.member_id')->order('f.id desc' )->select();
		//echo M()->getlastsql();
		if(!$list)$list=array();
		$arr	=	array();
		foreach($list as $key=>$item)
		{
			$member	=	M('Member')->field('face,login_user')->where(array('id'=>$item['member_id']))->find();
			$item['face']=$member['face'];
			$row	=	M('Personal')->where(array('member_id'=>$item['member_id']))->field('nickname')->find();
			if(!$row)
			{
				$item['nickname']=$member['login_user'];
			}
			else
			{
				$item['nickname']=$row['nickname'];
			}
			$arr[]=$item;
		}
		if(count($arr)<1)$arr=array(array('id'=>''));
		$this->json_ok_page($arr, $page, $count);
	}
	/**
	* 功能：判断是否中奖
	*/
	public function getWinnig()
	{
		$member_id=	intval(I('post.member_id'));
		$member	=	M('Member')->where(array('id'=>$member_id))->find();
		if(!$member)
		{
			$this->json_error('会员不存在');
		}
		$person	=	M('Personal')->where(array('member_id'=>$member_id))->find();;
		if(empty($person['nickname']))$person['nickname']=$member['login_user'];
		$date	=	date("Y-m-d");
		$map['_string']=" status=1 and start_date<='".$date."' and end_date>='".$date."'";
		$row	=	M('Amusing')->where($map)->order('id desc')->find();
		if(!$row)
		{
			$this->json_error('奖品尚未开放');
		}
		$randnum=	rand(0,1000);//随机数
		$arr	=	array();
		$prizerow=	array();//中奖数组
		$flag	=	false;
		$list	=	M('Prize')->where(array('aid'=>$row['id']))->select();
		foreach($list as $item)
		{
			if($randnum<=$item['rate'])
			{
				$flag=true;
				$prizerow=$item;
				break;
			}
		}
		if($flag==false)
		{
			$this->json_error('您没有中奖');
		}
		$count	=	M('PrizeList')->where(array('pid'=>$prizerow['id']))->count();
		if($count+1>$prizerow['num'])
		{
			$this->json_error('您没有中奖');
		}
		$data				=	array();
		$data['aid']		=	$prizerow['aid'];
		$data['member_id']	=	$member_id;
		$data['username']	=	$person['nickname'];
		$data['pid']		=	$prizerow['id'];
		$data['ptitle']		=	$prizerow['ptitle'];
		$data['card_number']=	$this->getWinCardNumber();
		$data['addtime']	=	time();
		$insert_id	=	M('PrizeList')->add($data);
		if($insert_id<1)
		{
			$this->json_error('您没有中奖');
		}
		$this->json_ok($prizerow);
	}
	/**
	* 功能：获取用户中奖列表
	*/
	public function getWinGoodsList()
	{
		$member_id=	intval(I('post.member_id'));
		$page	=	intval(I('post.page'));
		if($page<1)$page=1;
		$list	=	M('PrizeList')->where(array('member_id'=>$member_id))->order('id desc')->limit(($page-1)*20,20)->select();
		if(!$list)$list=array(array('id'=>''));
		$this->json_ok($list);
	}
	/**
	* 功能：获取我的服务列表
	*/
	public function getMyServiceList()
	{
		$member_id=	intval(I('post.member_id'));
		$page	=	intval(I('post.page'));
		if($page<1)$page=1;
		$count	=	M('ShopService')->where(array('member_id'=>$member_id))->order('id desc')->count('*');
		$list	=	M('ShopService')->where(array('member_id'=>$member_id))->order('id desc')->limit(($page-1)*20,20)->select();
		if(!$list)$list=array();
		$arr	=	array();
		foreach($list as $key=>$item)
		{
			switch($item['type'])
			{
				case 1://会员卡
					$service	=	M('Card')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 2://活动
					$service	=	M('Activity')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 3://新品
					$service	=	M('NewProduct')->field('img1,img2,img3,img4,img5,img6,title as content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 0://优惠券
					$service	=	M('Coupon')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 4://纯吆喝
					$service	=	M('Call')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
			}
			if(empty($item['content']))$item['content']=$service['content'];
			if(!empty($item['img6']))$item['img']	=	$item['img6'];
			if(!empty($item['img5']))$item['img']	=	$item['img5'];
			if(!empty($item['img4']))$item['img']	=	$item['img4'];
			if(!empty($item['img3']))$item['img']	=	$item['img3'];
			if(!empty($item['img2']))$item['img']	=	$item['img2'];
			if(!empty($item['img1']))$item['img']	=	$item['img1'];
			if(!isset($item['img']))
			{
				if(!empty($service['img6']))$item['img']=	$service['img6'];
				if(!empty($service['img5']))$item['img']=	$service['img5'];
				if(!empty($service['img4']))$item['img']=	$service['img4'];
				if(!empty($service['img3']))$item['img']=	$service['img3'];
				if(!empty($service['img2']))$item['img']=	$service['img2'];
				if(!empty($service['img1']))$item['img']=	$service['img1'];
			}
			if(!isset($item['img']))$item['img']='';
			/*
			if(!empty($service['img6']))$item['img']		=	$service['img6'];
			if(!empty($service['img5']))$item['img']		=	$service['img5'];
			if(!empty($service['img4']))$item['img']		=	$service['img4'];
			if(!empty($service['img3']))$item['img']		=	$service['img3'];
			if(!empty($service['img2']))$item['img']		=	$service['img2'];
			if(!empty($service['img1']))$item['img']		=	$service['img1'];
			if(!isset($item['img']))$item['img']='';*/
			$item['addtime']=date("Y-m-d H:i:s",$item['addtime']);
			$arr[]=$item;
			//$list[$key]['img1']=$row['img1'];
			//$list[$key]['content']=$row['content'];
			//$list[$key]['addtime']=date("Y-m-d H:i:s",$item['addtime']);
		}
		if(count($arr)<1)$arr=array(array('id'=>''));
		$this->json_ok_page($arr, $page, $count);
	}
	/**
	* 功能：获取个人基本信息
	*/
	public function getUserBase()
	{
		$member_id	=	intval(I('post.member_id'));
		$map['id']	=	$member_id;
		$row		=	M('Member')->where($map)->find();
		if(!$row)
		{
			$this->json_error('会员不存在');
		}
		$arr['login_user']	=	$row['login_user'];
		$arr['face']		=	$row['face'];
		$row	=	M('Personal')->where(array('member_id'=>$member_id))->find();
		if($row)
		{
			$arr['nickname']=	$row['nickname'];
			$arr['sex']		=	$row['sex'];//0男1女
		}
		else
		{
			$arr['nickname']=	'';
			$arr['sex']		=	0;//0男1女
		}
		$this->json_ok($arr);
	}
	/**
	* 功能：获取商家基本信息
	*/
	public function getShopBase()
	{
		$member_id	=	intval(I('post.member_id'));
		$map['id']	=	$member_id;
		$row		=	M('Member')->where($map)->find();
		if(!$row)
		{
			$this->json_error('会员不存在');
		}
		$field		=	'id,title,star,one_id';
		$shop		=	M('Shop')->field($field)->where(array('member_id'=>$member_id))->find();
		if(!$shop)
		{
			$this->json_error('商家不存在');
		}
		$class		=	M('Classify')->where(array('id'=>$shop['one_id']))->find();
		if(!$class)
		{
			$this->json_error('商家尚未选择分类');
		}
		$arr['face']=	$row['face'];
		$arr['title']=	$shop['title'];
		$arr['star']=	$shop['star'];
		$arr['_class']=	$class['title'];
		$arr['shop_id']=$shop['id'];
		$this->json_ok($arr);
	}
	/**
	* 功能：搜索商家及相关吆喝
	*/
	public function getSearchShopCallList()
	{
		$keywords	=	I('get.keywords');
		$city_id	=	I('post.city_id');
		$map['city_id']=$city_id;
		if($city_id<1)
		{
			$this->json_error('请选择城市');
		}
		if(empty($keywords))
		{
			$this->json_error('请输入关键字');
		}
		$map['_string']=' title like "%'.$keywords.'%"';
		//TODO 为推广需呀，暂时先不加状态控制
		//$map['status']	=	1 ;
		$shoplist	=	M('Shop')->field('id,member_id,title')->where($map)->order('id asc')->select();
		$arr		=	array();
		foreach($shoplist as $item)
		{
			$member	=	M('Member')->where(array('id'=>$item['member_id']))->find();
			$item['face']=$member['face'];
			$arr[]	=	$item;
		}
		if(count($arr)<1)$arr=array(array('id'=>''));
		$shoplist	=	$arr;
		//搜索吆喝列表
		$calllist	=	array();
		$arr		=	array();
		$map['_string']=' title like "%'.$keywords.'%"';
		$list		=	M('ShopService')->where($map)->order('id desc')->select();
		foreach($list as $item)
		{
			$shop=$this->getShop($item['member_id']);
			$item['shop_title']=$shop['title'];//店铺名称
			$item['shop_id']=$shop['id'];//店铺ID
			switch($item['type'])
			{
				case 1://会员卡
					$service	=	M('Card')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 2://活动
					$service	=	M('Activity')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 3://新品
					$service	=	M('NewProduct')->field('img1,img2,img3,img4,img5,img6,title as content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 0://优惠券
					$service	=	M('Coupon')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
				case 4://纯吆喝
					$service	=	M('Call')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				break;
			}
			//$item['content']	=	$service['content'];

			if(!empty($service['img6']))$item['img']=	$service['img6'];
			if(!empty($service['img5']))$item['img']=	$service['img5'];
			if(!empty($service['img4']))$item['img']=	$service['img4'];
			if(!empty($service['img3']))$item['img']=	$service['img3'];
			if(!empty($service['img2']))$item['img']=	$service['img2'];
			if(!empty($service['img1']))$item['img']=	$service['img1'];

			if(!isset($item['img']))
			{
				if(!empty($item['img6']))$item['img']	=	$item['img6'];
				if(!empty($item['img5']))$item['img']	=	$item['img5'];
				if(!empty($item['img4']))$item['img']	=	$item['img4'];
				if(!empty($item['img3']))$item['img']	=	$item['img3'];
				if(!empty($item['img2']))$item['img']	=	$item['img2'];
				if(!empty($item['img1']))$item['img']	=	$item['img1'];
			}
			if(!isset($item['img']))$item['img']='';
			if(empty($item['content']))$item['content']=$service['content'];
			$arr[]=$item;
		}
		if(count($arr)<1)$arr=array(array('id'=>''));
		$calllist	=	$arr;
		$data['shoplist']=$shoplist;
		$data['calllist']=$calllist;
		$this->json_ok($data);
	}
	/**
	* 功能：删除吆喝
	*/
	public function delCall()
	{
		$id			=	intval(I('post.id'));
		$member_id	=	intval(I('post.member_id'));//会员ID
		$row		=	M('ShopService')->where(array('id'=>$id))->find();
		if(!$row)
		{
			$this->json_error('吆喝不存在');
		}
		if($row['member_id']!=$member_id)
		{
			$this->json_error('吆喝不是您的');
		}
		M('ShopService')->where(array('id'=>$id))->delete();
		$this->json_ok(true);
	}
	/**
	* 功能：删除优惠券
	*/
	public function delCoupon()
	{
		$id	=	intval(I('post.id'));
		$member_id=intval(I('post.member_id'));
		$row=	M('Coupon')->where(array('id'=>$id))->find();
		if(!$row)
		{
			$this->json_error('优惠券不存在');
		}
		if($row['member_id']!=$member_id)
		{
			$this->json_error('优惠券不是您的');
		}
		M('Coupon')->where(array('id'=>$id))->delete();
		M('MemberCoupon')->where(array('card_id'=>$id))->delete();
		$this->json_ok(true);
	}
	/**
	* 功能：删除会员卡
	*/
	public function delCard()
	{
		$id	=	intval(I('post.id'));
		$member_id=intval(I('post.member_id'));
		$row=	M('Card')->where(array('id'=>$id))->find();
		if(!$row)
		{
			$this->json_error('会员卡不存在');
		}
		if($row['member_id']!=$member_id)
		{
			$this->json_error('会员卡不是您的');
		}
		M('Card')->where(array('id'=>$id))->delete();
		M('MemberCard')->where(array('card_id'=>$id))->delete();
		$this->json_ok(true);
	}
	/**
	* 功能：删除新品
	*/
	public function delNewProduct()
	{
		$id	=	intval(I('post.id'));
		$member_id=intval(I('post.member_id'));
		$row=	M('NewProduct')->where(array('id'=>$id))->find();
		if(!$row)
		{
			$this->json_error('新品不存在');
		}
		if($row['member_id']!=$member_id)
		{
			$this->json_error('新品不是您的');
		}
		M('NewProduct')->where(array('id'=>$id))->delete();
		$this->json_ok(true);
	}
	/**
	* 功能：删除活动
	*/
	public function delActivity()
	{
		$id	=	intval(I('post.id'));
		$member_id=intval(I('post.member_id'));
		$row=	M('Activity')->where(array('id'=>$id))->find();
		if(!$row)
		{
			$this->json_error('活动不存在');
		}
		if($row['member_id']!=$member_id)
		{
			$this->json_error('活动不是您的');
		}
		M('Activity')->where(array('id'=>$id))->delete();
		$this->json_ok(true);
	}
	/**
	* 功能：我的消息总记录数 
	*/
	public function mySms()
	{
		$member_id	=	intval(I('post.member_id'));
		//获取点赞总记录数
		$map	=	array();
		$map['member_id']=$member_id;
		$map['is_read']	=	1;
		$zannum	=	M('ShopServiceZan')->where($map)->count();
		//获取我的吆喝评论数
		$map	=	array();
		$map['is_read']	=	1;
		$map['_string']	=	' member_id="'.$member_id.'" or to_member_id="'.$member_id.'"';
		$callcommentnum=M('ShopServiceComment')->where($map)->count();
		//获取店铺的评论数
		$map	=	array();
		$map['is_read']	=	1;
		$map['_string']	=	' member_id="'.$member_id.'" or to_member_id="'.$member_id.'"';
		$shopcommentnum	=	M('ShopComment')->where($map)->count();

		//我的消息
		$map	=	array();
		$map['is_read']	=	1;
		$map['_string']	=	' to_member_id="'.$member_id ;
		$smsnum	=	M('Sms')->where($map)->count();

		$data['zannum']	=	$zannum;
		$data['commentnum']	=	$callcommentnum+$shopcommentnum;
		$data['$smsnum']=	$smsnum ;
		$this->json_ok($data);
	}
	/**
	* 功能：我的消息列表
	*/
	public function getMySms()
	{
		$member_id	=	intval(I('post.member_id'));

		$map['_string']='to_member_id="'.$member_id.'"';
		$list	=	M('Sms')->where($map)->order('id desc')->select();

		if(!$list)
		{
			$this->json_ok(array(array('id'=>'')));
		}
		$arr	=	array();
		foreach($list as $item)
		{

			$item['addtime']	=	date('Y-m-d H:i',$item['addtime']);
			$member		=	M('Member')->where(array('id'=>$item['member_id']))->find();
			$to_member		=	M('Member')->where(array('id'=>$item['to_member_id']))->find();
			$person		=	M('Personal')->where(array('member_id'=>$item['member_id']))->find();
			$to_person		=	M('Personal')->where(array('member_id'=>$item['to_member_id']))->find();

			$item['face']		=	$member['face'];
			$item['nickname']	=	$person['nickname'];

			$item['to_face']		=	$to_member['face'];
			$item['to_nickname']	=	$to_person['nickname'];
			$arr[]=$item;

			//更新is_read为0
			$data['is_read'] 	=	0 ;
			M('Sms')->where(array('id'=>$item['id']))->save($data);
		}
		$this->json_ok($arr);
	}

	/**
	 * 功能：我的点赞列表
	 */
	public function getMyZanList()
	{
		$member_id	=	intval(I('post.member_id'));
		$member		=	M('Member')->where(array('id'=>$member_id))->find();
		$person		=	M('Personal')->where(array('member_id'=>$member_id))->find();
		if(!$person)$person['nickname']='吆喝'.$member_id;
		$map['_string']='member_id="'.$member_id.'"';
		$list	=	M('ShopServiceZan')->where($map)->order('id desc')->select();
		if(!$list)
		{
			$this->json_ok(array(array('id'=>'')));
		}
		$arr	=	array();
		foreach($list as $item)
		{
			$row	=	M('ShopService')->where(array('id'=>$item['shop_service_id']))->find();
			switch($row['type'])
			{
				case 1://会员卡
					$service	=	M('Card')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$row['service_id']))->find();
					break;
				case 2://活动
					$service	=	M('Activity')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$row['service_id']))->find();
					break;
				case 3://新品
					$service	=	M('NewProduct')->field('img1,img2,img3,img4,img5,img6,title as content')->where(array('id'=>$row['service_id']))->find();
					break;
				case 0://优惠券
					$service	=	M('Coupon')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$row['service_id']))->find();
					break;
				case 4://纯吆喝
					$service	=	M('Call')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$row['service_id']))->find();
					break;
			}
			//$item['content']	=	$service['content'];
			if(!empty($item['img6']))$item['img']	=	$item['img6'];
			if(!empty($item['img5']))$item['img']	=	$item['img5'];
			if(!empty($item['img4']))$item['img']	=	$item['img4'];
			if(!empty($item['img3']))$item['img']	=	$item['img3'];
			if(!empty($item['img2']))$item['img']	=	$item['img2'];
			if(!empty($item['img1']))$item['img']	=	$item['img1'];
			if(!isset($item['img']))
			{
				if(!empty($service['img6']))$item['img']=	$service['img6'];
				if(!empty($service['img5']))$item['img']=	$service['img5'];
				if(!empty($service['img4']))$item['img']=	$service['img4'];
				if(!empty($service['img3']))$item['img']=	$service['img3'];
				if(!empty($service['img2']))$item['img']=	$service['img2'];
				if(!empty($service['img1']))$item['img']=	$service['img1'];
			}
			if(!isset($item['img']))$item['img']='';
			if(empty($item['content']))$item['content']=$service['content'];
			$item['addtime']	=	date('Y-m-d H:i',$item['addtime']);
			$item['face']		=	$member['face'];
			$item['nickname']	=	$person['nickname'];
			$arr[]=$item;

			//更新is_read为0
			$data['is_read'] 	=	0 ;
			M('ShopServiceZan')->where(array('id'=>$item['id']))->save($data);
		}
		$this->json_ok($arr);
	}

	/**
	* 功能：获取我的店铺评论
	*/
	public function getMyShopCommentList()
	{
		$member_id	=	intval(I('post.member_id'));
		$map['_string']='member_id="'.$member_id.'" or to_member_id="'.$member_id.'"';
		$list	=M('ShopComment')->where($map)->order('id desc')->select();
		if(!$list)
		{
			$this->json_ok(array(array('id'=>'')));
		}
		$arr	=	array();
		foreach($list as $item)
		{
			//获取会员信息
			$row=M('Member')->where(array('id'=>$item['member_id']))->find();
			$item['face']=$row['face'];
			//获取会员昵称
			$person=M('Personal')->where(array('member_id'=>$item['member_id']))->find();
			if(!$person)$person['nickname']='吆喝'.$item['member_id'];
			$arr[]=array('id'=>$item['id'],'face'=>$item['face'],'nickname'=>$person['nickname'],'star'=>$item['star'],'content'=>$item['content'],'addtime'=>date('Y-m-d H:i',$item['addtime']));

			//更新is_read为0
			$data['is_read'] 	=	0 ;
			M('ShopComment')->where(array('id'=>$item['id']))->save($data);
		}
		if(count($arr)<1)
		{
			$this->json_ok(array(array('id'=>'')));
		}
		$this->json_ok($arr);
	}
	/**
	* 功能：获取我的吆喝评论
	*/
	public function getMyCallCommentList()
	{
		$member_id	=	intval(I('get.member_id'));
		$is_del		= 	intval(I('get.is_del'));
		$map['_string']='member_id="'.$member_id.'" or to_member_id="'.$member_id.'"';
		$list	=M('ShopServiceComment')->where($map)->order('id desc')->select();
		if(!$list)
		{
			$this->json_ok(array(array('id'=>'')));
		}
		$arr	=	array();
		foreach($list as $item)
		{
			//获取会员信息
			$row=M('Member')->where(array('id'=>$item['member_id']))->find();
			$item['face']=$row['face'];
			//获取会员昵称
			$person=M('Personal')->where(array('member_id'=>$item['member_id']))->find();
			if(!$person)$person['nickname']='吆喝'.$item['member_id'];
			$arr[]=array('id'=>$item['id'],'face'=>$item['face'],'nickname'=>$person['nickname'],'is_anonymous'=>$item['is_anonymous'],'content'=>$item['content'],'addtime'=>date('Y-m-d H:i',$item['addtime']));

			if ($is_del == "Y") {
				//更新is_read为0
				$data['is_read'] = 0;
				M('ShopServiceComment')->where(array('id' => $item['id']))->save($data);
			}
		}
		if(count($arr)<1)
		{
			$this->json_ok(array(array('id'=>'')));
		}
		$this->json_ok($arr);
	}
	/**
	* 功能：获取中奖码券
	*/
	private function getWinCardNumber()
	{
		$card_number	=	getRandomNum(12);
		$map['card_number']	=	$data['card_number'];
		$row	=	M('PrizeList')->where($map)->find();
		if($row)return $this->getWinCardNumber();
		return $card_number;
	}
	/**
	* 功能：新增优惠券卡号时获取
	*/
	private function getCouponCardNumber()
	{
		$data['card_number']=	getRandomNum(12);//领取卡号
		$map['card_number']	=	$data['card_number'];
		$row	=	M('MemberCoupon')->where($map)->find();
		if($row)return $this->getCouponCardNumber();
		return $data['card_number'];
	}
	/**
	* 功能：新增会员卡号时获取
	*/
	private function getMemberCardNumber()
	{
		$data['card_number']=	getRandomNum(9);//领取卡号
		$map['card_number']	=	$data['card_number'];
		$row	=	M('MemberCard')->where($map)->find();
		if($row)return $this->getMemberCardNumber();
		return $data['card_number'];
	}
	/**
	* 功能：获取商家
	*/
	private function getShop($member_id)
	{
		$row	=	M('Shop')->where(array('member_id'=>$member_id))->find();
		if(!$row)
		{
			$this->json_error('商家不存在');
		}
		return $row;
	}
	
	private function getShopById($shop_id)
	{
		$row = M('Shop')->where(array('id'=>$shop_id))->find() ;
		if(!$row)
		{
			$this->json_error("商家不存在") ;
		}
		return $row ;
	}

	private function json_ok($data)
	{
		$arr['status']['code']=0;
		$arr['status']['message']='a';
		$arr['data']=$data;
		echo json_encode($arr);
		exit;
	}
	private function json_ok_page($data, $page, $totalNumber){
		$arr['status']['code']=0;
		$arr['status']['message']='a';
		$arr['page']=$page ;
		$arr['totalNumber'] = $totalNumber ;
		$arr['pageNumber'] = intval($totalNumber/20 + 1) ;
		$arr['data']=$data;
		echo json_encode($arr);
		exit;
	}

	private function json_error($message)
	{
		$arr['status']['code']=1;
		$arr['status']['message']=$message;
		echo json_encode($arr);
		exit;
	}
	/**
	* 功能：上传附件
	*/
	private function uploads()
	{
		$config['maxSize']	=	C('MAXSIZE') ;// 设置附件上传大小
		$config['exts']		=	C('ALLOWEXTS') ;// 设置附件上传大小
		$config['rootPath']	=	C('SAVEPATH').'imgs/'; // 设置附件上传目录
		$upload = new \Think\Upload($config);// 实例化上传类
		// 上传文件 
		$filelist   =   $upload->upload();
		if(!$filelist) $filelist=	array();
		/*$image = new \Think\Image(); 
		foreach($list as $key=>$item)
		{
			$image->open(C('SAVEPATH').'imgs/'.$item['savepath'].$item['savename']);
			//将图片裁剪为400x400并保存为corp.jpg
			$savenamearr	=	explode('.',$item['savename']);
			$image->thumb(100, 100)->save(C('SAVEPATH').'imgs/'.$item['savepath'].$savenamearr[0].'_thumb'.'.'.$savenamearr[1]);
			$list[$key]['savename']=$savenamearr[0].'_thumb'.'.'.$savenamearr[1];
		}*/
		return $filelist;
	}
}
?>