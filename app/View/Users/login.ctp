<div class="users form">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<ul class="nav nav-pills pull-right">
					<li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Register'), array('action' => 'add'), array('escape' => false)); ?></li>
				</ul>
				<h1><?php echo __('Log In'); ?></h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<?php echo $this->Form->create('User', $this->App->defaultForm); ?>
			<?php echo $this->Form->input('username'); ?>
			<?php echo $this->Form->input('password'); ?>
			<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
			<?php echo $this->Form->end() ?>

		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
