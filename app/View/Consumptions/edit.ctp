<div class="consumptions form">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo __('Edit Consumption'); ?></h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<?php echo $this->Form->create('Consumption', $this->App->defaultForm); ?>
			<?php echo $this->Form->input('id', array('placeholder' => 'Id'));?>
			<div class="form-group">
				<?php echo $this->Form->label('when'); ?>
				<?php echo $this->Form->text('when', array('type' => 'date', 'div' => 'form-group', 'wrapInput' => false, 'class' => 'form-control'));?>
			</div>
			<?php echo $this->Form->input('user_id', array('placeholder' => 'User Id'));?>
			<?php echo $this->Form->input('good_id', array('placeholder' => 'Good Id'));?>
			<?php echo $this->Form->input('notes', array('placeholder' => 'Notes'));?>
			<?php echo $this->Form->submit(__('Submit')); ?>
			<?php echo $this->Form->end() ?>
		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>

<?php

	$this->Js->get('document')->event('ready', "$('#ConsumptionWhen').datetimepicker({pickTime: false});")

?>
