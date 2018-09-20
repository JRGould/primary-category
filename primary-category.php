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

$base_path = dirname( __FILE__ );


if ( is_admin() ) {
	// do admin stuff.
	require $base_path . '/classes/class-primary-category-admin.php';
	$admin = new Primary_Category_Admin(
		array(
			'base_path' => $base_path,
		)
	);
	$admin->init();
} else {
	// do frontend stuff.
	require $base_path . '/classes/class-primary-category-frontend.php';
	$frontend = new Primary_Category_Frontend(
		array(
			'base_path' => $base_path,
		)
	);
	$frontend->init();
}
