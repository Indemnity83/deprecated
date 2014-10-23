<div class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<?php echo $this->Html->link('<img src="/img/logo-sm.png" style="vertical-align:baseline;" height="19px" alt="">&nbsp;Caffeinated', '/', array('escape' => false, 'class'=>'navbar-brand')); ?>
		</div>

		<div class="collapse navbar-collapse navbar-ex1-collapse">
			<ul class="nav navbar-nav">
				<li><?php echo $this->Html->link('Roles', array('controller' => 'roles', 'action'=>'index')); ?></li>
				<li><?php echo $this->Html->link('Users', array('controller' => 'users', 'action'=>'index')); ?></li>
				<li><?php echo $this->Html->link('Goods', array('controller' => 'goods', 'action'=>'index')); ?></li>
				<li><?php echo $this->Html->link('Consumption', array('controller' => 'consumptions', 'action'=>'index')); ?></li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<?php if($user = AuthComponent::user()) : ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $user['username']; ?> <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><?php echo $this->Html->link('Profile', array('controller'=>'users', 'action'=>'profile')); ?></li>
						<li><?php echo $this->Html->link('Logout', array('controller'=>'users', 'action'=>'logout')); ?></li>
					</ul>
				</li>
				<?php else: ?>
				<li><?php echo $this->Html->link('Login', array('controller'=>'users', 'action'=>'login')); ?></li>
				<li><?php echo $this->Html->link('Register', array('controller'=>'users', 'action' => 'add')); ?></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>
