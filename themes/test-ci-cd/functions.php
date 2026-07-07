<?php
/**
 * Theme Functions.
 *
 * @package test-ci-cd
 */

declare( strict_types = 1 );

if ( ! defined( 'TEST_CI_CD_THEME_VERSION' ) ) {
	define( 'TEST_CI_CD_THEME_VERSION', '1.0' );
}

if ( ! defined( 'TEST_CI_CD_THEME_PATH' ) ) {
	define( 'TEST_CI_CD_THEME_PATH', __DIR__ );
}

if ( ! defined( 'TEST_CI_CD_THEME_URL' ) ) {
	define( 'TEST_CI_CD_THEME_URL', get_template_directory_uri() );
}

if ( ! defined( 'TEST_CI_CD_BUILD_URI' ) ) {
	define( 'TEST_CI_CD_BUILD_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/build' );
}

if ( ! defined( 'TEST_CI_CD_BUILD_PATH' ) ) {
	define( 'TEST_CI_CD_BUILD_PATH', untrailingslashit( get_template_directory() ) . '/assets/build' );
}

if ( ! defined( 'TEST_CI_CD_SRC_BLOCK_DIR_PATH' ) ) {
	define( 'TEST_CI_CD_SRC_BLOCK_DIR_PATH', get_template_directory() . '/assets/build/blocks' );
}

/**
 * Load up the class autoloader.
 */
require_once TEST_CI_CD_THEME_PATH . '/includes/helpers/autoloader.php';

/**
 * Theme Init
 *
 * Sets up the theme.
 *
 * @return void
 * @since 1.0.0
 */
function test_ci_cd_get_theme_instance(): void {
	\TEST_CI_CD\Includes\Test_Ci_Cd::get_instance();
}

test_ci_cd_get_theme_instance();
