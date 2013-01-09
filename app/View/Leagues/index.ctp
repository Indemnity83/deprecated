<h2>Leagues</h2>
			
<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

	<thead>
		<tr>
			<th>id</th>
			<th>Name</th>
			<th>Members</th>
		</tr>
	</thead>
	
	<tbody>
	    <?php foreach ($leagues as $league): ?>
		<tr>
			<td><strong><?php echo $league['League']['id']; ?>&nbsp;</strong></td>
			<td><?php echo $this->Html->link($league['League']['name'], array('controller'=>'leagues', 'action'=>'view', $league['League']['id']), array('escape'=>false)); ?>&nbsp;</td>
			<td><?php echo count($league['Users']); ?></td>
		</tr>
        <?php endforeach; ?>
	</tbody>
	
</table>

<div class="table_pagination">
	<?php echo $this->Paginator->numbers(array('first'=>2, 'last'=>2, 'separator'=>'', 'ellipsis'=>'&nbsp;&hellip;&nbsp;')); ?>
</div>