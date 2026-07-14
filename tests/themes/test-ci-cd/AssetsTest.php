<?php
/**
 * Tests for Assets SVG and CSV upload support.
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
		Functions\when( '__' )->returnArg( 1 );
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
		$this->assertArrayHasKey( 'csv', $file_types );
		$this->assertSame( 'text/csv', $file_types['csv'] );
	}

	/**
	 * Verify non-administrators cannot upload SVG or CSV files.
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
		$this->assertArrayNotHasKey( 'csv', $file_types );
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

	/**
	 * Verify a valid CSV file is accepted by content validation.
	 */
	public function test_is_valid_csv_file_accepts_valid_csv(): void {
		$temp_file = tempnam( sys_get_temp_dir(), 'csv' );
		$this->assertNotFalse( $temp_file );

		file_put_contents( $temp_file, "name,email\nJane,jane@example.com\n" );

		$assets = Assets::get_instance();

		$this->assertTrue( $assets->is_valid_csv_file( $temp_file, 'text/csv' ) );

		unlink( $temp_file );
	}

	/**
	 * Verify a binary file with a .csv extension is rejected.
	 */
	public function test_is_valid_csv_file_rejects_binary_content(): void {
		$temp_file = tempnam( sys_get_temp_dir(), 'csv' );
		$this->assertNotFalse( $temp_file );

		// Simulate an executable payload renamed with a .csv extension.
		file_put_contents( $temp_file, "MZ\0\0\0This is not a CSV file" );

		$assets = Assets::get_instance();

		$this->assertFalse( $assets->is_valid_csv_file( $temp_file ) );

		unlink( $temp_file );
	}

	/**
	 * Verify unsupported MIME types are rejected even with a .csv name.
	 */
	public function test_is_valid_csv_file_rejects_disallowed_mime_type(): void {
		$temp_file = tempnam( sys_get_temp_dir(), 'csv' );
		$this->assertNotFalse( $temp_file );

		file_put_contents( $temp_file, "name,email\nJane,jane@example.com\n" );

		$assets = Assets::get_instance();

		$this->assertFalse( $assets->is_valid_csv_file( $temp_file, 'application/x-dosexec' ) );

		unlink( $temp_file );
	}

	/**
	 * Verify filetype check sets CSV type for valid CSV files.
	 */
	public function test_validate_csv_filetype_check_sets_csv_mime_type_for_valid_file(): void {
		$temp_file = tempnam( sys_get_temp_dir(), 'csv' );
		$this->assertNotFalse( $temp_file );

		file_put_contents( $temp_file, "id,title\n1,Sample\n" );

		$assets = Assets::get_instance();

		$data = $assets->validate_csv_filetype_check(
			array(
				'ext'             => false,
				'type'            => false,
				'proper_filename' => 'data.csv',
			),
			$temp_file,
			'data.csv',
			array(
				'csv' => 'text/csv',
			),
			'text/plain'
		);

		$this->assertSame( 'csv', $data['ext'] );
		$this->assertSame( 'text/csv', $data['type'] );

		unlink( $temp_file );
	}

	/**
	 * Verify filetype check rejects non-CSV content using a .csv extension.
	 */
	public function test_validate_csv_filetype_check_rejects_fake_csv_extension(): void {
		$temp_file = tempnam( sys_get_temp_dir(), 'csv' );
		$this->assertNotFalse( $temp_file );

		file_put_contents( $temp_file, "MZ\0\0executable-content" );

		$assets = Assets::get_instance();

		$data = $assets->validate_csv_filetype_check(
			array(
				'ext'             => 'csv',
				'type'            => 'text/csv',
				'proper_filename' => 'malware.csv',
			),
			$temp_file,
			'malware.csv',
			array(
				'csv' => 'text/csv',
			),
			'application/x-dosexec'
		);

		$this->assertFalse( $data['ext'] );
		$this->assertFalse( $data['type'] );

		unlink( $temp_file );
	}

	/**
	 * Verify upload prefilter allows valid CSV files.
	 */
	public function test_validate_csv_upload_allows_valid_csv(): void {
		$temp_file = tempnam( sys_get_temp_dir(), 'csv' );
		$this->assertNotFalse( $temp_file );

		file_put_contents( $temp_file, "sku,price\nABC,9.99\n" );

		$assets = Assets::get_instance();

		$file = $assets->validate_csv_upload(
			array(
				'name'     => 'products.csv',
				'type'     => 'text/csv',
				'tmp_name' => $temp_file,
				'error'    => 0,
				'size'     => filesize( $temp_file ),
			)
		);

		$this->assertSame( 0, $file['error'] );

		unlink( $temp_file );
	}

	/**
	 * Verify upload prefilter blocks renamed executables with a .csv extension.
	 */
	public function test_validate_csv_upload_rejects_fake_csv_extension(): void {
		$temp_file = tempnam( sys_get_temp_dir(), 'csv' );
		$this->assertNotFalse( $temp_file );

		file_put_contents( $temp_file, "MZ\0\0\0fake-executable" );

		$assets = Assets::get_instance();

		$file = $assets->validate_csv_upload(
			array(
				'name'     => 'payload.csv',
				'type'     => 'text/csv',
				'tmp_name' => $temp_file,
				'error'    => 0,
				'size'     => filesize( $temp_file ),
			)
		);

		$this->assertSame( 'Invalid CSV file. Only valid CSV files are allowed.', $file['error'] );

		unlink( $temp_file );
	}

	/**
	 * Verify non-CSV uploads are left unchanged by the CSV prefilter.
	 */
	public function test_validate_csv_upload_ignores_non_csv_files(): void {
		$assets = Assets::get_instance();

		$original = array(
			'name'     => 'photo.jpg',
			'type'     => 'image/jpeg',
			'tmp_name' => '/tmp/photo.jpg',
			'error'    => 0,
			'size'     => 123,
		);

		$file = $assets->validate_csv_upload( $original );

		$this->assertSame( $original, $file );
	}
}
