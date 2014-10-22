<div class="consumptions form">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo __('Add Consumption'); ?></h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<?php echo $this->Form->create('Consumption', $this->App->defaultForm); ?>
			<div class="form-group">
				<?php echo $this->Form->label('when'); ?>
				<?php echo $this->Form->text('when', array('value' => date('Y-m-d'), 'type' => 'date', 'div' => 'form-group', 'wrapInput' => false, 'class' => 'form-control'));?>
			</div>
			<?php echo $this->Form->input('good_id', array('placeholder' => 'Good Id'));?>
			<?php echo $this->Form->input('quantity', array('beforeInput' => '<div class="input-group">', 'afterInput' => '<span class="input-group-addon">fl. oz</span></div>'));?>
			<?php echo $this->Form->input('notes', array('placeholder' => 'Notes'));?>
			<?php echo $this->Form->submit(__('Submit')); ?>
			<?php echo $this->Form->end() ?>
		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
