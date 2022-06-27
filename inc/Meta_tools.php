<?php
/**
 * Class Meta_tools
 * Use for post meta data & meta boxes
 */

class Meta_tools
{

    protected static $_instance;

    /**
     * @return Meta_tools
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Meta_tools constructor.
     */
    public function __construct( ) {

        // add actions & hooks
        add_action('add_meta_boxes', [ $this, 'meta_boxes_preview' ]);

    }

    /**
     * Add meta box for OG preview
     */
    public function meta_boxes_preview() {
        $screens = [ 'post' ];
        if ( !is_active() ){
            add_meta_box(
                'oggwc_og_preview__box',
                __('Preview Open Graph', OGGWC_DOMAIN),
                [ $this, 'callback_og_preview_metabox' ],
                $screens
            );
        }
        
    }

    /**
     * HTML block in admin panel
     * @param $post
     * @param $meta
     */
    public function callback_og_preview_metabox($post, $meta){
        ?>
        <div>
            <div class="og-preview_container">
                <div name="<?php echo $post->ID?>" class="og-preview_button components-button is-primary classical-editor-button">  Generate
                </div>
            </div>
        </div>
        <div class="og-preview__container">
            
            <?php
            if ( $img = $this->get_post_meta_preview($post->ID,OGGWC_META_VK) ) { ?>
                <div class="og-preview__image-wrap">
                    <h3 class="og-preview__subtitle"><?php echo __('VK', OGGWC_DOMAIN) ?></h3>
                    <a href="<?php echo $img ?>">
                        <img src="<?php echo $img ?>" class="og-preview__image">
                    </a>
                    
                </div>
            <?php }

            if ( $img = $this->get_post_meta_preview($post->ID, OGGWC_META_FB) ) { ?>
                <div class="og-preview__image-wrap">
                    <h3 class="og-preview__subtitle"><?php echo __('Facebook / Twitter', OGGWC_DOMAIN) ?></h3>
                    <a href="<?php echo $img ?>">
                        <img src="<?php echo $img ?>" class="og-preview__image">
                    </a>
                </div>
            <?php } ?>

        </div>
    <?php
    }

    /**
     * Get meta data by field name
     * @param $id
     * @param $social
     * @return bool|mixed
     */
    public function get_post_meta_preview( $id, $social ) {
        if (!$attach = get_post_meta($id, $social, true)){
            return false;
        } else {
            return $attach;
        }
    }
}

/**
 * Check if Block Editor is active.
 * Must only be used after plugins_loaded action is fired.
 *
 * @return bool
 */
function is_active() {
    // Gutenberg plugin is installed and activated.
    $gutenberg = ! ( false === has_filter( 'replace_editor', 'gutenberg_init' ) );

    // Block editor since 5.0.
    $block_editor = version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' );

    if ( ! $gutenberg && ! $block_editor ) {
        return false;
    }

    if ( is_classic_editor_plugin_active() ) {
        $editor_option       = get_option( 'classic-editor-replace' );
        $block_editor_active = array( 'no-replace', 'block' );

        return in_array( $editor_option, $block_editor_active, true );
    }

    return true;
}

/**
 * Check if Classic Editor plugin is active.
 *
 * @return bool
 */
function is_classic_editor_plugin_active() {
    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if ( is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
        return true;
    }

    return false;
}