<?php get_header(); ?>
	
	<div id="page">
	    
		<?php boutique_menu(); ?>
	      
		<div id="content" class="body">
			
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
					<?php the_content(''); ?>
				<?php endwhile; ?>
			
			<?php else : ?>
			
				<h1>Not Found</h1>
				<p>An error occurred that prevented this page from being displayed. This could be due to one of the following reasons.</p>
				<ul>
					<li>An internal site error occured.</li>
					<li>A URL was incorrectly entered.</li>
					<li>The file no longer exists.</li>
				</ul>
				<p>To continue browsing, return to the <a href="<?php echo get_option('home'); ?>/">homepage</a> now. If this problem persists, please contact the site administrator.</p>
					
			<?php endif; ?>
				        
		</div>
		
		<?php boutique_page_style($post->ID); ?>
		
	</div>

<?php get_footer(); ?>