<?php
	# ugly but callbacks don't work on associated models
	# so the enumerable behavior doesn't append the string
	# see issue 1730 for more information
	App::import('Model', 'Good');
	$this->Good = new Good();
?>

<div class="users view">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<ul class="nav nav-pills pull-right">
					<li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-edit"></span>&nbsp&nbsp;Edit Profile'), array('action' => 'settings'), array('escape' => false)); ?> </li>
					<li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-lock"></span>&nbsp&nbsp;Change Password'), array('action' => 'change_password'), array('escape' => false)); ?> </li>
				</ul>
				<h1><?php echo h($user['User']['username']); ?>&nbsp;</h1>
			</div>
		</div>
	</div>
</div>

<div class="related row">
	<div class="col-md-12">
		<ul class="nav nav-pills pull-right">
			<li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp&nbsp;Add Consumption'), array('controller' => 'consumptions', 'action' => 'add'), array('escape' => false)); ?> </li>
		</ul>
		<h3><?php echo __('Consumption'); ?></h3>
		<?php if (!empty($user['Consumption'])): ?>
			<table cellpadding = "0" cellspacing = "0" class="table table-striped">
			<thead>
				<tr>
					<th>When</th>
					<th>What</td>
					<th class="text-right">Quantity</td>
					<th class="text-right">Caffeine</td>
					<th class="actions text-right"></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($user['Consumption'] as $consumption): ?>
				<tr>			
					<th><?php echo $consumption['when']; ?></th>
					<td><?php echo $consumption['Good']['name']; ?></td>
					<td class="text-right"><?php echo $consumption['quantity'] . ' ' . $this->Good->enum('unit')[$consumption['Good']['unit']]; ?></td>
					<td class="text-right"><?php echo $consumption['quantity'] * $consumption['Good']['caffeine_level'] / $consumption['Good']['per']; ?> mg</td>
					<td class="actions text-right">
						<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('controller' => 'consumptions', 'action' => 'edit', $consumption['id']), array('escape' => false)); ?>
						<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span>', array('controller' => 'consumptions', 'action' => 'delete', $consumption['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $consumption['id'])); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
		<?php endif; ?>
	</div><!-- end col md 12 -->
</div>
