<?php

add_action('admin_menu', 'boutique_add_theme_page');

/**
 * Add the menu page that allows the user to update the front page and some settings
 * @return unknown_type
 */
function boutique_add_theme_page() {
	add_theme_page(__('Boutique Options'), __('Boutique Options'), 'edit_themes', basename(__FILE__), 'boutique_theme_page');
}

/**
 * Display the Boutique options page
 * @return unknown_type
 */
function boutique_theme_page() { ?>
<div class="wrap">
	<div id="icon-themes" class="icon32"><br /></div>
	<h2><?php _e('Boutique Options') ?></h2>

	
	<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>
	
	<table class="form-table">
	
		<tr valign="top">
		<th scope="row"><?php _e('Front Image URL') ?></th>
		<td><input type="text" size="60" name="boutique_front_url" value="<?php echo htmlspecialchars(get_option('boutique_front_url')); ?>" /></td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><?php _e('Front Image Credit') ?></th>
		<td><input type="text" size="60" name="boutique_front_credit" value="<?php echo htmlspecialchars(get_option('boutique_front_credit')); ?>" /></td>
		</tr>	
		
		<tr valign="top">
		<th scope="row"><?php _e('Site Content Credit') ?></th>
		<td><input type="text" size="60" name="boutique_content_credit" value="<?php echo htmlspecialchars(get_option('boutique_content_credit')); ?>" /></td>
		</tr>				
	
	</table>
	
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="boutique_front_url,boutique_front_credit,boutique_content_credit" />
	
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
	
	</form>
</div>
<?php }

/**
 * Print out the navigation menu, using the function most
 * appropriate to the current wordpress version
 * @return bool success
 */
function boutique_menu() {
	if( function_exists(wp_page_menu) && false) {
		// If possible, use the new Wordpress 2.7+ function		
		wp_page_menu('show_home=1&depth=1');
	} else {
		// Old fashoned method, Wordpress > 2.5
		echo '<div class="menu"><ul>';
		echo '<li class="page_item '.(is_home() ? 'current_page_item' : '').'"><a href="'. get_option('home') .'/">Home</a></li>';
		wp_list_pages('title_li=&sort_column=menu_order&depth=1');
		echo '</ul></div>';
	}
	
	return true;
}

/**
 * Print out the photo credit link if necessary
 * @param $post_id Post ID (from $post->ID)
 * @param $pre string to append
 * @param $post string to prepend
 * @return bool success
 */
function boutique_photo_credit($post_id,$pre,$post) {
	if( is_home() ) {
		$photoCredit = get_option('boutique_front_credit');
	} else {
		$photoCredit = get_post_meta($post_id, 'photo-credit', true);
	}
	
	if( $photoCredit != '' )
		echo $pre . $photoCredit . $post;
		
	return true;
}

/**
 * Print out the content credit link if necessary
 * @param $pre string to append
 * @param $post string to prepend
 * @return bool success
 */
function boutique_content_credit($pre,$post) {
	$siteCredit = get_option('boutique_content_credit');
	
	if( $siteCredit != '' )
		echo $pre . $siteCredit . $post;
		
	return true;
}

/**
 * Print out the css override menu using custom fields 
 * from the given post
 * @param $post_id Post ID (from $post->ID)
 * @return bool success 
 */
function boutique_page_style($post_id) {
	echo "<style type=\"text/css\">\n";
	echo "<!--\n";
	
	$backgroundImage = get_post_meta($post_id, 'background-image', true);
	$backgroundColor = get_post_meta($post_id, 'background-color', true);
	$backgroundRepeat = get_post_meta($post_id, 'background-repeat', true);
	
	echo "#page {\n";
	echo ($backgroundImage != '' ? 'background-image: url(\''.$backgroundImage."');\n" : '');
	echo ($backgroundColor != '' ? 'background-color: '.$backgroundColor.";\n" : '');
	echo ($backgroundRepeat != '' ? 'background-repeat: '.$backgroundRepeat.";\n" : '');
	echo "}\n";
	
	echo "-->\n";
	echo "</style>\n";
	
	return true;
}

?>
