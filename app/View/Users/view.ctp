<h2><?php echo $user['User']['name']; ?></h2>

<p>Not much to see here yet</p>

<h3>Weigh-ins</h3>

<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

	<thead>
		<tr>
			<th>id</th>
			<th>Date</th>
			<th>Weight</th>
			<th></th>
		</tr>
	</thead>
	
	<tbody>
	    <?php foreach ($user['Weight'] as $weight): ?>
		<tr>
			<td><strong><?php echo $weight['id']; ?>&nbsp;</strong></td>
			<td><?php echo $this->Time->niceShort($weight['date']); ?>&nbsp;</td>
			<td><?php echo $weight['weight']; ?>&nbsp;</td>
			<td class="delete">
				<?php echo $this->Html->link($this->Html->image('/img/pencil-16.png', array('alt'=>'Edit')), array('action' => 'edit', $weight['Weight']['id']), array('escape'=>false)); ?>
				<?php echo $this->Form->postLink($this->Html->image('/img/cross-16.png', array('alt'=>'Delete')), array('action' => 'delete', $weight['Weight']['id']), array('escape'=>false, 'confirm'=>'Are you sure you want to delete that entry?')); ?>
			</td>
		</tr>
        <?php endforeach; ?>
	</tbody>
	
</table>