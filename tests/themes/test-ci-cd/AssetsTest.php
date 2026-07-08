<?php
/**
 * Tests for Assets.
 *
 * @package test-ci-cd
 */

declare( strict_types = 1 );

namespace TEST_CI_CD\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use TEST_CI_CD\Includes\Assets;

/**
 * Assets test case.
 */
class AssetsTest extends TestCase {

	use MockeryPHPUnitIntegration;

	/**
	 * Set up Brain Monkey and reset singleton state.
	 */
	protected function setUp(): void {
		parent::setUp();

		Monkey\setUp();
		test_ci_cd_reset_singleton( Assets::class );

		Functions\when( 'add_action' )->justReturn( true );
		Functions\when( 'add_filter' )->justReturn( true );
	}

	/**
	 * Tear down Brain Monkey and reset singleton state.
	 */
	protected function tearDown(): void {
		test_ci_cd_reset_singleton( Assets::class );
		Monkey\tearDown();

		parent::tearDown();
	}

	/**
	 * Verify SVG mime type is registered for users who can upload files.
	 */
	public function test_add_file_types_to_uploads_adds_svg_for_upload_capable_users(): void {
		Functions\when( 'current_user_can' )->justReturn( true );

		$assets = Assets::get_instance();

		$result = $assets->add_file_types_to_uploads(
			array(
				'jpg' => 'image/jpeg',
			)
		);

		$this->assertArrayHasKey( 'svg', $result );
		$this->assertSame( 'image/svg+xml', $result['svg'] );
		$this->assertSame( 'image/jpeg', $result['jpg'] );
	}

	/**
	 * Verify SVG mime type is not registered without upload capability.
	 */
	public function test_add_file_types_to_uploads_does_not_add_svg_without_upload_capability(): void {
		Functions\when( 'current_user_can' )->justReturn( false );

		$assets = Assets::get_instance();

		$result = $assets->add_file_types_to_uploads(
			array(
				'jpg' => 'image/jpeg',
			)
		);

		$this->assertArrayNotHasKey( 'svg', $result );
		$this->assertSame( 'image/jpeg', $result['jpg'] );
	}
}
