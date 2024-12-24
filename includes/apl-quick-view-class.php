<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
class APL_Quick_View
{
    private static $instance;

    public static function get_instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    // Constructor to initialize hooks
    public function __construct()
    {
        $enable_quick_view = get_option('algo_wc_enable_quick_view', 1);
        // Plugin initialization - always run init_plugin, even if Quick View is disabled
        add_action('init', [$this, 'init_plugin']);

        // Enqueue admin assets (you can move this inside the condition if needed)
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);

        if ($enable_quick_view) {
            add_action('admin_enqueue_scripts', [$this, 'enqueue_color_picker']);
            $algo_wc_enable_lightbox = get_option('algo_wc_enable_lightbox', 1);
            $algo_wc_enable_flexslider = get_option('algo_wc_enable_flexslider', 0);

            if ($algo_wc_enable_lightbox) {
                add_action('wp_enqueue_scripts', [$this, 'enqueue_lightbox_assets']);
            }
            if ($algo_wc_enable_flexslider) {
                add_action('wp_enqueue_scripts', [$this, 'enqueue_flexslider_assets']);
            }
            // Enqueue scripts and styles together
            add_action('wp_enqueue_scripts', function () {
                $this->enqueue_scripts();
                $this->enqueue_dynamic_css();
            });

            // Add Quick View Button to Products
            add_action('woocommerce_after_shop_loop_item', [$this, 'add_quick_view_button'], 20);

            // Handle AJAX for Quick View
            add_action('wp_ajax_awp_wc_quick_view', [$this, 'handle_quick_view_ajax']);
            add_action('wp_ajax_nopriv_awp_wc_quick_view', [$this, 'handle_quick_view_ajax']);

            // Add Drawer Markup to Footer
            add_action('wp_footer', [$this, 'add_drawer_markup']);
        }
    }

    // Set default values for the options on activation
    public static function on_activation()
    {
        $default_options = awp_wc_get_default_options();
        foreach ($default_options as $key => $value) {
            if (get_option($key) === false) {
                add_option($key, $value);
            }
        }
    }

    // Delete default values for the options on deactivation
    public static function on_deactivation()
    {
        $default_options = awp_wc_get_default_options();
        foreach ($default_options as $key => $value) {
            delete_option($key);
        }
    }

    // Initialize the plugin
    public function init_plugin()
    {
        require_once(plugin_dir_path(__FILE__) . 'init.php');
        require_once(plugin_dir_path(__FILE__) . 'shortcodes.php');
    }

    public function enqueue_lightbox_assets()
    {
        wp_enqueue_script('lightbox-js', APL_QUICK_VIEW_BASE_URL.'assets/lightbox/lightbox.min.js', array('jquery'), '2.11.4', true);
        wp_enqueue_style('lightbox-css', APL_QUICK_VIEW_BASE_URL.'assets/lightbox/lightbox.min.css', array(), '2.11.4');
    }

    public function enqueue_flexslider_assets()
    {
        wp_enqueue_style('flexslider-css', APL_QUICK_VIEW_BASE_URL.'assets/flexslider/flexslider.css', array(), '2.7.2');
        wp_enqueue_script('flexslider-js', APL_QUICK_VIEW_BASE_URL.'assets/flexslider/jquery.flexslider-min.js', array('jquery'), '2.7.2', true);
    }

    public function enqueue_color_picker($hook_suffix)
    {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }

    // Enqueue frontend scripts and styles
    public function enqueue_scripts()
    {
        wp_enqueue_style('awp-wc-qv-styles', APL_QUICK_VIEW_BASE_URL.'assets/css/awp-wc-qv-styles.css', array(), '1.0');
        wp_enqueue_script('awp-wc-qv-scripts', APL_QUICK_VIEW_BASE_URL.'assets/js/awp-wc-qv-scripts.js', ['jquery'], '1.0', true);

        wp_localize_script('awp-wc-qv-scripts', 'awp_wc_qv_ajax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('awp_qv_nonce_action'),
            'lightbox' => get_option('algo_wc_enable_lightbox', 1),
            'flexslider' => get_option('algo_wc_enable_flexslider', 0),
            'algo_wc_quick_view_mode' => get_option('algo_wc_quick_view_mode', 'popup'),
            'algo_wc_right_arrow' => esc_url(APL_QUICK_VIEW_BASE_URL.'assets/images/angle-small-right.svg'),
            'algo_wc_cross_arrow' => esc_url(APL_QUICK_VIEW_BASE_URL.'assets/images/cross.svg')
        ]);
    }

    // Enqueue admin scripts and styles
    public function enqueue_admin_assets($hook_suffix)
    {
        // Enqueue only on relevant admin pages
        if ($hook_suffix != 'toplevel_page_apl-quick-view-settings') {
            return;
        }

        wp_enqueue_style('algo-wc-admin-style', APL_QUICK_VIEW_BASE_URL . 'assets/admin/css/admin-style.css', array(), '1.0');
        wp_enqueue_media();  // For media uploader
        wp_enqueue_script('algo-wc-admin-js', APL_QUICK_VIEW_BASE_URL . 'assets/admin/js/admin.js', ['jquery'], '1.0', true);
    }

    // Enqueue dynamic CSS based on admin settings
    public function enqueue_dynamic_css()
    {
        $custom_css = $this->get_dynamic_css();
        wp_enqueue_style('algo-wc-dynamic-style', APL_QUICK_VIEW_BASE_URL . 'assets/css/algo-wc-quick-view-dynamic-style.css', array(), '1.0');
        wp_add_inline_style('algo-wc-dynamic-style', $custom_css);
        wp_enqueue_style('dashicons');
    }

    // Get dynamic CSS based on admin settings
    private function get_dynamic_css()
    {
        // Get the admin-defined colors
        $algo_wc_quick_view_button_color = get_option('algo_wc_quick_view_button_color', '#eeeeee');
        $algo_wc_modal_background_color = get_option('algo_wc_modal_background_color', '#ffffff');
        $algo_wc_modal_overlay_bg_color = get_option('algo_wc_modal_overlay_bg_color', '#ffffff');
        $algo_wc_quick_view_button_text_color = get_option('algo_wc_quick_view_button_text_color', '#333333');
        $algo_wc_quick_view_button_hover_color = get_option('algo_wc_quick_view_button_hover_color', '#bebcbc');
        $algo_wc_quick_view_button_hover_text_color = get_option('algo_wc_quick_view_button_hover_text_color', '#333333');
        $algo_wc_main_text_color = get_option('algo_wc_main_text_color', '#333333');
        $algo_wc_star_color = get_option('algo_wc_star_color', '#ffe234');
        $algo_wc_add_cart_button_color = get_option('algo_wc_add_cart_button_color', '#000000');
        $algo_wc_add_cart_button_text_color = get_option('algo_wc_add_cart_button_text_color', '#FFFFFF');
        $algo_wc_add_cart_button_hover_color = get_option('algo_wc_add_cart_button_hover_color', '#2c2d33');
        $algo_wc_add_cart_button_hover_text_color = get_option('algo_wc_add_cart_button_hover_text_color', '#FFFFFF');
        $algo_wc_view_details_button_color = get_option('algo_wc_view_details_button_color', '#000000');
        $algo_wc_view_details_button_text_color = get_option('algo_wc_view_details_button_text_color', '#FFFFFF');
        $algo_wc_view_details_button_hover_color = get_option('algo_wc_view_details_button_hover_color', '#000000');
        $algo_wc_view_details_button_hover_text_color = get_option('algo_wc_view_details_button_hover_text_color', '#FFFFFF');
        $algo_wc_modal_width = get_option('algo_wc_modal_width', '1000');
        $algo_wc_modal_height = get_option('algo_wc_modal_height', '600');
        $algo_wc_close_btn_color = get_option('algo_wc_close_btn_color', '#333333');
        $algo_wc_awp_wc_nextprev_button_color = get_option('algo_wc_awp_wc_nextprev_button_color', '#000000');
        $algo_wc_awp_wc_nextprev_button_text_color = get_option('algo_wc_awp_wc_nextprev_button_text_color', '#FFFFFF');
        $algo_wc_awp_wc_nextprev_button_hover_color = get_option('algo_wc_awp_wc_nextprev_button_hover_color', '#000000');
        $algo_wc_awp_wc_nextprev_button_hover_text_color = get_option('algo_wc_awp_wc_nextprev_button_hover_text_color', '#FFFFFF');
        $algo_wc_awp_wc_loader_color = get_option('algo_wc_awp_wc_loader_color', '#000000');
        $algo_wc_custom_css = get_option('algo_wc_custom_css');
        $algo_wc_product_img_width = get_option('algo_wc_product_img_width', '400');
        $algo_wc_product_img_height = get_option('algo_wc_product_img_height', '600');

        return "
            .awp-wc-quick-view-button, a.button.btn.awp-wc-quick-view-button {
                background-color: {$algo_wc_quick_view_button_color} !important;
                color: {$algo_wc_quick_view_button_text_color} !important;
            }
            .awp-wc-quick-view-button:hover, a.button.btn.awp-wc-quick-view-button:hover {
                background-color: {$algo_wc_quick_view_button_hover_color} !important;
                color: {$algo_wc_quick_view_button_hover_text_color} !important;
            }
            .awp-wc-quick-view-popup .product_title, .awp-wc-quick-view-popup .summary, .awp-wc-quick-view-popup .onsale,
            .awp-wc-quick-view-popup .product_meta a, .awp-wc-quick-view-drawer .summary .product_title,
             .awp-wc-quick-view-drawer .summary, .awp-wc-quick-view-drawer .onsale, .awp-wc-quick-view-drawer .product_meta a {
                color: {$algo_wc_main_text_color} !important;
            }
            .awp-wc-quick-view-popup .awp-wc-quick-view-popup-scroll, .awp-wc-quick-view-drawer{
             background-color: {$algo_wc_modal_background_color} !important;
            }
            .awp-wc-quick-view-overlay{
             background-color: {$algo_wc_modal_overlay_bg_color} !important;
            }
             .awp-wc-quick-view-drawer .summary button, .awp-wc-quick-view-popup .summary button{
             background-color: {$algo_wc_add_cart_button_color} !important;
             color:{$algo_wc_add_cart_button_text_color} !important;
             }
             .awp-wc-quick-view-drawer .summary button:hover, .awp-wc-quick-view-popup .summary button:hover{
             background-color: {$algo_wc_add_cart_button_hover_color} !important;
             color:{$algo_wc_add_cart_button_hover_text_color} !important;
             }
            .awp-wc-quick-view-drawer .summary .single_add_to_cart_button{
             background-color: {$algo_wc_add_cart_button_color} !important;
             color:{$algo_wc_add_cart_button_text_color} !important;
            }
            .awp-wc-quick-view-drawer .summary .single_add_to_cart_button:hover{
             background-color: {$algo_wc_add_cart_button_hover_color} !important;
             color:{$algo_wc_add_cart_button_hover_text_color} !important;
            }
            .awp-wc-quick-view-drawer .star-rating span:before, .awp-wc-quick-view-popup .star-rating span:before, .products .star-rating span:before{
             color: {$algo_wc_star_color};
            }
            .awp-wc-quick-view-popup .awp-wc-quick-view-popup-scroll{
             width: {$algo_wc_modal_width}px;
             height:{$algo_wc_modal_height}px;
            }
            .awp-wc-close-popup, .awp-wc-close-drawer{
             color:{$algo_wc_close_btn_color} !important;
            }
            .awp-wc-product-detail-btn{
                background-color: {$algo_wc_view_details_button_color} !important;
                color:{$algo_wc_view_details_button_text_color} !important;
            }
            .awp-wc-product-detail-btn:hover{
                background-color: {$algo_wc_view_details_button_hover_color} !important;
                color:{$algo_wc_view_details_button_hover_text_color} !important;
            }
            .awp-wc-nextprev-product{
                background-color: {$algo_wc_awp_wc_nextprev_button_color} !important;
                color:{$algo_wc_awp_wc_nextprev_button_text_color} !important;
            }
            .awp-wc-loader, .algo-wc-loader{
                border-top-color: {$algo_wc_awp_wc_loader_color} !important;
            }
            
            .awp-wc-nextprev-product:hover{
                background-color: {$algo_wc_awp_wc_nextprev_button_hover_color} !important;
                color:{$algo_wc_awp_wc_nextprev_button_hover_text_color} !important;
            }
            .awp-wc-quick-view-popup-scroll .awp-qv-slides li img, .awp-wc-quick-view-drawer-scroll .awp-qv-slides li img{
                width: {$algo_wc_product_img_width}px ;
            }
            .awp-wc-quick-view-popup-scroll .awp-qv-slides li img, .awp-wc-quick-view-drawer-scroll .awp-qv-slides li img{
                height: {$algo_wc_product_img_height}px ;
            }
            .awp-wc-quick-view-popup-scroll .awp-wc-prod-gallery  img, .awp-wc-quick-view-drawer-scroll .awp-wc-prod-gallery  img{
                width: {$algo_wc_product_img_width}px ;
            }
            .awp-wc-quick-view-popup-scroll .awp-wc-prod-gallery  img, .awp-wc-quick-view-drawer-scroll .awp-wc-prod-gallery  img{
                height: {$algo_wc_product_img_height}px ;
            }
            
            {$algo_wc_custom_css}
        ";
    }    

    // Add Quick View Button to Products
    public function add_quick_view_button()
    {
        $enable_quick_view = get_option('algo_wc_enable_quick_view', 1);
        $button_type = get_option('algo_wc_quick_view_button_type', 'use_button');
        $button_position = get_option('algo_wc_quick_view_button_position', 'after_cart_btn');
        $btn_qv_class = 'awp-wc-qv-btn-' . $button_position;
        $button_label = get_option('algo_wc_quick_view_button_label', 'Quick View');

        if ($enable_quick_view && $button_position !== "use_shortcode") {
            global $product;
            echo '<div class="awp-wc-qv-button-container ' . esc_attr($btn_qv_class) . '">';
            if ($button_type === 'use_button') {
                echo '<a href="#" id="product_' . esc_attr($product->get_id()) . '" class="button btn awp-wc-quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_attr($button_label) . '</a>';
                echo '<div id="product_' . esc_attr($product->get_id()) . '_loader" class="awp-wc-quick-view-button algo_wc_prod_add_to_cart_loader"><div class="algo-wc-btn-loader"><div class="algo-wc-loader"></div></div>';
            } elseif ($button_type === 'use_button_icon') {
                $icon = get_option('algo_wc_quick_view_button_icon');
                if (!empty($icon)) {
                    echo '<a href="#" id="product_' . esc_attr($product->get_id()) . '" class="button btn awp-wc-quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">';
                    echo wp_get_attachment_image($icon, 'full', false, array('class' => 'algo-wc-quick-view-icon'));
                    echo '</a>';
                } else {
                    echo '<a href="#" id="product_' . esc_attr($product->get_id()) . '" class="button btn awp-wc-quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">';
                    echo '<span class="dashicons dashicons-visibility" aria-hidden="true"></span>';
                    echo '<span class="awp-wc-qv-btn-label" aria-hidden="true">'.esc_attr($button_label).'</span>';
                    echo '</a>';                
                }
                echo '<div id="product_' . esc_attr($product->get_id()) . '_loader" class="awp-wc-quick-view-button algo_wc_prod_add_to_cart_loader"><div class="algo-wc-btn-loader"><div class="algo-wc-loader"></div></div>';
            } elseif ($button_type === 'use_icon') {
                $icon = get_option('algo_wc_quick_view_button_icon');
                if (!empty($icon)) {
                    echo '<a href="#" id="product_' . esc_attr($product->get_id()) . '" class="awp-wc-quick-view-button awp-quickview-icon-only" data-product-id="' . esc_attr($product->get_id()) . '">';
                    echo wp_get_attachment_image($icon, 'full', false, array('class' => 'algo-wc-quick-view-icon'));
                    echo '</a>';
                } else {
                    echo '<a href="#" id="product_' . esc_attr($product->get_id()) . '" class="awp-wc-quick-view-button awp-quickview-icon-only" data-product-id="' . esc_attr($product->get_id()) . '">';
                    echo '<span class="dashicons dashicons-visibility" aria-hidden="true"></span>';
                    echo '</a>';                
                }
                echo '<div id="product_' . esc_attr($product->get_id()) . '_loader" class="awp-wc-quick-view-button algo_wc_prod_add_to_cart_loader awp-quickview-icon-only"><div class="algo-wc-btn-loader"><div class="algo-wc-loader"></div></div>';
            }
            echo '</div>';
        }
    }

    // Handle AJAX request for Quick View
    public function handle_quick_view_ajax()
    {        
        // Check if nonce and product_id are set and valid
        if (!empty($_POST['nonce']) && !empty($_POST['product_id'])) {
            $nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
            // Verify the nonce
            if (wp_verify_nonce($nonce, 'awp_qv_nonce_action')) {
                $product_id = intval($_POST['product_id']); // Sanitize product ID as integer

                // Ensure a valid product ID
                if ($product_id > 0) {
                    $product = wc_get_product($product_id);

                    // Check if the product exists
                    if ($product && is_a($product, 'WC_Product')) {
                        global $post;
                        $post = get_post($product_id); // Set up the post object for the product
                        if (!$post) {
                            wp_send_json_error('Product not found in the database.');
                        }
                        setup_postdata($post); // Set up the post data

                        // Start output buffering to capture the content
                        ob_start();
                        wp('p=' . $product_id . '&post_type=product'); // Query for the product
                        // Include the product template, checking if the file exists first
                        $template_path = APL_QUICK_VIEW_BASE_PATH . 'templates/single-product-template.php';
                        if (file_exists($template_path)) {
                            include $template_path; // Include product template
                        } else {
                            wp_send_json_error('Template file is missing or cannot be loaded.');
                        }
                        $content = ob_get_clean(); // Get the content from the output buffer

                        wp_reset_postdata(); // Reset global $post to avoid conflicts

                        if (!empty($content)) {
                            wp_send_json_success($content); // Send success response with product content
                        } else {
                            wp_send_json_error('Failed to load product content.'); // Content was empty or failed
                        }
                    } else {
                        wp_send_json_error('Product not found or invalid product ID.'); // Product was not found or invalid product
                    }
                } else {
                    wp_send_json_error('Invalid product ID.'); // Invalid product ID
                }
            } else {
                wp_send_json_error('Invalid nonce.'); // Invalid nonce, possible CSRF attempt
            }
        } else {
            wp_send_json_error('Missing nonce or product ID.'); // Missing nonce or product ID in request
        }
    }


    // Add Drawer or Popup Markup to Footer
    public function add_drawer_markup()
    {
        $quick_view_mode = get_option('algo_wc_quick_view_mode', 'popup');
        $enable_quick_view = get_option('algo_wc_enable_quick_view', 1);
        $algo_wc_select_modal_effect = get_option('algo_wc_select_modal_effect', 'fade_in');

        if ($enable_quick_view) {
            if ($quick_view_mode === 'drawer') {
                echo '<div id="algo_wc_qv_container" class="awp-wc-quick-view-drawer">
                        <div class="awp-wc-close-drawer">
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        
                        </div>
                        <div class="awp-wc-quick-view-drawer-scroll">
                            <div class="awp-wc-loader-overlay"><div class="awp-wc-loader"></div></div>
                        </div>
                    </div>';
            } else {
                echo '<div id="algo_wc_qv_container" class="awp-wc-quick-view-popup awp-modal-' . esc_attr($algo_wc_select_modal_effect) . '">
                        <div class="awp-wc-quick-view-popup-scroll">
                            <div class="awp-wc-close-popup"><i class="fas fa-times"></i></div>
                        </div>
                    </div>';
            }
        }
    }
}
?>