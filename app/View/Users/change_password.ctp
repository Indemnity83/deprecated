<div class="users form">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo __('Change Password'); ?></h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<?php echo $this->Form->create('User', $this->App->defaultForm); ?>
			<?php echo $this->Form->input('id', array('placeholder' => 'Id'));?>
			<?php echo $this->Form->input('old_password', array('label' => 'Old Password', 'type' => 'password'));?>
			<?php echo $this->Form->input('new_password', array('label' => 'New Password', 'type' => 'password'));?>
			<?php echo $this->Form->input('confirm_password', array('label' => 'New Password (again)', 'type' => 'password'));?>
			<?php echo $this->Form->submit(__('Submit')); ?>
			<?php echo $this->Form->end() ?>
		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
