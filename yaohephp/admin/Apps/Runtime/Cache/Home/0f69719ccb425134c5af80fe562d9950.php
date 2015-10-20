<?php if (!defined('THINK_PATH')) exit();?><table class="table table-bordered table-hover">
	<tbody>
	<tr>
	<?php
 foreach($list as $key=>$item) { ?>
	<td>
	<input name="shop_member_id" type="radio" id="shop_member_id" value="<?php echo ($item["member_id"]); ?>" title="<?php echo ($item["title"]); ?>" /><?php echo ($item["title"]); ?>
	</td>
	<?php
 if($key>0 && $key%3==0)echo '</tr><tr>'; } ?>
	</tr>
   </tbody><tfoot></tfoot>
</table>