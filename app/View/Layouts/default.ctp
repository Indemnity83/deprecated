<?php echo $this->Html->docType('html5'); ?>
<html lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<?php echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">'; ?>
	<?php echo '<meta name="viewport" content="width=device-width, initial-scale=1">'; ?>
	<title>Caffeinated | <?php echo $this->fetch('title'); ?></title>

	<!-- iOS Webapp -->
	<link rel="apple-touch-icon" href="/img/ios/Icon-60.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/img/ios/Icon-76.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/img/ios/Icon-40@3x.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/img/ios/Icon-76@2x.png">
	<link rel="apple-touch-startup-image" href="/img/ios/apple-touch-startup-image-640x920.png">
	<meta name="apple-mobile-web-app-capable" content="yes">

	<!-- Bootstrap -->
	<?php echo $this->Html->css('/assets/bootstrap/dist/css/bootstrap'); ?>

	<!-- Bootstrap Theme -->
	<?php echo $this->Html->css('//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/united/bootstrap.min.css'); ?>

	<!-- Custom styles -->
	<?php echo $this->Html->css('/assets/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min'); ?>
	<?php echo $this->Html->css('style'); ?>

	<!-- CakePHP -->
	<?php echo $this->fetch('meta'); ?>
	<?php echo $this->fetch('css');	?>
</head>
<body role="document">

	<?php echo $this->element('navbar'); ?>

	<div class="container" role="main">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->Session->flash('auth'); ?>
		<?php echo $this->fetch('content'); ?>
	</div>

	<!-- jQuery -->
	<?php echo $this->Html->script("/assets/jquery/dist/jquery"); ?>

	<!-- Moment -->
	<?php echo $this->Html->script("/assets/moment/min/moment.min"); ?>

	<!-- Bootstrap -->
	<?php echo $this->Html->script("/assets/bootstrap/dist/js/bootstrap"); ?>

	<!-- Bootstrap Plugins -->
	<?php echo $this->Html->script("/assets/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min"); ?>

	<!-- CakePHP -->
	<?php echo $this->fetch('script'); ?>
	<?php echo $this->Js->writeBuffer(); // Write cached scripts ?>
</body>
</html>
