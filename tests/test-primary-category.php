<?php
/**
 * Class Primary Category Test
 *
 * @package JRG_Primary_Category
 */

/**
 * Sample test case.
 */
class PrimaryCategoryTest extends WP_UnitTestCase {

	protected $Primary_Category;
	protected $config;

	public function setUp() {
		parent::setUp();

		$current_dir = dirname( __FILE__ );
		$plugin_dir  = dirname( $current_dir );
		require_once $plugin_dir . '/jrg-primary-category.php';

		$this->config = array(
			'base_path'   => $plugin_dir,
			'base_url'    => plugin_dir_url( $plugin_dir ),
			'meta_slug'   => '_jrg-primary-category',
			'text_domain' => 'jrg-primary-category',
			'version'     => '0.1',
		);

		$this->Primary_Category = new \JRG\Primary_Category\Primary_Category( $this->config );
	}

	/**
	 * Test setting primary category on post
	 */
	public function test_set_primary_category() {
		$post = $this->factory()->post->create();
		$term = $this->factory()->term->create( array(
			'taxonomy' => 'category'
		) );

		$primary_category_was_set = $this->Primary_Category->set_primary_category_for_post( $post, $term );
		$this->assertTrue( $primary_category_was_set );

		$primary_category_from_meta = get_post_meta( $post, $this->config['meta_slug'], true );
		$this->assertEquals( $term, $primary_category_from_meta );
	}

	/**
	 * Test getting primary category id
	 */
	public function test_get_primary_category() {
		$post = $this->factory()->post->create();
		$term = $this->factory()->term->create( array(
			'taxonomy' => 'category'
		) );

		$primary_category_was_set = $this->Primary_Category->set_primary_category_for_post( $post, $term );
		$this->assertTrue( $primary_category_was_set );

		$primary_category_from_helper = JRG\Primary_Category\get_primary_category_id( $post );

		$this->assertEquals( $term, $primary_category_from_helper );
	}
}
