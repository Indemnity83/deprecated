<h2><?php echo $user['User']['name']; ?></h2>
<p>Latest weight: <?php echo $recent_weight; ?>
<div class="stats_charts">
	<table class="stats" rel="line" cellpadding="0" cellspacing="0"
		width="100%">
		<thead>
			<tr>
				<td>&nbsp;</td>
				<?php foreach ($user['Weight'] as $weight): ?>
				<th scope="col"><?php echo date('Y-m-d', strtotime($weight['date'])); ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<tr>
				<th>Weigh-in</th>
				<?php foreach ($user['Weight'] as $weight): ?>
				<td><?php echo $weight['weight']; ?></td>
				<?php endforeach; ?>
			</tr>

		</tbody>
	</table>
</div>

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
				<?php echo $this->Html->link($this->Html->image('/img/pencil-16.png', array('alt'=>'Edit')), array('controller'=>'weights', 'action' => 'edit', $weight['id']), array('escape'=>false)); ?>
				<?php echo $this->Form->postLink($this->Html->image('/img/cross-16.png', array('alt'=>'Delete')), array('controller'=>'weights', 'action' => 'delete', $weight['id']), array('escape'=>false, 'confirm'=>'Are you sure you want to delete that entry?')); ?>
			</td>
		</tr>
        <?php endforeach; ?>
	</tbody>
</table>

