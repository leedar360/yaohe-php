<?php
/*
* author	袁绍月
* date		2015/6/3
* 所有优惠
*/
namespace Home\Controller;
use Think\Controller;
class CouponAllController extends CommonController {
	public function index()
	{
		$member_id	=	intval(I('get.shop_id'));
		$this->assign('shop_id',$member_id);
		//优惠券数量
		$map['member_id']=$member_id;
		$coupon_num	=	M('Coupon')->where($map)->count();
		$this->assign('coupon_num',$coupon_num);
		//优惠券领取数量
		$coupon_draw_num	=	M('MemberCoupon')->where($map)->count();
		$this->assign('coupon_draw_num',$coupon_draw_num);

		//会员卡数量
		$card_num	=	M('Card')->where($map)->count();
		$this->assign('card_num',$card_num);
		//会员卡领取数量
		$card_draw_num	=	M('MemberCard')->where($map)->count();
		$this->assign('card_draw_num',$card_draw_num);

		//新品数量
		$new_product_num=	M('NewProduct')->where($map)->count();
		$this->assign('new_product_num',$new_product_num);

		//活动数量
		$activity_num=	M('Activity')->where($map)->count();
		$this->assign('activity_num',$activity_num);

		//吆喝数量
		$call_num=	M('Call')->count();
		$this->assign('call_num',$call_num);
		$this->display();
	}
}
?>