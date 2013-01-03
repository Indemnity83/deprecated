<?php echo $this->Form->create('User', array('inputDefaults'=>array('div' => false,'error'=>array('attributes'=>array('wrap'=>'span', 'class'=>'note error'))))); ?>
<p><?php echo $this->Form->input('name', array('class'=>'text', 'size'=>'30', 'between'=>'<br />')); ?><span class="note">I suggest using just your first name</span></p>
<p class="formend"><?php echo $this->Form->end(array('label'=>'Submit','div'=>false,'class'=>'submit')); ?></p>
