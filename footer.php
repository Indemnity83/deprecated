
	<div id="title"><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></div>
	<hr />
	<!-- If you'd like to support WordPress, having the "powered by" link somewhere on your blog is the best way; it's our only promotion or advertising. -->
	<div id="footer">
		<div class="left">Proudly powered by <a href="http://wordpress.org/">WordPress</a></div>
		<div class="right">Design By <a href="http://indemnity83.com">Kyle Klaus</a> <?php boutique_content_credit(' | ',''); ?><?php boutique_photo_credit($post->ID,' | ',''); ?> | <?php wp_loginout(); ?><?php wp_register(' | ',''); ?>
	</div>
</div>

<?php wp_footer(); ?>

</body>
</html>