<?php
/**
 * Plugin Name: Primary Category - 10up Exercise Project
 * Plugin URI:  https://www.jrgould.com
 * Description: Add the ability to specify a primary category for posts
 * Version:     0.1
 * Author:      Jeff Gould
 * Author URI:  https://www.jrgould.com
 * License:     GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: primary-category
 * Domain Path: /languages
 *
 * @package Primary_Category
 */

namespace JRG;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$config = array(
	'base_path'   => dirname( __FILE__ ),
	'base_url'    => plugin_dir_url( __FILE__ ),
	'text_domain' => 'primary-category',
	'version'     => '0.1',
);

/**
 * Load textdomain
 */
add_action( 'init', function () use ( $config ) {
	$locale = apply_filters( 'plugin_locale', get_locale(), $config['text_domain'] );
	load_textdomain( $config['text_domain'], WP_LANG_DIR . '/primary-category/primary-category-' . $locale . '.mo' );
	load_plugin_textdomain( $config['text_domain'], false, plugin_basename( $config['base_path'] ) . '/languages/' );
} );

if ( is_admin() ) {
	// do admin stuff.
	require_once $config['base_path'] . '/classes/class-primary-category-admin.php';
	$admin = new Primary_Category_Admin( $config );
	$admin->init();
}

require_once $config['base_path'] . '/classes/class-primary-category-frontend.php';
$frontend = new Primary_Category_Frontend( $config );
$frontend->init();
