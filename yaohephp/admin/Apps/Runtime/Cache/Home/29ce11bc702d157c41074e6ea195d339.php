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
	$('.has-items').eq(1).children(".level-2").show();
	$('.has-items').eq(1).addClass("selected");
	$('li .item').removeClass("selected");
	$('.has-items').eq(1).children(".level-2").children('li').eq(0).addClass('selected');
	
	$("#all").click(function(){ 
		if(($(this).attr('checked')))
		{
			$("input[name='id[]']").each(function(){
				$(this).attr("checked",true);
			});  
		}
		else
		{
			$("input[name='id[]']").each(function(){
				$(this).attr("checked",false);
			});  
		}
	});
	$('#province_id').change(function(){
		$.ajax({
                type: "post",
                url: "?c=Shop&a=getcitylist",
				data:{'province_id':$(this).val()},
                cache: false,
                datatype: "json",
                success: function(msg) {
					var str='<option value="0">市</option>';
					var obj=eval(msg); 
                    $.each(obj, function(i) {
						str+='<option value="'+obj[i].id+'">'+obj[i].title+'</option>';
                    });
					$('#city_id').html(str);
					$('#area_id').html('<option value="0">区</option>');
					$('#district_id').html('<option value="0">商圈</option>');
                }
        });
	})
	$('#city_id').change(function(){
		$.ajax({
                type: "post",
                url: "?c=Shop&a=getarealist",
				data:{'city_id':$(this).val()},
                cache: false,
                datatype: "json",
                success: function(msg) {
					var str='<option value="0">区</option>';
					var obj=eval(msg); 
                    $.each(obj, function(i) {
						str+='<option value="'+obj[i].id+'">'+obj[i].title+'</option>';
                    });
					$('#area_id').html(str);
					$('#district_id').html('<option value="0">商圈</option>');
                }
        });
	})
	$('#area_id').change(function(){
		$.ajax({
                type: "post",
                url: "?c=Shop&a=getdistrictlist",
				data:{'area_id':$(this).val()},
                cache: false,
                datatype: "json",
                success: function(msg) {
					var str='<option value="0">商圈</option>';
					var obj=eval(msg); 
                    $.each(obj, function(i) {
						str+='<option value="'+obj[i].id+'">'+obj[i].title+'</option>';
                    });
					$('#district_id').html(str);
                }
        });
	})
	<?php
 if($province_id>0) { echo "$('#province_id').val(".$province_id.");\n"; } if($city_id>0) { echo "$('#city_id').val(".$city_id.");\n"; } if($area_id>0) { echo "$('#area_id').val(".$area_id.");\n"; } if($district_id>0) { echo "$('#district_id').val(".$district_id.");\n"; } if($one_id>0) { echo "$('#one_id').val(".$one_id.");\n"; } if($is_sign!='') { echo "$('#is_sign').val(".$is_sign.");\n"; } if($level!='') { echo "$('#level').val('".$level."');\n"; } if($status!='') { echo "$('#status').val(".$status.");\n"; } if($order_num!='') { echo "$('#order_num').val(".$order_num.");\n"; } ?>
});
function search()
{
	var url	=	'?c=Shop&a=index&province_id='+$('#province_id').val()+'&city_id='+$('#city_id').val();
	url+='&area_id='+$('#area_id').val()+'&district_id='+$('#district_id').val();
	url+='&one_id='+$('#one_id').val()+'&is_sign='+$('#is_sign').val()+'&level='+$('#level').val();
	url+='&status='+$('#status').val()+'&order_num='+$('#order_num').val()+'&keywords='+$('#keywords').val();
	window.location.replace(url);
}
//上线
function online()
{
	var variable = $("input[name='id[]']:checked").serialize();
	if(variable=='')
	{
		alert('请选择记录');
		return false;
	}
	if(!confirm("确认上线选中记录？"))return false;
	$.ajax({
		url: "?c=Shop&a=online",
		type: "post",
		data: variable,
		success: function (result) {
			//handle
			alert(result.msg);
			window.location.reload();
		}
	});
}
//下线
function offline()
{
	var variable = $("input[name='id[]']:checked").serialize();
	if(variable=='')
	{
		alert('请选择记录');
		return false;
	}
	if(!confirm("确认下线选中记录？"))return false;
	$.ajax({
		url: "?c=Shop&a=offline",
		type: "post",
		data: variable,
		success: function (result) {
			//handle
			alert(result.msg);
			window.location.reload();
		}
	});
}
function del()
{
	if(!confirm("确认删除此商家？"))return false;
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
                        	<?php
 ?>
                            <li class="cate has-items computing_networks"><a href="#"><span class="fa fa-computing_networks"></span><span class="text">首页管理</span></a>
                            	<ul class="level-2">
									<?php
 ?>
                                	<li class="item instances"><a href="/?c=HomeClass" data-permalink=""><span class="fa fa-dot"></span><span class="text">首页分类管理</span></a></li>
									<?php
 ?>
									<!--<li class="item instances"></li>-->
									<?php
 ?>
                                    <li class="item images"><a href="/?c=Rotate" data-permalink=""><span class="fa fa-dot"></span><span class="text">轮换图管理</span></a></li>
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
                                    <li class="item snapshots"><a href="/?c=Shop" data-permalink=""><span class="fa fa-dot"></span><span class="text">商家列表</span></a></li>
                                    <li class="item snapshots"><a href="/?c=Shop&a=add" data-permalink=""><span class="fa fa-dot"></span><span class="text">新增商家</span></a></li>
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
                                	<li class="item security_groups"><a href="/?c=Role" data-permalink=""><span class="fa fa-dot"></span><span class="text">权限列表</span></a></li>
                                    <!--<li class="item keypairs"><a href="/Authority/add" data-permalink=""><span class="fa fa-dot"></span><span class="text">新增角色</span></a></li>-->
                                </ul>
                            </li>
                            <li class="cate has-items management"><a href="#"><span class="fa fa-access_keys"></span><span class="text">账号管理</span></a>
                            	<ul class="level-2">
                                	<li class="item security_groups"><a href="/?c=User" data-permalink=""><span class="fa fa-dot"></span><span class="text">用户管理</span></a></li>
                                    <li class="item keypairs"><a href="/?c=ShopUser" data-permalink=""><span class="fa fa-dot"></span><span class="text">商家管理</span></a></li>
                                    <li class="item keypairs"><a href="/?c=AdminUser" data-permalink=""><span class="fa fa-dot"></span><span class="text">管理员管理</span></a></li>
                                </ul>
                            </li>
                            <li class="cate has-items database"><a href="#"><span class="fa fa-database"></span><span class="text">优惠管理</span></a>
                            	<ul class="level-2">
                                	<li class="item rdbs"><a href="/?c=Coupon" data-permalink=""><span class="fa fa-dot"></span><span class="text">优惠券</span></a></li>
                                    <li class="item caches"><a href="/?c=Card" data-permalink=""><span class="fa fa-dot"></span><span class="text">会员卡</span></a></li>
                                    <li class="item caches"><a href="/?c=NewProduct" data-permalink=""><span class="fa fa-dot"></span><span class="text">新品</span></a></li>
                                    <li class="item caches"><a href="/?c=Activity" data-permalink=""><span class="fa fa-dot"></span><span class="text">活动</span></a></li>
                                    <li class="item caches"><a href="/?c=Call" data-permalink=""><span class="fa fa-dot"></span><span class="text">吆喝</span></a></li>
                                </ul>
                            </li>
                            <li class="cate has-items management"><a href="#"><span class="fa fa-management"></span><span class="text">系统设置</span></a>
                            	<ul class="level-2">
                                	<!--<li class="item alarms"><a href="/Province" data-permalink=""><span class="fa fa-dot"></span><span class="text">省份管理</span></a></li>-->
									<li class="item alarms"></li>
                                	<li class="item alarms"><a href="/?c=City" data-permalink=""><span class="fa fa-dot"></span><span class="text">城市管理</span></a></li>
									<li class="item alarms"></li>
                                    <li class="item autoscaling"><a href="/?c=District" data-permalink=""><span class="fa fa-dot"></span><span class="text">商圈管理</span></a></li>
                                    <li class="item activities"><a href="/?c=BindingClass" data-permalink=""><span class="fa fa-dot"></span><span class="text">绑定分类</span></a></li>
                                    <li class="item recyclebin"><a href="/?c=Classify" data-permalink=""><span class="fa fa-dot"></span><span class="text">分类管理</span></a></li>
                                    <li class="item schedulers"><a href="?c=Area" data-permalink=""><span class="fa fa-dot"></span><span class="text">行政区管理</span></a></li>
                                </ul>
                            </li>
                            <li class="cate has-items management"><a href="#"><span class="fa fa-subusers"></span><span class="text">评论管理</span></a>
                            	<ul class="level-2">
                                	<li class="item alarms"><a href="/?c=DynamicComment" data-permalink=""><span class="fa fa-dot"></span><span class="text">动态评论</span></a></li>
                                    <li class="item schedulers"><a href="/?c=ShopComment" data-permalink=""><span class="fa fa-dot"></span><span class="text">店铺点评</span></a></li>
                                </ul>
                            </li>
							
                            <li class="cate has-items management"><a href="#"><span class="fa fa-notification_lists"></span><span class="text">其它管理</span></a>
                            	<ul class="level-2">
                                	<li class="item alarms"><a href="/?c=Opinion" data-permalink=""><span class="fa fa-dot"></span><span class="text">意见反馈</span></a></li>
                                    <li class="item schedulers"><a href="/?c=Report" data-permalink=""><span class="fa fa-dot"></span><span class="text">举报管理</span></a></li>
                                </ul>
                            </li>
							
                            <li class="cate has-items management"><a href="#"><span class="fa fa-notification_lists"></span><span class="text">好玩</span></a>
                            	<ul class="level-2">
                                	<li class="item alarms"><a href="/?c=Amusing" data-permalink=""><span class="fa fa-dot"></span><span class="text">好玩列表</span></a></li>
                                    <li class="item schedulers"><a href="/?c=Amusing&a=add" data-permalink=""><span class="fa fa-dot"></span><span class="text">添加好玩</span></a></li>
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

					
                    <div class="pane">
                    
                    	<div class="toolbar">
							<div class="select-con">
								<select class="dropdown-select" name="province_id" id="province_id"><option value="0">省</option><?php if(is_array($province_list)): $i = 0; $__LIST__ = $province_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select>
							</div>
							<div class="select-con">
								<select class="dropdown-select" name="city_id" id="city_id"><option value="0">市</option>
								<?php if(is_array($city_list)): $i = 0; $__LIST__ = $city_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
								</select>
							</div>
							<div class="select-con">
								<select class="dropdown-select" name="area_id" id="area_id"><option value="0">区</option>
								<?php if(is_array($area_list)): $i = 0; $__LIST__ = $area_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
								</select>
							</div>
							<div class="select-con">
								<select class="dropdown-select" name="district_id" id="district_id"><option value="0">商圈</option>
								<?php if(is_array($district_list)): $i = 0; $__LIST__ = $district_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
								</select>
							</div>
							<div class="select-con">
								<select class="dropdown-select" name="one_id" id="one_id"><option value="0">分类</option>
								<?php if(is_array($class_list)): $i = 0; $__LIST__ = $class_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option value="<?php echo ($item["id"]); ?>"><?php echo ($item["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
								</select>
							</div>
							<div class="select-con">
								<select class="dropdown-select" name="is_sign" id="is_sign"><option value="">签约</option><option value="0">否</option><option value="1">是</option></select>
							</div>
							<div class="select-con">
								<select class="dropdown-select" name="level" id="level"><option value="">等级</option><option value="A">A</option><option value="B">B</option><option value="C">C</option></select>
							</div>
							<div class="select-con">
								<select class="dropdown-select" name="status" id="status"><option value="">状态</option><option value="0">待审核</option><option value="1">已上线</option><option value="2">已驳回</option><option value="3">已下线</option></select>
							</div>
							<div class="select-con">
								<select class="dropdown-select" name="order_num" id="order_num"><option value="0">排序</option><option value="1">评价最高</option><option value="2">最新发布</option><option value="3">人气最高</option><option value="4">价格最低</option><option value="5">价格最高</option></select>
							</div>
							<div class="form-search"><input type="search" id="keywords" placeholder="商家名称/商家编号" value="<?php echo ($keywords); ?>"></div>
							<a class="btn" href="javascript:search()"><span class="icon"></span><span class="text">搜索</span></a>
                        </div>
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="checkbox"><input type="checkbox" id="all"></th>
									<th>商家编号</th>
									<th>商家名称</th>
									<th>分类</th>
									<th>城市</th>
									<th>商圈</th>
									<th>是否签约</th>
									<th>等级</th>
									<th>状态</th>
									<th>最后更新时间</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
							<?php
 $statusarr[0]='待审核'; $statusarr[1]='已上线'; $statusarr[2]='已驳回'; $statusarr[3]='已下线'; $signarr[0]='否'; $signarr[1]='是'; ?>
							<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="dbclick">
									<td class="checkbox"><input type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>"></td>
									<td><?php echo ($vo["id"]); ?></td>
									<td><a href='?c=Shop&a=view&id=<?php echo ($vo["id"]); ?>'><?php echo ($vo["title"]); ?></a></td>
									<td><?php echo ($classarr[$vo['industry_class_id']]); ?></td>
									<td><?php echo ($cityarr[$vo['city_id']]); ?></td>
									<td><?php echo ($districtarr[$vo['district_id']]); ?></td>
									<td><?php echo ($signarr[$vo['is_sign']]); ?></td>
									<td><?php echo ($vo["level"]); ?></td>
									<td><?php echo ($statusarr[$vo['status']]); ?></td>
									<td><?php echo (date('Y-m-d H:i:s',$vo["last_update_time"])); ?></td>
									<td><!--审核通过 上线--> <a href='?c=Shop&a=edit&id=<?php echo ($vo["id"]); ?>'>编辑</a> <a href='?c=Shop&a=delete&id=<?php echo ($vo["id"]); ?>' onclick="return del()">删除</a></td>
								</tr><?php endforeach; endif; else: echo "" ;endif; ?>
						   </tbody><tfoot></tfoot>
						</table>
						<?php echo ($page); ?>
						<div class="toolbar">
						<a class="btn" href="javascript:online()"><span class="icon"></span><span class="text">上线</span></a>
						<a class="btn" href="javascript:offline()"><span class="icon"></span><span class="text">下线</span></a>
						</div>
                   </div>
                    
               </div>
           </div>
           <div class="notify" style=""></div>
           <div class="loading-overlay" style="display: none"></div>
           <div class="account-lock" style="display:none"></div>
		   
       </div>
   </div>
   
   </body></html>