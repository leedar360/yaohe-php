<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
	var $classname_arr;
	var $module_arr;
	var $method_arr;
    protected function _initialize()
	{
		if(!isset($_SESSION['ht_admin']))
		{
			$this->error('没有登录','/?c=Login');
		}
		$options = array('host' => C('MEMCACHE_HOST'), 'port' => C('MEMCACHE_PORT'), 'timeout' => 14400,
		'persistent' => false);
		$Cache = \Think\Cache::getInstance('memcache', $options);
		$role_id=$Cache->get('role_id_'.session_id());
		if($role_id<0)
		{
			$role_id=$_SESSION['ht_admin']['role_id'];
		}
		//echo $role_id;
		$role	=	M('Role')->where(array('id'=>$role_id))->find();
		if(!$role)
		{
			//$this->error('角色已经不存在了','/Login');
		}
		$this->classname_arr=	unserialize($role['classname']);
		$this->module_arr	=	unserialize($role['module']);
		$this->method_arr	=	unserialize($role['method']);
		//var_dump($this->module_arr);exit;
		$this->assign('classname_arr',$this->classname_arr);
		$this->assign('module_arr',$this->module_arr);
		$this->assign('method_arr',$this->method_arr);
		//echo CONTROLLER_NAME.'='.ACTION_NAME;
	}
	//搜索  暂时不用，以后扩展
	protected function _search($name = '') 
	{
		//生成查询条件
		if (empty ( $name )) {
			$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
			$model = D($model_name);
		}
	}
	public function ajax_list()
	{
		/*if(!in_array(CONTROLLER_NAME,$this->module_arr))
		{
			$this->error('您没有权限');
		}*/
		$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
        $model = D($model_name);
		$map	=	array();
		if (method_exists ( $this, '_ajax_filter' )) {
			$this->_ajax_filter ( $map );
		}
		$this->_list($model,$map);
		$this->display();
	}
	// 查询数据
    public function index() {
		/*if(!in_array(CONTROLLER_NAME,$this->module_arr))
		{
			$this->error('您没有权限');
		}*/
		$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
        $model = D($model_name);
		//$map = $this->_search($model_name);
		$map	=	array();
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		//获取总数
		/*$count	=	$model->where($map)->count();
		//import("Think/Page");//导入分页类
		$Page = new \Think\Page($count, C('PAGE_NUM'));
		$list   = $model->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->order($model->getPk().' desc')->select();
		//echo $model->getlastsql();
		$page = $Page->show();
		//echo $page;
		$this->assign('currentPage',$Page->firstRow/C('PAGE_NUM')+1);
		$this->assign('numPerPage',C('PAGE_NUM'));
		$this->assign('totalcount',intval($count));
        $this->assign("page", $page);
        $this->assign("list", $list);*/
		$this->_list($model,$map);
		$this->display();
    }
	protected function _list($model, $map=array(), $sortBy = '', $asc = TRUE) {

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
			$sort = $asc ? 'desc' : 'asc';
		}
		//取得满足条件的记录数
		$count = $model->where ( $map )->count ( '*' );
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
		return;
	}


	//添加页面
	public function add()
	{
		/*if(!in_array(CONTROLLER_NAME,$this->module_arr) || !in_array('insert',$this->method_arr[CONTROLLER_NAME]))
		{
			$this->error('您没有权限');
		}*/
		$this->display();
	}

	//复制增加
	public function copyadd($id)
	{
		/*if(!in_array(CONTROLLER_NAME,$this->module_arr) || !in_array('insert',$this->method_arr[CONTROLLER_NAME]))
		{
			$this->error('您没有权限');
		}*/
        if (!empty($id)) {
			$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
			$model = D($model_name);
            $vo = $model->getById($id);
            if ($vo) {
                $this->vo   =   $vo;
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

    //写入数据
    public function insert() {
		/*if(!in_array(CONTROLLER_NAME,$this->module_arr) || !in_array('insert',$this->method_arr[CONTROLLER_NAME]))
		{
			$this->error('您没有权限');
		}*/
		//M('Province')->add(array('title'=>'dd'));exit;
		$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
		//echo $model_name;exit;
        $model = D($model_name);
		//var_dump($model);exit;
        if ($model->create()) 
		{
			$filelist=$this->getUploads();
			foreach($filelist as $item)
			{
				$model->__set($item['key'],$item['savepath'].$item['savename']);
			}
            $flag = $model->add();
			//echo $model->getlastsql();exit;
            if ($flag !== false) 
			{
                //$this->ajaxsuccess('数据保存成功！',__CONTROLLER__);
				$return['msg']='数据保存成功！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->success($return['msg']);
            }
			else
			{
				$return['msg']='数据写入错误！';
				IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
                //$this->ajaxerror('数据写入错误！',__CONTROLLER__);
            }
        }
		else
		{
			//echo $model->getError();exit;
			$return['msg']=$model->getError();
			IS_AJAX ? $this->ajaxReturn($return,'JSON') : $this->error($return['msg']);
            //$this->ajaxerror($model->getError());
        }
    }
    // 编辑数据
    public function edit($id) {
		/*if(!in_array(CONTROLLER_NAME,$this->module_arr) || !in_array('update',$this->method_arr[CONTROLLER_NAME]))
		{
			$this->error('您没有权限');
		}*/
        if (!empty($id)) {
			$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
			$model = D($model_name);
            $vo = $model->getById($id);
            if ($vo) {
                $this->vo   =   $vo;
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
		//echo CONTROLLER_NAME.'='.$this->method_arr[CONTROLLER_NAME];
		/*if(!in_array(CONTROLLER_NAME,$this->module_arr) || !in_array('update',$this->method_arr[CONTROLLER_NAME]))
		{
			$this->error('您没有权限');
		}*/
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

            $list = $model->save();
            if ($list !== false) {
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

    // 删除数据
    public function delete() {
		/*if(!in_array(CONTROLLER_NAME,$this->module_arr) || !in_array('delete',$this->method_arr[CONTROLLER_NAME]))
		{
			$this->error('您没有权限');
		}*/
		$id	=	$_REQUEST['id'];//I('post.id');
        if (!empty($id)) {
			//var_dump($id);exit;
			$model_name	=	$this->model_name?$this->model_name:CONTROLLER_NAME;
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
	/**
	* 上传单图片
	* 参数：$file_name	上传文件类
	* 返回：上传之后路径
	*/
	protected function getOneUpload($file_name)
	{
		$config['maxSize']	=	C('MAXSIZE') ;// 设置附件上传大小
		$config['exts']		=	C('ALLOWEXTS') ;// 设置附件上传大小
		$config['rootPath']	=	C('SAVEPATH').'imgs/'; // 设置附件上传目录
		$upload = new \Think\Upload();// 实例化上传类
		// 上传单个文件     
		$info   =   $upload->uploadOne($_FILES[$file_name]);    
		if(!$info)return false;
		// 上传成功 获取上传文件信息         
		return 'imgs/'.$info['savepath'].$info['savename'];    
	}
	/**
	* 上传多图片
	* 参数：无
	* 返回：Array
	*/
	protected function getUploads()
	{
		$config['maxSize']	=	C('MAXSIZE') ;// 设置附件上传大小
		$config['exts']		=	C('ALLOWEXTS') ;// 设置附件上传大小
		$config['rootPath']	=	C('SAVEPATH').'imgs/'; // 设置附件上传目录
		$upload = new \Think\Upload($config);// 实例化上传类
		$thumbPrefix='m_';
		$thumbMaxWidth='120';
		$thumbMaxHeight='140';
		$upload->thumb = true;
		//设置需要生成缩略图的文件后缀
		$upload->thumbPrefix = $thumbPrefix; //生产2张缩略图
		//设置缩略图最大宽度
		$upload->thumbMaxWidth = $thumbMaxWidth;
		//设置缩略图最大高度
		$upload->thumbMaxHeight = $thumbMaxHeight;
		// 上传文件 
		$list   =   $upload->upload();
		if(!$list) return array();
		$image = new \Think\Image(); 
		foreach($list as $key=>$item)
		{
			$image->open(C('SAVEPATH').'imgs/'.$item['savepath'].$item['savename']);
			//将图片裁剪为400x400并保存为corp.jpg
			$savenamearr	=	explode('.',$item['savename']);
			$image->thumb(100, 100)->save(C('SAVEPATH').'imgs/'.$item['savepath'].$savenamearr[0].'_thumb'.'.'.$savenamearr[1]);
			$list[$key]['savename']=$savenamearr[0].'_thumb'.'.'.$savenamearr[1];
		}
		return $list;
	}
}