<div class="goods index">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<ul class="nav nav-pills pull-right">
					<li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Add Good'), array('action' => 'add'), array('escape' => false)); ?></li>
				</ul>
				<h1><?php echo __('Goods'); ?></h1>
			</div>
		</div><!-- end col md 12 -->
	</div><!-- end row -->

	<div class="row">
		<div class="col-md-12">
			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<thead>
					<tr>						
						<th><?php echo $this->Paginator->sort('name'); ?></th>
						<th><?php echo $this->Paginator->sort('caffeine_level'); ?></th>
						<th><?php echo $this->Paginator->sort('created'); ?></th>
						<th class="actions text-right"></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($goods as $good): ?>
					<tr>
						<td><?php echo $this->Html->link(h($good['Good']['name']), array('action' => 'view', $good['Good']['slug']), array('escape' => false)); ?>&nbsp;</td>
						<td><?php echo h($good['Good']['caffeine_level']) . 'mg per ' . h($good['Good']['per']) . ' '  . h($good['Good']['unit_enum']); ?>&nbsp;</td>
						<td><?php echo h($good['Good']['created']); ?>&nbsp;</td>
						<td class="actions text-right">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('action' => 'view', $good['Good']['slug']), array('escape' => false)); ?>
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $good['Good']['slug']), array('escape' => false)); ?>
							<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $good['Good']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $good['Good']['name'])); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>

		</div> <!-- end col md 9 -->
	</div><!-- end row -->

</div><!-- end containing of content -->
