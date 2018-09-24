<?php
/**
 * Plugin Name: JRG Primary Category
 * Plugin URI:  https://www.jrgould.com
 * Description: Add the ability to specify a primary category for posts.
 * Version:     0.1
 * Author:      Jeff Gould
 * Author URI:  https://www.jrgould.com
 * License:     GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: jrg-primary-category
 * Domain Path: /languages
 *
 * @package JRG_Primary_Category
 */

namespace JRG\Primary_Category;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
$config = array(
	'base_path'   => dirname( __FILE__ ),
	'base_url'    => plugin_dir_url( __FILE__ ),
	'meta_slug'   => '_jrg-primary-category',
	'text_domain' => 'jrg-primary-category',
	'version'     => '0.1',
);

/**
 * Load textdomain
 */
add_action( 'init', function () use ( $config ) {
	$locale = apply_filters( 'plugin_locale', get_locale(), $config['text_domain'] );
	load_textdomain( $config['text_domain'], WP_LANG_DIR . '/jrg-primary-category/jrg-primary-category-' . $locale . '.mo' );
	load_plugin_textdomain( $config['text_domain'], false, plugin_basename( $config['base_path'] ) . '/languages/' );
} );

/**
 * Instantiate Primary_Category class
 */
require_once $config['base_path'] . '/classes/class-primary-category.php';

$instance = new Primary_Category( $config );
$instance->init();

/**
 * Helper function. Gets the primary category ID for the current post or the post with the provided ID.
 *
 * @param int $post_id
 *
 * @return int
 */
function get_primary_category_id( $post_id = 0 ) {
	if ( 0 === $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	return Primary_Category::$instance->get_primary_category_for_post( $post_id );
}

/**
 * Helper function. Gets the Term object for the current post or post with provided ID.
 *
 * @param int    $post_id
 * @param string $output
 *
 * @return mixed
 */
function get_primary_category( $post_id = 0, $output = null ) {
	$primary_category_id = get_primary_category_id( $post_id );

	return get_term( $primary_category_id, 'category', $output );
}
