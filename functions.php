<?php

/**
 * Print out the navigation menu, using the function most
 * appropriate to the current wordpress version
 * @return bool success
 */
function boutique_menu() {
	if( function_exists(wp_page_menu) && false) {
		// If possible, use the new Wordpress 2.7+ function	
		wp_page_menu('show_home=1');
	} else {
		// Old fashoned method
		echo '<div class="menu"><ul>';
		echo '<li class="page_item '.(is_home() ? 'current_page_item' : '').'"><a href="'. get_option('home') .'/">Home</a></li>';
		wp_list_pages('title_li=&sort_column=menu_order');
		echo '</ul></div>';
	}
	
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
