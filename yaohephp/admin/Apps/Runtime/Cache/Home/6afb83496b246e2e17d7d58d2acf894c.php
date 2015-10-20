<?php if (!defined('THINK_PATH')) exit();?><table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th class="checkbox"><input type="checkbox" id="all"></th>
			<th>商家编号</th>
			<th>商家名称</th>
			<th>分类</th>
			<th>城市</th>
			<th>商圈</th>
			<th>是否签约</th>
			<th>等级</th>
			<th>状态</th>
			<th>最后更新时间</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="dbclick">
			<td class="checkbox"><input type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>"></td>
			<td><?php echo ($vo["id"]); ?></td>
			<td><?php echo ($vo["title"]); ?></td>
			<td><?php echo ($vo["industry_class_id"]); ?></td>
			<td><?=$cityarr[$vo['city_id']]?></td>
			<td><?php echo ($vo["district_id"]); ?></td>
			<td><?php echo ($vo["is_sign"]); ?></td>
			<td><?php echo ($vo["level"]); ?></td>
			<td><?php echo ($vo["status"]); ?></td>
			<td><?php echo ($vo["last_update_time"]); ?></td>
			<td>审核通过 上线 <a href='/index.php/Home/Shop/edit?id=<?php echo ($vo["id"]); ?>'>编辑</a> 删除</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
   </tbody><tfoot></tfoot>
</table>