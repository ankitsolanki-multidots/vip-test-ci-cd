<?php
/**
 * Registers the test-ci-cd/sample block.
 *
 * @global array    $attrs   Block attributes passed to the render callback.
 * @global string   $content Block content from InnerBlocks passed to the render callback.
 * @global WP_Block $block   Block registration object.
 *
 * @package test-ci-cd
 */

namespace TEST_CI_CD\Blocks;

use TEST_CI_CD\Includes\Block_Base;

/**
 *  Class for the test-ci-cd/sample block.
 */
class Sample extends Block_Base {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->_block = 'sample';
	}
}
