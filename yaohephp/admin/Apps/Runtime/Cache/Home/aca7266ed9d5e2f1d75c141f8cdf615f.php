<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>吆喝第一期后台</title>
<link rel="icon" href="https://console.qingcloud.com/static/images/favicon.ico?v=1" type="image/x-icon">
<link href="/Public/console.1431962384.css" rel="stylesheet" type="text/css">
<script src="/Public/jquery-1.7.2.min.js"></script>
<script>

$(document).ready(function(){ 
   //alert("Hello World");
	$('.has-items').click(function(){
		//alert($(this).children(".level-2").children("li .item").attr("class"))
		if($(this).children(".level-2").is(":hidden")==true)
		{
			$(".has-items").each(function(index){
				$(this).children(".level-2").hide();
				$(this).removeClass("selected");
			})
			$(this).children(".level-2").show();
			$(this).addClass("selected");
		}
		else
		{
			$(".has-items").each(function(index){
				//$(this).children(".level-2").hide();
				//$(this).removeClass("selected");
			})
			$(this).children(".level-2").hide();
			$(this).removeClass("selected");
		}
	/*$('.has-items').eq(0).children(".level-2").show();
	$('.has-items').eq(0).addClass("selected");
	$('li .item').removeClass("selected");
	$('li .item').eq(1).addClass('selected');*/
	});
	$('li .item').click(function(){
		$("li .item").removeClass("selected");
		$(this).addClass("selected");
		//alert($(this).parent().parent().children('.level-2').show());
	})
   /*$('.has-items').eq(0).children(".level-2").show();
   $('.has-items').eq(0).addClass("selected");
   $('li .item').removeClass("selected");
   $('li .item').eq(1).addClass('selected');*/
	$('.nav-item').mousemove(function(){
		//alert('nav');
		$('.dropdown-menu').hide();
		$(this).children('.dropdown-menu').show();
	});
	$('.nav-item').mouseout(function(){
		$('.dropdown-menu').hide();
	});
}); 
</script>
<style type="text/css">
.mask {    
            position: absolute; top: 0px; filter: alpha(opacity=60); background-color: #777;  
            z-index: 1002; left: 0px;  
            opacity:0.5; -moz-opacity:0.5;  
        }
</style>
</head>
<script>
$(document).ready(function(){
	$('.has-items').eq(2).children(".level-2").show();
	$('.has-items').eq(2).addClass("selected");
	$('li .item').removeClass("selected");
	$('.has-items').eq(2).children(".level-2").children('li').eq(0).addClass('selected');
	$('#ajax_list').load('/index.php/Home/Authority/ajax_list?pageNum=<?php echo ($currentPage); ?>');
});
function close_modal()
{
	$('#modal').hide();
}
function open_modal()
{
	$('#modal').show();  
}
function insert()
{
	if($('#p_id').val()==0)
	{
		alert('请选择省份');
	}
	else if($('#title').val()=='')
	{
		alert('请输入名称');
		$('#title').focus();
	}
	else
	{
		$.post("/index.php/Home/Authority/insert", { title: $('#title').val(),p_id:$('#p_id').val() },
			function(data){
				//alert(data.msg);
				$('#ajax_list').load('/index.php/Home/Authority/ajax_list?pageNum=<?php echo ($currentPage); ?>');
				$('#ajax_txt').html(data.msg);
				$('#title').val('');
			}
		);
	}
}
function update()
{
	if($('#edit_p_id').val()==0)
	{
		alert('请选择省份');
	}
	else if($('#edit_title').val()=='')
	{
		alert('请输入名称');
		$('#edit_title').focus();
	}
	else
	{
		$.post("/index.php/Home/Authority/update", { id:$('#edit_id').val(),title: $('#edit_title').val(),p_id:$('#edit_p_id').val() },
			function(data){
				//alert(data.msg);
				$('#ajax_list').load('/index.php/Home/Authority/ajax_list?pageNum=<?php echo ($currentPage); ?>');
				$('#edit_ajax_txt').html(data.msg);
				$('#title').val('');
			}
		);
	}
}
function del()
{
	var variable = $("input[name='id[]']:checked").serialize();
	if(variable=='')
	{
		alert('请选择记录');
		return false;
	}
	if(!confirm("确认删除选中记录"))return false;
	$.ajax({
		url: "/index.php/Home/Authority/delete",
		type: "post",
		data: variable,
		success: function (result) {
			//handle
			alert(result.msg);
			$('#ajax_list').load('/index.php/Home/Authority/ajax_list?pageNum=<?php echo ($currentPage); ?>');
		}
	});
}
function close_edit_modal()
{
	$('#edit_modal').hide();
}
function open_edit_modal()
{
	$('#edit_modal').show();  
}
</script>

<body class="modal-ready">
<div id="mask" class="mask"></div>
	<div class="container">
    	<div class="viewport" id="console">
        	


            <div class="nav-zone" style="">
            	<div class="nav-zone-inner">


                    <div class="zone-items"><h5>吆喝</h5>
                    	<ul class="level-1">
                        	
                            <li class="cate has-items computing_networks"><a href="#"><span class="fa fa-computing_networks"></span><span class="text">首页管理</span></a>
                            	<ul class="level-2">
                                	<li class="item instances"><a href="/HomeClass" data-permalink=""><span class="fa fa-dot"></span><span class="text">首页分类管理</span></a></li>
                                    <li class="item images"><a href="/Rotate" data-permalink=""><span class="fa fa-dot"></span><span class="text">轮换图管理</span></a></li>
                                </ul>
                            </li>
                            <li class="cate has-items storage"><a href="#"><span class="fa fa-storage"></span><span class="text">商家管理</span></a>
                            	<ul class="level-2">
                                    <li class="item snapshots"><a href="/Shop/add" data-permalink=""><span class="fa fa-dot"></span><span class="text">新增商家</span></a></li>
                                    <li class="item snapshots"><a href="/Shop" data-permalink=""><span class="fa fa-dot"></span><span class="text">商家列表</span></a></li>
                                	<li class="item volumes"><a href="https://console.qingcloud.com/pek1/volumes/" data-permalink=""><span class="fa fa-dot"></span><span class="text">所有优惠</span></a></li>
                                    <li class="item vsans"><a href="https://console.qingcloud.com/pek1/vsans/" data-permalink=""><span class="fa fa-dot"></span><span class="text">所有评论</span></a></li>
                                    <li class="item snapshots"><a href="https://console.qingcloud.com/pek1/snapshots/" data-permalink=""><span class="fa fa-dot"></span><span class="text">所有粉丝</span></a></li>
                                </ul>
                            </li>
                            <li class="cate has-items security"><a href="#"><span class="fa fa-security"></span><span class="text">权限管理</span></a>
                            	<ul class="level-2">
                                	<li class="item security_groups"><a href="/Authority" data-permalink=""><span class="fa fa-dot"></span><span class="text">权限列表</span></a></li>
                                    <!--<li class="item keypairs"><a href="/Authority/add" data-permalink=""><span class="fa fa-dot"></span><span class="text">新增角色</span></a></li>-->
                                </ul>
                            </li>
                            <li class="cate has-items management"><a href="#"><span class="fa fa-access_keys"></span><span class="text">账号管理</span></a>
                            	<ul class="level-2">
                                	<li class="item security_groups"><a href="https://console.qingcloud.com/pek1/security_groups/" data-permalink=""><span class="fa fa-dot"></span><span class="text">用户管理</span></a></li>
                                    <li class="item keypairs"><a href="https://console.qingcloud.com/pek1/keypairs/" data-permalink=""><span class="fa fa-dot"></span><span class="text">商家管理</span></a></li>
                                    <li class="item keypairs"><a href="https://console.qingcloud.com/pek1/keypairs/" data-permalink=""><span class="fa fa-dot"></span><span class="text">管理员管理</span></a></li>
                                </ul>
                            </li>
                            <li class="cate has-items database"><a href="#"><span class="fa fa-database"></span><span class="text">优惠管理</span></a>
                            	<ul class="level-2">
                                	<li class="item rdbs"><a href="/Coupon" data-permalink=""><span class="fa fa-dot"></span><span class="text">优惠券</span></a></li>
                                    <li class="item caches"><a href="/Card" data-permalink=""><span class="fa fa-dot"></span><span class="text">会员卡</span></a></li>
                                    <li class="item caches"><a href="/NewProduct" data-permalink=""><span class="fa fa-dot"></span><span class="text">新品</span></a></li>
                                    <li class="item caches"><a href="/Activity" data-permalink=""><span class="fa fa-dot"></span><span class="text">活动</span></a></li>
                                    <li class="item caches"><a href="https://console.qingcloud.com/pek1/caches/" data-permalink=""><span class="fa fa-dot"></span><span class="text">吆喝</span></a></li>
                                </ul>
                            </li>
                            <li class="cate has-items management"><a href="#"><span class="fa fa-management"></span><span class="text">系统设置</span></a>
                            	<ul class="level-2">
                                	<li class="item alarms"><a href="/Province" data-permalink=""><span class="fa fa-dot"></span><span class="text">省份管理</span></a></li>
                                	<li class="item alarms"><a href="/City" data-permalink=""><span class="fa fa-dot"></span><span class="text">城市列表</span></a></li>
                                    <li class="item schedulers"><a href="/Area" data-permalink=""><span class="fa fa-dot"></span><span class="text">行政区管理</span></a></li>
                                    <li class="item autoscaling"><a href="/District" data-permalink=""><span class="fa fa-dot"></span><span class="text">商圈管理</span></a></li>
                                    <li class="item activities"><a href="/BindingClass" data-permalink=""><span class="fa fa-dot"></span><span class="text">绑定分类</span></a></li>
                                    <li class="item recyclebin"><a href="/Classify" data-permalink=""><span class="fa fa-dot"></span><span class="text">分类管理</span></a></li>
                                </ul>
                            </li>
                            <li class="cate has-items management"><a href="#"><span class="fa fa-subusers"></span><span class="text">评论管理</span></a>
                            	<ul class="level-2">
                                	<li class="item alarms"><a href="https://console.qingcloud.com/pek1/alarms/" data-permalink=""><span class="fa fa-dot"></span><span class="text">动态评论</span></a></li>
                                    <li class="item schedulers"><a href="https://console.qingcloud.com/pek1/schedulers/" data-permalink=""><span class="fa fa-dot"></span><span class="text">店铺点评</span></a></li>
                                </ul>
                            </li>
							
                            <li class="cate has-items management"><a href="#"><span class="fa fa-notification_lists"></span><span class="text">其它管理</span></a>
                            	<ul class="level-2">
                                	<li class="item alarms"><a href="https://console.qingcloud.com/pek1/alarms/" data-permalink=""><span class="fa fa-dot"></span><span class="text">意见反馈</span></a></li>
                                    <li class="item schedulers"><a href="https://console.qingcloud.com/pek1/schedulers/" data-permalink=""><span class="fa fa-dot"></span><span class="text">举报管理</span></a></li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>

			<!--下拉菜单-->

			<div class="nav-global" style="">
            	<div>
                	<div class="global-logo"><a class="logo" href="https://console.qingcloud.com/" data-permalink=""></a></div>
                    <div class="items-inner">
                    	<div class="global-toggle"><!--<a class="fa fa-nav-toggle show" href="javascript:void(0)"></a>--></div>
                        <div class="global-notice"></div>
                        <ul class="global-items">
                        	<li class="nav-item"><a class="nav-item-inner" href="javascript:void(0)"><span class="fa fa-tickets"></span><span class="nav-text">系统设置</span></a>
                            	<div class="dropdown-menu dropdown-menu-help" style="display: none;">
									<div class="dropdown-menu-inner">
                                    	<ul>
                                        	<li class="dropdown-menu-item"><a href="/Province"><span class="fa fa-guide"></span>省份管理</a></li>
                                        	<li class="dropdown-menu-item"><a href="/City"><span class="fa fa-guide"></span>城市管理</a></li>
                                            <li class="dropdown-menu-item"><a href="https://www.qingcloud.com/icp" target="_blank"><span class="fa fa-icp"></span>商圈管理</a></li>
                                            <li class="dropdown-menu-item"><a href="https://docs.qingcloud.com/faq/index.html" target="_blank"><span class="fa fa-faq"></span>绑定分类</a></li>
                                        </ul><div class="pipe"></div>
                                        <ul>
                                        	<li class="dropdown-menu-item"><a href="http://status.qingcloud.com/" target="_blank"><span class="fa fa-service_health"></span>分类管理</a></li>
                                        </ul>
                                    </div>
								</div></li>
                                
                            
                            <li class="nav-item"><a class="nav-item-inner" href="javascript:void(0)"><span class="fa fa-help"></span><span class="nav-text">首页管理</span></a>                      			<div class="dropdown-menu dropdown-menu-help" style="display: none;">
                                	<div class="dropdown-menu-inner">
                                    	<ul>
                                        	<li class="dropdown-menu-item"><a href="https://docs.qingcloud.com/guide/index.html" target="_blank"><span class="fa fa-guide"></span>首页分类管理</a></li>
                                            <li class="dropdown-menu-item"><a href="https://www.qingcloud.com/icp" target="_blank"><span class="fa fa-icp"></span>轮换图管理</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            
                            <li class="nav-item"><a class="nav-item-inner" href="javascript:void(0)"><span class="user-avatar" href="https://www.qingcloud.com/profile"></span><span class="user-name">账号管理</span></a>
                            	<div class="dropdown-menu dropdown-menu-profile" style="display: none;">
                                	<div class="dropdown-menu-inner">
                                    	<ul>
                                        	<li class="dropdown-menu-item"><a href="https://www.qingcloud.com/profile/basic" target="_blank"><span class="fa fa-settings"></span>用户管理</a></li>
                                            <li class="dropdown-menu-item"><a href="https://www.qingcloud.com/profile/phone" target="_blank"><span class="fa fa-phone"></span>商家管理</a></li>
                                            <li class="dropdown-menu-item"><a href="https://www.qingcloud.com/profile/password" target="_blank"><span class="fa fa-password"></span>管理员管理</a></li>
                                        </ul><!--<div class="pipe"></div>
                                        <ul>
                                        	<li class="dropdown-menu-item"><a href="https://console.qingcloud.com/logout"><span class="fa fa-logout"></span>退出</a></li>
                                        </ul>-->
                                    </div>
                                </div>
                            </li>
                            
                            <li class="nav-item"><a class="nav-item-inner" href="javascript:void(0)"><span class="fa fa-lock"></span><span class="nav-text">评论管理</span></a>
                            	<div class="dropdown-menu dropdown-menu-create-lock" style="display: none;">
                                	<div class="dropdown-menu-inner">
                                    	<ul>
                                        	<li class="dropdown-menu-item"><a class="create-lock" href="javascript:void(0)"><span class="fa"></span>动态评论</a></li>
                                        	<li class="dropdown-menu-item"><a class="create-lock" href="javascript:void(0)"><span class="fa"></span>店铺点评</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item"><a class="nav-item-inner" href="javascript:void(0)"><span class="fa fa-lang">其它</span></a>
                            	<div class="dropdown-menu" style="display: none;">
                                	<div class="dropdown-menu-inner">
                                    	<ul>
                                        	<li class="dropdown-menu-item"><a href="javascript:void(0)" data-lang="zh-cn"><span class="lang-text">意见反馈</span></a></li>
                                            <li class="dropdown-menu-item"><a href="javascript:void(0)" data-lang="en"><span class="lang-text">举报管理</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item"><a class="nav-item-inner" href="javascript:void(0)"><span class="fa fa-activities"></span></a>
                            	<div class="dropdown-menu" style="display: none;">
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            
            
            
            <div class="content" style="">
            	<div class="wrapper page">

					
                    <div class="pane">
                    
                    	<div class="toolbar"><a class="btn" href="javascript:void(0)"><span class="icon icon-refresh"></span></a><a class="btn" href="/index.php/Home/Authority/add"><span class="icon icon-run"></span><span class="text">新建</span></a><a class="btn" href="javascript:void(0)" onclick="del()"><span class="text">删除</span></a>
                            <!--
                            <div class="pagination"><span class="per-page">每页显示：&nbsp;</span>
                            	<div class="select-con"><select class="dropdown-select" name="limit"><option value="10" selected="">10</option><option value="20">20</option><option value="50">50</option><option value="100">100</option></select>
                                </div>
                            </div>
							-->
                        </div>
						<div id="ajax_list">
						</div>
						<?php echo ($page); ?>
                        
                       <p class="none" style="display: none;">结果为空</p>
                       <p class="tips">* 提示：可通过在各个资源上点击
                            <span class="alert-info">“双击”</span>来修改基本属性。
                       </p>
                       <div style="display: none;"><p class="loading">正在加载...</p></div>
                   </div>
                    
               </div>
           </div>
           <div class="notify" style=""></div>
           <div class="loading-overlay" style="display: none"></div>
           <div class="account-lock" style="display:none"></div>
		   
       </div>
   </div>
   
   </body></html>