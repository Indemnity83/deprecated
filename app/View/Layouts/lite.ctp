<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<?php echo $this->Html->charset(); ?>
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<meta name="viewport" content="width=device-width;" />

<link rel="apple-touch-icon-precomposed" href="/img/ios_icon.png" />

<meta name="author" content="Kyle Klaus" />
<meta name="description" content="." />
<meta name="keywords" content="." />

<title><?php echo $siteName ?>: <?php echo $title_for_layout; ?></title>

<?php echo $this->Html->css('style'); ?>
<?php echo $this->Html->css('responsive'); ?>
<?php echo $this->Html->css('visualize'); ?>
<?php echo $this->Html->css('date_input'); ?>
<?php echo $this->Html->css('jquery.minicolors'); ?>
<?php echo $this->Html->css('jquery.wysiwyg'); ?>
<?php echo $this->Html->css('jquery.fancybox'); ?>
<?php echo $this->Html->css('tipsy'); ?>

<!--[if lt IE 9]>
	<?php echo $this->Html->css('ie'); ?>
	<script src="js/html5shiv.js"></script>
<![endif]-->

<?php echo $scripts_for_layout; ?>

</head>

<body class="loginpage">

	<header>
	    <h1><?php echo $this->Html->link($siteName, '/'); ?></h1>
        <?php echo $this->Html->link('Register', array('controller'=>'users', 'action'=>'register'), array('class' => 'backlink')); ?>
        <?php echo $this->Html->link('Login', array('controller'=>'users', 'action'=>'login'), array('class' => 'backlink')); ?>
	</header>

    <section id="content" class="loginbox">	
	    <?php echo $this->Session->flash(); ?> <?php echo $this->Session->flash('auth'); ?>
        <?php echo $content_for_layout; ?>
	</section>

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/ui/1.8.16/jquery-ui.min.js"></script>
	
	<script type="text/javascript" src="js/excanvas.js"></script>
	<script type="text/javascript" src="js/jquery.visualize.js"></script>
	<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="js/jquery.date_input.min.js"></script>
	<script type="text/javascript" src="js/jquery.minicolors.min.js"></script>
	<script type="text/javascript" src="js/jquery.wysiwyg.js"></script>
	<script type="text/javascript" src="js/jquery.fancybox.js"></script>
	<script type="text/javascript" src="js/jquery.tipsy.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>

</body>
</html>