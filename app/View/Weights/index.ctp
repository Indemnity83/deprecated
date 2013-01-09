<h2>Weights</h2>
			
<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

	<thead>
		<tr>
			<th>id</th>
			<th>Name</th>
			<th>Date</th>
			<th>Weight</th>
			<th>Created</th>
			<th>Modified</th>
			<th></th>
		</tr>
	</thead>
	
	<tbody>
	    <?php foreach ($weights as $weight): ?>
		<tr>
			<td><strong><?php echo $weight['Weight']['id']; ?>&nbsp;</strong></td>
			<td><?php echo $this->Html->link($weight['User']['name'], array('controller'=>'users', 'action'=>'view', $weight['User']['id']), array('escape'=>false)); ?>&nbsp;</td>
			<td><?php echo $this->Time->format('M j',$weight['Weight']['date']); ?>&nbsp;</td>
			<td><?php echo $weight['Weight']['weight']; ?>&nbsp;</td>
			<td><?php echo $this->Time->timeAgoInWords($weight['Weight']['created']); ?>&nbsp;</td>
			<td><?php echo $this->Time->timeAgoInWords($weight['Weight']['modified']); ?>&nbsp;</td>
			<td class="delete">
				<?php echo $this->Html->link($this->Html->image('/img/pencil-16.png', array('alt'=>'Edit')), array('action' => 'edit', $weight['Weight']['id']), array('escape'=>false)); ?>
				<?php echo $this->Form->postLink($this->Html->image('/img/cross-16.png', array('alt'=>'Delete')), array('action' => 'delete', $weight['Weight']['id']), array('escape'=>false, 'confirm'=>'Are you sure you want to delete that entry?')); ?>
			</td>
		</tr>
        <?php endforeach; ?>
	</tbody>
	
</table>