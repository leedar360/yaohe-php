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
<script type="text/javascript" src="/Public/wdatepicker/WdatePicker.js"></script>
<script>
$(document).ready(function(){
	$('.has-items').eq(4).children(".level-2").show();
	$('.has-items').eq(4).addClass("selected");
	$('li .item').removeClass("selected");
	$('.has-items').eq(4).children(".level-2").children('li').eq(0).addClass('selected');
	$('#type').change(function(){
		if($(this).val()==0)
		{
			$('#type0').show();
			$('#type1').hide();
			$('#type2').hide();
			$('#type3').hide();
		}
		else if($(this).val()==1)
		{
			$('#type0').hide();
			$('#type1').show();
			$('#type2').hide();
			$('#type3').hide();
		}
		else if($(this).val()==2)
		{
			$('#type0').hide();
			$('#type1').hide();
			$('#type2').show();
			$('#type3').hide();
		}
		else if($(this).val()==3)
		{
			$('#type0').hide();
			$('#type1').hide();
			$('#type2').hide();
			$('#type3').show();
		}

	})
	$('#type<?php echo ($vo["type"]); ?>').show();
	$('#type').val(<?php echo ($vo["type"]); ?>);
	<?php
 switch($vo['type']) { case 0: echo "$('#term_start_0').val('".$vo['term_start']."');\n"; echo "$('#term_end_0').val('".$vo['term_end']."');\n"; break; case 1: echo "$('#term_start_1').val('".$vo['term_start']."');\n"; echo "$('#term_end_1').val('".$vo['term_end']."');\n"; break; case 2: echo "$('#term_start_2').val('".$vo['term_start']."');\n"; break; case 3: echo "$('#term_start_3').val('".$vo['term_start']."');\n"; break; } ?>
	$('#province_id').change(function(){
		$.ajax({
                type: "post",
                url: "?c=Coupon&a=getcitylist",
				data:{'province_id':$(this).val()},
                cache: false,
                datatype: "json",
                success: function(msg) {
					var str='<option value="0">全部</option>';
					var obj=eval(msg); 
                    $.each(obj, function(i) {
						str+='<option value="'+obj[i].id+'">'+obj[i].title+'</option>';
                    });
					$('#city_id').html(str);
                }
        });
	})
	$('#city_id').change(function(){
		$.ajax({
                type: "post",
                url: "?c=Coupon&a=getarealist",
				data:{'city_id':$(this).val()},
                cache: false,
                datatype: "json",
                success: function(msg) {
					var str='<option value="0">全部</option>';
					var obj=eval(msg); 
                    $.each(obj, function(i) {
						str+='<option value="'+obj[i].id+'">'+obj[i].title+'</option>';
                    });
					$('#area_id').html(str);
                }
        });
	})
	$('#area_id').change(function(){
		$.ajax({
                type: "post",
                url: "?c=Coupon&a=getdistrictlist",
				data:{'area_id':$(this).val()},
                cache: false,
                datatype: "json",
                success: function(msg) {
					var str='<option value="0">全部</option>';
					var obj=eval(msg); 
                    $.each(obj, function(i) {
						str+='<option value="'+obj[i].id+'">'+obj[i].title+'</option>';
                    });
					$('#district_id').html(str);
                }
        });
	})
});
function update()
{
	if($('#member_id').val()<1)
	{
		alert('请选择商家');
		return false;
	}
	if($('#type').val()==0)
	{
		if($('#term_start_0').val()=='' || $('#term_end_0').val()=='')
		{
			alert('请填写条件');
			$('#term_start_0').focus();
			return false;
		}
	}
	if($('#type').val()==1)
	{
		if($('#term_start_1').val()=='' || $('#term_end_1').val()=='')
		{
			alert('请填写条件');
			$('#term_start_1').focus();
			return false;
		}
	}
	if($('#type').val()==2)
	{
		if($('#term_start_2').val()=='')
		{
			alert('请填写条件');
			$('#term_start_2').focus();
			return false;
		}
	}
	if($('#type').val()==3)
	{
		if($('#term_start_3').val()=='')
		{
			alert('请填写条件');
			$('#term_start_3').focus();
			return false;
		}
	}
	if($('#title').val()=='')
	{
		alert('请输入标题');
		$('#title').focus();
		return false;
	}
	if($('#num').val()=='')
	{
		alert('请输入发行量');
		$('#num').focus();
		return false;
	}
}
function close_modal()
{
	$('#modal').hide();
}
function open_modal()
{
	$('#modal').show();  
}
function confirmcheck()
{
	var member_id=$('input[name="shop_member_id"]:checked').val();
	if(member_id===undefined)
	{
		alert('请选择商家');
	}
	else
	{
		$('#member_id').val(member_id);
		$('#shop_title').html($('input[name="shop_member_id"]:checked').attr('title'));
		close_modal();
		//alert($('input[name="member_id"]:checked').attr('title'));
	}
	//alert('确认选中');
}
function search()
{
	var url='?c=Coupon&a=search_list&province_id='+$('#province_id').val()+'&city_id='+$('#city_id').val();
	url+='&area_id='+$('#area_id').val()+'&district_id='+$('#district_id').val()+'&keywords='+$('#keywords').val();
	$('#ajax_list').load(url);
}
</script>

<!--弹出窗口-->
<div class="modal" id="modal" style="width: 750px; height: auto; margin-left: -300px; margin-top: -200px; top: 208px; z-index: 1000; left: 683px;display:none"><div class="modal-header" style="cursor: move;"><h4>选择商家<a href="javascript:void(0)" onclick="close_modal()" class="close"><span class="icon-close icon-Large"></span></a></h4></div>
<div class="modal-content" id="">
<div class="item"><div class="controls">
<div class="toolbar">
	<div class="select-con"><select class="dropdown-select" name="province_id" id="province_id"><option value="0">省</option><?php if(is_array($province_list)): $i = 0; $__LIST__ = $province_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option value="<?php echo ($item["id"]); ?>"><?php echo ($item["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select></div>
	
	<div class="select-con"><select class="dropdown-select" name="city_id" id="city_id"><option value="0">城</option></select></div>
	
	<div class="select-con"><select class="dropdown-select" name="area_id" id="area_id"><option value="0">区</option></select></div>
	
	<div class="select-con"><select class="dropdown-select" name="district_id" id="district_id"><option value="0">商圈</option></select></div>
	<div class="form-search"><input type="search" id="keywords" placeholder="商家名称/商家编号" value="<?php echo ($keywords); ?>"></div>
	<a class="btn" href="javascript:search()"><span class="icon"></span><span class="text">搜索</span></a>
	<a class="btn" href="javascript:confirmcheck()"><span class="icon"></span><span class="text">确认</span></a>
</div>
<div id="ajax_list">
<table class="table table-bordered table-hover">
	<tbody>
	<!--
	<tr>
	<td>
	<input name="member_id" type="radio" id="member_id" value="1" />绍月公司
	</td>
	<td>
	<input name="member_id" type="radio" id="member_id" value="1" />绍月公司
	</td>
	<td>
	<input name="member_id" type="radio" id="member_id" value="1" />绍月公司
	</td>
	</tr>
	-->
   </tbody><tfoot></tfoot>
</table>
</div>
</div></div>
</div>
</div>

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
                    <ul class="page-tabs"><li class="selected"><a href="?c=Coupon&a=edit?id=<?php echo ($vo["id"]); ?>" data-permalink="">编辑优惠券</a></li></ul>
                    	<form name="form1" method="post" action="?c=Coupon&a=update" onSubmit="return update()" enctype="multipart/form-data">
						<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
                    	<table width="100%" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th width="127"><span style="color:#FF0000;">*</span>商家</th>
									<th width="972" align="left"><span id="shop_title"><?php echo ($shop_title); ?></span><input type="hidden" id="member_id" name="member_id" value="<?php echo ($vo["member_id"]); ?>" /><input type="button" value="选择商家" onclick="open_modal()"/></th>
								</tr>
								<tr>
								  <th width="127">类型</th>
								  <th align="left"><select name="type" id="type">
									<option value="0">满减券</option>
									<option value="1">满赠券</option>
									<option value="2">代金券</option>
									<option value="3">折扣券</option>
									</select></th>
								  </tr>

								  <tr id="type0" style="display:none">
								  <th width="127">条件：</th>
								  <th align="left">满：<input type="text" name="term_start_0" id="term_start_0" onkeypress="return event.keyCode>=48&&event.keyCode<=57"/>元，减<input type="text" name="term_end_0" id="term_end_0" onkeypress="return event.keyCode>=48&&event.keyCode<=57">元</th>
								  </tr>
								  <!--满赠券-->
								<tr id="type1" style="display:none">
								  <th width="127">条件：</th>
								  <th align="left">满：<input type="text"  name="term_start_1" id="term_start_1" onkeypress="return event.keyCode>=48&&event.keyCode<=57"/>赠送<input type="text" name="term_end_1" id="term_end_1" onkeypress="return event.keyCode>=48&&event.keyCode<=57">礼品</th>
								  </tr>
								  <!--代金券-->
								<tr id="type2" style="display:none">
								  <th width="127">条件：</th>
								  <th align="left"><input type="text"  name="term_start_2" id="term_start_2" onkeypress="return event.keyCode>=48&&event.keyCode<=57"/>元</th>
								  </tr>
								  <!--折扣券-->
								<tr id="type3" style="display:none">
								  <th width="127">条件：</th>
								  <th align="left"><input type="text"  name="term_start_3" id="term_start_3" onkeypress="return event.keyCode>=48&&event.keyCode<=57"/>折</th>
								  </tr>

								<tr>
								  <th width="127">标题</th>
								  <th align="left"><input name="title" type="text" id="title" size="40" value="<?php echo ($vo["title"]); ?>"/></th>
								  </tr>
								<tr>
								  <th width="127">使用范围</th>
								  <th align="left"><textarea name="content" id="content" cols="45" rows="5"><?php echo ($vo["content"]); ?></textarea></th>
								  </tr>
								<?php
 if(!empty($vo['img1'])) { ?>
								<tr>
								  <th width="127">已传图片1</th>
								  <th align="left"><img src='http://static.htcheng.com/imgs/<?php echo ($vo["img1"]); ?>' width="200" height="200" ></th>
								</tr>
								<?php
 } ?>
								<tr>
								  <th width="127">图片1</th>
								  <th align="left"><input type="file" id="img1" name="img1"/></th>
								</tr>
								<?php
 if(!empty($vo['img2'])) { ?>
								<tr>
								  <th width="127">已传图片2</th>
								  <th align="left"><img src='http://static.htcheng.com/imgs/<?php echo ($vo["img2"]); ?>' width="200" height="200" ></th>
								</tr>
								<?php
 } ?>
								<tr>
								  <th width="127">图片2</th>
								  <th align="left"><input type="file" id="img2" name="img2"/></th>
								</tr>
								<?php
 if(!empty($vo['img3'])) { ?>
								<tr>
								  <th width="127">已传图片3</th>
								  <th align="left"><img src='http://static.htcheng.com/imgs/<?php echo ($vo["img3"]); ?>' width="200" height="200" ></th>
								</tr>
								<?php
 } ?>
								<tr>
								  <th width="127">图片3</th>
								  <th align="left"><input type="file" id="img3" name="img3"/></th>
								</tr>
								<?php
 if(!empty($vo['img4'])) { ?>
								<tr>
								  <th width="127">已传图片4</th>
								  <th align="left"><img src='http://static.htcheng.com/imgs/<?php echo ($vo["img4"]); ?>' width="200" height="200" ></th>
								</tr>
								<?php
 } ?>
								<tr>
								  <th width="127">图片4</th>
								  <th align="left"><input type="file" id="img4" name="img4"/></th>
								</tr>
								<?php
 if(!empty($vo['img5'])) { ?>
								<tr>
								  <th width="127">已传图片5</th>
								  <th align="left"><img src='http://static.htcheng.com/imgs/<?php echo ($vo["img5"]); ?>' width="200" height="200" ></th>
								</tr>
								<?php
 } ?>
								<tr>
								  <th width="127">图片5</th>
								  <th align="left"><input type="file" id="img5" name="img5"/></th>
								</tr>
								<?php
 if(!empty($vo['img6'])) { ?>
								<tr>
								  <th width="127">已传图片6</th>
								  <th align="left"><img src='http://static.htcheng.com/imgs/<?php echo ($vo["img6"]); ?>' width="200" height="200" ></th>
								</tr>
								<?php
 } ?>
								<tr>
								  <th width="127">图片6</th>
								  <th align="left"><input type="file" id="img6" name="img6"/></th>
								</tr>
								<tr>
								  <th width="127">有效期</th>
								  <th align="left"><input type="text1" style="width:150px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px" name="valid_start" id="valid_start" class="Wdate" onfocus="WdatePicker()" value="<?php echo ($vo["valid_start"]); ?>"/>至<input type="text1" style="width:150px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px" name="valid_end" id="valid_end" class="Wdate" onfocus="WdatePicker()" value="<?php echo ($vo["valid_end"]); ?>"/></th>
								</tr>
								<tr>
								  <th width="127">上线时间</th>
								  <th align="left"><input type="text1" name="online_time" id="online_time" style="width:150px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px" name="valid_start" id="valid_start" class="Wdate" onfocus="WdatePicker()" value="<?php echo ($vo["online_time"]); ?>"/></th>
								</tr>
								<tr>
								  <th width="127">发行量</th>
								  <th align="left"><input type="text" name="num" id="num" value="<?php echo ($vo["num"]); ?>"/></th>
								</tr>
								<tr>
								  <th width="127">使用次数</th>
								  <th align="left"><input name="use" type="radio" id="use1" value="0" <?php if($vo['use']==0)echo 'checked="checked"';?> />单次
									<input name="use" type="radio" id="use2" value="1" <?php if($vo['use']==1)echo 'checked="checked"';?>/>多次</th>
								</tr>
								<tr>
								  <th width="127">&nbsp;</th>
								  <th align="left"><input type="submit" name="button" id="button" value="提交"></th>
								  </tr>
							</thead>
							<tbody>
							
						   </tbody><tfoot></tfoot>
						</table>
                  	  </form>

                        
                       
                   </div>
                    
               </div>
           </div>
           <div class="notify" style=""></div>
           <div class="loading-overlay" style="display: none"></div>
           <div class="account-lock" style="display:none"></div>
		   
       </div>
   </div>
   
   </body></html>