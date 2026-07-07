<?php
/**
 * Tests for Register_Post_Types.
 *
 * @package test-ci-cd
 */

declare( strict_types = 1 );

namespace TEST_CI_CD\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use TEST_CI_CD\Includes\Register_Post_Types;

/**
 * Register_Post_Types test case.
 */
class RegisterPostTypesTest extends TestCase {

	use MockeryPHPUnitIntegration;

	/**
	 * Set up Brain Monkey and reset singleton state.
	 */
	protected function setUp(): void {
		parent::setUp();

		Monkey\setUp();
		test_ci_cd_reset_singleton( Register_Post_Types::class );

		Functions\when( 'add_action' )->justReturn( true );
		Functions\when( 'do_action' )->justReturn( null );
		Functions\when( '__' )->returnArg( 1 );
		Functions\when( '_x' )->returnArg( 1 );
	}

	/**
	 * Tear down Brain Monkey and reset singleton state.
	 */
	protected function tearDown(): void {
		test_ci_cd_reset_singleton( Register_Post_Types::class );
		Monkey\tearDown();

		parent::tearDown();
	}

	/**
	 * Verify the theme registers the movies custom post type.
	 */
	public function test_register_movie_cpt_registers_movies_post_type(): void {
		Functions\expect( 'register_post_type' )
			->once()
			->with(
				'movies',
				\Mockery::on(
					function ( array $args ): bool {
						return 'Movie' === $args['label']
							&& true === $args['public']
							&& true === $args['show_in_rest'];
					}
				)
			);

		$register_post_types = Register_Post_Types::get_instance();
		$register_post_types->register_movie_cpt();
	}
}
