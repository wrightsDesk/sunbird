<?php
/**
 * The MetaVisibility
 *
 * @package   MetaFieldBlock
 * @author    Phi Phan <mrphipv@gmail.com>
 * @copyright Copyright (c) 2026, Phi Phan
 */

namespace MetaFieldBlock;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( MetaVisibility::class ) ) :
	/**
	 * The MetaVisibility class.
	 */
	class MetaVisibility extends CoreComponent {
		/**
		 * The strict mode
		 *
		 * @var boolean
		 */
		protected $strict_mode = false;

		/**
		 * The constructor
		 */
		public function __construct( $the_plugin_instance ) {
			parent::__construct( $the_plugin_instance );

			// Load strict mode.
			$this->strict_mode = get_option( 'mfb_enable_strict_mode', false );
		}
		/**
		 * Run main hooks
		 *
		 * @return void
		 */
		public function run() {
			// Handle visibility for meta value.
			add_filter( 'meta_field_block_get_object_id', [ $this, 'validate_object_id' ], 5, 3 );
		}

		/**
		 * Validate object id to protect private fields
		 *
		 * @param int|string $object_id
		 * @param string     $object_type
		 * @param array      $attributes
		 * @return int|string
		 */
		public function validate_object_id( $object_id, $object_type, $attributes ) {
			$validated_id = absint( $object_id );

			if ( ! $this->is_meta_field( $attributes ) ) {
				return $validated_id;
			}

			switch ( $object_type ) {
				case 'post':
					$validated_id = $this->validate_post_object( $validated_id, $attributes );
					break;

				case 'term':
					$validated_id = $this->validate_term_object( $validated_id, $attributes );
					break;

				case 'user':
					$validated_id = $this->validate_user_object( $validated_id, $attributes );
					break;
			}

			return apply_filters( 'meta_field_block_validate_object_id', $validated_id, $attributes['fieldName'] ?? '', $object_id, $object_type, $attributes );
		}

		/**
		 * Has strict mode
		 *
		 * @return boolean
		 */
		public function has_strict_mode() {
			return $this->strict_mode;
		}

		/**
		 * Is meta field
		 *
		 * @param array $attributes
		 * @return boolean
		 */
		private function is_meta_field( $attributes ) {
			return in_array( $attributes['fieldType'] ?? '', [ 'meta', 'dynamic' ], true );
		}

		/**
		 * Is custom object id
		 *
		 * @param array $attributes
		 * @return boolean
		 */
		private function is_custom_object_id( $attributes ) {
			return ( $attributes['isCustomSource'] ?? false ) && ( $attributes['objectId'] ?? false );
		}

		/**
		 * Validate post object
		 *
		 * @param int   $post_id
		 * @param array $attributes
		 * @return int
		 */
		private function validate_post_object( $post_id, $attributes ) {
			if ( ! $post_id ) {
				return 0;
			}

			$strict_mode   = $this->has_strict_mode();
			$has_custom_id = $this->is_custom_object_id( $attributes );

			if ( ! $has_custom_id && ! $strict_mode ) {
				return $post_id;
			}

			$post = get_post( $post_id );
			if ( ! $post ) {
				return 0;
			}

			if ( ! is_post_publicly_viewable( $post_id ) || ! $this->is_rest_visible( 'post', $attributes['fieldName'] ?? '' ) ) {
				return $this->can_edit_post( $post_id, $post->post_type ) ? $post_id : 0;
			}

			return $post_id;
		}

		/**
		 * Validate term object
		 *
		 * @param int   $term_id
		 * @param array $attributes
		 * @return int
		 */
		private function validate_term_object( $term_id, $attributes ) {
			if ( ! $term_id ) {
				return 0;
			}

			$strict_mode   = $this->has_strict_mode();
			$has_custom_id = $this->is_custom_object_id( $attributes );

			if ( ! $has_custom_id && ! $strict_mode ) {
				return $term_id;
			}

			$term = get_term( $term_id );
			if ( ! $term || is_wp_error( $term ) ) {
				return 0;
			}

			$taxonomy = get_taxonomy( $term->taxonomy );
			if ( ! $taxonomy ) {
				return 0;
			}

			if ( ! $taxonomy->publicly_queryable || ! $this->is_rest_visible( 'term', $attributes['fieldName'] ?? '' ) ) {
				return current_user_can( $taxonomy->cap->manage_terms ?? 'manage_categories' ) ? $term_id : 0;
			}

			return $term_id;
		}

		/**
		 * Validate user field
		 *
		 * @param int   $user_id
		 * @param array $attributes
		 * @return int
		 */
		private function validate_user_object( $user_id, $attributes ) {
			if ( current_user_can( 'list_users' ) ) {
				return $user_id;
			}

			if ( $this->is_rest_visible( 'user', $attributes['fieldName'] ?? '' ) ) {
				return $user_id;
			}

			return get_current_user_id() === $user_id ? $user_id : 0;
		}

		/**
		 * User can edit the post
		 *
		 * @param int    $post_id
		 * @param string $post_type
		 * @return boolean
		 */
		private function can_edit_post( $post_id, $post_type ) {
			$post_type_object = get_post_type_object( $post_type );

			return $post_type_object && current_user_can( $post_type_object->cap->edit_post ?? 'edit_post', $post_id );
		}

		/**
		 * Check if the meta field is visible in REST API
		 */
		private function is_rest_visible( $object_type, $meta_key ) {
			if ( ! $meta_key ) {
				return false;
			}

			$registered = get_registered_meta_keys( $object_type );
			return ! empty( $registered[ $meta_key ]['show_in_rest'] );
		}
	}
endif;
