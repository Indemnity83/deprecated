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
						<th>Name</th>
						<th>Caffeine Level</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($goods as $good): ?>
					<tr>
						<td><?php echo $this->Html->link(h($good['Good']['name']), array('action' => 'view', $good['Good']['slug']), array('escape' => false)); ?>&nbsp;</td>
						<td><?php echo h($good['Good']['caffeine_level']) . 'mg per ' . h($good['Good']['per']) . ' '  . h($good['Good']['unit_enum']); ?>&nbsp;</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>

		</div> <!-- end col md 9 -->
	</div><!-- end row -->

</div><!-- end containing of content -->
