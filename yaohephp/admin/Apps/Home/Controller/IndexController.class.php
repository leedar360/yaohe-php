<?php
/*
* author	袁绍月
* date		2014/9/8
* 用户类
*/
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
	//首页
    public function index(){
		//M('
		$this->display();
    }
	//主页
	public function main()
	{
		$this->display();
	}
}