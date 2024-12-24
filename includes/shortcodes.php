<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
function awp_wc_quick_view_shortcode($atts)
{
    $button_type = get_option('algo_wc_quick_view_button_type', 'use_button');
    $button_label = get_option('algo_wc_quick_view_button_label', 'Quick View');
    // Extract shortcode attributes
    $atts = shortcode_atts(
        array(
            'product_id' => 0, // Default product ID
            'button_label' => $button_label, // Default button label
        ),
        $atts,
        'awp_wc_quick_view'
    );

    $product_id = intval($atts['product_id']);
    $button_label = sanitize_text_field($atts['button_label']);
    if (!$product_id) {
        return '<p>' . esc_html__('Please provide a valid product ID.', 'apl-quick-view') . '</p>';
    }

    // Get product details using the product ID
    $product = wc_get_product($product_id);
    if (!$product) {
        return '<p>' . esc_html__('Product not found.', 'apl-quick-view') . '</p>';
    }

    ob_start();
    ?>
    <div class="awp-wc-quick-view-shortcode" id="awp_wc_qv_sc_<?php echo esc_attr($product_id); ?>">
        <h3><?php echo esc_html($product->get_name()); ?></h3>
        <?php
        $icon_url = APL_QUICK_VIEW_BASE_URL . "assets/images/view-icon.png";
        if ($button_type === 'use_button') {
            echo '<a href="#" id="product_' . esc_attr($product_id) . '" class="button btn awp-wc-quick-view-button" data-product-id="' . esc_attr($product_id) . '">' . esc_attr($button_label) . '</a>';
            echo '<div id="product_' . esc_attr($product_id) . '_loader" class="awp-wc-quick-view-button algo_wc_prod_add_to_cart_loader"><div class="algo-wc-btn-loader"><div class="algo-wc-loader"></div></div>';
        } elseif ($button_type === 'use_button_icon') {
            $icon = get_option('algo_wc_quick_view_button_icon');
            if (!empty($icon)) {
                echo '<a href="#" id="product_' . esc_attr($product_id) . '" class="button btn awp-wc-quick-view-button" data-product-id="' . esc_attr($product_id) . '">';
                echo wp_get_attachment_image($icon, 'full', false, array('class' => 'algo-wc-quick-view-icon'));
                echo '<span class="awp-wc-qv-btn-label" aria-hidden="true">'.esc_attr($button_label).'</span>';
                echo '</a>';
            } else {
                echo '<a href="#" id="product_' . esc_attr($product_id) . '" class="button btn awp-wc-quick-view-button" data-product-id="' . esc_attr($product_id) . '">';
                echo '<span class="dashicons dashicons-visibility" aria-hidden="true"></span>';
                echo '<span class="awp-wc-qv-btn-label" aria-hidden="true">'.esc_attr($button_label).'</span>';
                echo '</a>';                
            }            
            echo '<div id="product_' . esc_attr($product_id) . '_loader" class="awp-wc-quick-view-button algo_wc_prod_add_to_cart_loader"><div class="algo-wc-btn-loader"><div class="algo-wc-loader"></div></div>';
        } elseif ($button_type === 'use_icon') {
            $icon = get_option('algo_wc_quick_view_button_icon');
            if (!empty($icon)) {
                echo '<a href="#" id="product_' . esc_attr($product_id) . '" class="awp-wc-quick-view-button awp-quickview-icon-only" data-product-id="' . esc_attr($product_id) . '">';
                echo wp_get_attachment_image($icon, 'full', false, array('class' => 'algo-wc-quick-view-icon'));
                echo '</a>';
            } else {
                echo '<a href="#" id="product_' . esc_attr($product_id) . '" class="awp-wc-quick-view-button awp-quickview-icon-only" data-product-id="' . esc_attr($product_id) . '">';
                echo '<span class="dashicons dashicons-visibility" aria-hidden="true"></span>';
                echo '</a>';
            }
            echo '<div id="product_' . esc_attr($product_id) . '_loader" class="awp-wc-quick-view-button algo_wc_prod_add_to_cart_loader awp-quickview-icon-only"><div class="algo-wc-btn-loader"><div class="algo-wc-loader"></div></div>';
        }
        ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('awp_wc_quick_view', 'awp_wc_quick_view_shortcode');
?>