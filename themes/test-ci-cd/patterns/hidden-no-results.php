<?php
/**
 * Title: Hidden No Results Content
 * Slug: test-ci-cd/hidden-no-results-content
 * Inserter: no
 *
 * @package test-ci-cd
 */

?>
<!-- wp:paragraph -->
<p>
<?php echo esc_html_x( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'Message explaining that there are no results returned from a search', 'test-ci-cd' ); ?>
</p>
<!-- /wp:paragraph -->

<!-- wp:search {"label":"<?php echo esc_html_x( 'Search', 'label', 'test-ci-cd' ); ?>","placeholder":"<?php echo esc_attr_x( 'Search...', 'placeholder for search field', 'test-ci-cd' ); ?>","showLabel":false,"buttonText":"<?php esc_attr_e( 'Search', 'test-ci-cd' ); ?>","buttonUseIcon":true} /-->
