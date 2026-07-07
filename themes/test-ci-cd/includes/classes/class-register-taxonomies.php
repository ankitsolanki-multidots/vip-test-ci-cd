<?php
/**
 * Register Custom Taxonomies
 *
 * @package test-ci-cd
 */

declare( strict_types = 1 );

namespace TEST_CI_CD\Includes;

use TEST_CI_CD\Includes\Traits\Singleton;

/**
 * Class for register taxonomies.
 */
class Register_Taxonomies {
	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {

		// load class.
		$this->setup_hooks();
	}

	/**
	 * To register action/filter.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function setup_hooks(): void {

		/**
		 * Actions.
		 */
		add_action( 'init', array( $this, 'register_year_taxonomy' ) );
	}

	/**
	 * Register Taxonomy Year.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register_year_taxonomy(): void {

		$labels = array(
			'name'              => _x( 'Years', 'taxonomy general name', 'test-ci-cd' ),
			'singular_name'     => _x( 'Year', 'taxonomy singular name', 'test-ci-cd' ),
			'search_items'      => __( 'Search Years', 'test-ci-cd' ),
			'all_items'         => __( 'All Years', 'test-ci-cd' ),
			'parent_item'       => __( 'Parent Year', 'test-ci-cd' ),
			'parent_item_colon' => __( 'Parent Year:', 'test-ci-cd' ),
			'edit_item'         => __( 'Edit Year', 'test-ci-cd' ),
			'update_item'       => __( 'Update Year', 'test-ci-cd' ),
			'add_new_item'      => __( 'Add New Year', 'test-ci-cd' ),
			'new_item_name'     => __( 'New Year Name', 'test-ci-cd' ),
			'menu_name'         => __( 'Year', 'test-ci-cd' ),
		);
		$args   = array(
			'labels'             => $labels,
			'description'        => __( 'Movie Release Year', 'test-ci-cd' ),
			'hierarchical'       => false,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => true,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
		);
		register_taxonomy( 'movie-year', array( 'movies' ), $args );
	}
}
