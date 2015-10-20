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
	$('.has-items').eq(2).children(".level-2").show();
	$('.has-items').eq(2).addClass("selected");
	$('li .item').removeClass("selected");
	$('.has-items').eq(2).children(".level-2").children('li').eq(0).addClass('selected');
	$('#province_id').change(function(){
		$.ajax({
                type: "post",
                url: "?c=Role&a=getcitylist",
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
                }
        });
	})
});
function checkall()
{
	$("input:[type='checkbox']").attr('checked','true');
	//$("[name='classname']").attr("checked",'true');//全选
}
function cancelall()
{
	$("input:[type='checkbox']").attr('checked',false);
}
function addcity()
{
	//var tocitylist		=	$('#tocitylist').val();
	var cityid	=	parseInt($('#city_id').val());	
	if(cityid<1)
	{
		alert('请选择城市');
	}
	else
	{
		var checkcity=	$("#city_id").find("option:selected").text();
		var city_list=$('#tocitylist').val().split(',');
		flag=true;
		for (i = 0; i < city_list.length; i++)
		{
			if(city_list[i]==cityid)
			{
				flag=false;
				break;
			}
		}
		if(!flag)
		{
			alert(checkcity+"已经存在！");
		}
		else
		{	
			if(city_list=='')city_list=cityid;
			else city_list=$('#tocitylist').val()+','+cityid;
			
			$('#tocitylist').val(city_list);
			var cityhtml	=	'<span id="cityid_'+cityid+'">'+checkcity+"<input type='button' value='删除' onclick='deltocity("+cityid+")'></span>&nbsp;&nbsp;&nbsp;";
			$('#vid_city').append(cityhtml);
		}
	}
}
function deltocity(cityid)
{
	var tocitylist	=	$.trim($('#tocitylist').val());
	var city_list	=	tocitylist.split(',');
	var tocityhtml	=	'';
	var key=0;
	for (i = 0; i < city_list.length; i++)
	{
		if(city_list[i]==cityid)
		{
			key=i;
			continue;
		}
		if(tocityhtml=='')
		{
			tocityhtml	=	city_list[i];
		}
		else
		{
			tocityhtml	+=	','+city_list[i];
		}
	}
	$('#tocitylist').val(tocityhtml);

	$('#cityid_'+cityid).remove();
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
                    <ul class="page-tabs"><li class="selected"><a href="?c=Role&a=add" data-permalink="">添加角色</a></li></ul>
                    	<form name="form1" method="post" action="?c=Role&a=insert" onSubmit="return insert()" enctype="multipart/form-data">
                    	<table width="100%" class="table table-bordered table-hover">
							<thead>

								<tr>
								  <th width="103">角色名称</th>
								  <th colspan="4" align="left"><input name="title" type="text" id="title" size="40" /></th>
								  </tr>
								<tr>
								  <th width="103">职能描述</th>
								  <th colspan="4" align="left"><textarea name="content" id="content" cols="45" rows="5"></textarea>
								  <input type="hidden" name="tocitylist" id="tocitylist" />
								  </th>
								  </tr>
								<tr>
								  <th width="103">选择城市</th>
								  <th colspan="4" align="left"><select name="province_id" id="province_id">
									<option value="0">省</option>
									<?php if(is_array($province_list)): $i = 0; $__LIST__ = $province_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
									</select>
									<select name="city_id" id="city_id">
									  <option value="0">市</option>
									  </select><input type="button" value="添加城市" onClick="addcity()"></th>
								  </tr>
								<tr>
								  <th width="103">管理城市</th>
								  <th colspan="4" align="left" id="vid_city"></th>
								  </tr>
								<tr>
								  <th width="103">管理权限</th>
								  <th colspan="4" align="left"><input type="button" value="全选" onClick="checkall()"/><input type="button" value="取消全选" onClick="cancelall()"/></th>
								  </tr>
								<tr>
								  <th width="103">&nbsp;</th>
								  <th colspan="4" align="left">1.首页管理
							        <input name="classname[]" type="checkbox" id="classname[]" value="homepage">
								  <br />
							      &nbsp;&nbsp;&nbsp;&nbsp;1.1首页分类管理
                                    <input name="module[]" type="checkbox" id="module[]" value="HomeClass">
                                  <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.1.1编辑
<input name="method[HomeClass][]" type="checkbox" id="method[HomeClass][]" value="update">
								  <br />
                                  &nbsp;&nbsp;&nbsp;&nbsp;1.2轮换图管理
                                  <input name="module[]" type="checkbox" id="module[]" value="Rotate">
                                  <br />
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.2.1添加
                                  <input name="method[Rotate][]" type="checkbox" id="method[Rotate][]" value="insert">
1.2.2编辑
<input name="method[Rotate][]" type="checkbox" id="method[Rotate][]" value="update">
1.2.3上线
<input name="method[Rotate][]" type="checkbox" id="method[Rotate][]" value="online">
1.2.4下线
<input name="method[Rotate][]" type="checkbox" id="method[Rotate][]" value="offline">
								  <br />
                                  2.商家管理
                                  <input name="classname[]" type="checkbox" id="classname[]" value="shop"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;2.1商家管理
								  <input name="module[]" type="checkbox" id="module[]" value="Shop"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.1.1添加
								  <input name="method[Shop][]" type="checkbox" id="method[Shop][]" value="insert">
								  2.1.2编辑
								  <input name="method[Shop][]" type="checkbox" id="method[Shop][]" value="update">
								  2.1.3删除
								  <input name="method[Shop][]" type="checkbox" id="method[Shop][]" value="delete">
								2.1.4上线
								<input name="method[Shop][]" type="checkbox" id="method[Shop][]" value="online">
								2.1.5下线
								<input name="method[Shop][]" type="checkbox" id="method[Shop][]" value="offline">
								2.1.6审核通过
								<input name="method[Shop][]" type="checkbox" id="method[Shop][]" value="aduit">
								2.1.7驳回
								<input name="method[Shop][]" type="checkbox" id="method[Shop][]" value="reject">
								  <br />
                                  3.权限管理
                                  <input name="classname[]" type="checkbox" id="classname[]" value="authority">
                                  <br />
								  &nbsp;&nbsp;&nbsp;&nbsp;3.1权限管理
								  <input name="module[]" type="checkbox" id="module[]" value="Role"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.1.1添加
								  <input name="method[Role][]" type="checkbox" id="method[Role][]" value="insert">
								  3.1.2编辑
								  <input name="method[Role][]" type="checkbox" id="method[Role][]" value="update">
								  3.1.3删除
								  <input name="method[Role][]" type="checkbox" id="method[Role][]" value="delete">
								  <br />
								  4.账号管理
								  <input name="classname[]" type="checkbox" id="classname[]" value="account">
								  <br />
								  &nbsp;&nbsp;&nbsp;&nbsp;4.1用户管理
								  <input name="module[]" type="checkbox" id="module[]" value="User"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.1.1启用
								  <input name="method[User][]" type="checkbox" id="method[User][]" value="enable">
								  4.1.2禁用
								  <input name="method[User][]" type="checkbox" id="method[User][]" value="disable">
								  <br />
								  &nbsp;&nbsp;&nbsp;&nbsp;4.2商家管理
								  <input name="module[]" type="checkbox" id="module[]" value="Shopuser"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.2.1修改密码
								  <input name="method[Shopuser][]" type="checkbox" id="method[Shopuser][]" value="edit">
								  4.2.2启用
								  <input name="method[Shopuser][]" type="checkbox" id="method[Shopuser][]" value="enable">
								  4.2.3禁用
								  <input name="method[Shopuser][]" type="checkbox" id="method[Shopuser][]" value="disable"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;4.3管理员管理
								  <input name="module[]" type="checkbox" id="module[]" value="AdminUser"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.3.1增加
								  <input name="method[AdminUser][]" type="checkbox" id="method[AdminUser][]" value="insert">
								  4.3.2编辑
								  <input name="method[Adminuser][]" type="checkbox" id="method[AdminUser][]" value="update">
								  4.3.3删除
								  <input name="method[AdminUser][]" type="checkbox" id="method[AdminUser][]" value="delete">
								  <br />
								  5.优惠管理
								  <input name="classname[]" type="checkbox" id="classname[]" value="coupon"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;5.1优惠券
								  <input name="module[]" type="checkbox" id="module[]" value="Coupon"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5.1.1增加
								  <input name="method[Coupon][]" type="checkbox" id="method[Coupon][]" value="insert">
								  5.1.2编辑
								  <input name="method[Coupon][]" type="checkbox" id="method[Coupon][]" value="update">
								  5.1.3删除
								  <input name="method[Coupon][]" type="checkbox" id="method[Coupon][]" value="delete">
								  5.1.4上线
								  <input name="method[Coupon][]" type="checkbox" id="method[Coupon][]" value="online">
								  5.1.5下线
								  <input name="method[Coupon][]" type="checkbox" id="method[Coupon][]" value="offline"><br />
								  
								  &nbsp;&nbsp;&nbsp;&nbsp;5.2会员卡
								  <input name="module[]" type="checkbox" id="module[]" value="Card"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5.2.1增加
								  <input name="method[Card][]" type="checkbox" id="method[Card][]" value="insert">
								  5.2.2编辑
								  <input name="method[Card][]" type="checkbox" id="method[Card][]" value="update">
								  5.2.3删除
								  <input name="method[Card][]" type="checkbox" id="method[Card][]" value="delete">
								  5.2.4上线
								  <input name="method[Card][]" type="checkbox" id="method[Card][]" value="online">
								  5.2.5下线
								  <input name="method[Card][]" type="checkbox" id="method[Card][]" value="offline"><br />
								  
								  &nbsp;&nbsp;&nbsp;&nbsp;5.3新品
								  <input name="module[]" type="checkbox" id="module[]" value="NewProduct"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5.3.1增加
								  <input name="method[NewProduct][]" type="checkbox" id="method[NewProduct][]" value="insert">
								  5.3.2编辑
								  <input name="method[NewProduct][]" type="checkbox" id="method[NewProduct][]" value="update">
								  5.3.3删除
								  <input name="method[NewProduct][]" type="checkbox" id="method[NewProduct][]" value="delete">
								  5.3.4上线
								  <input name="method[NewProduct][]" type="checkbox" id="method[NewProduct][]" value="online">
								  5.3.5下线
								  <input name="method[NewProduct][]" type="checkbox" id="method[NewProduct][]" value="offline"><br />
								  
								  &nbsp;&nbsp;&nbsp;&nbsp;5.4活动
								  <input name="module[]" type="checkbox" id="module[]" value="Activity"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5.4.1增加
								  <input name="method[Activity][]" type="checkbox" id="method[Activity][]" value="insert">
								  5.4.2编辑
								  <input name="method[Activity][]" type="checkbox" id="method[Activity][]" value="update">
								  5.4.3删除
								  <input name="method[Activity][]" type="checkbox" id="method[Activity][]" value="delete">
								  5.4.4上线
								  <input name="method[Activity][]" type="checkbox" id="method[Activity][]" value="online">
								  5.4.5下线
								  <input name="method[Activity][]" type="checkbox" id="method[Activity][]" value="offline"><br />

								  &nbsp;&nbsp;&nbsp;&nbsp;5.5吆喝
								  <input name="module[]" type="checkbox" id="module[]" value="Call"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5.5.1删除
								  <input name="method[Call][]" type="checkbox" id="method[Call][]" value="delete">
								  <br />
								  6.系统设置
								  <input name="classname[]" type="checkbox" id="classname[]" value="system"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;6.1城市管理
								  <input name="module[]" type="checkbox" id="module[]" value="City"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6.1.1新增
								  <input name="method[City][]" type="checkbox" id="method[City][]" value="insert">
								  6.1.2热门
								  <input name="method[City][]" type="checkbox" id="method[City][]" value="hot">
								  6.1.3开启
								  <input name="method[City][]" type="checkbox" id="method[City][]" value="enable">
								  6.1.4关闭
								  <input name="method[City][]" type="checkbox" id="method[City][]" value="close"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;6.2商圈管理
								  <input name="module[]" type="checkbox" id="module[]" value="District"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6.2.1新增
								  <input name="method[District][]" type="checkbox" id="method[District][]" value="insert">
								  6.2.2编辑
								  <input name="method[District][]" type="checkbox" id="method[District][]" value="update">
								  6.2.3删除
								  <input name="method[District][]" type="checkbox" id="method[District][]" value="delete"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;6.3绑定分类
								  <input name="module[]" type="checkbox" id="module[]" value="BindingClass"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;6.4分类管理
								  <input name="module[]" type="checkbox" id="module[]" value="Classify"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6.4.1新增
								  <input name="method[Classify][]" type="checkbox" id="method[Classify][]" value="insert">
								  6.4.2编辑
								  <input name="method[Classify][]" type="checkbox" id="method[Classify][]" value="update">
								  6.4.3删除
								  <input name="method[Classify][]" type="checkbox" id="method[Classify][]" value="delete"><br />
								  7.评论管理
								  <input name="classname[]" type="checkbox" id="classname[]" value="comment"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;7.1动态评论
								  <input name="module[]" type="checkbox" id="module[]" value="DynamicComment"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7.1.1查看
								  <input name="method[DynamicComment][]" type="checkbox" id="method[DynamicComment][]" value="look">
								  7.1.2删除
								  <input name="method[DynamicComment][]" type="checkbox" id="method[DynamicComment][]" value="delete"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;7.2店铺点评
								  <input name="module[]" type="checkbox" id="module[]" value="ShopComment"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7.2.1查看
								  <input name="method[ShopComment][]" type="checkbox" id="method[ShopComment][]" value="look">
								  7.2.2删除
								  <input name="method[ShopComment][]" type="checkbox" id="method[ShopComment][]" value="delete">

								  <br />
								  8.其它管理
								  <input name="classname[]" type="checkbox" id="classname[]" value="other"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;8.1意见反馈
								  <input name="module[]" type="checkbox" id="module[]" value="Opinion"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8.1.1查看
								  <input name="method[Opinion][]" type="checkbox" id="method[Opinion][]" value="look">
								  8.1.2处理
								  <input name="method[Opinion][]" type="checkbox" id="method[Opinion][]" value="handle">
								  8.1.3撰写
								  <input name="method[Opinion][]" type="checkbox" id="method[Opinion][]" value="compose"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;8.2举报管理
								  <input name="module[]" type="checkbox" id="module[]" value="Report"><br />
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8.2.1查看
								  <input name="method[Report][]" type="checkbox" id="method[Report][]" value="look">
								  8.2.2处理
								  <input name="method[Report][]" type="checkbox" id="method[Report][]" value="handle">
								  8.2.3撰写
								  <input name="method[Report][]" type="checkbox" id="method[Report][]" value="compose">
								</th>
							  </tr>
								<tr>
								  <th width="103">&nbsp;</th>
								  <th width="87" align="left"></th>
								  <th colspan="3" align="left"></th>
						      </tr>
								<tr>
								  <th width="103">&nbsp;</th>
								  <th width="87" align="left"></th>
								  <th width="125" align="left"></th>
								  <th width="764" align="left">&nbsp;</th>
								  <th width="8" align="left"></th>
						      </tr>
								<tr>
								  <th width="103">&nbsp;</th>
								  <th colspan="4" align="left"><input type="submit" name="button" id="button" value="提交"></th>
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