<?php
/*
* author	袁绍月
* date		2015/5/28
* 优惠券
*/
namespace Home\Controller;
use Think\Controller;
class CouponController extends CommonController {
	public function _before_add()
	{
		$province_list	=	M('Province')->select();
		$this->assign('province_list',$province_list);
	}
	public function _before_edit()
	{
		$province_list	=	M('Province')->select();
		$this->assign('province_list',$province_list);
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
	/**
	* 功能：获取行政区列表
	*/
	public function getarealist()
	{
		$city_id=	intval(I('post.city_id'));
		$map['c_id']=$city_id;
		$list	=	M('Area')->where($map)->select();
		if(!$list)$list=array();
		echo json_encode($list);
	}
	/**
	* 功能：获取商圈列表
	*/
	public function getdistrictlist()
	{
		$area_id=	intval(I('post.area_id'));
		$map['area_id']=$area_id;
		$list	=	M('District')->where($map)->select();
		if(!$list)$list=array();
		echo json_encode($list);
	}
	public function index()
	{
		//获取省份列表
		$province_list	=	M('Province')->select();
		$this->assign('province_list',$province_list);
		//获取一级分类
		$map['parentid']=	0;
		$map['is_hidden']=	0;
		$class_list	=	M('Classify')->where($map)->select();
		$this->assign('class_list',$class_list);

		$member_id	=	intval(I('get.member_id'));
		$province_id=	intval(I('get.province_id'));//省份
		$city_id	=	intval(I('get.city_id'));//城市
		$area_id	=	intval(I('get.area_id'));//行政区域
		$district_id=	intval(I('get.district_id'));//商圈
		$one_id		=	intval(I('get.one_id'));//一级分类
		$type		=	I('get.type');//类型
		$status		=	I('get.status');//状态
		$valid_start=	I('get.valid_start');//开始日期
		$valid_end	=	I('get.valid_end');//截止日期
		$keywords	=	I('get.keywords');//关键字

		if($province_id>0)
		{
			$city_list	=	M('City')->where(array('p_id'=>$province_id))->select();
			$this->assign('city_list',$city_list);
		}

		if($city_id>0)
		{
			$area_list	=	M('Area')->where(array('c_id'=>$city_id))->select();
			$this->assign('area_list',$area_list);
		}

		if($area_id>0)
		{
			$district_list	=	M('District')->where(array('area_id'=>$area_id))->select();
			$this->assign('district_list',$district_list);
		}
		$this->assign('province_id',$province_id);
		$this->assign('city_id',$city_id);
		$this->assign('area_id',$area_id);
		$this->assign('district_id',$district_id);
		$this->assign('one_id',$one_id);
		$this->assign('keywords',$keywords);
		
		$this->assign('type',$type);
		$this->assign('status',$status);
		$this->assign('valid_start',$valid_start);
		$this->assign('valid_end',$valid_end);
		
		$model	=	M('Coupon');
		$map	=	array();
		if($member_id>0)$map['member_id']		=	$member_id;
		if($province_id>0)$map['province_id']	=	$province_id;
		if($city_id>0)$map['city_id']			=	$city_id;
		if($area_id>0)$map['area_id']			=	$area_id;
		if($district_id>0)$map['district_id']	=	$district_id;
		if($one_id>0)$map['one_id']				=	$one_id;
		if($type!='')$map['type']	=	$type;
		if($status!='')$map['status']=	$status;
		if($valid_start!='')
		{
			$map['valid_start']=array('egt',$valid_start);
		}
		if($valid_end!='')
		{
			$map['valid_end']=array('elt',$valid_end);
		}
		if(!empty($keywords))
		{
			$map['_string']=" id like '%".$keywords."%' or title like '%".$keywords."%'";
		}
		$count	=	$model->where ( $map )->count ( '*' );
		//echo $model->getlastsql();
		if ($count > 0) {
			//import ( "ORG.Util.Page" );
			import("Think/Page");//导入分页类
			$p = new \Think\Page($count, C('PAGE_NUM'));
			//分页查询数据，增加关联模型，Add By 4wei.cn
			$voList = $model->where($map)->order("id desc")->limit($p->firstRow.','.$p->listRows)->select();
			//echo $model->getlastsql();
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if($key=='_string')continue;
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
			
			
			//模板赋值显示
			$this->assign ( 'list', $voList );
			$this->assign ( "page", $page );
			//echo $p->firstRow;
		}
		$this->assign ( 'totalcount', $count );
		$this->assign ( 'numPerPage', C('PAGE_NUM') );
		//echo $p->firstRow.'--'.C('PAGE_NUM');
		$this->assign ( 'currentPage',$p->firstRow/C('PAGE_NUM')+1);
		$this->display();
	}
	/**
	* ajax搜索
	*/
	public function search_list()
	{
		$province_id=	intval(I('get.province_id'));//省份
		$city_id	=	intval(I('get.city_id'));//城市
		$area_id	=	intval(I('get.area_id'));//行政区域
		$district_id=	intval(I('get.district_id'));//商圈
		$keywords	=	I('get.keywords');
		if($province_id>0)$map['province_id']	=	$province_id;
		if($city_id>0)$map['city_id']			=	$city_id;
		if($area_id>0)$map['area_id']			=	$area_id;
		if($district_id>0)$map['district_id']	=	$district_id;
		if($keywords!='')
		{
			$map['_string'] =	'id="'.$keywords.'" or title like "%'.$keywords.'%"';
		}
		$voList = M('Shop')->where($map)->order("id desc")->select();
		$this->assign ( 'list', $voList );
		$this->display();
	}
	public function insert()
	{
		$data['member_id']	=	intval(I('post.member_id'));//会员ID
		$data['type']		=	intval(I('post.type'));//类型
		$data['title']		=	I('post.title');//标题
		$data['content']	=	I('post.content');//使用范围
		$data['valid_start']=	I('post.valid_start');//有效开始期
		$data['valid_end']	=	I('post.valid_end');//有效截止期
		$data['num']		=	intval(I('post.num'));//发行量
		$data['use']		=	intval(I('post.use'));//使用次数 0单次 1多次
		$data['addtime']	=	time();
		$shop	=	M('Shop')->where(array('member_id'=>$data['member_id']))->find();
		if(!$shop)
		{
			$this->error('商家不存在');
		}
		$data['province_id']=	$shop['province_id'];
		$data['city_id']	=	$shop['city_id'];
		$data['area_id']	=	$shop['area_id'];
		$data['district_id']=	$shop['district_id'];
		$data['one_id']		=	$shop['one_id'];
		$data['industry_class_id']=	$shop['industry_class_id'];
		switch($data['type'])
		{
			case 3://折扣券
				$data['term_start']	=	I('post.term_start_3');
			break;
			case 2://代金券
				$data['term_start']	=	I('post.term_start_2');
			break;
			case 1://满赠券
				$data['term_start']	=	I('post.term_start_1');
				$data['term_end']	=	I('post.term_end_1');
			break;
			case 0://满减券
				$data['term_start']	=	I('post.term_start_0');
				$data['term_end']	=	I('post.term_end_0');
			break;
		}
		//var_dump($data);exit;
		$filelist=$this->getUploads();
		foreach($filelist as $item)
		{
			$data[$item['key']]	=	$item['savepath'].$item['savename'];
			//$model->__set($item['key'],$item['savepath'].$item['savename']);
		}
		//var_dump($data);exit;
		$flag	=	D('Coupon')->add($data);
		//echo D('Coupon')->getlastsql();exit;
		if ($flag !== false) 
		{
			$data	=	array();
			$data['service_id']	=	$flag;
			$data['type']		=	0;
			$data['city_id']	=	$shop['city_id'];
			$data['member_id']	=	intval(I('post.member_id'));
			$data['title']		=	I('post.title');
			$data['addtime']	=	time();
			$data['province_id']=	$shop['province_id'];
			$data['city_id']	=	$shop['city_id'];
			$data['area_id']	=	$shop['area_id'];
			$data['district_id']=	$shop['district_id'];
			$data['one_id']		=	$shop['one_id'];
			$data['industry_class_id']=	$shop['industry_class_id'];
			$data['content']	=	I('post.content');
			M('ShopService')->add($data);
			$return['msg']='数据保存成功！';
			$this->success($return['msg']);
		}
		else
		{
			$return['msg']='数据写入错误！';
			$this->error($return['msg']);
		}
	}
	public function edit($id)
	{
        if (!empty($id)) {
			$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
			$model = D($model_name);
            $vo = $model->getById($id);
            if ($vo) {
                $this->vo=	$vo;
				//echo $vo['member_id'];exit;
				$shop	=	M('Shop')->where(array('member_id'=>$vo['member_id']))->find();
				$this->assign('shop_title',$shop['title']);
                $this->display();
            } else {
				$return['msg']='数据不存在！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
                //$this->ajaxerror('数据不存在！',__CONTROLLER__);
            }
        } else {
			$return['msg']='数据不存在！';
			IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
            //$this->ajaxerror('数据不存在！',__CONTROLLER__);
        }
	}
    //更新数据
    public function update() {
		$data['member_id']	=	intval(I('post.member_id'));
		$shop	=	M('Shop')->where(array('member_id'=>$data['member_id']))->find();
		if(!$shop)
		{
			$this->error('商家不存在');
		}
		/*$data['province_id']=	$shop['province_id'];
		$data['city_id']	=	$shop['city_id'];
		$data['area_id']	=	$shop['area_id'];
		$data['district_id']=	$shop['district_id'];
		$data['one_id']		=	$shop['one_id'];
		$data['industry_class_id']=	$shop['industry_class_id'];*/
		$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
		$model = D($model_name);
        if ($vo = $model->create()) {
			$filelist=$this->getUploads();
			if(count($filelist)>0)
			{
				$row	=	$model->where(array($model->getPk()=>intval($_POST[$model->getPk()])))->find();
				if(!$row)
				{
					$return['msg']='数据不存在！';
					IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
					//$this->ajaxerror('数据不存在！',__CONTROLLER__);
				}
				foreach($filelist as $item)
				{
					@unlink(C('SAVEPATH').$row[$item['key']]);
					$model->__set($item['key'],$item['savepath'].$item['savename']);
				}
			}
			$model->__set('province_id',$shop['province_id']);
			$model->__set('city_id',$shop['city_id']);
			$model->__set('area_id',$shop['area_id']);
			$model->__set('district_id',$shop['district_id']);
			$model->__set('one_id',$shop['one_id']);
			$model->__set('industry_class_id',$shop['industry_class_id']);

			$model->__set('status',0);

            $list = $model->save();
            if ($list !== false) {
				$map['member_id']	=	$data['member_id'];
				$map['type']		=	0;
				$map['service_id']	=	I('post.id');
				$row	=	M('ShopService')->where($map)->find();
				$data['service_id']	=	I('post.id');
				$data['city_id']	=	$shop['city_id'];
				$data['type']		=	0;
				$data['member_id']	=	$data['member_id'];
				$data['title']		=	I('post.title');
				$data['content']	=	I('post.content');
				$data['addtime']	=	time();
				$data['province_id']=	$shop['province_id'];
				$data['city_id']	=	$shop['city_id'];
				$data['area_id']	=	$shop['area_id'];
				$data['district_id']=	$shop['district_id'];
				$data['one_id']		=	$shop['one_id'];
				$data['industry_class_id']=	$shop['industry_class_id'];
				if(!$row)
				{
					M('ShopService')->add($data);
				}
				else
				{
					M('ShopService')->where(array('id'=>$row['id']))->save($data);
				}
				//echo M('ShopService')->getlastsql();exit;
				$return['msg']='数据更新成功！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->success($return['msg']);
                //$this->ajaxsuccess('数据更新成功！',__CONTROLLER__);
            } else {
				$return['msg']='没有更新任何数据';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
                //$this->ajaxerror("没有更新任何数据!",__CONTROLLER__);
            }
        } else {
			$return['msg']='异常';
			IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
            //$this->ajaxerror($Form->getError());
        }
    }
	public function _before_delete()
	{
		$id	=	$_REQUEST['id'];
		$model = D('ShopService');
		$where['type']		=	0;
		$where['service_id']=	is_array($id)?array('in',$id):$id;
		$model->where($where)->delete();
	}
	/**
	* 上线
	*/
	public function online()
	{
		$id	=	$_REQUEST['id'];
		if(is_array($id))
		{
			$map['id']=array('in',$id);
		}
		else
		{
			$map['id']=$id;
		}
		$data['status']=1;
		M('Coupon')->where($map)->save($data);
		//echo M('Coupon')->getlastsql();exit;
		$this->success('上线成功','/?c=Coupon');
		//$return['msg']='上线成功';
		//$this->ajaxReturn($return,'JSON');
	}
	/**
	* 下线
	*/
	public function offline()
	{
		$id	=	$_REQUEST['id'];
		if(is_array($id))
		{
			$map['id']=array('in',$id);
		}
		else
		{
			$map['id']=$id;
		}
		$data['status']=2;
		M('Coupon')->where($map)->save($data);
		//$return['msg']='下线成功';
		$this->success('下线成功','/?c=Coupon');
	}
}
?>