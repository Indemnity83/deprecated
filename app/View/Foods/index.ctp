<h2>Foods</h2>
			
<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

	<thead>
		<tr>
			<th>id</th>
			<th>Name</th>
			<th>Manufacture</th>
		</tr>
	</thead>
	
	<tbody>
	    <?php foreach ($foods as $food): ?>
		<tr>
			<td><strong><?php echo $food['Food']['id']; ?>&nbsp;</strong></td>
			<td><?php echo $this->Html->link($food['Food']['name'], array('controller'=>'foods', 'action'=>'view', $food['Food']['id']), array('escape'=>false)); ?>&nbsp;</td>
			<td><?php echo $food['Food']['manufacture']; ?>&nbsp;</td>
		</tr>
        <?php endforeach; ?>
	</tbody>
	
</table>

<div class="table_pagination">
	<?php echo $this->Paginator->numbers(array('first'=>2, 'last'=>2, 'separator'=>'', 'ellipsis'=>'&nbsp;&hellip;&nbsp;')); ?>
</div>