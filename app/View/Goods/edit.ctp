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
			<?php echo $this->Form->input('name', array('placeholder' => 'Coca-Cola Classic'));?>
			<div class="form-group row">
				<div class="col-xs-4">
					<?php echo $this->Form->input('caffeine_level', array('label' => false, 'placeholder' => 'Coca-Cola', 'beforeInput' => '<div class="input-group">', 'afterInput' => '<span class="input-group-addon">mg</span></div>', 'div' => false));?>
				</div>
				<div class="col-xs-4">
					<?php echo $this->Form->input('per', array('label' => false, 'placeholder' => '12', 'beforeInput' => '<div class="input-group"><span class="input-group-addon">per</span>', 'afterInput' => '</div>', 'div' => false));?>
				</div>
				<?php echo $this->Form->input('unit', array('options' => $units, 'label' => false, 'div' => 'col-xs-4')); ?>
			</div>
			<?php echo $this->Form->submit(__('Submit')); ?>
			<?php echo $this->Form->end() ?>
		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
