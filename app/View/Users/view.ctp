<div class="users view">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<ul class="nav nav-pills pull-right">
					<li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-edit"></span>&nbsp&nbsp;Edit User'), array('action' => 'edit', $user['User']['id']), array('escape' => false)); ?> </li>
					<li><?php echo $this->Form->postLink(__('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete User'), array('action' => 'delete', $user['User']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $user['User']['id'])); ?> </li>
				</ul>
				<h1><?php echo h($user['User']['username']); ?>&nbsp;<small><?php echo h($user['Role']['title']); ?></small></h1>
			</div>
		</div>
	</div>
</div>

<div class="related row">
	<div class="col-md-12">
		<h3><?php echo __('Consumption'); ?></h3>
		<?php if (!empty($user['Consumption'])): ?>
			<table cellpadding = "0" cellspacing = "0" class="table table-striped">
			<tbody>
			<?php foreach ($user['Consumption'] as $consumption): ?>
				<tr>			
					<th><?php echo $consumption['when']; ?></th>
					<td><?php echo $consumption['Good']['name']; ?></td>
					<td class="text-right"><?php echo $consumption['quantity']; ?> fl. oz</td>
					<td class="text-right"><?php echo $consumption['quantity'] * $consumption['Good']['caffeine_level'] / $consumption['Good']['fluid_ounces']; ?> mg</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
		<?php endif; ?>
	</div><!-- end col md 12 -->
</div>

<div class="related row">
	<div class="col-md-12">
		<h3><?php echo __('Activity'); ?></h3>
		<?php if (!empty($user['Action'])): ?>
			<table cellpadding = "0" cellspacing = "0" class="table table-striped">
			<tbody>
			<?php foreach ($user['Action'] as $log): ?>
				<tr>			
					<th><?php echo $this->Time->timeAgoInWords($log['created']); ?></th>
					<td><?php echo $this->Log->describe($log); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
		<?php endif; ?>
	</div><!-- end col md 12 -->
</div>

<div class="related row">
	<div class="col-md-12">
		<h3><?php echo __('History'); ?></h3>
		<?php if (!empty($user['Log'])): ?>
			<table cellpadding = "0" cellspacing = "0" class="table table-striped">
			<tbody>
			<?php foreach ($user['Log'] as $log): ?>
				<tr>			
					<th><?php echo $this->Time->timeAgoInWords($log['created']); ?></th>
					<td><?php echo $this->Log->describe($log); ?></td>					
				</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
		<?php endif; ?>
	</div><!-- end col md 12 -->
</div>
