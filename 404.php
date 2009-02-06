<?php get_header(); ?>
	
	<div id="home">
	    
		<ul id="nav">
			<li><a href="<?php echo get_option('home'); ?>/" class="first active">Home</a></li>
			<?php wp_list_pages('title_li=&sort_column=menu_order'); ?>
		</ul>
	      
		<div id="body">
        	<h1>404 Not Found</h1>

			<p>The requested URL was not found on this server. If you entered the URL manually please check your spelling and try again.</p>
			<p>If you think this is a server error, please contact the <a href="">webmaster.</a></p>
		
        </div>

	</div>

<?php get_footer(); ?>