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
	$('#audit').click(function() {
		var content = $('#content').val();
		if (content == '') {
			alert('请填写驳回理由');
			return false;
		};
		$('#form1').attr('action', '?c=Shop&a=audit');
		$('#form1').submit();
	});
	$('#audit2').click(function() {
		var content = $('#contract_content').val();
		if (content == '') {
			alert('请填写驳回理由');
			return false;
		};
		$('#form2').attr('action', '?c=Shop&a=contractaudit');
		$('#form2').submit();
	});
});
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
                    <ul class="page-tabs">
					<li class="selected"><a href="?c=Shop&a=view?id=<?php echo ($vo["member_id"]); ?>" data-permalink="">基本信息</a></li>
					<li class=""><a href="?c=CouponAll&shop_id=<?php echo ($vo["member_id"]); ?>" data-permalink="">所有优惠</a></li>
					<li class=""><a href="?c=ShopComment&shop_id=<?php echo ($vo["member_id"]); ?>" data-permalink="">评论</a></li>
					<li class=""><a href="?c=ShopFans&shop_id=<?php echo ($vo["member_id"]); ?>" data-permalink="">粉丝</a></li>
					<li class=""><a href="?c=ShopLogs&shop_id=<?php echo ($vo["member_id"]); ?>" data-permalink="">操作记录</a></li>
					</ul>
                    	<table width="100%" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th width="127">商家名称</th>
									<th width="972" align="left"><?php echo ($vo["title"]); ?></th>
								</tr>
								<tr>
								  <th width="127">所属分类</th>
								  <th align="left"><?php echo ($class_arr[$vo['one_id']]); ?>&nbsp;&nbsp;<?php echo ($class_arr[$vo['industry_class_id']]); ?></th>
								  </tr>
								<tr>
								  <th width="127">所属城市</th>
								  <th align="left"><?php echo ($province_arr[$vo['province_id']]); ?>&nbsp;&nbsp;
									<?php echo ($city_arr[$vo['city_id']]); ?></th>
								  </tr>
								<tr>
								  <th width="127"><span style="color:#FF0000;">*</span>所属商圈</th>
								  <th align="left">
								  <?php echo ($district_arr[$vo['district_id']]); ?>	
								  </th>
								  </tr>
								<tr>
								  <th width="127">营业时间</th>
								  <th align="left"><?php echo ($vo["business_time"]); ?></th>
								  </tr>
								<tr>
								  <th width="127">预约电话</th>
								  <th align="left"><?php echo ($vo["subscribe_tel"]); ?></th>
								  </tr>
								<tr>
								  <th width="127">详细地址</th>
								  <th align="left"><?php echo ($vo["address"]); ?></th>
								  </tr>
								<tr>
								  <th width="127">地图标点</th>
								  <th align="left">坐标<span id="long_lat_txt">（<?php echo ($vo["lng"]); ?>，<?php echo ($vo["lat"]); ?>）</span></th>
								  </tr>
								<tr>
								  <th width="127">签约状态</th>
								  <th align="left">
									<?php if($vo['is_sign']==0)echo '未签约';?>
									<?php if($vo['is_sign']==1)echo '已签约';?></th>
								  </tr>
								<tr>
								  <th width="127">等级</th>
								  <th align="left"><?php if($vo['level']=='A')echo 'A';?>
									<?php if($vo['level']=='B')echo 'B';?>
									<?php if($vo['level']=='C')echo 'C';?>
								  </th>
								  </tr>
								<tr>
								  <th width="127">人均消费</th>
								  <th align="left"><?php echo ($vo["per_person"]); ?></th>
								  </tr>
								<tr>
								  <th width="127">搜索关键词</th>
								  <th align="left"><?php echo ($$keywords_list[0]['title']); ?>
									-
									  <?php echo ($keywords_list[1]['title']); ?>
									  -
									  <?php echo ($keywords_list[2]['title']); ?>
									  -
									  <?php echo ($keywords_list[3]['title']); ?>
									  -
									  <?php echo ($keywords_list[4]['title']); ?></th>
								  </tr>
								<tr>
								  <th width="127">商家介绍</th>
								  <th align="left"><?php echo htmlspecialchars_decode($vo['content']);?></th>
								  </tr>
                    			<form name="form1" id="form1" method="post" action="?c=Shop&a=online">
								<tr>
								  <th width="127">驳回内容</th>
								  <th align="left">								  
								  <textarea name="content" id="content" cols="45" rows="5"><?php echo ($vo["auditcontent"]); ?></textarea>								  
								  </th>
								  </tr>
								<tr>
								  <th width="127">&nbsp;</th>
								  <th align="left">
								  <input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
								  <?php
 if($vo['status']==0) { ?>
								  <input type="submit" name="button" id="button" value="审核通过">
								  <input type="button" name="button" id="audit" value="驳回">
								  <?php
 } ?>
								  </th>
								  </tr>
                  				</form>
								<?php
 $contract_status[0]='待审核'; $contract_status[1]='审核通过'; $contract_status[2]='驳回'; ?>
								<tr>
								  <th width="127"><b>合同信息</b>&nbsp;(<?php echo ($contract_status[$vo['is_contract']]); ?>)</th>
								  <th align="left">&nbsp;</th>
								  </tr>
								<tr>
								  <th width="127">商家全称</th>
								  <th align="left"><?php echo ($vo["full_name"]); ?></th>
								  </tr>
								<tr>
								  <th width="127">公司法人</th>
								  <th align="left"><?php echo ($vo["legal_person"]); ?></th>
								  </tr>
								<tr>
								  <th width="127">公司固话</th>
								  <th align="left"><?php echo ($vo["tel"]); ?></th>
								  </tr>
								<tr>
								  <th width="127">公司邮箱</th>
								  <th align="left"><?php echo ($vo["email"]); ?></th>
								  </tr>
								<tr>
								  <th width="127">合同照片</th>
								  <th align="left">
								  <?php
 if(!empty($vo['contract_photo'])) { ?>
								  <img src='http://static.htcheng.com/imgs/<?php echo ($vo['contract_photo']); ?>' width='100' height='100' />
								  <?php
 } ?>
								  </th>
								  </tr>
								<tr>
								  <th width="127">法人身份证</th>
								  <th align="left">
								  <?php
 if(!empty($vo['legal_person_card'])) { ?>
								  <img src='http://static.htcheng.com/imgs/<?php echo ($vo['legal_person_card']); ?>' width='100' height='100' />
								  <?php
 } ?>
								  </th>
								  </tr>
								<tr>
								  <th width="127">卫生许可证</th>
								  <th align="left">
								  <?php
 if(!empty($vo['hygienic_license'])) { ?>
								  <img src='http://static.htcheng.com/imgs/<?php echo ($vo['hygienic_license']); ?>' width='100' height='100' />
								  <?php
 } ?>
								  </th>
								  </tr>
								<tr>
								  <th width="127">营业执照</th>
								  <th align="left">
								  <?php
 if(!empty($vo['business_licence'])) { ?>
								  <img src='http://static.htcheng.com/imgs/<?php echo ($vo['business_licence']); ?>' width='100' height='100' />
								  <?php
 } ?>
								  </th>
								  </tr>
								<tr>
								  <th width="127">其它证件</th>
								  <th align="left">
								  <?php
 if(!empty($vo['other_documents'])) { ?>
								  <img src='http://static.htcheng.com/imgs/<?php echo ($vo['other_documents']); ?>' width='100' height='100' />
								  <?php
 } ?>
								  </th>
								  </tr>
                    			<form name="form2" id="form2" method="post" action="?c=Shop&a=contractstatus">
								<tr>
								  <th width="127">驳回内容</th>
								  <th align="left">								  
								  <textarea name="contract_content" id="contract_content" cols="45" rows="5"><?php echo ($vo["contract_content"]); ?></textarea>								  
								  </th>
								  </tr>
								<tr>
								  <th width="127">&nbsp;</th>
								  <th align="left">
								  <input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
								  <?php
 if($vo['is_contract']==0) { ?>
								  <input type="submit" name="button" id="button2" value="审核通过">
								  <input type="button" name="button" id="audit2" value="驳回">
								  <?php
 } ?>
								  </th>
								  </tr>
                  				</form>
							</thead>
							<tbody>
							
						   </tbody><tfoot></tfoot>
						</table>

                        
                       
                   </div>
                    
               </div>
           </div>
           <div class="notify" style=""></div>
           <div class="loading-overlay" style="display: none"></div>
           <div class="account-lock" style="display:none"></div>
		   
       </div>
   </div>
   
   </body></html>