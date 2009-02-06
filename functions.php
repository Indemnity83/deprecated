<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

add_action('admin_menu', 'boutique_add_theme_page');

function boutique_add_theme_page() {
	add_theme_page(__('Boutique Options'), __('Front Image'), 'edit_themes', basename(__FILE__), 'boutique_theme_page');
}

function boutique_theme_page() {

}

?>
