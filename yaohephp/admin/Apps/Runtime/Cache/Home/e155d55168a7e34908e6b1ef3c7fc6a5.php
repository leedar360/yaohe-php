<?php if (!defined('THINK_PATH')) exit();?><style type="text/css">
._dbclick{background:url('Public/jiantou.png') no-repeat;}
</style>
<table class="table">
	<thead>
		<tr>
			<th class="checkbox"><input type="checkbox" id="all"></th>
			<th>ID</th>
			<th>名称</th>
			<th>是否显示</th>
			<th>排序</th>
		</tr>
	</thead>
	<tbody>
	<?php echo ($html); ?>
   </tbody><tfoot></tfoot>
</table>
<script>
$(document).ready(function(){
	$(".dbclick").dblclick(function() {
		//alert($(this).attr('d_id')+'_'+$(this).attr('d_title'));
		edit_classify_list($(this).attr('d_parentid'));
		$('#edit_id').val($(this).attr('d_id'));
		$('#edit_title').val($(this).attr('d_title'));
		$('#edit_order_num').val($(this).attr('d_order_num'));
		$('#edit_is_hidden').val($(this).attr('d_is_hidden'));
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