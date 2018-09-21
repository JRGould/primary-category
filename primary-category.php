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

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}


$config = array(
	'base_path' => dirname( __FILE__ ),
	'base_url'  => plugin_dir_url( __FILE__ ),
	'version'   => '0.1',
);

if ( is_admin() ) {
	// do admin stuff.
	require $config['base_path'] . '/classes/class-primary-category-admin.php';
	$admin = new Primary_Category_Admin( $config );
	$admin->init();
} else {
	// do frontend stuff.
	require $config['base_path'] . '/classes/class-primary-category-frontend.php';
	$frontend = new Primary_Category_Frontend( $config );
	$frontend->init();
}
