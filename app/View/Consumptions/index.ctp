<?php
	# ugly but callbacks don't work on associated models
	# so the enumerable behavior doesn't append the string
	# see issue 1730 for more information
	App::import('Model', 'Good');
	$this->Good = new Good();
?>

<div class="consumptions index">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<ul class="nav nav-pills pull-right">
					<li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Add Consumption'), array('action' => 'add'), array('escape' => false)); ?></li>
				</ul>
				<h1><?php echo __('Consumptions'); ?></h1>
			</div>
		</div><!-- end col md 12 -->
	</div><!-- end row -->

	<div class="row">
		<div class="col-md-12">
			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('when'); ?></th>
						<th><?php echo $this->Paginator->sort('user_id'); ?></th>
						<th><?php echo $this->Paginator->sort('good_id'); ?></th>						
						<th class="actions text-right"></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($consumptions as $consumption): ?>
					<tr>						
						<td><?php echo h($consumption['Consumption']['when']); ?>&nbsp;</td>
						<td><?php echo $this->Html->link($consumption['User']['username'], array('controller' => 'users', 'action' => 'view', $consumption['User']['id'])); ?></td>
						<td><?php echo $this->Html->link($consumption['Good']['name'], array('controller' => 'goods', 'action' => 'view', $consumption['Good']['id'])); ?></td>
						<td class="text-right"><?php echo $consumption['Consumption']['quantity'] . ' ' . $this->Good->enum('unit')[$consumption['Good']['unit']]; ?></td>
						<td class="text-right"><?php echo $consumption['Consumption']['quantity'] * $consumption['Good']['caffeine_level'] / $consumption['Good']['per']; ?> mg</td>
						<td class="actions text-right">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $consumption['Consumption']['id']), array('escape' => false)); ?>
							<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $consumption['Consumption']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $consumption['Consumption']['id'])); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>

		</div> <!-- end col md 9 -->
	</div><!-- end row -->

</div><!-- end containing of content -->
