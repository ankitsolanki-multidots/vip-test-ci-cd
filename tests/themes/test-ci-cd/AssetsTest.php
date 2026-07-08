<?php
/**
 * Tests for Assets.
 *
 * @package test-ci-cd
 */

declare( strict_types=1 );

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
	 * Verify SVG mime types are added for users who can upload files.
	 */
	public function test_add_file_types_to_uploads_adds_svg_for_upload_capable_users(): void {
		Functions\when( 'is_user_logged_in' )->justReturn( true );
		Functions\when( 'current_user_can' )->justReturn( true );

		$assets = Assets::get_instance();
		$result = $assets->add_file_types_to_uploads( array( 'jpg' => 'image/jpeg' ) );

		$this->assertSame( 'image/svg+xml', $result['svg'] );
		$this->assertSame( 'image/svg+xml', $result['svgz'] );
		$this->assertSame( 'image/jpeg', $result['jpg'] );
	}

	/**
	 * Verify SVG mime types are not added for users without upload capability.
	 */
	public function test_add_file_types_to_uploads_skips_svg_for_users_without_upload_capability(): void {
		Functions\when( 'is_user_logged_in' )->justReturn( true );
		Functions\when( 'current_user_can' )->justReturn( false );

		$assets = Assets::get_instance();
		$result = $assets->add_file_types_to_uploads( array( 'jpg' => 'image/jpeg' ) );

		$this->assertArrayNotHasKey( 'svg', $result );
		$this->assertArrayNotHasKey( 'svgz', $result );
	}

	/**
	 * Verify SVG file extension and mime type are recognized during upload validation.
	 */
	public function test_fix_svg_filetype_sets_svg_mime_type(): void {
		Functions\when( 'is_user_logged_in' )->justReturn( true );
		Functions\when( 'current_user_can' )->justReturn( true );

		$assets = Assets::get_instance();
		$result = $assets->fix_svg_filetype(
			array(
				'ext'             => false,
				'type'            => false,
				'proper_filename' => false,
			),
			'/tmp/example.svg',
			'example.svg',
			array()
		);

		$this->assertSame( 'svg', $result['ext'] );
		$this->assertSame( 'image/svg+xml', $result['type'] );
	}

	/**
	 * Verify non-SVG files are left unchanged during upload validation.
	 */
	public function test_fix_svg_filetype_leaves_non_svg_files_unchanged(): void {
		Functions\when( 'is_user_logged_in' )->justReturn( true );
		Functions\when( 'current_user_can' )->justReturn( true );

		$assets     = Assets::get_instance();
		$input_data = array(
			'ext'             => 'jpg',
			'type'            => 'image/jpeg',
			'proper_filename' => false,
		);

		$result = $assets->fix_svg_filetype(
			$input_data,
			'/tmp/example.jpg',
			'example.jpg',
			array()
		);

		$this->assertSame( $input_data, $result );
	}
}
