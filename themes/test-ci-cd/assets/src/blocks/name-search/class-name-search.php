<?php
/**
 * Registers the test-ci-cd/name-search block.
 *
 * @package test-ci-cd
 */

declare( strict_types = 1 );

namespace TEST_CI_CD\Blocks;

use TEST_CI_CD\Includes\Block_Base;
use WP_Block;
use WP_Query;

/**
 * Class for the test-ci-cd/name-search block.
 */
class Name_Search extends Block_Base {

	/**
	 * Default results per page.
	 */
	public const DEFAULT_POSTS_PER_PAGE = 16;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->_block = 'name-search';
	}

	/**
	 * Resolve the active search term.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public function get_search_term( array $attributes ): string {
		$term = '';

		if ( function_exists( 'get_search_query' ) ) {
			$term = (string) get_search_query();
		}

		if ( '' === $term && isset( $_GET['s'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$term = sanitize_text_field( wp_unslash( $_GET['s'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		if ( '' === $term && ! empty( $attributes['previewTerm'] ) ) {
			$term = sanitize_text_field( (string) $attributes['previewTerm'] );
		}

		return $term;
	}

	/**
	 * Build pagination page numbers for the UI.
	 *
	 * @param int $current Current page number.
	 * @param int $total   Total number of pages.
	 * @return array<int|string>
	 */
	public function get_pagination_pages( int $current, int $total ): array {
		if ( $total < 1 ) {
			return array();
		}

		if ( $total <= 7 ) {
			return range( 1, $total );
		}

		$pages = array( 1 );

		if ( $current > 3 ) {
			$pages[] = '…';
		}

		$start = max( 2, $current - 1 );
		$end   = min( $total - 1, $current + 1 );

		for ( $page = $start; $page <= $end; $page++ ) {
			$pages[] = $page;
		}

		if ( $current < $total - 2 ) {
			$pages[] = '…';
		}

		$pages[] = $total;

		return array_values( array_unique( $pages, SORT_REGULAR ) );
	}

	/**
	 * Render block.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block content.
	 * @param WP_Block $block      Block object.
	 * @return string
	 */
	public function render_callback(
		// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		array $attributes,
		string $content,
		WP_Block $block
		// phpcs:enable
	): string {
		$wrapper_attributes = get_block_wrapper_attributes(
			array(
				'class' => 'md-name-search',
			)
		);

		$search_term       = $this->get_search_term( $attributes );
		$posts_per_page    = isset( $attributes['postsPerPage'] ) ? absint( $attributes['postsPerPage'] ) : self::DEFAULT_POSTS_PER_PAGE;
		$posts_per_page    = $posts_per_page > 0 ? $posts_per_page : self::DEFAULT_POSTS_PER_PAGE;
		$button_label      = ! empty( $attributes['buttonLabel'] ) ? (string) $attributes['buttonLabel'] : __( 'Search', 'test-ci-cd' );
		$background_url    = ! empty( $attributes['backgroundImageUrl'] ) ? (string) $attributes['backgroundImageUrl'] : '';
		$current_page      = max( 1, absint( get_query_var( 'paged' ) ) );
		$form_action       = get_permalink();
		$results           = array();
		$found_posts       = 0;
		$max_pages         = 0;

		if ( ! $form_action ) {
			$form_action = home_url( '/' );
		}

		if ( isset( $_GET['paged'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$current_page = max( 1, absint( wp_unslash( $_GET['paged'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		if ( '' !== $search_term ) {
			$query = new WP_Query(
				array(
					's'              => $search_term,
					'post_type'      => 'any',
					'post_status'    => 'publish',
					'posts_per_page' => $posts_per_page,
					'paged'          => $current_page,
					'no_found_rows'  => false,
				)
			);

			$results     = $query->posts;
			$found_posts = (int) $query->found_posts;
			$max_pages   = (int) $query->max_num_pages;
			wp_reset_postdata();
		}

		ob_start();
		?>
		<div <?php echo wp_kses_post( $wrapper_attributes ); ?>>
			<section
				class="md-name-search__hero<?php echo '' !== $background_url ? ' has-background-image' : ''; ?>"
				<?php if ( '' !== $background_url ) : ?>
					style="background-image: url(<?php echo esc_url( $background_url ); ?>);"
				<?php endif; ?>
			>
				<div class="md-name-search__hero-inner">
					<?php if ( '' !== $search_term ) : ?>
						<h1 class="md-name-search__title">
							<?php
							echo wp_kses(
								sprintf(
									/* translators: %s: search term */
									__( 'Search Result for %s', 'test-ci-cd' ),
									'<strong>&#8216;' . esc_html( $search_term ) . '&#8217;</strong>'
								),
								array(
									'strong' => array(),
								)
							);
							?>
						</h1>
					<?php else : ?>
						<h1 class="md-name-search__title">
							<?php esc_html_e( 'Name Search', 'test-ci-cd' ); ?>
						</h1>
					<?php endif; ?>

					<form
						class="md-name-search__form"
						role="search"
						method="get"
						action="<?php echo esc_url( $form_action ); ?>"
					>
						<label class="screen-reader-text" for="md-name-search-field">
							<?php esc_html_e( 'Search by name', 'test-ci-cd' ); ?>
						</label>
						<input
							id="md-name-search-field"
							class="md-name-search__input"
							type="search"
							name="s"
							value="<?php echo esc_attr( $search_term ); ?>"
							placeholder="<?php esc_attr_e( 'Search by name…', 'test-ci-cd' ); ?>"
						/>
						<button class="md-name-search__submit" type="submit">
							<?php echo esc_html( $button_label ); ?>
						</button>
					</form>
				</div>
			</section>

			<section class="md-name-search__results">
				<?php if ( '' === $search_term ) : ?>
					<p class="md-name-search__empty">
						<?php esc_html_e( 'Enter a name to search.', 'test-ci-cd' ); ?>
					</p>
				<?php elseif ( empty( $results ) ) : ?>
					<p class="md-name-search__empty">
						<?php
						printf(
							/* translators: %s: search term */
							esc_html__( 'No results found for &#8216;%s&#8217;.', 'test-ci-cd' ),
							esc_html( $search_term )
						);
						?>
					</p>
				<?php else : ?>
					<?php
					$this->render_results_toolbar( $found_posts, $search_term, $current_page, $max_pages, $form_action );
					?>
					<ul class="md-name-search__list">
						<?php foreach ( $results as $post ) : ?>
							<li class="md-name-search__item">
								<a class="md-name-search__link" href="<?php echo esc_url( get_permalink( $post ) ); ?>">
									<?php echo esc_html( get_the_title( $post ) ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
					<?php
					$this->render_results_toolbar( $found_posts, $search_term, $current_page, $max_pages, $form_action );
					?>
				<?php endif; ?>
			</section>
		</div>
		<?php
		return (string) ob_get_clean();
	}

	/**
	 * Render the results count and pagination toolbar.
	 *
	 * @param int    $found_posts  Total found posts.
	 * @param string $search_term  Active search term.
	 * @param int    $current_page Current page.
	 * @param int    $max_pages    Total pages.
	 * @param string $form_action  Base URL for pagination links.
	 * @return void
	 */
	protected function render_results_toolbar(
		int $found_posts,
		string $search_term,
		int $current_page,
		int $max_pages,
		string $form_action
	): void {
		?>
		<div class="md-name-search__toolbar">
			<p class="md-name-search__count">
				<?php
				printf(
					/* translators: 1: number of results, 2: search term */
					esc_html__( 'Showing %1$s results for &#8216;%2$s&#8217;', 'test-ci-cd' ),
					esc_html( (string) $found_posts ),
					esc_html( $search_term )
				);
				?>
			</p>
			<?php if ( $max_pages > 1 ) : ?>
				<nav class="md-name-search__pagination" aria-label="<?php esc_attr_e( 'Search results pagination', 'test-ci-cd' ); ?>">
					<?php if ( $current_page > 1 ) : ?>
						<a class="md-name-search__page-link md-name-search__page-link--nav" href="<?php echo esc_url( $this->get_page_url( $form_action, $search_term, $current_page - 1 ) ); ?>">
							&larr; <?php esc_html_e( 'prev', 'test-ci-cd' ); ?>
						</a>
					<?php else : ?>
						<span class="md-name-search__page-link md-name-search__page-link--nav is-disabled">
							&larr; <?php esc_html_e( 'prev', 'test-ci-cd' ); ?>
						</span>
					<?php endif; ?>

					<?php foreach ( $this->get_pagination_pages( $current_page, $max_pages ) as $page ) : ?>
						<?php if ( '…' === $page ) : ?>
							<span class="md-name-search__page-ellipsis">&hellip;</span>
						<?php elseif ( (int) $page === $current_page ) : ?>
							<span class="md-name-search__page-link is-active" aria-current="page"><?php echo esc_html( (string) $page ); ?></span>
						<?php else : ?>
							<a class="md-name-search__page-link" href="<?php echo esc_url( $this->get_page_url( $form_action, $search_term, (int) $page ) ); ?>">
								<?php echo esc_html( (string) $page ); ?>
							</a>
						<?php endif; ?>
					<?php endforeach; ?>

					<?php if ( $current_page < $max_pages ) : ?>
						<a class="md-name-search__page-link md-name-search__page-link--nav" href="<?php echo esc_url( $this->get_page_url( $form_action, $search_term, $current_page + 1 ) ); ?>">
							<?php esc_html_e( 'next', 'test-ci-cd' ); ?> &rarr;
						</a>
					<?php else : ?>
						<span class="md-name-search__page-link md-name-search__page-link--nav is-disabled">
							<?php esc_html_e( 'next', 'test-ci-cd' ); ?> &rarr;
						</span>
					<?php endif; ?>
				</nav>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Build a paginated search URL.
	 *
	 * @param string $base_url Base URL.
	 * @param string $term     Search term.
	 * @param int    $page     Page number.
	 * @return string
	 */
	protected function get_page_url( string $base_url, string $term, int $page ): string {
		$args = array(
			's' => $term,
		);

		if ( $page > 1 ) {
			$args['paged'] = $page;
		}

		return add_query_arg( $args, $base_url );
	}
}
