<?php
/*
* author	袁绍月
* date		2015/6/2
* 首页轮换图
*/
namespace Home\Controller;
use Think\Controller;
class RotateController extends CommonController {
	
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

		$province_id=	intval(I('get.province_id'));//省份
		$city_id	=	intval(I('get.city_id'));//城市
		$area_id	=	intval(I('get.area_id'));//行政区域
		$district_id=	intval(I('get.district_id'));//商圈
		$one_id		=	intval(I('get.one_id'));//一级分类
		$status		=	I('get.status');//状态
		$keywords	=	I('get.keywords');
		$this->assign('province_id',$province_id);
		$this->assign('city_id',$city_id);
		$this->assign('area_id',$area_id);
		$this->assign('district_id',$district_id);
		$this->assign('one_id',$one_id);
		$this->assign('status',$status);
		$this->assign('keywords',$keywords);

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
		
		$model	=	M('Rotate');
		$map	=	array();
		if($province_id>0)$map['province_id']	=	$province_id;
		if($city_id>0)$map['city_id']			=	$city_id;
		if($area_id>0)$map['area_id']			=	$area_id;
		if($district_id>0)$map['district_id']	=	$district_id;
		if($one_id>0)$map['one_id']				=	$one_id;
		if($status!='')$map['status']=$status;
		if($keywords!='')
		{
			$map['_string'] =	'id="'.$keywords.'" or title like "%'.$keywords.'%"';
			//$map['id']=$keywords;
			//$map['title']=array('like','%'.$keywords.'%');
		}
		//排序字段 默认为主键名 FOR DWZ, Add BY 4wei.cn
		if (isset ( $_REQUEST['orderField'] )) {
			$order = $_REQUEST['orderField'];
		} else {
			$order = ! empty( $sortBy ) ? $sortBy : $model->getPk ();
		}
		
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset ( $_REQUEST['orderDirection'] )) {
			$sort = (strtolower($_REQUEST ['orderDirection']) == 'asc') ? 'asc' : 'desc';
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}
		//取得满足条件的记录数
		$count = $model->where ( $map )->count ( '*' );
		//echo $model->getlastsql();
		if ($count > 0) {
			//import ( "ORG.Util.Page" );
			import("Think/Page");//导入分页类
			$p = new \Think\Page($count, C('PAGE_NUM'));
			//分页查询数据，增加关联模型，Add By 4wei.cn
			$voList = $model->where($map)->order("order_num asc,id desc")->limit($p->firstRow.','.$p->listRows)->select();
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
			$this->assign ( 'sort', $sort );
			$this->assign ( 'order', $order );
			$this->assign ( 'sortImg', $sortImg );
			$this->assign ( 'sortType', $sortAlt );
			$this->assign ( "page", $page );
			//echo $p->firstRow;
		}
		$this->assign ( 'totalcount', $count );
		$this->assign ( 'numPerPage', C('PAGE_NUM') );
		//echo $p->firstRow.'--'.C('PAGE_NUM');
		$this->assign ( 'currentPage',$p->firstRow/C('PAGE_NUM')+1);
		$this->display();
	}
	public function _before_add()
	{
		$province_list	=	M('Province')->select();
		$this->assign('province_list',$province_list);
		$map	=	array('parentid'=>0,'is_hidden'=>0);
		$class_list		=	M('Classify')->where($map)->order('order_num asc,id asc')->select();
		$this->assign('class_list',$class_list);
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
	* 功能：获取子分类列表
	*/
	public function getsonclasslist()
	{
		$parentid=	intval(I('post.parentid'));
		$map	=	array('parentid'=>$parentid,'is_hidden'=>0);
		$list	=	M('Classify')->where($map)->order('order_num asc,id asc')->select();
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
	/**
	* 功能：检测手机是否重复
	*/
	public function checkmobile()
	{
		$mobile	=	I('post.mobile');
		$row	=	M('Member')->where(array('login_user'=>$mobile))->find();
		if($row)
		{
			$arr['flag']=0;
			$arr['msg']='此手机已经被使用';
			echo json_encode($arr);exit;
		}
		$arr['flag']=1;
		$arr['msg']='此手机可以使用';
		echo json_encode($arr);exit;
	}
	public function insert()
	{
		$model = D('Rotate');
		if($model->create())
		{
			$model->__set('up_admin_id',$_SESSION['ht_admin']['id']);
			$model->__set('up_admin_username',$_SESSION['ht_admin']['username']);
			$model->__set('last_update_time',time());
			$model->__set('addtime',time());
			$filelist=$this->getUploads();
			foreach($filelist as $item)
			{
				$model->__set($item['key'],$item['savepath'].$item['savename']);
			}
			$rotate_id = $model->add();
            if ($rotate_id !== false) 
			{
				$return['msg']='数据保存成功！';
				$this->success($return['msg'],'?c=Rotate');
            }
			else
			{
				$return['msg']='数据写入错误！';
				$this->error($return['msg'],'?c=Rotate&a=add');
            }
		}
		else
		{
			$return['msg']=$model->getError();
			$this->error($return['msg'],__URL__.'?c=Rotate&a=add');
        }
	}
	public function edit($id)
	{
		if (!empty($id)) {
			$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
			$model = D($model_name);
            $vo = $model->getById($id);
            if ($vo) {
				//省份列表
				$province_list	=	M('Province')->select();
				$this->assign('province_list',$province_list);
				//一级分类列表
				$map		=	array('parentid'=>0,'is_hidden'=>0);
				$class_list	=	M('Classify')->where($map)->order('order_num asc,id asc')->select();
				$this->assign('class_list',$class_list);
				//城市列表
				$city_list	=	M('City')->where(array('p_id'=>$vo['province_id']))->select();
				//echo M('City')->getlastsql();
				$this->assign('city_list',$city_list);
				//行政区域列表
				$area_list	=	M('Area')->where(array('c_id'=>$vo['city_id']))->select();
				//echo M('Area')->getlastsql();
				$this->assign('area_list',$area_list);
				//商圈列表
				$district_list=	M('District')->where(array('area_id'=>$vo['area_id']))->select();
				//echo M('District')->getlastsql();
				$this->assign('district_list',$district_list);
				//二级分类列表
				$map		=	array('parentid'=>$vo['one_id'],'is_hidden'=>0);
				$industry_class_list=	M('Classify')->where($map)->order('order_num asc,id asc')->select(); 
				$this->assign('industry_class_list',$industry_class_list);
				//var_dump($vo);exit;
				$this->assign('vo',$vo);
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
	public function update()
	{
		$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
		$model = D($model_name);
        if ($vo = $model->create()) {
			//var_dump($_SESSION);exit;
			$model->__set('up_admin_id',$_SESSION['ht_admin']['id']);
			$model->__set('up_admin_username',$_SESSION['ht_admin']['username']);
			$model->__set('last_update_time',time());
			$filelist=$this->getUploads();
			foreach($filelist as $item)
			{
				$model->__set($item['key'],$item['savepath'].$item['savename']);
			}
            $list = $model->save();
            if ($list !== false) {
				$return['msg']='数据更新成功！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->success($return['msg']);
            } else {
				$return['msg']='没有更新任何数据';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
            }
        } else {
			$return['msg']=$Form->getError();
			IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
        }
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
		M('Rotate')->where($map)->save($data);
		$this->success('上线成功','/?c=Rotate');
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
		M('Rotate')->where($map)->save($data);
		//$return['msg']='下线成功';
		$this->success('下线成功','/?c=Rotate');
		//$this->ajaxReturn($return,'JSON');
	}
	/**
	* 搜索结果
	*/
	public function searchresult()
	{
		header("Content-type: application/json");
		$id		=	I('post.id');//内容ID
		$type	=	I('post.type');//类别
		$serviceType	=	4;
		switch($type)
		{
			case 0://商家
				$model=M('Shop');
			break;
			case 1://优惠券
				$model=M('Coupon');
				$serviceType	=	0;
			break;
			case 2://会员卡
				$model=M('Card');
				$serviceType	=	1;
			break;
			case 3://活动
				$model=M('Activity');
				$serviceType	=	2;
			break;
			case 4://新品
				$model=M('NewProduct');
				$serviceType	=	3;
			break;
			default:
				$return['flag']=0;
				$return['msg']='信息不存在';
				echo json_encode($return);
				exit;
		}
		$row	=	$model->where(array('id'=>$id))->find();
		if(!$row)
		{
			$return['flag']=0;
			$return['msg']='信息不存在';
			echo json_encode($return);
			exit;
		}
		//get the service from table shop service
		$serviceMap['type']	=	$serviceType ;
		$serviceMap['service_id']	=	$id	;
		$serviceRow	=	M('ShopService')->where($serviceMap)->find() ;
		if(!$serviceRow)
		{
			$return['flag']	=	0;
			$return['msg']	=	'信息不存在'	;
			echo json_encode($return) ;
			exit ;
		}
		$return['flag']	=	1;
		$return['msg']	=	$row['title'];
		$return['service_id']	=	$serviceRow['id'] ;
		echo json_encode($return);
		exit;
	}
	public function uporder()
	{
		$order_num_list	=	I('post.order_num');
		$id_list		=	I('post.id');
		//var_dump($id_list);
		foreach($id_list as $key=>$id)
		{
			$where['id']	=	$id;
			$data['order_num']=	$order_num_list[$key];
			M('Rotate')->where($where)->save($data);
			//echo M('Rotate')->getlastsql().'<br>';
		}
	}
}
?>