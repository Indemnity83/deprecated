<div class="roles index">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<ul class="nav nav-pills pull-right">
					<li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Add Role'), array('action' => 'add'), array('escape' => false)); ?></li>
				</ul>
				<h1><?php echo __('Roles'); ?></h1>
			</div>
		</div><!-- end col md 12 -->
	</div><!-- end row -->

	<div class="row">
		<div class="col-md-12">
			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('title'); ?></th>
						<th class="actions text-right"></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($roles as $role): ?>
					<tr>
						<td><?php echo $this->Html->link($role['Role']['title'], array('controller' => 'roles', 'action' => 'view', $role['Role']['id'])); ?> </td>
						<td class="actions text-right">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('action' => 'view', $role['Role']['id']), array('escape' => false)); ?>
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $role['Role']['id']), array('escape' => false)); ?>
							<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $role['Role']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $role['Role']['id'])); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>

		</div> <!-- end col md 9 -->
	</div><!-- end row -->

</div><!-- end containing of content -->
