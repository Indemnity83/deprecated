<div class="users form">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo __('Edit User'); ?></h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<?php echo $this->Form->create('User', $this->App->defaultForm); ?>
			<?php echo $this->Form->input('id', array('placeholder' => 'Id'));?>
			<?php echo $this->Form->input('username', array('placeholder' => 'Username'));?>
			<?php echo $this->Form->input('email', array('placeholder' => 'Email'));?>
			<?php echo $this->Form->input('role_id', array('placeholder' => 'Role Id'));?>
			<?php echo $this->Form->submit(__('Submit')); ?>
			<?php echo $this->Form->end() ?>
		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
