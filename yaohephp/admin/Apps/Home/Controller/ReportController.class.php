<?php
/*
* author	袁绍月
* date		2015/6/1
* 举报管理
*/
namespace Home\Controller;
use Think\Controller;
class ReportController extends CommonController {
	public function index()
	{
		$model	=	M('Report');

		$count	=	$model->where ( $map )->count ( '*' );
		//echo $model->getlastsql();
		if ($count > 0) {
			//import ( "ORG.Util.Page" );
			import("Think/Page");//导入分页类
			$p = new \Think\Page($count, C('PAGE_NUM'));
			//分页查询数据，增加关联模型，Add By 4wei.cn
			$voList = $model->order("id desc")->limit($p->firstRow.','.$p->listRows)->select();
			foreach($voList as $key=>$item)
			{
				$member	=	M('Member')->field('login_user')->where(array('member_id'=>$item['member_id']))->find();
				$voList[$key]['member_name']=$member['login_user'];
				$shop	=	M('Shop')->field('title')->where(array('id'=>$item['shop_id']))->find();
				$voList[$key]['shop_name']=$shop['title'];
			}
			//echo $model->getlastsql();
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
}
?>