<?php
/**
 * WP Admin Functionality
 * 
 * @package Primary_Category
 */
class Primary_Category_Admin {

	public $config;

	function __construct( $config ) {
		$this->config = $config;
	}

	function init() {
		if( ! $this->should_init() ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	function enqueue_scripts() {
		wp_register_script( 'primary-category-admin-script', $this->config['base_url'] . '/assets/primary-category.js', 'JQuery', $this->config['version'], true );
		wp_enqueue_script( 'primary-category-admin-script' );

		wp_register_style( 'primary-category-admin-style', $this->config['base_url'] . '/assets/primary-category.css', array(), $this->config['version'] );
		wp_enqueue_style( 'primary-category-admin-style' );
	}

	private function should_init() {
		global $pagenow;
		if( in_array( $pagenow , array( 'edit.php', 'post.php' ) ) ) {
			return true;
		}
		return false;
	}

}