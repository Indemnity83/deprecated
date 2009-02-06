<?php get_header(); ?>
	
	<div id="page">
	    
		<ul id="nav">
			<li><a href="<?php echo get_option('home'); ?>/" class="first">Home</a></li>
			<?php wp_list_pages('title_li=&sort_column=menu_order'); ?>
		</ul>
	      
		<div id="body">
			
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
					<?php edit_post_link('Edit', '<p class="edit">', '</p>'); ?>
					<?php the_content('Read the rest of this entry &raquo;'); ?>
				<?php endwhile; ?>
			
			<?php else : ?>
			
				<h1>Not Found</h2>
				<p>An error occurred that prevented this page from being displayed. This could be due to one of the following reasons.</p>
				<ul>
					<li>An internal site error occured.</li>
					<li>A URL was incorrectly entered.</li>
					<li>The file no longer exists.</li>
				</ul>
				<p>To continue browsing, return to the <a href="<?php echo get_option('home'); ?>/">homepage</a> now. If this problem persists, please contact the site administrator.</p>
					
			<?php endif; ?>
				        
		</div>
		
	</div>

<?php get_footer(); ?>