<div class="goods view">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo h($good['Good']['name']); ?>&nbsp;<small><?php echo h($good['Good']['caffeine_level']) . 'mg per ' . h($good['Good']['fluid_ounces']) . ' fl. oz'; ?></small></h1>
			</div>
		</div>
	</div>
</div>

<div class="related row">
	<div class="col-md-12">
		<h3><?php echo __('Consumptions'); ?></h3>
		<?php if (!empty($good['Consumption'])): ?>
			<table cellpadding = "0" cellspacing = "0" class="table table-striped">
			<thead>
				<tr>			
					<th>When</th>
					<th>Who</td>
					<th class="text-right">Consumed</td>
					<th class="text-right">Caffeine</td>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($good['Consumption'] as $consumption): ?>
				<tr>			
					<th><?php echo $consumption['when']; ?></th>
					<td><?php echo $this->Html->link($consumption['User']['username'], array('controller' => 'users', 'action' => 'view', $consumption['User']['id'])); ?></td>
					<td class="text-right"><?php echo $consumption['quantity']; ?> fl. oz</td>
					<td class="text-right"><?php echo $consumption['quantity'] * $good['Good']['caffeine_level'] / $good['Good']['fluid_ounces']; ?> mg</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
		<?php endif; ?>
	</div><!-- end col md 12 -->
</div>
