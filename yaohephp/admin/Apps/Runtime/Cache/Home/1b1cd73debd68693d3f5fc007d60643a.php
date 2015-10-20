<?php if (!defined('THINK_PATH')) exit();?><style type="text/css">
body, html {width: 100%;height: 100%;overflow: hidden;margin:0;}
#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;}
#l-map{height:100%;width:100%;}
</style>
<body id="nv_member"  onload="initialize()">

<script type="text/javascript" src="/Public/jquery-1.7.2.min.js"></script>
<script src="/Public/dialog/jquery.artDialog.js?skin=default"></script>
<script src="/Public/dialog/iframeTools.js"></script>
<script src="http://webapi.amap.com/maps?v=1.2&key=8325164e247e15eea68b59e89200988b"></script>
<script>
	function initialize(){
		if($('#longitude').val()==0 || $('#latitude').val()==0)
		{
			var position = new AMap.LngLat(116.353819,39.94284);//创建中心点坐标
		}
		else
		{
			var position = new AMap.LngLat($('#longitude').val(),$('#latitude').val());//创建中心点坐标
		}
		var mapObj   = new AMap.Map("mapContainer",{center:position,level:12});//创建地图实例
		mapObj.plugin(["AMap.ToolBar","AMap.Scale"],function(){
			//加载工具条
			var tool = new AMap.ToolBar();
			mapObj.addControl(tool);
			var scale = new AMap.Scale();
			mapObj.addControl(scale); 
		});
		var marker = new AMap.Marker({ //自定义构造AMap.Marker对象                
			map:mapObj,                
			position: position,                
			offset: new AMap.Pixel(-10,-34),
			draggable:true,			  
			icon: "http://webapi.amap.com/images/0.png",
			raiseOnDrag:true
		});

		var mouseupEventListener=AMap.event.addListener(mapObj,'mouseup',function(e){
			var lnglat_str = marker.getPosition();
			//alert(lnglat_str);
			/*var arr	=	lnglat_str.split(",");
			//alert(arr[0]);
			$('#longitude').val(arr[0]);
			$('#latitude').val(arr[1]);*/
			//alert(lnglat_str);
			document.getElementById("map").value = lnglat_str;  
			var arr = $('#map').val().split(',');
			$('#longitude').val(arr[0]);
			$('#latitude').val(arr[1]);
		});                 
	}
	</script>

<div class="ftip" style="margin:0">拖动标记获取地图坐标<a id="ok" style="float:none" href="###">确 认</a></div>
<div id="mapContainer" style="width:500px; height:500px;margin:0 auto;"></div>
<input type="hidden" id="map" value="" />
<input type="hidden" name="longitude" id="longitude" value="" />
<input type="hidden" name="latitude" id="latitude" value="" />

<script type="text/javascript">
if (art.dialog.data('longitude')) {
	document.getElementById('longitude').value = art.dialog.data('longitude');// 获取由主页面传递过来的数据
	document.getElementById('latitude').value = art.dialog.data('latitude');
};
// 关闭并返回数据到主页面
document.getElementById('ok').onclick = function () {
	var origin = artDialog.open.origin;
	var longitudeinput = origin.document.getElementById('long');
	var latitudeinput = origin.document.getElementById('lat');
	//var point	=	origin.document.getElementById('point');
	longitudeinput.value = $('#longitude').attr('value');
	latitudeinput.value = $('#latitude').attr('value');
	var html='（'+$('#longitude').attr('value')+'，'+$('#latitude').attr('value')+'）';
	//alert(html);
	origin.$('#long_lat_txt').html(html);
	//point.value=$('#longitude').attr('value')+','+$('#latitude').attr('value')

	art.dialog.close();
};




</script>
</body>