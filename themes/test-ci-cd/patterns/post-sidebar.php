<?php
/**
 * Title: Post Sidebar
 * Slug: test-ci-cd/post-sidebar
 * Categories: sidebar
 * Block Types: core/template-part/post-sidebar
 *
 * @package test-ci-cd
 */

?>
<!-- wp:group -->
<div class="wp-block-group">
	<!-- wp:search {"label":"","showLabel":false,"placeholder":" Search","width":100,"widthUnit":"%","buttonText":"Search","buttonUseIcon":true,"backgroundColor":"primary","textColor":"light"} /-->

	<!-- wp:spacer {"height":"var(--wp--preset--spacing--10)"} -->
	<div style="height:var(--wp--preset--spacing--10)" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->
</div>
<!-- /wp:group -->

<!-- wp:group -->
<div class="wp-block-group">
	<!-- wp:group {"layout":{"inherit":true,"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php echo esc_html__( 'Recent Posts', 'test-ci-cd' ); ?></h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group -->
	<div class="wp-block-group">
		<!-- wp:latest-posts {"displayPostDate":true,"displayFeaturedImage":true,"featuredImageAlign":"left","featuredImageSizeWidth":75,"featuredImageSizeHeight":75} /-->
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
		<h2 class="wp-block-heading"><?php echo esc_html__( 'Recent Comments', 'test-ci-cd' ); ?></h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group -->
	<div class="wp-block-group">
		<!-- wp:latest-comments {"displayAvatar":false,"displayDate":false,"displayExcerpt":false} /-->
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
		<h2 class="wp-block-heading"><?php echo esc_html__( 'Archive', 'test-ci-cd' ); ?></h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group -->
	<div class="wp-block-group">
		<!-- wp:archives /-->
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
