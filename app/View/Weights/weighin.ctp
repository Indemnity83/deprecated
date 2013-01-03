<h2>Weigh-in</h2>

<?php echo $this->Form->create('Weight', array('inputDefaults'=>array('div' => false,'error'=>array('attributes'=>array('wrap'=>'span', 'class'=>'note error'))))); ?>
<p><?php echo $this->Form->input('weight', array('class'=>'text', 'size'=>'30', 'between'=>'<br />')); ?><span class="note">You can type your wieght in directly</span></p>
<p class="formend"><?php echo $this->Form->end(array('label'=>'Submit','div'=>false,'class'=>'submit')); ?></p>
