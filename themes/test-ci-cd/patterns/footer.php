<?php
/**
 * Title: Footer
 * Slug: test-ci-cd/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 *
 * @package test-ci-cd
 */

?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"gray","className":"test-ci-cd-footer has-background-color","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background-color test-ci-cd-footer has-gray-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)"><!-- wp:group {"align":"wide","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"blockGap":"0"}},"layout":{"type":"flex"}} -->
<div class="wp-block-group" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
	<!-- wp:paragraph {"align":"left"} -->
	<p class="has-text-align-right">
		<a style="color:var(--wp--preset--color--primary);" href="<?php echo esc_url( __( 'https://www.multidots.com/', 'test-ci-cd' ) ); ?>">
			<?php
			/* translators: %s: CMS name, i.e. WordPress. */
			printf( esc_html__( 'Proudly powered by %s', 'test-ci-cd' ), 'WordPress' );
			?>
		</a>
	</p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:paragraph {"fontSize":"normal"} -->
<p class="has-normal-font-size">
	<?php
	/* translators: 1: Theme name, 2: Theme author. */
	printf( esc_html__( 'Theme: %1$s by %2$s.', 'test-ci-cd' ), 'Multidots', '<a style="color:var(--wp--preset--color--primary);" href="htts://www.multidots.com/">multidots.com</a>' );
	?>
</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
