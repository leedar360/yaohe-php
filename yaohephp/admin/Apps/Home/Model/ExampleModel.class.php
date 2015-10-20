<?php 
namespace Home\Model;
use Think\Model;
class ExampleModel extends Model {
	protected $_validate =array(
		array('username', 'require', '用户名不能为空', 1)
	);
}