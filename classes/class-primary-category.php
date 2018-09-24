<?php
/**
 * WP Admin Functionality
 *
 * @package JRG_Primary_Category
 */

namespace JRG\Primary_Category;

class Primary_Category {

	protected $config;

	/**
	 * Primary_Category_Admin constructor.
	 *
	 * @param array $config
	 *
	 * @return void
	 */
	public function __construct( $config ) {
		$this->config = $config;
	}

	/**
	 * Init class specifically rather than when constructed.
	 *
	 * @return void
	 */
	public function init() {
		if ( ! $this->should_init_admin() ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'save_post', array( $this, 'save_primary_category' ) );
	}

	/**
	 * Enqueues styles and scripts including localizations / php values to be passed to js.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_register_script( 'jrg-primary-category-admin-script', $this->config['base_url'] . '/assets/jrg-primary-category.js', 'JQuery', $this->config['version'], true );

		wp_localize_script( 'jrg-primary-category-admin-script', 'primary_category_data', array(
			'nonce'            => wp_create_nonce( 'save-jrg-primary-category-field' ),
			'primary_category' => $this->get_current_post_primary_category(),
			'strings'          => $this->get_js_localized_strings(),
		) );

		wp_enqueue_script( 'jrg-primary-category-admin-script' );

		wp_register_style( 'jrg-primary-category-admin-style', $this->config['base_url'] . '/assets/jrg-primary-category.css', array(), $this->config['version'] );
		wp_enqueue_style( 'jrg-primary-category-admin-style' );
	}

	/**
	 * Determines if this class should be allowed to initialize itself, prevents loading assets where not needed.
	 *
	 * @return bool
	 */
	protected function should_init_admin() {
		global $pagenow;
		if ( is_admin() && in_array( $pagenow, array( 'edit.php', 'post.php' ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Saves the primary category from posted data for the current post.
	 *
	 * @return void
	 */
	public function save_primary_category() {
		global $post;

		check_admin_referer( 'save-jrg-primary-category-field', '_jrg-primary-category_nonce' );

		// set to 0 if GET val casts to anything other than a positive int
		$primary_category = max( (int) $_POST['_jrg-primary-category'], 0 );

		if( $primary_category ) {
			$this->set_primary_category_for_post( $post->ID, $primary_category );
		}
	}

	/**
	 * @param int $post_id
	 * @param string $primary_category
	 *
	 * @return bool
	 */
	protected function set_primary_category_for_post( $post_id, $primary_category ) {
		return (bool) update_post_meta( $post_id, $this->config['meta_slug'], $primary_category );
	}


	/**
	 * Gets the primary category for the current global $post, returns 0 if not specified or available.
	 *
	 * @return int
	 */
	protected function get_current_post_primary_category() {
		global $post;
		if ( ! $post || ! $post->ID ) {
			return 0;
		}
		return $this->get_primary_category_for_post( $post->ID );
	}

	/**
	 * @param int $post_id
	 *
	 * @return int
	 */
	public function get_primary_category_for_post( $post_id ) {
		return get_post_meta( $post_id, $this->config['meta_slug'], true ) ?: 0;
	}

	/**
	 * Assembles translations for js strings.
	 *
	 * @return array
	 */
	protected function get_js_localized_strings() {
		return array(
			'primary'      => _x( 'Primary', 'main or principal', $this->config['text_domain'] ),
			'make primary' => _x( 'Make Primary', 'designate category as primary category', $this->config['text_domain'] ),
		);
	}
}
