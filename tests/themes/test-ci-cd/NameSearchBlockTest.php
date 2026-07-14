<?php
/**
 * Tests for the Name Search Gutenberg block.
 *
 * @package test-ci-cd
 */

declare( strict_types = 1 );

namespace TEST_CI_CD\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use TEST_CI_CD\Blocks\Name_Search;
use WP_Block;

/**
 * Name_Search block test case.
 */
class NameSearchBlockTest extends TestCase {

	use MockeryPHPUnitIntegration;

	/**
	 * Set up Brain Monkey and reset singleton state.
	 */
	protected function setUp(): void {
		parent::setUp();

		Monkey\setUp();
		test_ci_cd_reset_singleton( Name_Search::class );

		Functions\when( 'add_action' )->justReturn( true );
		Functions\when( 'add_filter' )->justReturn( true );
		Functions\when( 'do_action' )->justReturn( null );
		Functions\when( '__' )->returnArg( 1 );
		Functions\when( 'esc_html__' )->returnArg( 1 );
		Functions\when( 'esc_html_e' )->alias(
			static function ( $text ): void {
				echo $text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		);
		Functions\when( 'esc_attr_e' )->alias(
			static function ( $text ): void {
				echo $text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		);
		Functions\when( 'esc_html' )->returnArg( 1 );
		Functions\when( 'esc_attr' )->returnArg( 1 );
		Functions\when( 'esc_url' )->returnArg( 1 );
		Functions\when( 'sanitize_text_field' )->returnArg( 1 );
		Functions\when( 'wp_unslash' )->returnArg( 1 );
		Functions\when( 'wp_kses_post' )->returnArg( 1 );
		Functions\when( 'wp_kses' )->returnArg( 1 );
		Functions\when( 'absint' )->alias(
			static function ( $value ): int {
				return abs( (int) $value );
			}
		);
		Functions\when( 'home_url' )->justReturn( 'https://example.com/' );
		Functions\when( 'get_permalink' )->justReturn( 'https://example.com/search-page/' );
		Functions\when( 'get_query_var' )->justReturn( 0 );
		Functions\when( 'get_block_wrapper_attributes' )->justReturn( 'class="wp-block-test-ci-cd-name-search md-name-search"' );
		Functions\when( 'add_query_arg' )->alias(
			static function ( $args, $url ) {
				return $url . '?' . http_build_query( $args );
			}
		);
	}

	/**
	 * Tear down Brain Monkey and reset singleton state.
	 */
	protected function tearDown(): void {
		unset( $_GET['s'], $_GET['paged'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		test_ci_cd_reset_singleton( Name_Search::class );
		Monkey\tearDown();

		parent::tearDown();
	}

	/**
	 * Verify the block slug is name-search.
	 */
	public function test_block_slug_is_name_search(): void {
		$block      = Name_Search::get_instance();
		$reflection = new \ReflectionClass( $block );
		$property   = $reflection->getProperty( '_block' );
		$property->setAccessible( true );

		$this->assertSame( 'name-search', $property->getValue( $block ) );
	}

	/**
	 * Verify search term comes from the request query string.
	 */
	public function test_get_search_term_uses_query_string(): void {
		Functions\when( 'get_search_query' )->justReturn( '' );
		$_GET['s'] = 'Woodward';

		$block = Name_Search::get_instance();

		$this->assertSame( 'Woodward', $block->get_search_term( array() ) );
	}

	/**
	 * Verify previewTerm attribute is used when no live query exists.
	 */
	public function test_get_search_term_falls_back_to_preview_term(): void {
		Functions\when( 'get_search_query' )->justReturn( '' );

		$block = Name_Search::get_instance();

		$this->assertSame(
			'Woodward',
			$block->get_search_term(
				array(
					'previewTerm' => 'Woodward',
				)
			)
		);
	}

	/**
	 * Verify pagination page list for large result sets.
	 */
	public function test_get_pagination_pages_includes_ellipsis_for_large_sets(): void {
		$block  = Name_Search::get_instance();
		$pages  = $block->get_pagination_pages( 2, 50 );

		$this->assertContains( 1, $pages );
		$this->assertContains( 2, $pages );
		$this->assertContains( 3, $pages );
		$this->assertContains( '…', $pages );
		$this->assertContains( 50, $pages );
	}

	/**
	 * Verify empty-state markup when no search term is present.
	 */
	public function test_render_callback_shows_empty_state_without_search_term(): void {
		Functions\when( 'get_search_query' )->justReturn( '' );

		$block  = Name_Search::get_instance();
		$output = $block->render_callback(
			array(
				'postsPerPage' => 16,
				'buttonLabel'  => 'Search',
			),
			'',
			$this->createMock( WP_Block::class )
		);

		$this->assertStringContainsString( 'md-name-search__hero', $output );
		$this->assertStringContainsString( 'md-name-search__form', $output );
		$this->assertStringContainsString( 'name="s"', $output );
		$this->assertStringContainsString( 'Enter a name to search.', $output );
		$this->assertStringContainsString( 'Search', $output );
	}
}
