<?php echo $this->Form->create('User', array('inputDefaults'=>array('div' => false,'error'=>array('attributes'=>array('wrap'=>'span', 'class'=>'note error'))))); ?>
<p><?php echo $this->Form->input('name', array('class' => 'text')); ?></p>
<p><?php echo $this->Form->input('username', array('class' => 'text')); ?></p>
<p><?php echo $this->Form->input('email', array('class' => 'text')); ?></p>
<p><?php echo $this->Form->input('password', array('class' => 'text')); ?></p>
<p><?php echo $this->Form->input('password_confirmation', array('type'=>'password', 'class'=>'text')); ?></p>
<p><?php echo $this->Form->input('invitation_code', array('class' => 'text')); ?></p>
<p class="formend"><?php echo $this->Form->submit('Register', array('class'=>'submit', 'div'=>false)); ?></p>
<?php echo $this->Form->end(); ?>
