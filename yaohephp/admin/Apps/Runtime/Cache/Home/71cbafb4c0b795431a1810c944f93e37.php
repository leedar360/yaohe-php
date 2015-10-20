<?php if (!defined('THINK_PATH')) exit(); $hotarr[0]='不热门'; $hotarr[1]='热门'; ?>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th class="checkbox"><input type="checkbox" id="all"></th>
			<th>ID</th>
			<th>省份</th>
			<th>名称</th>
			<th>热门状态</th>
			<th>排序</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr d_id="<?php echo ($vo["id"]); ?>" d_title="<?php echo ($vo["title"]); ?>" d_p_id="<?php echo ($vo["p_id"]); ?>" d_order_num="<?php echo ($vo["order_num"]); ?>" d_is_hot="<?php echo ($vo["is_hot"]); ?>" class="dbclick">
			<td class="checkbox"><input type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>"></td>
			<td><?php echo ($vo["id"]); ?></td>
			<td><?php echo ($provincearr[$vo['p_id']]); ?></td>
			<td><?php echo ($vo["title"]); ?></td>
			<td><?php echo ($hotarr[$vo['is_hot']]); ?></td>
			<td><?php echo ($vo["order_num"]); ?></td>
			<td><!--取消热门--> 
			<a href='/?c=District&province_id=<?php echo ($vo["p_id"]); ?>&city_id=<?php echo ($vo["id"]); ?>'>商圈管理</a>
			<a href='/?c=BindingClass&province_id=<?php echo ($vo["p_id"]); ?>&city_id=<?php echo ($vo["id"]); ?>'>绑定分类</a>
			<?php
 if($vo['status']==0) { ?>
			<a href='/?c=City&a=status&id=<?php echo ($vo["id"]); ?>'>关闭城市</a>
			<?php
 } else { ?>
			<a href='/?c=City&a=status&id=<?php echo ($vo["id"]); ?>'>开启城市</a>
			<?php
 } ?>
			</td>

		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
   </tbody><tfoot></tfoot>
</table>
<script>
$(document).ready(function(){
	$(".dbclick").dblclick(function() {
		//alert($(this).attr('d_id')+'_'+$(this).attr('d_title'));
		$('#edit_id').val($(this).attr('d_id'));
		$('#edit_title').val($(this).attr('d_title'));
		$('#edit_p_id').val($(this).attr('d_p_id'));
		//$('#edit_is_hot').val($(this).attr('d_is_hot'));
		$('#edit_order_num').val($(this).attr('d_order_num'));
		//alert($(this).attr('d_is_hot'));
		//$("input[@type=radio][name=edit_is_hot][@value="+$(this).attr('d_is_hot')+"]").attr("checked",true);
		if($(this).attr('d_is_hot')>0)
		{
			$("#edit_is_hot2").attr("checked","checked");
		}
		else
		{
			$("#edit_is_hot1").attr("checked","checked");
		}
		$('#edit_modal').show();
	});
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
});
</script>