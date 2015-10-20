<?php
/*
* author	袁绍月
* date		2015/5/28
* 商家
*/
namespace Home\Controller;
use Think\Controller;
class ShopController extends CommonController {
	
	public function index()
	{
		//商圈列表
		$district_list=	M('District')->select();
		$districtarr=array();
		foreach($district_list as $item)
		{
			$districtarr[$item['id']]=$item['title'];
		}
		$this->assign('districtarr',$districtarr);
		$class_list	=	M('Classify')->select();
		$classarr	=	array();
		foreach($class_list as $item)
		{
			$classarr[$item['id']]=$item['title'];
		}
		$this->assign('classarr',$classarr);
		//获取省份列表
		$province_list	=	M('Province')->select();
		$this->assign('province_list',$province_list);
		//获取城市列表
		$city_list	=	M('City')->select();
		$cityarr	=	array();
		foreach($city_list as $item)
		{
			$cityarr[$item['id']]=$item['title'];
		}
		$this->assign('cityarr',$cityarr);
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
		$is_sign	=	I('get.is_sign');//是否签约
		$level		=	I('get.level');//等级
		$status		=	I('get.status');//状态
		$order_num	=	I('get.order_num');//排序
		$keywords	=	I('get.keywords');
		$this->assign('province_id',$province_id);
		$this->assign('city_id',$city_id);
		$this->assign('area_id',$area_id);
		$this->assign('district_id',$district_id);
		$this->assign('one_id',$one_id);
		$this->assign('is_sign',$is_sign);
		$this->assign('level',$level);
		$this->assign('status',$status);
		$this->assign('order_num',$order_num);
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
		
		$model	=	M('Shop');
		$map	=	array();
		if($province_id>0)$map['province_id']	=	$province_id;
		if($city_id>0)$map['city_id']			=	$city_id;
		if($area_id>0)$map['area_id']			=	$area_id;
		if($district_id>0)$map['district_id']	=	$district_id;
		if($one_id>0)$map['one_id']				=	$one_id;
		if($is_sign!='')$map['is_sign']=$is_sign;
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
		$asc=true;
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset ( $_REQUEST['orderDirection'] )) {
			$sort = (strtolower($_REQUEST ['orderDirection']) == 'asc') ? 'asc' : 'desc';
		} else {
			$sort = $asc ? 'desc' : 'asc';
		}
		//取得满足条件的记录数
		$count = $model->where ( $map )->count ( '*' );
		//echo $model->getlastsql();
		if ($count > 0) {
			//import ( "ORG.Util.Page" );
			import("Think/Page");//导入分页类
			$p = new \Think\Page($count, C('PAGE_NUM'));
			//分页查询数据，增加关联模型，Add By 4wei.cn
			$voList = $model->where($map)->order($order . " " . $sort)->limit($p->firstRow.','.$p->listRows)->select();
			//echo $model->getlastsql();
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if($key=='_string')continue;
				if (! is_array ( $val )) {
					//$p->parameter .= "$key=" . urlencode ( $val ) . "&";
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
		$login_pass	=	getRandomNum(8);
		$data['login_user']	=	I('post.mobile');
		$data['login_pass']	=	md5($login_pass);
		$member	=	M('Member')->where(array('login_user'=>$data['login_user']))->find();
		if($member)
		{
			$this->error('会员已经存在','?c=Shop&a=add');
		}
		$data['type']=	1;
		$member_id	=	M('Member')->add($data);
		if($member_id<1)
		{
			$this->error('服务器忙，请稍侯尝试','?c=Shop&a=add');
		}
		$_account = 'cf_zcsd';
		$_password = '69wZ74';
		$_url = 'http://106.ihuyi.cn/webservice/sms.php?method=Submit&format=json';
		$data = array(
			'account' => $_account,
			'password' => $_password,
			'mobile' => $login_user,
			'content' => '亲爱的客户大大，您的吆喝密码是【'.$login_pass.'】 小的马不停蹄给您送来了，敬请笑纳，么么哒！',
		);
		$res_json = curlPost($_url, $data, 5);
		$model = D('Shop');
		if($model->create()) 
		{
			$filelist=$this->getUploads();
			foreach($filelist as $item)
			{
				//$data[$item['key']]	=	$item['savepath'].$item['savename'];
				$model->__set($item['key'],$item['savepath'].$item['savename']);
			}
			$shop_id = $model->add();
            if ($shop_id !== false) 
			{
				$data	=	array();
				$data['shop_id']=$shop_id;
				$keywords	=	$_POST['keywords'];//I('post.keywords');
				foreach($keywords as $title)
				{
					if(empty($title))continue;
					$data['title']=$title;
					M('ShopKeywords')->add($data);
				}
				$return['msg']='数据保存成功！';
				$this->success($return['msg'],'?c=Shop&a=contract&id='.$shop_id);
            }
			else
			{
				$return['msg']='数据写入错误！';
				$this->error($return['msg'],'?c=Shop&a=add');
            }
		}
		else
		{
			$return['msg']=$model->getError();
			$this->error($return['msg'],'?c=Shop&a=add');
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
				//获取关键字列表
				$keywords_list=	M('ShopKeywords')->where(array('shop_id'=>$vo['id']))->order('id asc')->select(); 
				$this->assign('keywords_list',$keywords_list);
				//var_dump($vo);exit;
				$this->assign('vo',$vo);
				/*$squares=returnSquarePoint($vo['lng'],$vo['lat'],3);
				$info_sql = "select id,title from `ht_shop` where lat<>0 and lat>{$squares['right-bottom']['lat']} and lat<{$squares['left-top']['lat']} and lng>{$squares['left-top']['lng']} and lng<{$squares['right-bottom']['lng']} "; 
				echo $info_sql;exit;*/
				//var_dump($row);exit;
			//echo $vo['long'].'='.$vo['lat'];
			//exit;
				//var_dump($vo);exit;
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
			$filelist=$this->getUploads();
			//var_dump($filelist);exit;
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
            $list = $model->save();
            if ($list !== false) {
				$data	=	array();
				$data['shop_id']=intval(I('post.id'));
				M('ShopKeywords')->where(array('shop_id'=>$data['shop_id']))->delete();
				$keywords	=	$_POST['keywords'];//I('post.keywords');
				//var_dump($keywords);exit;
				foreach($keywords as $title)
				{
					if(empty($title))continue;
					$data['title']=$title;
					M('ShopKeywords')->add($data);
					//echo M('ShopKeywords')->getlastsql().'<br>';
				}
				//exit;
				$return['msg']='数据更新成功！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->success($return['msg']);
                //$this->ajaxsuccess('数据更新成功！',__CONTROLLER__);
            } else {
				$return['msg']='没有更新任何数据';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
                //$this->ajaxerror("没有更新任何数据!",__CONTROLLER__);
            }
        } else {
			$return['msg']=$Form->getError();
			IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
            //$this->ajaxerror($Form->getError());
        }
	}
	public function contract($id)
	{
		//echo getDistance(39.94284,116.353819,39.962649,116.357918);exit;

		if (!empty($id)) {
			$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
			$model = D($model_name);
            $vo = $model->getById($id);
            if ($vo) {
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
	public function savecontract()
	{
		$data['full_name']	=	I('post.full_name');
		$data['legal_person']=	I('post.legal_person');
		$data['tel']		=	I('post.tel');
		$data['email']		=	I('post.email');
		$data['status']		=	0;
		$filelist=$this->getUploads();
		if(count($filelist)>0)
		{
			$model	=	M('Shop');
			$row	=	$model->where(array($model->getPk()=>intval($_POST[$model->getPk()])))->find();
			if(!$row)
			{
				$return['msg']='数据不存在！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
				//$this->ajaxerror('数据不存在！',__CONTROLLER__);
			}
			foreach($filelist as $item)
			{
				@unlink(C('SAVEPATH').'imgs/'.$row[$item['key']]);
				$data[$item['key']]=$item['savepath'].$item['savename'];
				//$model->__set($item['key'],$item['savepath'].$item['savename']);
			}
		}
		//var_dump(I('post.id'));exit;
		$map['id']	=	intval(I('post.id'));
		$flag	=	M('Shop')->where($map)->save($data);
		if ($flag !== false) {
			$return['msg']='数据更新成功！';
			IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->success($return['msg']);
			//$this->ajaxsuccess('数据更新成功！',__CONTROLLER__);
		} else {
			$return['msg']='没有更新任何数据';
			IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
			//$this->ajaxerror("没有更新任何数据!",__CONTROLLER__);
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
		M('Shop')->where($map)->save($data);
		$return['msg']='上线成功';
		$this->ajaxReturn($return,'JSON');
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
		$data['status']=3;
		M('Shop')->where($map)->save($data);
		$return['msg']='下线成功';
		$this->ajaxReturn($return,'JSON');
	}
	/**
	* 查看商家信息
	*/
	public function view($id)
	{
		$model=M('Shop');
		$vo = $model->getById($id);
		if(!$vo)
		{
			$this->error('商家不存在');
		}
		//省份列表
		$province_list	=	M('Province')->select();
		$province_arr	=	array();
		foreach($province_list as $item)
		{
			if($vo['province_id']==$item['id'])
			{
				$province_arr[$item['id']]=$item['title'];
			}
		}
		$this->assign('province_arr',$province_arr);
		//一级分类列表
		$class_list	=	M('Classify')->select();
		$class_arr	=	array();
		foreach($class_list as $item)
		{
			$class_arr[$item['id']]=$item['title'];
		}
		$this->assign('class_arr',$class_arr);
		//城市列表
		$city_list	=	M('City')->where(array('p_id'=>$vo['province_id']))->select();
		$city_arr	=	array();
		foreach($city_list as $item)
		{
			if($vo['city_id']==$item['id'])
			{
				$city_arr[$item['id']]=$item['title'];
			}
		}
		$this->assign('city_arr',$city_arr);
		//商圈列表
		$district_list	=	M('District')->where(array('area_id'=>$vo['area_id']))->select();
		$district_arr	=	array();
		foreach($district_list as $item)
		{
			if($vo['district_id']==$item['id'])
			{
				$district_arr[$item['id']]=$item['title'];
			}
		}
		$this->assign('district_arr',$district_arr);
		//获取关键字列表
		$keywords_list=	M('ShopKeywords')->where(array('shop_id'=>$vo['id']))->select(); 
		$this->assign('keywords_list',$keywords_list);
		$this->assign('vo',$vo);
		$this->display();
	}
	/**
	* 驳回
	*/
	public function audit()
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
		$data['auditcontent']=I('post.content');//驳回内容
		M('Shop')->where($map)->save($data);
		$this->success('驳回成功');
	}
	/**
	* 审核通过合同
	*/
	public function contractstatus()
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
		$data['is_contract']=1;
		M('Shop')->where($map)->save($data);
		$this->success('审核通过');
	}
	/**
	* 驳回合同
	*/
	public function contractaudit()
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
		$data['is_contract']=2;
		$data['contract_content']=I('post.contract_content');//驳回内容
		M('Shop')->where($map)->save($data);
		$this->success('驳回成功');
	}
}
?>