<?php
/**
 * Enqueue theme assets
 *
 * @package test-ci-cd
 */

declare( strict_types = 1 );

namespace TEST_CI_CD\Includes;

use TEST_CI_CD\Includes\Traits\Singleton;

/**
 * Class Assets
 */
class Assets {
	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {

		// load class.
		$this->setup_hooks();
	}

	/**
	 * To register action/filter.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function setup_hooks(): void {

		/**
		 * Actions.
		 */
		add_action( 'init', array( $this, 'remove_wp_emoji' ) );
		add_action( 'init', array( $this, 'move_scripts_to_footer' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		/**
		 * The 'enqueue_block_assets' hook includes styles and scripts both in editor and frontend,
		 * except when is_admin() is used to include them conditionally
		 */
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_editor_assets' ) );
		add_action( 'wp_footer', array( $this, 'enqueue_editor_assets' ) );
		add_filter( 'upload_mimes', array( $this, 'add_file_types_to_uploads' ) ); //phpcs:ignore WordPressVIPMinimum.Hooks.RestrictedHooks.upload_mimes
		add_filter( 'wp_check_filetype_and_ext', array( $this, 'fix_svg_filetype' ), 10, 4 );

		add_filter( 'script_loader_tag', array( $this, 'script_additional_attrs' ), 10, 2 );
		add_action( 'wp_print_footer_scripts', array( $this, 'lazy_load_scripts' ) );
		add_filter( 'should_load_separate_core_block_assets', '__return_true' );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_view_js_in_footer' ), 999 );
	}

	/**
	 * Remove Emoji from the page.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function remove_wp_emoji(): void {

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
	}

	/**
	 * Move render blocking JS to the footer.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function move_scripts_to_footer(): void {
		// Remove default jQuery registration through WordPress.
		wp_dequeue_script( 'jquery-core' );
		wp_dequeue_script( 'jquery-migrate' );
		wp_dequeue_script( 'wp-embed' );
		wp_deregister_script( 'jquery-core' );
		wp_deregister_script( 'jquery-migrate' );
		wp_deregister_script( 'wp-embed' );

		wp_enqueue_script( 'jquery-core', '/wp-includes/js/jquery/jquery.min.js', array(), TEST_CI_CD_THEME_VERSION, true );
	}

	/**
	 * Load critical CSS.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function hook_critical_css(): void {

		$response = vip_safe_wp_remote_get( TEST_CI_CD_BUILD_URI . '/inline.css' );   // load template output in buffer.

		if ( ! is_wp_error( $response ) ) {
			$css = wp_remote_retrieve_body( $response );
			wp_register_style( 'test-ci-cd-inline-css', false, array(), TEST_CI_CD_THEME_VERSION, true );
			wp_add_inline_style( 'test-ci-cd-inline-css', $css );
			wp_enqueue_style( 'test-ci-cd-inline-css' );
		}
	}

	/**
	 * Register and Enqueue styles.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register_styles(): void {

		$this->hook_critical_css();

		// Register styles.
		wp_register_style( 'main-css', TEST_CI_CD_BUILD_URI . '/main.css', array(), TEST_CI_CD_THEME_VERSION, 'all' );

		// Enqueue Styles.
		wp_enqueue_style( 'main-css' );
	}

	/**
	 * Register and Enqueue Scripts.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register_scripts(): void {
		// Register scripts.
		wp_register_script( 'main-js', TEST_CI_CD_BUILD_URI . '/main.js', array( 'jquery-core' ), TEST_CI_CD_THEME_VERSION, true );

		// Enqueue Scripts.
		wp_enqueue_script( 'main-js' );

		wp_localize_script(
			'main-js',
			'siteConfig',
			array(
				'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'loadmore_post_nonce' ),
			)
		);
	}

	/**
	 * Enqueue editor scripts and styles.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function enqueue_editor_assets(): void {

		// Editor CSS.
		if ( is_admin() ) {
			wp_enqueue_style(
				'test-ci-cd-editor-css',
				TEST_CI_CD_BUILD_URI . '/geditor.css',
				array(),
				TEST_CI_CD_THEME_VERSION,
				'all'
			);
		}

		// Change block Priority to head.
		$blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
		foreach ( $blocks as $block ) {
			if ( has_block( $block->name ) ) {
				wp_enqueue_style( $block->style );
			}
		}
	}

	/**
	 * Action Function to add SVG support in file uploads.
	 *
	 * @param array $file_types Supported file types.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function add_file_types_to_uploads( array $file_types ): array {
		if ( is_user_logged_in() && current_user_can( 'upload_files' ) ) {
			$file_types['svg']  = 'image/svg+xml';
			$file_types['svgz'] = 'image/svg+xml';
		}

		return $file_types;
	}

	/**
	 * Ensure WordPress recognizes SVG file extensions during upload validation.
	 *
	 * @param array  $data     File data array.
	 * @param string $file     Full path to the file.
	 * @param string $filename The name of the file.
	 * @param array  $mimes    Allowed mime types.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function fix_svg_filetype( array $data, string $file, string $filename, array $mimes ): array {
		unset( $file, $mimes );

		if ( ! is_user_logged_in() || ! current_user_can( 'upload_files' ) ) {
			return $data;
		}

		$extension = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );

		if ( in_array( $extension, array( 'svg', 'svgz' ), true ) ) {
			$data['ext']  = $extension;
			$data['type'] = 'image/svg+xml';
		}

		return $data;
	}

	/**
	 * Lazy load script code.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function lazy_load_scripts(): void {
		$timeout = '5';
		?>
		<script type="text/javascript" id="flying-scripts">const loadScriptsTimer = setTimeout(loadScripts,<?php echo esc_html( $timeout ); ?>* 1000
			)
			;const userInteractionEvents = ["mouseover", "keydown", "touchstart", "touchmove", "wheel"];
			userInteractionEvents.forEach(function (event) {
				window.addEventListener(event, triggerScriptLoader, {passive: !0})
			});

			function triggerScriptLoader() {
				loadScripts();
				clearTimeout(loadScriptsTimer);
				userInteractionEvents.forEach(function (event) {
					window.removeEventListener(event, triggerScriptLoader, {passive: !0})
				})
			}

			function loadScripts() {
				document.querySelectorAll("script[data-type='lazy']").forEach(function (elem) {
					elem.setAttribute("src", elem.getAttribute("data-src"))
				})
			}</script>
		<?php
	}


	/**
	 * Identify script and do the lazy load.
	 *
	 * @param string $tag Tags string.
	 * @param string $handle Handle name.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function script_additional_attrs( string $tag, string $handle ): string {
		if ( 'grs-ad' === $handle ) {
			return str_replace( ' src', ' data-type="lazy" data-src', $tag );
		}

		return $tag;
	}

	/**
	 * Loads block's view.js scripts in the footer.
	 */
	public function load_view_js_in_footer() {
		// Get all enqueued scripts.
		$scripts = wp_scripts();

		// Loop through all enqueued scripts.
		foreach ( $scripts->queue as $handle ) {
			// Check if the script is a view.js script.
			if ( strpos( $handle, 'view-script' ) !== false ) {
				// Change the 'group' property to true.
				$scripts->add_data( $handle, 'group', 1 );
			}
		}
	}
}
