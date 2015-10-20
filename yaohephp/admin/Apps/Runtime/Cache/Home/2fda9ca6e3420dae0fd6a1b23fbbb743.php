<?php if (!defined('THINK_PATH')) exit();?><table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>行政区</th>
			<th>商圈</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
			<td><?php echo ($vo["title"]); ?></td>
			<td><?php echo ($vo["district"]); ?></td>
			<td><a href="javascript:open_modal(<?php echo ($vo["id"]); ?>)">新增</a></td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
   </tbody><tfoot></tfoot>
</table>