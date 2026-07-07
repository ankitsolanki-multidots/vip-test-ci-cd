<?php
/**
 * Title: Sidebar
 * Slug: test-ci-cd/default-sidebar
 * Categories: sidebar
 * Block Types: core/template-part/sidebar
 *
 * @package test-ci-cd
 */

?>
<!-- wp:group -->
<div class="wp-block-group">
	<!-- wp:search {"label":"","showLabel":false,"placeholder":" Search","width":100,"widthUnit":"%","buttonText":"Search","buttonUseIcon":true,"backgroundColor":"primary","textColor":"light"} /-->

	<!-- wp:spacer {"height":"var(--wp--preset--spacing--20)"} -->
	<div style="height:var(--wp--preset--spacing--20)" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->
</div>
<!-- /wp:group -->

<!-- wp:group -->
<div class="wp-block-group">
	<!-- wp:group {"layout":{"inherit":true,"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php echo esc_html__( 'Categorise', 'test-ci-cd' ); ?></h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group -->
	<div class="wp-block-group">
		<!-- wp:categories /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:spacer {"height":"var(--wp--preset--spacing--20)"} -->
	<div style="height:var(--wp--preset--spacing--20)" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->
</div>
<!-- /wp:group -->

<!-- wp:group -->
<div class="wp-block-group">
	<!-- wp:group {"layout":{"inherit":true,"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php echo esc_html__( 'Tags', 'test-ci-cd' ); ?></h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group -->
	<div class="wp-block-group">
		<!-- wp:tag-cloud /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:spacer {"height":"var(--wp--preset--spacing--20)"} -->
	<div style="height:var(--wp--preset--spacing--20)" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->
</div>
<!-- /wp:group -->
