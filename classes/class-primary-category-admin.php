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

		add_action( 'save_post', array( $this, 'save_primary_category' ) );
	}

	function enqueue_scripts() {
		wp_register_script( 'primary-category-admin-script', $this->config['base_url'] . '/assets/primary-category.js', 'JQuery', $this->config['version'], true );

		wp_localize_script(
			'primary-category-admin-script',
			'primary_category_data',
			array(
				'nonce'            => wp_create_nonce( 'save-primary-category-field' ),
				'primary_category' => $this->get_current_post_primary_category(),
				'strings'          => $this->get_js_localized_strings(),
			)
		);

		wp_enqueue_script( 'primary-category-admin-script' );

		wp_register_style( 'primary-category-admin-style', $this->config['base_url'] . '/assets/primary-category.css', array(), $this->config['version'] );
		wp_enqueue_style( 'primary-category-admin-style' );
	}

	private function get_js_localized_strings() {
		return array(
			'primary' => _x( 'Primary', 'main or principal', $this->config['text_domain'] ),
			'make primary' => _x( 'Make Primary', 'designate category as primary category', $this->config['text_domain'] ),
		);
	}

	private function should_init() {
		global $pagenow;
		if( in_array( $pagenow , array( 'edit.php', 'post.php' ) ) ) {
			return true;
		}
		return false;
	}

	private function get_current_post_primary_category() {
		global $post;
		if ( ! $post || ! $post->ID) {
			return 0;
		}
		$primary = get_post_meta( $post->ID, '_primary-category', true );

		return $primary;
	}

	function save_primary_category() {
		global $post;
		
		check_admin_referer( 'save-primary-category-field', '_primary-category_nonce' );
		$primary_category = $_POST['_primary-category'];
		
		if( ! is_numeric( $primary_category ) ) {
			$primary_category = 0;
		}

		update_post_meta( $post->ID, '_primary-category', $primary_category );
	}

}