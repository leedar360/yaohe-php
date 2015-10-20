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
<link rel="stylesheet" href="/Public/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="/Public/kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="/Public/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=5f4ba5ea591df8f97c6cf70820e576e8"></script>
<script type="text/javascript">
		var marker = new Array();
		var windowsArr = new Array(); 
		var map = new AMap.Map("mapContainer", {
			resizeEnable: true
		});
		function getmap() {
			var address=$('#address').val();
		    var MGeocoder;
			if(address=='')
			{
				alert('请输入详细地址');
			}
			else
			{
				//加载地理编码插件
				AMap.service(["AMap.Geocoder"], function() {        
					MGeocoder = new AMap.Geocoder({ 
						radius:1000 //范围，默认：500
					});
					//返回地理编码结果  
					//地理编码
					MGeocoder.getLocation(address, function(status, result){
						//alert(result.info);
						if(status === 'complete' && result.info === 'OK'){
							geocoder_CallBack(result);
						}else{
							alert('地址不存在');
						}
					});
				});
			}
		}  
		function addmarker(i, d) {
		    var lngX = d.location.getLng();
		    var latY = d.location.getLat();
		    var markerOption = {
		        map:map,                 
		        icon:"http://webapi.amap.com/images/"+(i+1)+".png",  
		        position:new AMap.LngLat(lngX, latY)
		    };            
		    var mar = new AMap.Marker(markerOption);  
		    marker.push(new AMap.LngLat(lngX, latY));
		
		    var infoWindow = new AMap.InfoWindow({  
		        content:d.formattedAddress, 
		        autoMove:true, 
		        size:new AMap.Size(150,0),  
		        offset:{x:0,y:-30}
		    });  
		    windowsArr.push(infoWindow);  
		    
		    var aa = function(e){infoWindow.open(map,mar.getPosition());};  
		    AMap.event.addListener(mar,"mouseover",aa);  
		}
		//地理编码返回结果展示   
		function geocoder_CallBack(data){
		    var resultStr="";
		    //地理编码结果数组
		    var geocode = new Array();
		    geocode = data.geocodes;  
			var $lng='';
			var $lat='';
		    for (var i = 0; i < geocode.length; i++) {
				$lng=geocode[i].location.getLng();
				$lat=geocode[i].location.getLat();
		    }  
			$('#lng').val($lng);
			$('#lat').val($lat)
			$('#long_lat_txt').html($lng+','+$lat);
		}  
	</script> 
<script>
$(document).ready(function(){
	$('.has-items').eq(1).children(".level-2").show();
	$('.has-items').eq(1).addClass("selected");
	$('li .item').removeClass("selected");
	$('.has-items').eq(1).children(".level-2").children('li').eq(1).addClass('selected');
	$('#province_id').change(function(){
		$.ajax({
                type: "post",
                url: "?c=Shop&a=getcitylist",
				data:{'province_id':$(this).val()},
                cache: false,
                datatype: "json",
                success: function(msg) {
					var str='<option value="0">请选择</option>';
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
                url: "?c=Shop&a=getarealist",
				data:{'city_id':$(this).val()},
                cache: false,
                datatype: "json",
                success: function(msg) {
					var str='<option value="0">请选择</option>';
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
                url: "?c=Shop&a=getdistrictlist",
				data:{'area_id':$(this).val()},
                cache: false,
                datatype: "json",
                success: function(msg) {
					var str='<option value="0">请选择</option>';
					var obj=eval(msg); 
                    $.each(obj, function(i) {
						str+='<option value="'+obj[i].id+'">'+obj[i].title+'</option>';
                    });
					$('#district_id').html(str);
                }
        });
	})
	$('#one_id').change(function(){
		$.ajax({
                type: "post",
                url: "?c=Shop&a=getsonclasslist",
				data:{'parentid':$(this).val()},
                cache: false,
                datatype: "json",
                success: function(msg) {
					var str='<option value="0">请选择</option>';
					var obj=eval(msg); 
                    $.each(obj, function(i) {
						str+='<option value="'+obj[i].id+'">'+obj[i].title+'</option>';
                    });
					$('#industry_class_id').html(str);
                }
        });
	})
});
KindEditor.ready(function(K) {
	K.create('textarea[name="content"]', {
		allowFileManager : true
	});
});
function ajax_mobile()
{
	var sMobile = $('#mobile').val(); 
    if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(sMobile))){ 
        alert("不是完整的11位手机号或者正确的手机号前七位"); 
		//$('#mobile').focus(); 
        return false; 
    }
	$.ajax({
			type: "post",
			url: "?c=Shop&a=checkmobile",
			data:{'mobile':sMobile},
			cache: false,
			datatype: "json",
			success: function(msg) {
				//var str='<option value="0">请选择</option>';
				var obj=eval(msg); 
				if(obj.falg==0)alert(obj.msg);
				/*$.each(obj, function(i) {
					str+='<option value="'+obj[i].id+'">'+obj[i].title+'</option>';
				});
				$('#industry_class_id').html(str);*/
			}
	});
}
function insert()
{
	var sMobile = $('#mobile').val(); 
    if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(sMobile))){ 
        alert("不是完整的11位手机号或者正确的手机号前七位"); 
		$('#mobile').focus(); 
        return false; 
    } 
	if($.trim($('#title').val())=='')
	{
		alert('请输入店铺名称');
		$('#title').val('');
		$('#title').focus();
		return false;
	}
	if($('#city_id').val()==0)
	{
		alert('请选择城市');
		return false;
	}
	if($('#industry_class_id').val()==0)
	{
		alert('请选择分类');
		return false;
	}
	/*if($('#district_id').val()==0)
	{
		alert('请选择商圈');
		return false;
	}*/
	if($.trim($('#business_time').val())=='')
	{
		alert('请输入营业时间');
		$('#business_time').val('');
		$('#business_time').focus();
		return false;
	}
	if($.trim($('#subscribe_tel').val())=='')
	{
		alert('请输入预约电话');
		$('#subscribe_tel').val('');
		$('#subscribe_tel').focus();
		return false;
	}
	if($.trim($('#address').val())=='')
	{
		alert('请输入详细地址');
		$('#address').val('');
		$('#address').focus();
		return false;
	}
	if($('#lng').val()=='0')
	{
		alert('请点击获取坐标');
		return false;
	}
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
                    <ul class="page-tabs"><li class="selected"><a href="?c=Shop&a=add" data-permalink="">基本信息</a></li><!--<li class=""><a href="?c=Shop&a=contract" data-permalink="">商家合同</a></li>--></ul>
                    	<form name="form1" method="post" action="?c=Shop&a=insert" onSubmit="return insert()" enctype="multipart/form-data">
                    	<table width="100%" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th width="127"><span style="color:#FF0000;">*</span>手机</th>
									<th width="972" align="left"><input name="mobile" type="text" id="mobile" size="40" onblur="ajax_mobile()"/></th>
								</tr>
								<tr>
									<th width="127"><span style="color:#FF0000;">*</span>店铺名称</th>
									<th width="972" align="left"><input name="title" type="text" id="title" size="40" /></th>
								</tr>
								<tr>
								  <th width="127"><span style="color:#FF0000;">*</span>所属城市</th>
								  <th align="left"><select name="province_id" id="province_id">
									<option value="0">请选择</option>
									<?php if(is_array($province_list)): $i = 0; $__LIST__ = $province_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
									</select>
									<select name="city_id" id="city_id">
									  <option value="0">请选择</option>
									  </select></th>
								  </tr>
								<tr>
								  <th width="127"><span style="color:#FF0000;">*</span>所属分类</th>
								  <th align="left"><select name="one_id" id="one_id">
									<option value="0">请选择</option>
									<?php if(is_array($class_list)): $i = 0; $__LIST__ = $class_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
									</select>
									<select name="industry_class_id" id="industry_class_id">
									  <option value="0">请选择</option>
									</select></th>
								  </tr>
								<tr>
								  <th width="127"><span style="color:#FF0000;">*</span>所属商圈</th>
								  <th align="left">
									<select name="area_id" id="area_id">
									<option value="0">请选择</option>
									</select>
									<select name="district_id" id="district_id">
									<option value="0">请选择</option>
									</select></th>
								  </tr>
								<tr>
								  <th width="127"><span style="color:#FF0000;">*</span>营业时间</th>
								  <th align="left"><input name="business_time" type="text" id="business_time" size="40" /></th>
								  </tr>
								<tr>
								  <th width="127"><span style="color:#FF0000;">*</span>预约电话</th>
								  <th align="left"><input name="subscribe_tel" type="text" id="subscribe_tel" size="40" /></th>
								  </tr>
								<tr>
								  <th width="127"><span style="color:#FF0000;">*</span>详细地址</th>
								  <th align="left"><input name="address" type="text" id="address" size="40" /></th>
								  </tr>
								<tr>
								  <th width="127">地图标点</th>
								  <th align="left">坐标<span id="long_lat_txt">（0，0）</span>
									<input type="hidden" name="lng" id="lng" value="0" />
									<input type="hidden" name="lat" id="lat" value="0" />
									<input type="button" name="button2" id="button2" value="获取坐标" onclick="getmap()">
									<!--<input type="button" name="button2" id="button2" value="地图标点" onclick="setlatlng($('#long').val(),$('#lat').val())">--></th>
								  </tr>
								<tr>
								  <th width="127"><span style="color:#FF0000;">*</span>签约状态</th>
								  <th align="left"><select name="is_sign" id="is_sign">
									<option value="0">未签约</option>
									<option value="1">已签约</option>
								  </select></th>
								  </tr>
								<tr>
								  <th width="127">图片</th>
								  <th align="left"><input type="file" id="img" name="img"/></th>
								</tr>
								<tr>
								  <th width="127">等级</th>
								  <th align="left"><select name="level" id="level">
									<option value="A">A</option>
									<option value="B">B</option>
									<option value="C">C</option>
								  </select></th>
								  </tr>
								<tr>
								  <th width="127">人均消费</th>
								  <th align="left"><input name="per_person" type="text" id="per_person" size="40" /></th>
								  </tr>
								<tr>
								  <th width="127">搜索关键词</th>
								  <th align="left"><input name="keywords[]" type="text" id="keywords1" size="10" style="width:100px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px"/>
									-
									  <input name="keywords[]" type="text" id="keywords2" size="10" style="width:100px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px"/>
									  -
									  <input name="keywords[]" type="text" id="keywords3" size="10" style="width:100px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px"/>
									  -
									  <input name="keywords[]" type="text" id="keywords4" size="10" style="width:100px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px"/>
									  -
									  <input name="keywords[]" type="text" id="keywords5" size="10" style="width:100px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px"/></th>
								  </tr>
								<tr>
								  <th width="127">商家介绍</th>
								  <th align="left"><textarea name="content" id="content" cols="45" rows="20"></textarea></th>
								  </tr>
								<tr>
								  <th width="127">&nbsp;</th>
								  <th align="left"><input type="submit" name="button" id="button" value="提交审核"></th>
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