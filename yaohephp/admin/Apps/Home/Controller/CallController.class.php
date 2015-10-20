<?php
/*
* author	袁绍月
* date		2015/6/21
* 吆喝类
*/
namespace Home\Controller;
use Think\Controller;
class CallController extends CommonController {
	public function index()
	{
		$title	=	I('request.title');//商店名称或编号
		$keywords=	I('request.keywords');//关键字
		//$where['_string']='';
		if(!empty($title))
		{
			$where['_string']	=	"  (s.id like '%".$title."%' || s.title like '%".$title."%')";
		}
		if(!empty($keywords))
		{
			if(!empty($where['_string']))
			{
				$where['_string']	.=	" and (c.content like '%".$keywords."%')";
			}
			else
			{
				$where['_string']	=	"  (c.content like '%".$keywords."%')";
			}
		}
		
		$obj1	=	M('Shop');
		$obj2	=	M('ShopService');
		$count = $obj1->table($obj1->getTableName().' t1') ->join($obj2->getTableName().' t2 on t1.`member_id` = t2.`member_id`')->field('t1.title as s_title,t2.*')->where($where) ->count();
		//$count	=	$model->where ( $map )->count ( '*' );
		//echo $obj1->getlastsql();
		if ($count > 0) {
			//import ( "ORG.Util.Page" );
			import("Think/Page");//导入分页类
			$p = new \Think\Page($count, C('PAGE_NUM'));
			//分页查询数据，增加关联模型，Add By 4wei.cn
			$voList = $obj1->table($obj1->getTableName().' t1') ->join($obj2->getTableName().' t2 on t1.`member_id` = t2.`member_id`')->field('t1.title as s_title,t2.*')->where($where) ->order("t2.id desc")->limit($p->firstRow.','.$p->listRows)->select();
			//var_dump($voList);exit;
			//echo $obj1->getlastsql();
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if (! is_array ( $val )) {
					$p->parameter .= "$key=" . urlencode ( $val ) . "&";
				}
			}
			
			//分页显示
			$page = $p->show ();
			
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			
			foreach($voList as $key=>$item)
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
					default://纯吆喝
						$service	=	M('Call')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$item['service_id']))->find();
				}
				$voList[$key]['img1']=$service['img1'];
				$voList[$key]['img2']=$service['img2'];
				$voList[$key]['img3']=$service['img3'];
				$voList[$key]['img4']=$service['img4'];
				$voList[$key]['img5']=$service['img5'];
				$voList[$key]['img6']=$service['img6'];
				$voList[$key]['content']=$service['content'];
			}
			//模板赋值显示
			$this->assign ( 'list', $voList );
			$this->assign ( "page", $page );
			//echo $p->firstRow;
		}
		$this->assign ( 'totalcount', $count );
		$this->assign ( 'numPerPage', C('PAGE_NUM') );
		//echo $p->firstRow.'--'.C('PAGE_NUM');
		$this->assign ( 'currentPage',$p->firstRow/C('PAGE_NUM')+1);
		/*$sql	=	"select s.title as s_title,c.* from ht_shop as s,ht_shop_service as c where s.member_id=c.member_id";
		if(!empty($title))
		{
			$sql.=	" and (s.id like '%".$title."%' || s.title like '%".$title."%')";
		}
		if(!empty($keywords))
		{
			$sql.=	" and (c.content like '%".$keywords."%')";
		}
		$list	=	M()->query($sql);*/
		$this->assign('title',$title);
		$this->assign('keywords',$keywords);
		//echo $sql;
		$this->display();
	}
	function view()
	{
		$id	=	intval(I('get.id'));
		$vo	=	M('ShopService')->where(array('id'=>$id))->find();
		$shop=	M('Shop')->where(array('member_id'=>$vo['member_id']))->find();
		$shop_name	=	$shop['title'];
		//echo $vo['type'];
		switch($vo['type'])
		{
			case 1://会员卡
				$service	=	M('Card')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$vo['service_id']))->find();
			break;
			case 2://活动
				$service	=	M('Activity')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$vo['service_id']))->find();
			break;
			case 3://新品
				$service	=	M('NewProduct')->field('img1,img2,img3,img4,img5,img6,title as content')->where(array('id'=>$vo['service_id']))->find();
			break;
			case 0://优惠券
				$service	=	M('Coupon')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$vo['service_id']))->find();
			break;
			default://纯吆喝
				$service	=	M('Call')->field('img1,img2,img3,img4,img5,img6,content')->where(array('id'=>$vo['service_id']))->find();
		}
		//var_dump($service);
		$vo['img1']=$service['img1'];
		$vo['img2']=$service['img2'];
		$vo['img3']=$service['img3'];
		$vo['img4']=$service['img4'];
		$vo['img5']=$service['img5'];
		$vo['img6']=$service['img6'];
		$vo['content']=$service['content'];
		$this->assign('shop_name',$shop_name);
		$this->assign('vo',$vo);
		$this->display();
	}
	function delete()
	{
		$id	=	$_REQUEST['id'];//I('post.id');
        if (!empty($id)) {
			//var_dump($id);exit;
			$model_name	=	'ShopService';
			$model = D($model_name);
			$where['id']=	is_array($id)?array('in',$id):$id;
			$result = $model->where($where)->delete($id);
			//echo $model->getlastsql();exit;
            if (false !== $result) {
				$return['msg']='删除成功！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->success($return['msg']);
                //$this->ajaxsuccess('删除成功！',__CONTROLLER__);
            } else {
				$return['msg']='删除出错！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
                //$this->ajaxerror('删除出错！',__CONTROLLER__);
            }
        } else {
			$return['msg']='ID错误！';
			IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
            //$this->ajaxerror('ID错误！',__CONTROLLER__);
        }
	}
}
?>