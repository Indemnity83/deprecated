<div class="roles form">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo __('Add Role'); ?></h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<?php echo $this->Form->create('Role', $this->App->defaultForm); ?>
			<?php echo $this->Form->input('title', array('placeholder' => 'Title'));?>
			<?php echo $this->Form->input('description', array('placeholder' => 'Description'));?>
			<?php echo $this->Form->submit(__('Submit')); ?>
			<?php echo $this->Form->end() ?>
		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
