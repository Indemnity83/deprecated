<div class="roles view">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<ul class="nav nav-pills pull-right">
					<li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-edit"></span>&nbsp&nbsp;Edit Role'), array('action' => 'edit', $role['Role']['id']), array('escape' => false)); ?> </li>
					<li><?php echo $this->Form->postLink(__('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete Role'), array('action' => 'delete', $role['Role']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $role['Role']['id'])); ?> </li>
				</ul>
				<h1><?php echo h($role['Role']['title']); ?>&nbsp;</h1>
				<p><?php echo h($role['Role']['description']); ?>&nbsp;</p>
			</div>
		</div>
	</div>
</div>

<div class="related row">
	<div class="col-md-12">
	<h3><?php echo __('Users'); ?></h3>
	<?php if (!empty($role['User'])): ?>
		<table cellpadding = "0" cellspacing = "0" class="table table-striped">
		<thead>
		<tr>
			<th><?php echo __('Username'); ?></th>
			<th><?php echo __('Email'); ?></th>
			<th class="actions text-right"></th>
		</tr>
		<thead>
		<tbody>
		<?php foreach ($role['User'] as $user): ?>
			<tr>
				<td><?php echo $this->Html->link($user['username'], array('controller' => 'users', 'action' => 'view', $user['id'])); ?></td>
				<td><?php echo $this->Html->link($user['email'], 'mailto:' . $user['email']); ?></td>
				<td class="actions text-right">
					<?php echo $this->Html->link(__('<span class="glyphicon glyphicon-search"></span>'), array('controller' => 'users', 'action' => 'view', $user['id']), array('escape' => false)); ?>
					<?php echo $this->Html->link(__('<span class="glyphicon glyphicon-edit"></span>'), array('controller' => 'users', 'action' => 'edit', $user['id']), array('escape' => false)); ?>
					<?php echo $this->Form->postLink(__('<span class="glyphicon glyphicon-remove"></span>'), array('controller' => 'users', 'action' => 'delete', $user['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $user['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
		</table>
	<?php endif; ?>
	</div><!-- end col md 12 -->
</div>
