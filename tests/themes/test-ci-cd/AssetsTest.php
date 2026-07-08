<?php
/**
 * Tests for Assets SVG upload support.
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
		Functions\when( 'do_action' )->justReturn( null );
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
	 * Verify administrators can upload SVG files.
	 */
	public function test_add_file_types_to_uploads_allows_svg_for_administrators(): void {
		Functions\when( 'is_user_logged_in' )->justReturn( true );
		Functions\when( 'current_user_can' )->justReturn( true );

		$assets = Assets::get_instance();

		$file_types = $assets->add_file_types_to_uploads(
			array(
				'jpg' => 'image/jpeg',
			)
		);

		$this->assertArrayHasKey( 'svg', $file_types );
		$this->assertSame( 'image/svg+xml', $file_types['svg'] );
		$this->assertArrayHasKey( 'svgz', $file_types );
		$this->assertSame( 'image/svg+xml', $file_types['svgz'] );
	}

	/**
	 * Verify non-administrators cannot upload SVG files.
	 */
	public function test_add_file_types_to_uploads_denies_svg_for_non_administrators(): void {
		Functions\when( 'is_user_logged_in' )->justReturn( true );
		Functions\when( 'current_user_can' )->justReturn( false );

		$assets = Assets::get_instance();

		$file_types = $assets->add_file_types_to_uploads(
			array(
				'jpg' => 'image/jpeg',
			)
		);

		$this->assertArrayNotHasKey( 'svg', $file_types );
		$this->assertArrayNotHasKey( 'svgz', $file_types );
	}

	/**
	 * Verify SVG mime type is restored when WordPress cannot detect it.
	 */
	public function test_fix_svg_filetype_check_sets_svg_mime_type_for_administrators(): void {
		Functions\when( 'is_user_logged_in' )->justReturn( true );
		Functions\when( 'current_user_can' )->justReturn( true );
		Functions\when( 'wp_check_filetype' )->justReturn(
			array(
				'ext'             => 'svg',
				'type'            => 'image/svg+xml',
				'proper_filename' => false,
			)
		);

		$assets = Assets::get_instance();

		$data = $assets->fix_svg_filetype_check(
			array(
				'ext'             => false,
				'type'            => false,
				'proper_filename' => 'icon.svg',
			),
			'/tmp/icon.svg',
			'icon.svg',
			array(
				'svg' => 'image/svg+xml',
			),
			'image/svg+xml'
		);

		$this->assertSame( 'svg', $data['ext'] );
		$this->assertSame( 'image/svg+xml', $data['type'] );
	}

	/**
	 * Verify non-administrators do not get SVG mime type overrides.
	 */
	public function test_fix_svg_filetype_check_denies_svg_for_non_administrators(): void {
		Functions\when( 'is_user_logged_in' )->justReturn( true );
		Functions\when( 'current_user_can' )->justReturn( false );

		$assets = Assets::get_instance();

		$original = array(
			'ext'             => false,
			'type'            => false,
			'proper_filename' => 'icon.svg',
		);

		$data = $assets->fix_svg_filetype_check(
			$original,
			'/tmp/icon.svg',
			'icon.svg',
			array(
				'svg' => 'image/svg+xml',
			),
			'image/svg+xml'
		);

		$this->assertSame( $original, $data );
	}
}
