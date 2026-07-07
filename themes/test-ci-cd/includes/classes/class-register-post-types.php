<?php
/**
 * Register Post Types
 *
 * @package test-ci-cd
 */

declare( strict_types = 1 );

namespace TEST_CI_CD\Includes;

use TEST_CI_CD\Includes\Traits\Singleton;

/**
 * Class for register post types.
 */
class Register_Post_Types {
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
		add_action( 'init', array( $this, 'register_movie_cpt' ), 0 );
	}

	/**
	 * Register Custom Post Type Movie.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register_movie_cpt(): void {

		$labels = array(
			'name'                  => _x( 'Movies', 'Post Type General Name', 'test-ci-cd' ),
			'singular_name'         => _x( 'Movie', 'Post Type Singular Name', 'test-ci-cd' ),
			'menu_name'             => _x( 'Movies', 'Admin Menu text', 'test-ci-cd' ),
			'name_admin_bar'        => _x( 'Movie', 'Add New on Toolbar', 'test-ci-cd' ),
			'archives'              => __( 'Movie Archives', 'test-ci-cd' ),
			'attributes'            => __( 'Movie Attributes', 'test-ci-cd' ),
			'parent_item_colon'     => __( 'Parent Movie:', 'test-ci-cd' ),
			'all_items'             => __( 'All Movies', 'test-ci-cd' ),
			'add_new_item'          => __( 'Add New Movie', 'test-ci-cd' ),
			'add_new'               => __( 'Add New', 'test-ci-cd' ),
			'new_item'              => __( 'New Movie', 'test-ci-cd' ),
			'edit_item'             => __( 'Edit Movie', 'test-ci-cd' ),
			'update_item'           => __( 'Update Movie', 'test-ci-cd' ),
			'view_item'             => __( 'View Movie', 'test-ci-cd' ),
			'view_items'            => __( 'View Movies', 'test-ci-cd' ),
			'search_items'          => __( 'Search Movie', 'test-ci-cd' ),
			'not_found'             => __( 'Not found', 'test-ci-cd' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'test-ci-cd' ),
			'featured_image'        => __( 'Featured Image', 'test-ci-cd' ),
			'set_featured_image'    => __( 'Set featured image', 'test-ci-cd' ),
			'remove_featured_image' => __( 'Remove featured image', 'test-ci-cd' ),
			'use_featured_image'    => __( 'Use as featured image', 'test-ci-cd' ),
			'insert_into_item'      => __( 'Insert into Movie', 'test-ci-cd' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Movie', 'test-ci-cd' ),
			'items_list'            => __( 'Movies list', 'test-ci-cd' ),
			'items_list_navigation' => __( 'Movies list navigation', 'test-ci-cd' ),
			'filter_items_list'     => __( 'Filter Movies list', 'test-ci-cd' ),
		);
		$args   = array(
			'label'               => __( 'Movie', 'test-ci-cd' ),
			'description'         => __( 'The movies', 'test-ci-cd' ),
			'labels'              => $labels,
			'menu_icon'           => 'dashicons-admin-post',
			'supports'            => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'revisions',
				'author',
				'comments',
				'trackbacks',
				'page-attributes',
				'custom-fields',
			),
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'hierarchical'        => false,
			'exclude_from_search' => false,
			'show_in_rest'        => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);

		register_post_type( 'movies', $args );
	}
}
