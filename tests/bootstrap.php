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
