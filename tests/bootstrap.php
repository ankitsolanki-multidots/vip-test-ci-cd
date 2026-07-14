<?php
/**
 * PHPUnit bootstrap.
 *
 * @package test-ci-cd
 */

declare( strict_types = 1 );

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

if ( ! defined( 'TEST_CI_CD_THEME_PATH' ) ) {
	define( 'TEST_CI_CD_THEME_PATH', dirname( __DIR__ ) . '/themes/test-ci-cd' );
}

require_once TEST_CI_CD_THEME_PATH . '/includes/traits/trait-singleton.php';
require_once TEST_CI_CD_THEME_PATH . '/includes/classes/class-register-post-types.php';
require_once TEST_CI_CD_THEME_PATH . '/includes/classes/class-assets.php';
require_once TEST_CI_CD_THEME_PATH . '/includes/classes/class-block-base.php';
require_once TEST_CI_CD_THEME_PATH . '/assets/src/blocks/name-search/class-name-search.php';

if ( ! class_exists( 'WP_Block' ) ) {
	/**
	 * Minimal WP_Block stub for unit tests.
	 */
	class WP_Block {}
}

if ( ! class_exists( 'WP_Query' ) ) {
	/**
	 * Minimal WP_Query stub for unit tests.
	 */
	class WP_Query {
		/**
		 * Queried posts.
		 *
		 * @var array
		 */
		public $posts = array();

		/**
		 * Found posts count.
		 *
		 * @var int
		 */
		public $found_posts = 0;

		/**
		 * Max number of pages.
		 *
		 * @var int
		 */
		public $max_num_pages = 0;
	}
}


/**
 * Reset singleton state between tests.
 *
 * @param class-string $class Class using the Singleton trait.
 */
function test_ci_cd_reset_singleton( string $class ): void {
	$reflection = new ReflectionClass( $class );
	$property   = $reflection->getProperty( '_instance' );
	$property->setAccessible( true );
	$property->setValue( null, array() );
}
