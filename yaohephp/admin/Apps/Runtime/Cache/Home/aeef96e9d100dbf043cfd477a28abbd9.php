<?php if (!defined('THINK_PATH')) exit();?><table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th class="checkbox"><input type="checkbox" id="all"></th>
			<th>ID</th>
			<th>权限名称</th>
			<th>权限备注</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr d_id="<?php echo ($vo["id"]); ?>" class="dbclick">
			<td class="checkbox"><input type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>"></td>
			<td><?php echo ($vo["id"]); ?></td>
			<td><?php echo ($vo["title"]); ?></td>
			<td><?php echo ($vo["content"]); ?></td>
			<td><a href='?c=Role&a=edit&id=<?php echo ($vo["id"]); ?>'>编辑</a> <a href='?c=Role&a=delete&id=<?php echo ($vo["id"]); ?>' onclick="return _delete()">删除</a></td>
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