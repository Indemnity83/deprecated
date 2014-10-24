<div class="users form">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<ul class="nav nav-pills pull-right">
					<li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Login'), array('controller' => 'users', 'action' => 'login'), array('escape' => false)); ?>
				</ul>
				<h1><?php echo __('Register'); ?></h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<?php echo $this->Form->create('User', $this->App->defaultForm); ?>
			<?php echo $this->Form->input('username', array('placeholder' => 'Username'));?>
			<?php echo $this->Form->input('password', array('placeholder' => 'Password'));?>
			<?php echo $this->Form->input('temppassword', array('placeholder' => 'Password (confirm)', 'label' => 'Password (confirm)', 'type' => 'password'));?>
			<?php echo $this->Form->input('email', array('placeholder' => 'Email'));?>
			<?php echo $this->Form->submit(__('Submit')); ?>
			<?php echo $this->Form->end() ?>
		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
