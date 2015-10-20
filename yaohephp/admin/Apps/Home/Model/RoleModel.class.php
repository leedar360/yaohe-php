<?php 
namespace Home\Model;
use Think\Model;
class RoleModel extends Model {
	//自动完成
	protected $_auto=array(array('classname','serialize',3,'function'),array('module','serialize',3,'function'),array('method','serialize',3,'function'),array('province_arr','getprovince',3,'callback'),array('city_arr','getcity',3,'callback'));

	public function getprovince()
	{
		$tocitylist	=	I('post.tocitylist');
		$map['id']	=	array('in',$tocitylist);
		$list	=	M('City')->where($map)->select();
		//echo M('City')->getlastsql();exit;
		$province_arr=	array();
		foreach($list as $item)
		{
			if(in_array($item['p_id'],$province_arr))continue;
			$province_arr[]=$item['p_id'];
		}
		return serialize($province_arr);
	}

	public function getcity()
	{
		$tocitylist	=	I('post.tocitylist');
		$city_arr	=	explode(',',$tocitylist);
		return serialize($city_arr);
	}
}