<?php echo $this->Form->create('User', array('inputDefaults'=>array('div' => false))); ?>
<p><?php echo $this->Form->input('username', array('class' => 'text')); ?></p>
<p><?php echo $this->Form->input('password', array('class' => 'text')); ?></p>
<p class="formend"><?php echo $this->Form->submit('Login', array('class'=>'submit', 'div'=>false)); ?></p>
<?php echo $this->Form->end(); ?>