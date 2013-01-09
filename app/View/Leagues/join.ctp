<?php echo $this->Form->create('League', array('inputDefaults'=>array('div' => false,'error'=>array('attributes'=>array('wrap'=>'span', 'class'=>'note error'))))); ?>
<p><?php echo $this->Form->input('league_id', array('class' => 'text')); ?></p>
<p><?php echo $this->Form->input('user_id', array('class' => 'text')); ?></p>
<p><?php echo $this->Form->input('league_secret', array('class' => 'text')); ?></p>
<p class="formend"><?php echo $this->Form->submit('Join', array('class'=>'submit', 'div'=>false)); ?></p>
<?php echo $this->Form->end(); ?>