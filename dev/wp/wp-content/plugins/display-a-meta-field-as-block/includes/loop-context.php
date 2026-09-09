<?php
/**
 * The Loop context hanlder
 *
 * @package   MetaFieldBlock
 * @author    Phi Phan <mrphipv@gmail.com>
 * @copyright Copyright (c) 2025, Phi Phan
 */

namespace MetaFieldBlock;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( LoopContext::class ) ) :
	/**
	 * The LoopContext class.
	 */
	class LoopContext extends CoreComponent {
		/**
		 * The loop context queue
		 */
		protected $loop_contexts = [];

		/**
		 * The editor script handle
		 */
		private $editor_script_handle = 'mfb-meta-field-block-editor-script';

		/**
		 * Run main hooks
		 *
		 * @return void
		 */
		public function run() {
			// Update current loop context.
			add_filter( 'render_block_context', [ $this, 'update_loop_context' ], 10, 3 );

			// Reset current loop context.
			add_filter( 'render_block', [ $this, 'reset_loop_context' ], 10, 2 );

			// Add query block data.
			add_action( 'init', [ $this, 'add_data_for_the_editor_script' ], 30 );
		}

		/**
		 * Update the loop context when starting rendering a loop block.
		 *
		 * @param array         $block_content The block context.
		 * @param array         $block         An associative array of the block being rendered.
		 * @param WP_Block|null $block         The parent block being rendered.
		 * @return array The block context.
		 */
		public function update_loop_context( $context, $block, $parent_block ) {
			if ( $parent_block && in_array( $parent_block->name, $this->get_query_blocks(), true ) ) {
				$this->loop_contexts[] = $parent_block->name;
			}

			return $context;
		}

		/**
		 * Reset the loop context when finishing rendering a loop block.
		 *
		 * @param string   $block_content The block content.
		 * @param WP_Block $block         The block being rendered.
		 *
		 * @return string The block content.
		 */
		public function reset_loop_context( $block_content, $block ) {
			if ( in_array( $block['blockName'], $this->get_query_blocks(), true ) ) {
				array_pop( $this->loop_contexts );
			}

			return $block_content;
		}

		/**
		 * Get loop context queue
		 *
		 * @return array
		 */
		public function get_loop_contexts() {
			return $this->loop_contexts;
		}

		/**
		 * Add query block data
		 *
		 * @return void
		 */
		public function add_data_for_the_editor_script() {
			// Add field types for JS.
			wp_add_inline_script(
				// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				apply_filters( 'meta_field_block_get_editor_script_handle', $this->editor_script_handle ),
				'const MFBQUERYBLOCKS=' . wp_json_encode(
					[
						'POST' => $this->get_post_query_blocks(),
						'TERM' => $this->get_taxonomy_query_blocks(),
					]
				) . ';',
				'before'
			);
		}

		/**
		 * Get a list of loop blocks.
		 *
		 * @return array
		 */
		private function get_query_blocks() {
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			return apply_filters( 'meta_field_block_get_query_blocks', array_merge( $this->get_post_query_blocks(), $this->get_taxonomy_query_blocks() ) );
		}

		/**
		 * Get a list of post loop blocks.
		 *
		 * @return array
		 */
		public function get_post_query_blocks() {
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			return apply_filters( 'meta_field_block_get_post_query_blocks', [ 'core/query', 'woocommerce/product-collection' ] );
		}

		/**
		 * Get a list of taxonomy loop blocks.
		 *
		 * @return array
		 */
		public function get_taxonomy_query_blocks() {
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			return apply_filters( 'meta_field_block_get_taxonomy_query_blocks', [ 'core/terms-query', 'mfb/terms-query' ] );
		}
	}
endif;
