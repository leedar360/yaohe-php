<?php if (!defined('THINK_PATH')) exit();?><table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>ID</th>
			<th>城市</th>
			<th>显示名称</th>
			<th>分类名称</th>
			<th>是否显示</th>
			<th>排序</th>
		</tr>
	</thead>
	<tbody>
	<?php
 $is_hidden[0]='显示'; $is_hidden[1]='不显示'; ?>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr d_id="<?php echo ($vo["id"]); ?>" d_show_title="<?php echo ($vo["show_title"]); ?>" d_title="<?php echo ($vo["title"]); ?>" d_is_hidden="<?php echo ($vo["is_hidden"]); ?>" d_order_num="<?php echo ($vo["order_num"]); ?>" d_city_title="<?=$cityarr[$vo['city_id']]?>" class="dbclick">
			<td><?php echo ($vo["id"]); ?></td>
			<td><?php echo ($cityarr[$vo['city_id']]); ?></td>
			<td><?php echo ($vo["show_title"]); ?></td>
			<td><?php echo ($vo["title"]); ?></td>
			<td><?php echo ($is_hidden[$vo['is_hidden']]); ?></td>
			<td><?php echo ($vo["order_num"]); ?></td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
   </tbody><tfoot></tfoot>
</table>
<script>
$(document).ready(function(){
	$(".dbclick").dblclick(function() {
		//alert($(this).attr('d_id')+'_'+$(this).attr('d_title'));
		/*$('#edit_id').val($(this).attr('d_id'));
		$('#edit_title').val($(this).attr('d_title'));
		$('#edit_p_id').val($(this).attr('d_p_id'));*/
		$('#edit_show_title').val($(this).attr('d_show_title'));
		$('#edit_id').val($(this).attr('d_id'));
		$('#edit_is_hidden').val($(this).attr('d_is_hidden'));
		$('#edit_order_num').val($(this).attr('d_order_num'));
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