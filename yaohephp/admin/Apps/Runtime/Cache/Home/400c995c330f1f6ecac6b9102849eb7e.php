<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>吆喝第一期后台</title>
<link rel="icon" href="https://console.qingcloud.com/static/images/favicon.ico?v=1" type="image/x-icon">
<link href=".//Public/console.1431962384.css" rel="stylesheet" type="text/css">
<script src=".//Public/jquery-1.7.2.min.js"></script>
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

<!--<div id="fullbg"></div>-->
<!--弹出窗口-->
<div class="modal" style="width: 600px; height: auto; margin-left: -300px; margin-top: -200px; top: 208px; z-index: 1000; left: 683px;display:none"><div class="modal-header" style="cursor: move;"><h4>创建私有网络<a href="javascript:void(0)" onclick="close_modal()" class="close"><span class="icon-close icon-Large"></span></a></h4></div><div class="modal-content" id=""><form class="form form-horizontal"><fieldset><legend>创建私有网络</legend><div class="item"><div class="control-label">名称</div><div class="controls"><input type="text" name="vxnet_name" autofocus></div></div><div class="item"><div class="control-label">数量</div><div class="controls"><input class="medium" type="text" name="count" value="1"></div></div><div class="item vxnet_type" style="display:none"><div class="control-label">管理方式</div><div class="controls"><div><input type="radio" id="vxnet_type_1" name="vxnet_type" value="1" checked=""><label class="inline" for="vxnet_type_1">受管</label><span class="help">受管私有网络可以使用青云 QingCloud 路由器来配置和管理其网络。</span></div><div><input type="radio" id="vxnet_type_0" name="vxnet_type" value="0"><label class="inline" for="vxnet_type_0">自管</label><span class="help">自管私有网络需要您自行配置和管理其网络。</span></div></div></div><div class="item"><div class="control-label"></div><div class="controls"><a class="advanced-options" href="#">显示高级选项...</a></div></div><div class="form-actions"><input class="btn btn-primary" type="submit" value="提交"><input class="btn btn-cancel" type="button" value="取消" onclick="close_modal()"></div></fieldset></form></div><div class="modal-footer"></div></div>
<!--弹出窗口-->
<body class="modal-ready">
<div id="mask" class="mask"></div>
	<div class="container">
    	<div class="viewport" id="console">
        	


            <div class="nav-zone" style="">
            	<div class="nav-zone-inner">


                    <div class="zone-items"><h5>吆喝</h5>
                    	<ul class="level-1">
                        	<?php
 ?>
                            <li class="cate has-items computing_networks"><a href="#"><span class="fa fa-computing_networks"></span><span class="text">首页管理</span></a>
                            	<ul class="level-2">
									<?php
 ?>
                                	<li class="item instances"><a href="/admin/?c=HomeClass" data-permalink=""><span class="fa fa-dot"></span><span class="text">首页分类管理</span></a></li>
									<?php
 ?>
									<!--<li class="item instances"></li>-->
									<?php
 ?>
                                    <li class="item images"><a href="/admin/?c=Rotate" data-permalink=""><span class="fa fa-dot"></span><span class="text">轮换图管理</span></a></li>
									<?php
 ?>
									<!--<li class="item instances"></li>-->
									<?php
 ?>
                                </ul>
                            </li>
							<?php
 ?>
							<!--<li class="cate has-items computing_networks"></li>-->
							<?php
 ?>
                            <li class="cate has-items storage"><a href="#"><span class="fa fa-storage"></span><span class="text">商家管理</span></a>
                            	<ul class="level-2">
                                    <li class="item snapshots"><a href="/admin/?c=Shop" data-permalink=""><span class="fa fa-dot"></span><span class="text">商家列表</span></a></li>
                                    <li class="item snapshots"><a href="/admin/?c=Shop&a=add" data-permalink=""><span class="fa fa-dot"></span><span class="text">新增商家</span></a></li>
									<!--
                                	<li class="item volumes"><a href="/CouponAll" data-permalink=""><span class="fa fa-dot"></span><span class="text">所有优惠</span></a></li>
                                    <li class="item vsans"><a href="/ShopComment" data-permalink=""><span class="fa fa-dot"></span><span class="text">所有评论</span></a></li>
                                    <li class="item snapshots"><a href="/ShopFans" data-permalink=""><span class="fa fa-dot"></span><span class="text">所有粉丝</span></a></li>
                                    <li class="item snapshots"><a href="/ShopLogs" data-permalink=""><span class="fa fa-dot"></span><span class="text">操作记录</span></a></li>
									-->
                                </ul>
                            </li>
                            <li class="cate has-items security"><a href="#"><span class="fa fa-security"></span><span class="text">权限管理</span></a>
                            	<ul class="level-2">
                                	<li class="item security_groups"><a href="/admin/?c=Role" data-permalink=""><span class="fa fa-dot"></span><span class="text">权限列表</span></a></li>
                                    <!--<li class="item keypairs"><a href="/Authority/add" data-permalink=""><span class="fa fa-dot"></span><span class="text">新增角色</span></a></li>-->
                                </ul>
                            </li>
                            <li class="cate has-items management"><a href="#"><span class="fa fa-access_keys"></span><span class="text">账号管理</span></a>
                            	<ul class="level-2">
                                	<li class="item security_groups"><a href="/admin/?c=User" data-permalink=""><span class="fa fa-dot"></span><span class="text">用户管理</span></a></li>
                                    <li class="item keypairs"><a href="/admin/?c=ShopUser" data-permalink=""><span class="fa fa-dot"></span><span class="text">商家管理</span></a></li>
                                    <li class="item keypairs"><a href="/admin/?c=AdminUser" data-permalink=""><span class="fa fa-dot"></span><span class="text">管理员管理</span></a></li>
                                </ul>
                            </li>
                            <li class="cate has-items database"><a href="#"><span class="fa fa-database"></span><span class="text">优惠管理</span></a>
                            	<ul class="level-2">
                                	<li class="item rdbs"><a href="/admin/?c=Coupon" data-permalink=""><span class="fa fa-dot"></span><span class="text">优惠券</span></a></li>
                                    <li class="item caches"><a href="/admin/?c=Card" data-permalink=""><span class="fa fa-dot"></span><span class="text">会员卡</span></a></li>
                                    <li class="item caches"><a href="/admin/?c=NewProduct" data-permalink=""><span class="fa fa-dot"></span><span class="text">新品</span></a></li>
                                    <li class="item caches"><a href="/admin/?c=Activity" data-permalink=""><span class="fa fa-dot"></span><span class="text">活动</span></a></li>
                                    <li class="item caches"><a href="/admin/?c=Call" data-permalink=""><span class="fa fa-dot"></span><span class="text">吆喝</span></a></li>
                                </ul>
                            </li>
                            <li class="cate has-items management"><a href="#"><span class="fa fa-management"></span><span class="text">系统设置</span></a>
                            	<ul class="level-2">
                                	<!--<li class="item alarms"><a href="/Province" data-permalink=""><span class="fa fa-dot"></span><span class="text">省份管理</span></a></li>-->
									<li class="item alarms"></li>
                                	<li class="item alarms"><a href="/admin/?c=City" data-permalink=""><span class="fa fa-dot"></span><span class="text">城市管理</span></a></li>
									<li class="item alarms"></li>
                                    <li class="item autoscaling"><a href="/admin/?c=District" data-permalink=""><span class="fa fa-dot"></span><span class="text">商圈管理</span></a></li>
                                    <li class="item activities"><a href="/admin/?c=BindingClass" data-permalink=""><span class="fa fa-dot"></span><span class="text">绑定分类</span></a></li>
                                    <li class="item recyclebin"><a href="/admin/?c=Classify" data-permalink=""><span class="fa fa-dot"></span><span class="text">分类管理</span></a></li>
                                    <li class="item schedulers"><a href="?c=Area" data-permalink=""><span class="fa fa-dot"></span><span class="text">行政区管理</span></a></li>
                                </ul>
                            </li>
                            <li class="cate has-items management"><a href="#"><span class="fa fa-subusers"></span><span class="text">评论管理</span></a>
                            	<ul class="level-2">
                                	<li class="item alarms"><a href="?c=DynamicComment" data-permalink=""><span class="fa fa-dot"></span><span class="text">动态评论</span></a></li>
                                    <li class="item schedulers"><a href="?c=ShopComment" data-permalink=""><span class="fa fa-dot"></span><span class="text">店铺点评</span></a></li>
                                </ul>
                            </li>
							
                            <li class="cate has-items management"><a href="#"><span class="fa fa-notification_lists"></span><span class="text">其它管理</span></a>
                            	<ul class="level-2">
                                	<li class="item alarms"><a href="?c=Opinion" data-permalink=""><span class="fa fa-dot"></span><span class="text">意见反馈</span></a></li>
                                    <li class="item schedulers"><a href="?c=Report" data-permalink=""><span class="fa fa-dot"></span><span class="text">举报管理</span></a></li>
                                </ul>
                            </li>
							
                            <li class="cate has-items management"><a href="#"><span class="fa fa-notification_lists"></span><span class="text">好玩</span></a>
                            	<ul class="level-2">
                                	<li class="item alarms"><a href="?c=Amusing" data-permalink=""><span class="fa fa-dot"></span><span class="text">好玩列表</span></a></li>
                                    <li class="item schedulers"><a href="?c=Amusing&a=add" data-permalink=""><span class="fa fa-dot"></span><span class="text">添加好玩</span></a></li>
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
                                        	<!--<li class="dropdown-menu-item"><a href="/Province"><span class="fa fa-guide"></span>省份管理</a></li>-->
                                        	<li class="dropdown-menu-item"><a href="/?c=City"><span class="fa fa-guide"></span>城市管理</a></li>
                                            <li class="dropdown-menu-item"><a href="/?c=District" target="_blank"><span class="fa fa-icp"></span>商圈管理</a></li>
                                            <li class="dropdown-menu-item"><a href="/?c=BindingClass"><span class="fa fa-faq"></span>绑定分类</a></li>
                                        </ul><div class="pipe"></div>
                                        <ul>
                                        	<li class="dropdown-menu-item"><a href="/?c=Classify"><span class="fa fa-service_health"></span>分类管理</a></li>
                                        </ul>
                                    </div>
								</div></li>
                                
                            
                            <li class="nav-item"><a class="nav-item-inner" href="javascript:void(0)"><span class="fa fa-help"></span><span class="nav-text">首页管理</span></a>                      			<div class="dropdown-menu dropdown-menu-help" style="display: none;">
                                	<div class="dropdown-menu-inner">
                                    	<ul>
                                        	<li class="dropdown-menu-item"><a href="/?c=HomeClass"><span class="fa fa-guide"></span>首页分类管理</a></li>
                                            <li class="dropdown-menu-item"><a href="/?c=Rotate"><span class="fa fa-icp"></span>轮换图管理</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            
                            <li class="nav-item"><a class="nav-item-inner" href="javascript:void(0)"><span class="user-avatar" href="https://www.qingcloud.com/?c=profile"></span><span class="user-name">账号管理</span></a>
                            	<div class="dropdown-menu dropdown-menu-profile" style="display: none;">
                                	<div class="dropdown-menu-inner">
                                    	<ul>
                                        	<li class="dropdown-menu-item"><a href="/?c=User"><span class="fa fa-settings"></span>用户管理</a></li>
                                            <li class="dropdown-menu-item"><a href="/?c=ShopUser"><span class="fa fa-phone"></span>商家管理</a></li>
                                            <li class="dropdown-menu-item"><a href="/?c=AdminUser"><span class="fa fa-password"></span>管理员管理</a></li>
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
                                        	<li class="dropdown-menu-item"><a class="create-lock" href="/?c=DynamicComment"><span class="fa"></span>动态评论</a></li>
                                        	<li class="dropdown-menu-item"><a class="create-lock" href="/?c=ShopComment"><span class="fa"></span>店铺点评</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item"><a class="nav-item-inner" href="javascript:void(0)"><span class="fa fa-lang">其它</span></a>
                            	<div class="dropdown-menu" style="display: none;">
                                	<div class="dropdown-menu-inner">
                                    	<ul>
                                        	<li class="dropdown-menu-item"><a href="/?c=Opinion" data-lang="zh-cn"><span class="lang-text">意见反馈</span></a></li>
                                            <li class="dropdown-menu-item"><a href="/?c=Report" data-lang="en"><span class="lang-text">举报管理</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item"><a class="nav-item-inner" href="?c=Login&a=logout"><span class="fa fa-activities"></span></a>
                            	<div class="dropdown-menu" style="display: none;">
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            
            
            
            <div class="content" style="">
            	<div class="wrapper page">
                	<div class="topbar">
                    	<div class="breadcrumbs">欢迎
                        </div>
                    </div>
                    <div class="page-intro instances"><p class="lead">欢迎进入</p>
                    </div>

					<!--
                    <div class="pane">
                    <!--
                    	<div class="toolbar"><a class="btn" href="javascript:void(0)"><span class="icon icon-refresh"></span></a><a class="btn" href="javascript:void(0)" onclick="open_modal()"><span class="icon icon-run"></span><span class="text">新建</span></a><a class="btn btn-forbidden" href="javascript:void(0)"><span class="icon icon-start"></span><span class="text">启动</span></a><a class="btn btn-forbidden" href="javascript:void(0)"><span class="icon icon-stop"></span><span class="text">关机</span></a>
                        	<div class="dropdown btn-disabled"><input class="dropdown-toggle" type="text">
                            	<div class="dropdown-text">更多操作</div>
                                <div class="dropdown-content">
                                	<a class="btn btn-forbidden" href=""><span class="icon icon-restart"></span><span class="text">重启</span></a>
                                    <a class="btn btn-forbidden" href="javascript:void(0)"><span class="icon icon-volumes"></span><span class="text">加载硬盘</span></a>
                                    <a class="btn btn-forbidden" href="javascript:void(0)"><span class="icon icon-keypairs"></span><span class="text">加载SSH密钥</span></a>
                                    <a class="btn btn-forbidden" href="javascript:void(0)"><span class="icon icon-security_groups"></span><span class="text">加载防火墙规则</span></a>
                                    <a class="btn btn-forbidden" href="javascript:void(0)"><span class="icon icon-vxnet"></span><span class="text">加入网络</span></a>
                                    <a class="btn btn-forbidden" href="javascript:void(0)"><span class="icon icon-resize"></span><span class="text">更改配置</span></a>
                                    <a class="btn btn-forbidden" href="javascript:void(0)"><span class="icon icon-snapshot"></span><span class="text">创建备份</span></a>
                                    <a class="btn btn-forbidden" href="javascript:void(0)"><span class="icon icon-alarms"></span><span class="text">绑定告警策略</span></a>
                                    <a class="btn btn-forbidden" href="javascript:void(0)"><span class="icon icon-reset"></span><span class="text">重置系统</span></a>
                                    <a class="btn btn-danger btn-forbidden" href="javascript:void(0)"><span class="icon icon-terminate"></span><span class="text">删除</span></a>
                                </div>
                            </div>
                            <div class="form-search"><input type="search" placeholder=" "></div>
                            <div class="pagination"><span class="per-page">每页显示：&nbsp;</span>
                            	<div class="select-con"><select class="dropdown-select" name="limit"><option value="10" selected="">10</option><option value="20">20</option><option value="50">50</option><option value="100">100</option></select>
                                </div>
                            </div>
                        </div>
					--><!--
                        <table class="table table-bordered table-hover">
                        	<thead>
                            	<tr>
                                	<th class="checkbox"><input type="checkbox"></th>
                                    <th>ID</th>
                                    <th>名称</th>
                                    <th>状态<div class="dropdown">
                                    	<input class="dropdown-toggle" type="text">
                                        	<div class="dropdown-text"></div>
                                            <div class="dropdown-content">
                                            	<a href="" data-filter="status" data-status="pending,running,stopped,suspended">全部</a>
                                                <a href="javascript:void(0)" data-filter="status" data-status="pending">等待中</a>
                                                <a href="javascript:void(0)" data-filter="status" data-status="running">运行中</a>
                                                <a href="javascript:void(0)" data-filter="status" data-status="stopped">已关机</a>
                                                <a href="javascript:void(0)" data-filter="status" data-status="suspended">已暂停</a>
                                                <a href="javascript:void(0)" data-filter="status" data-status="terminated">已删除</a>
                                                <a href="javascript:void(0)" data-filter="status" data-status="ceased">已销毁</a>
                                                </div></div></th>
                                    <th>映像 ID</th>
                                    <th>网络</th>
                                    <th>公网IP</th>
                                    <th>类型</th>
                                    <th>告警状态</th>
                                    <th>备份于</th>
                                    <th>创建于</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<tr id="i-7yummwd3">
                                	<td class="checkbox"><input type="checkbox"></td>
                                    <td><a class="id" href="https://console.qingcloud.com/pek1/instances/i-7yummwd3/" data-permalink="">i-7yummwd3</a><a class="btn-connect icon-instance" href="javascript:void(0)" data-tooltip="" title="" data-original-title="Web 终端"></a></td>
                                    <td class="name">cw_apps</td>
                                    <td class="running"><span class="icon-status icon-running"></span>&nbsp; 运行中</td>
                                    <td><a class="id" href="https://console.qingcloud.com/pek1/images/precisex64a/" data-permalink="">precisex64a</a></td><td><span class="id">(基础网络) / 10.50.81.31</span><br></td>
                                    <td><a class="id" href="https://console.qingcloud.com/pek1/eips/eip-wl1wdggk/" data-permalink="">117.121.25.43</a></td>
                                    <td>2核1G</td>
                                    <td><span class="none">无监控</span></td>
                                    <td class="time"><a class="btn-create-snapshot icon icon-snapshot" href="javascript:void(0)" data-tooltip="" title="" data-original-title="创建备份"></a></td>
                                    <td class="time">1年前</td>
                                </tr>
                                <tr id="i-dtmvt9nr">
                                	<td class="checkbox"><input type="checkbox"></td>
                                    <td><a class="id" href="https://console.qingcloud.com/pek1/instances/i-dtmvt9nr/" data-permalink="">i-dtmvt9nr</a><a class="btn-connect icon-instance" href="javascript:void(0)" data-tooltip="" title="" data-original-title="Web 终端"></a></td>
                                    <td class="name">cw_os</td>
                                    <td class="running"><span class="icon-status icon-running"></span>&nbsp; 运行中</td>
                                    <td><a class="id" href="https://console.qingcloud.com/pek1/images/precisex64a/" data-permalink="">precisex64a</a></td><td><span class="id">(基础网络) / 10.50.65.17</span><br></td>
                                    <td><a class="id" href="https://console.qingcloud.com/pek1/eips/eip-wzjydc8i/" data-permalink="">117.121.10.58</a></td><td>1核512MB</td>
                                    <td><span class="none">无监控</span></td>
                                    <td class="time"><a class="btn-create-snapshot icon icon-snapshot" href="javascript:void(0)" data-tooltip="" title="" data-original-title="创建备份"></a></td>
                                    <td class="time">1年前</td>
                               </tr>
                           </tbody><tfoot></tfoot>
                        </table>
                       <p class="none" style="display: none;">结果为空</p>
                       <p class="tips">* 提示：可通过在各个资源上点击
                       		<span class="alert-info">“右键”</span>来进行常用操作，以及
                            <span class="alert-info">“双击”</span>来修改基本属性。
                       </p>
                       <div style="display: none;"><p class="loading">正在加载...</p></div>
                   </div>
                    -->
               </div>
           </div>
           <div class="notify" style=""></div>
           <div class="loading-overlay" style="display: none"></div>
           <div class="account-lock" style="display:none"></div>
		   
       </div>
   </div>
   
   </body></html>