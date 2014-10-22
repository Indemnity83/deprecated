<div class="goods form">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo __('Edit Good'); ?></h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<?php echo $this->Form->create('Good', $this->App->defaultForm); ?>
			<?php echo $this->Form->input('id', array('placeholder' => 'Id'));?>
			<?php echo $this->Form->input('name', array('placeholder' => 'Name'));?>
			<?php echo $this->Form->input('caffeine_level', array('label' => false, 'placeholder' => 'Caffeine Level', 'beforeInput' => '<div class="input-group">', 'afterInput' => '<span class="input-group-addon">mg</span></div>'));?>
			<?php echo $this->Form->input('fluid_ounces', array('label' => false, 'placeholder' => 'Fluid Ounces', 'beforeInput' => '<div class="input-group"><span class="input-group-addon">per</span>', 'afterInput' => '<span class="input-group-addon">fl. oz</span></div>'));?>
			<?php echo $this->Form->submit(__('Submit')); ?>
			<?php echo $this->Form->end() ?>
		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
