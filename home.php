<?php get_header(); ?>
	
	<div id="home">
	    
		<ul id="nav">
			<li><a href="<?php echo get_option('home'); ?>/" class="first active">Home</a></li>
			<?php wp_list_pages('title_li=&sort_column=menu_order'); ?>
		</ul>
	      
		<div id="body">&nbsp;</div>
		
	</div>

<?php get_footer(); ?>