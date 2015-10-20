<?php if (!defined('THINK_PATH')) exit();?>
<table class="table table-bordered table-hover">
	<tbody>
	<?php if(is_array($arrlist)): $i = 0; $__LIST__ = $arrlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
			<td><input type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>" <?php if(in_array($vo['id'],$class_id_arr))echo 'checked';?>><?php echo ($vo["title"]); ?></td>
		</tr>
		<tr>
			<td>
		<?php
 foreach($vo['list'] as $item) { echo '<input type="checkbox" name="id[]" value="'.$item['id'].'" '; if(in_array($item['id'],$class_id_arr))echo 'checked'; echo '>'.$item['title'].'&nbsp;&nbsp;'; } ?>
		</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
   </tbody><tfoot></tfoot>
</table>
<input type="button" name="button" id="button" value="保 存" onclick="save()"/>

<script>
$(document).ready(function(){
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