<?php

/**
 * Plugin Name:       Meta Field Block
 * Plugin URI:        https://metafieldblock.com?utm_source=MFB&utm_campaign=MFB+visit+site&utm_medium=link&utm_content=Plugin+URI
 * Description:       A single block to display custom fields in the Block Editor. It supports ACF, MetaBox, WooCommerce, meta, rest field, shortcode and more.
 * Requires at least: 6.9
 * Requires PHP:      8.0
 * Version:           1.5.4
 * Author:            Phi Phan
 * Author URI:        https://metafieldblock.com?utm_source=MFB&utm_campaign=MFB+visit+site&utm_medium=link&utm_content=Author+URI
 * License:           GPL-3.0
 *
 * @package   MetaFieldBlock
 * @copyright Copyright(c) 2022, Phi Phan
 *
 */
namespace MetaFieldBlock;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
if ( function_exists( __NAMESPACE__ . '\\mfb_fs' ) ) {
    mfb_fs()->set_basename( false, __FILE__ );
    return;
}
// Include Freemius functions.
require_once __DIR__ . '/freemius.php';
if ( !class_exists( MetaFieldBlock::class ) ) {
    /**
     * The main class
     */
    class MetaFieldBlock {
        /**
         * Plugin version
         *
         * @var String
         */
        protected $version = '1.5.4';

        /**
         * Components
         *
         * @var Array
         */
        protected $components = [];

        /**
         * Plugin instance
         *
         * @var MetaFieldBlock
         */
        private static $instance;

        /**
         * A dummy constructor
         */
        private function __construct() {
        }

        /**
         * Initialize the instance.
         *
         * @return MetaFieldBlock
         */
        public static function get_instance() {
            if ( !isset( self::$instance ) ) {
                self::$instance = new MetaFieldBlock();
                self::$instance->initialize();
            }
            return self::$instance;
        }

        /**
         * Kick start function.
         * Define constants
         * Load dependencies
         * Register components
         * Run the main hooks
         *
         * @return void
         */
        public function initialize() {
            // Setup constants.
            $this->setup_constants();
            // Load dependencies.
            $this->load_dependencies();
            // Register components.
            $this->register_components();
            // Run hooks.
            $this->run();
        }

        /**
         * Setup constants
         *
         * @return void
         */
        public function setup_constants() {
            $this->define_constant( 'MFB', true );
            $this->define_constant( 'MFB_ROOT_FILE', __FILE__ );
            $this->define_constant( 'MFB_VERSION', $this->version );
            $this->define_constant( 'MFB_PATH', trailingslashit( plugin_dir_path( MFB_ROOT_FILE ) ) );
            $this->define_constant( 'MFB_URL', trailingslashit( plugin_dir_url( MFB_ROOT_FILE ) ) );
        }

        /**
         * Load core components
         *
         * @return void
         */
        public function register_components() {
            // Load & register core components.
            $components = [
                'includes/loop-context.php'    => LoopContext::class,
                'includes/meta-visibility.php' => MetaVisibility::class,
                'includes/rest-fields.php'     => RestFields::class,
                'includes/acf-fields.php'      => ACFFields::class,
                'includes/mb-fields.php'       => MBFields::class,
                'includes/dynamic-field.php'   => DynamicField::class,
                'includes/settings.php'        => Settings::class,
                'includes/freemius-config.php' => FreemiusConfig::class,
            ];
            foreach ( $components as $file => $classname ) {
                $this->register_component( $file, $classname );
            }
            // Register additional components.
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
            do_action( 'mfb/register_components', $this );
        }

        /**
         * Load dependencies
         *
         * @return void
         */
        public function load_dependencies() {
            // Load core component.
            $this->include_file( 'includes/core-component.php' );
            $this->include_file( 'includes/helper-functions.php' );
        }

        /**
         * Run main hooks
         *
         * @return void
         */
        public function run() {
            // Register the block.
            add_action( 'init', [$this, 'register_block'] );
            // Save version and trigger upgraded hook.
            add_action( 'plugins_loaded', [$this, 'version_upgrade'], 1 );
            // Flush the server cache.
            add_action(
                'save_post',
                [$this, 'flush_cache'],
                10,
                2
            );
            // Run all components.
            foreach ( $this->components as $component ) {
                $component->run();
            }
        }

        /**
         * Register the block
         *
         * @return void
         */
        public function register_block() {
            // Register block.
            register_block_type( MFB_PATH . '/build', [
                'render_callback'   => [$this, 'render_block'],
                'skip_inner_blocks' => true,
            ] );
        }

        /**
         * Renders the `mbf/meta-field-block` block on the server.
         *
         * @param  array    $attributes Block attributes.
         * @param  string   $content    Block default content.
         * @param  WP_Block $block      Block instance.
         * @return string   Returns the value for the field.
         */
        public function render_block( $attributes, $content, $block ) {
            $field_name = $attributes['fieldName'] ?? '';
            if ( empty( $field_name ) ) {
                return '';
            }
            // Get object type.
            $object_type = $this->get_object_type( $field_name, $attributes, $block );
            // Get object id.
            $object_id = $this->get_object_id( $object_type, $attributes, $block );
            // Get field type.
            $field_type = $attributes['fieldType'] ?? 'rest_field';
            // Is dynamic block?
            $is_dynamic_block = $this->is_dynamic_block( $attributes );
            if ( $is_dynamic_block ) {
                if ( in_array( $field_type, [
                    'meta',
                    'dynamic',
                    'rest_field',
                    'option'
                ], true ) ) {
                    if ( in_array( $object_type, ['post', 'term', 'user'], true ) ) {
                        $get_meta_callback = "get_{$object_type}_meta";
                        $content = $get_meta_callback( $object_id, $field_name, true );
                    } else {
                        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
                        $content = apply_filters(
                            '_meta_field_block_get_field_value_other_type',
                            $content,
                            $field_name,
                            $object_id,
                            $object_type,
                            $attributes,
                            $block
                        );
                    }
                    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
                    $content = apply_filters(
                        '_meta_field_block_get_field_value',
                        $content,
                        $field_name,
                        $object_id,
                        $object_type,
                        $attributes,
                        $block
                    );
                } else {
                    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
                    $content = apply_filters(
                        '_meta_field_block_get_block_content_by_provider',
                        $content,
                        $field_name,
                        $field_type,
                        $object_id,
                        $object_type,
                        $attributes,
                        $block
                    );
                }
            } else {
                // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
                $content = apply_filters(
                    '_meta_field_block_render_static_block',
                    $content,
                    $field_name,
                    $object_id,
                    $object_type,
                    $attributes,
                    $block
                );
            }
            // Get the block markup.
            return meta_field_block_get_block_markup(
                $content,
                $attributes,
                $block,
                $object_id,
                $object_type,
                $is_dynamic_block
            );
        }

        /**
         * Get object type.
         *
         * @param string   $field_name Field name.
         * @param array    $attributes Block attributes.
         * @param WP_Block $block      The block instance.
         * @return string
         */
        public function get_object_type( $field_name, $attributes, $block ) {
            // Get object type from meta type.
            $object_type = $attributes['metaType'] ?? '';
            if ( !$object_type ) {
                // Cache key.
                $cache_key = 'object_type';
                // Get from the cache.
                $cache_data = wp_cache_get( $cache_key, 'mfb' );
                if ( false === $cache_data ) {
                    $cache_data = [];
                }
                // Get loop context handler.
                $loop_context_handler = $this->get_component( LoopContext::class );
                // Get loop context queue.
                $loop_contexts = $loop_context_handler->get_loop_contexts();
                $loop_context = ( !empty( $loop_contexts ) ? end( $loop_contexts ) : '' );
                $taxonomy_loop_blocks = $loop_context_handler->get_taxonomy_query_blocks();
                $kind = ( $loop_context && in_array( $loop_context, $taxonomy_loop_blocks, true ) ? 'term' : 'post' );
                $field_name_cache_key = $kind . '_' . $field_name;
                if ( isset( $cache_data[$field_name_cache_key] ) ) {
                    $object_type = $cache_data[$field_name_cache_key];
                } else {
                    if ( $loop_context ) {
                        $object_type = $kind;
                    } elseif ( is_category() || is_tag() || is_tax() ) {
                        $object_type = 'term';
                    } elseif ( is_author() ) {
                        $object_type = 'user';
                    } else {
                        $object_type = 'post';
                    }
                    // Update cache.
                    $cache_data[$field_name_cache_key] = $object_type;
                    wp_cache_set( $cache_key, $cache_data, 'mfb' );
                }
            }
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
            return apply_filters(
                'meta_field_block_get_object_type',
                $object_type,
                $attributes,
                $block
            );
        }

        /**
         * Get object id by object type.
         *
         * @param string   $object_type Object type.
         * @param array    $attributes  Block attributes.
         * @param WP_Block $block       Block instance.
         *
         * @return string
         */
        public function get_object_id( $object_type, $attributes, $block ) {
            if ( in_array( $object_type, ['post', 'term', 'user'], true ) && ($attributes['isCustomSource'] ?? false) && ($attributes['objectId'] ?? false) ) {
                $object_id = $attributes['objectId'];
            } elseif ( in_array( $object_type, ['term', 'user'], true ) ) {
                if ( 'term' === $object_type && isset( $block->context['termId'] ) ) {
                    // Get value from the context.
                    $object_id = $block->context['termId'];
                } else {
                    // Get queried object id.
                    $object_id = get_queried_object_id();
                }
            } elseif ( isset( $block->context['postId'] ) ) {
                // Get value from the context.
                $object_id = $block->context['postId'];
            } else {
                // Fallback to the current queried object id.
                $object_id = get_queried_object_id();
            }
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
            return apply_filters(
                'meta_field_block_get_object_id',
                $object_id,
                $object_type,
                $attributes,
                $block
            );
        }

        /**
         * Check whether if the block is dynamic of static
         *
         * @param array    $attributes
         * @param mixed    $content
         * @param WP_Block $block
         * @return boolean
         */
        private function is_dynamic_block( $attributes ) {
            $field_type = $attributes['fieldType'] ?? '';
            if ( in_array( $field_type, ['acf', 'mb'], true ) ) {
                if ( $attributes['fieldSettings']['isStatic'] ?? false ) {
                    return false;
                }
            }
            return true;
        }

        /**
         * Save version and trigger an upgrade hook
         *
         * @return void
         */
        public function version_upgrade() {
            if ( get_option( 'mfb_current_version' ) !== $this->version ) {
                // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
                do_action( 'mfb_version_upgraded', get_option( 'mfb_current_version' ), $this->version );
                update_option( 'mfb_current_version', $this->version );
            }
        }

        /**
         * Invalidate the server cache
         *
         * @param int     $post_id
         * @param WP_Post $post
         * @return void
         */
        public function flush_cache( $post_id, $post ) {
            if ( in_array( $post->post_type, ['wp_template', 'wp_template_part'] ) ) {
                wp_cache_delete( 'object_type', 'mfb' );
            }
        }

        /**
         * Register component
         *
         * @param string $file The file path of the component.
         * @param string $classname The class name of the component.
         * @return void
         */
        public function register_component( $file, $classname ) {
            if ( $this->include_file( $file ) ) {
                $this->components[$classname] = new $classname($this);
            }
        }

        /**
         * Get a component by class name
         *
         * @param string $classname The class name of the component.
         * @return mixed
         */
        public function get_component( $classname ) {
            return $this->components[$classname] ?? false;
        }

        /**
         * Define constant
         *
         * @param string $name The name of the constant.
         * @param mixed  $value The value of the constant.
         * @return void
         */
        public function define_constant( $name, $value ) {
            if ( !defined( $name ) ) {
                define( $name, $value );
            }
        }

        /**
         * Return file path for file or folder.
         *
         * @param string $path file path.
         * @return string
         */
        public function get_file_path( $path ) {
            return MFB_PATH . $path;
        }

        /**
         * Include file path.
         *
         * @param string $path file path.
         * @return mixed
         */
        public function include_file( $path ) {
            $file_path = $this->get_file_path( $path );
            if ( !file_exists( $file_path ) ) {
                if ( $this->is_debug_mode() ) {
                    // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                    error_log( "[MFB]: Missing file: {$file_path}" );
                }
                return false;
            }
            return include_once $file_path;
        }

        /**
         * Get file uri by file path.
         *
         * @param string $path file path.
         * @return string
         */
        public function get_file_uri( $path ) {
            return MFB_URL . $path;
        }

        /**
         * Create version for scripts/styles
         *
         * @param array $asset_file
         * @return string
         */
        public function get_script_version( $asset_file ) {
            return ( wp_get_environment_type() !== 'production' ? $asset_file['version'] ?? MFB_VERSION : MFB_VERSION );
        }

        /**
         * Get the plugin version
         *
         * @return string
         */
        public function get_plugin_version() {
            return $this->version;
        }

        /**
         * Is Debugging
         *
         * @return boolean
         */
        public function is_debug_mode() {
            return defined( 'MFB_DEBUG' ) && MFB_DEBUG || 'development' === wp_get_environment_type();
        }

        /**
         * Enqueue debug log information
         *
         * @param string $handle
         * @return void
         */
        public function enqueue_debug_information( $handle ) {
            wp_add_inline_script( $handle, 'var MFBLOG=' . wp_json_encode( [
                'environmentType' => ( $this->is_debug_mode() ? 'development' : wp_get_environment_type() ),
            ] ), 'before' );
        }

    }

    /**
     * Kick start
     *
     * @return MetaFieldBlock instance
     */
    function mfb_get_instance() {
        return MetaFieldBlock::get_instance();
    }

    // Instantiate.
    mfb_get_instance();
}
if ( !function_exists( __NAMESPACE__ . '\\meta_field_block_activate' ) ) {
    /**
     * Trigger an action when the plugin is activated.
     *
     * @return void
     */
    function meta_field_block_activate() {
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
        do_action( 'meta_field_block_activate' );
    }

    register_activation_hook( __FILE__, __NAMESPACE__ . '\\meta_field_block_activate' );
}
