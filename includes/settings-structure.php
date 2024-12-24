<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
function algo_wc_get_settings_structure() {
    return array(
        'general' => array(
            'title' => __('General Settings', 'apl-quick-view'),
            'fields' => array(
                'algo_wc_quick_heading_1' => array(
                    'title' => __('Quick View Feature:', 'apl-quick-view'),
                    'type' => 'heading',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => '',
                    'class' => 'awp-wc-heading'
                ),
                'algo_wc_enable_quick_view' => array(
                    'title' => __('Enable the Quick View Feature', 'apl-quick-view'),
                    'type' => 'switch',
                    'data_type' => 'boolean',
                    'sanitize_callback' => 'absint',
                    'default' => 1
                ),
                'algo_wc_enable_quick_view_mobile' => array(
                    'title' => __('Enable Quick View on Mobile Devices', 'apl-quick-view'),
                    'type' => 'switch',
                    'data_type' => 'boolean',
                    'sanitize_callback' => 'absint',
                    'default' => 1
                ),
                'algo_wc_quick_heading_2' => array(
                    'title' => __('Feature Enhancements:', 'apl-quick-view'),
                    'type' => 'heading',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => '',
                    'class' => 'awp-wc-heading'
                ),
                'algo_wc_enable_lightbox' => array(
                    'title' => __('Enable Lightbox Feature', 'apl-quick-view'),
                    'type' => 'switch',
                    'data_type' => 'boolean',
                    'sanitize_callback' => 'absint',
                    'default' => 1
                ),
                'algo_wc_quick_view_navigation' => array(
                    'title' => __('Enable Product Navigation in Quick View', 'apl-quick-view'),
                    'type' => 'switch',
                    'data_type' => 'boolean',
                    'sanitize_callback' => 'absint',
                    'default' => 1
                ),  
                'algo_wc_quick_heading_3' => array(
                    'title' => __('Display Options:', 'apl-quick-view'),
                    'type' => 'heading',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => '',
                    'class' => 'awp-wc-heading'
                ),              
                'algo_wc_quick_view_mode' => array(
                    'title' => __('Choose Quick View Mode', 'apl-quick-view'),
                    'type' => 'select',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'options' => array(
                        'popup' => __('Popup', 'apl-quick-view'),
                        'drawer' => __('Drawer', 'apl-quick-view')
                    ),
                    'default' => 'popup'
                ),                                
                'algo_wc_select_modal_effect' => array(
                    'title' => __('Choose Modal Display Effect', 'apl-quick-view'),
                    'type' => 'select',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'options' => array(
                        'slide_in' => __('Slide in', 'apl-quick-view'),
                        'slide_out' => __('Slide out', 'apl-quick-view'),
                        'fade_in' => __('Fade in', 'apl-quick-view'),
                        'fade_out' => __('Fade out', 'apl-quick-view'),
                        'zoom_in' => __('Zoom in', 'apl-quick-view'),
                        'zoom_out' => __('Zoom out', 'apl-quick-view'),
                        'flip' => __('Flip', 'apl-quick-view'),
                        'rotate' => __('Rotate', 'apl-quick-view'),
                    ),
                    'default' => 'fade_in'
                ),             
                'algo_wc_quick_heading_4' => array(
                    'title' => __('Button Configuration Settings:', 'apl-quick-view'),
                    'type' => 'heading',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => '',
                    'class' => 'awp-wc-heading'
                ),
                'algo_wc_quick_view_button_type' => array(
                    'title' => __('Quick View Button Display Type', 'apl-quick-view'),
                    'type' => 'select',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'options' => array(
                        'use_button' => __('Use Button', 'apl-quick-view'),
                        'use_button_icon' => __('Use Button With Icon', 'apl-quick-view'),
                        'use_icon' => __('Use Only Icon', 'apl-quick-view')
                    ),
                    'default' => 'use_button'
                ),                
                'algo_wc_quick_view_button_label' => array(
                    'title' => __('Quick View Button Label Text', 'apl-quick-view'),
                    'type' => 'text',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => 'Quick View'
                ),
                'algo_wc_quick_view_button_icon' => array(
                    'title' => __('Upload Icon for Quick View Button', 'apl-quick-view'),
                    'type' => 'image',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => ''
                ),
                'algo_wc_quick_view_button_position' => array(
                    'title' => __('Choose the Position for the Quick View Button', 'apl-quick-view'),
                    'type' => 'select',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'options' => array(
                        'after_cart_btn' => __('After add to cart button', 'apl-quick-view'),
                        'inside_product_img' => __('Inside product image', 'apl-quick-view'),
                        'use_shortcode' => __('Use shortcode/block', 'apl-quick-view'),
                    ),
                    'default' => 'after_cart_btn'
                ),
                
            )
        ),
        'quick_view_content' => array(
            'title' => __('Quick View Content Settings', 'apl-quick-view'),
            'fields' => array(
                'algo_wc_quick_heading_5' => array(
                    'title' => __('Product Information:', 'apl-quick-view'),
                    'type' => 'heading',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => '',
                    'class' => 'awp-wc-heading'
                ),
                'algo_wc_show_product_img' => array(
                    'title' => __('Display Product Image', 'apl-quick-view'),
                    'type' => 'switch', 
                    'data_type' => 'boolean',
                    'sanitize_callback' => 'absint',                   
                    'default' => 1
                ),
                'algo_wc_show_product_name' => array(
                    'title' => __('Display Product Name', 'apl-quick-view'),
                    'type' => 'switch',
                    'data_type' => 'boolean',
                    'sanitize_callback' => 'absint',                   
                    'default' => 1
                ),
                'algo_wc_show_product_rating' => array(
                    'title' => __('Display Product Rating', 'apl-quick-view'),
                    'type' => 'switch', 
                    'data_type' => 'boolean',
                    'sanitize_callback' => 'absint',                  
                    'default' => 1
                ),
                'algo_wc_show_product_price' => array(
                    'title' => __('Display Product Price', 'apl-quick-view'),
                    'type' => 'switch',
                    'data_type' => 'boolean',
                    'sanitize_callback' => 'absint',                    
                    'default' => 1
                ),
                'algo_wc_show_product_excerpt' => array(
                    'title' => __('Display Product Excerpt', 'apl-quick-view'),
                    'type' => 'switch', 
                    'data_type' => 'boolean',
                    'sanitize_callback' => 'absint',                   
                    'default' => 1
                ),
                'algo_wc_show_product_add_cart' => array(
                    'title' => __("Display Product 'Add to Cart' Button", 'apl-quick-view'),
                    'type' => 'switch', 
                    'data_type' => 'boolean',
                    'sanitize_callback' => 'absint',                   
                    'default' => 1                ),
                'algo_wc_show_product_meta' => array(
                    'title' => __('Display Product Meta', 'apl-quick-view'),
                    'type' => 'switch', 
                    'data_type' => 'boolean',
                    'sanitize_callback' => 'absint',                   
                    'default' => 1
                ),

                'algo_wc_show_full_dec' => array(
                    'title' => __('Display Full Description', 'apl-quick-view'),
                    'type' => 'switch',
                    'data_type' => 'boolean',
                    'sanitize_callback' => 'absint',
                    'default' => 0
                ),
                'algo_wc_quick_heading_6' => array(
                    'title' => __('Image Settings:', 'apl-quick-view'),
                    'type' => 'heading',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => '',
                    'class' => 'awp-wc-heading'
                ),
                'algo_wc_product_img_width' => array(
                    'title' => __('Product Image Width (in pixels)', 'apl-quick-view'),
                    'type' => 'number',
                    'data_type' => 'number',
                    'sanitize_callback' => 'intval',
                    'default' => 400
                ),
                'algo_wc_product_img_height' => array(
                    'title' => __('Product Image Height (in pixels)', 'apl-quick-view'),
                    'type' => 'number',
                    'data_type' => 'number',
                    'sanitize_callback' => 'intval',
                    'default' => 600
                ),
                'algo_wc_quick_heading_7' => array(
                    'title' => __('Button Behavior:', 'apl-quick-view'),
                    'type' => 'heading',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => '',
                    'class' => 'awp-wc-heading'
                ),
                'algo_wc_add_view_detail_btn' => array(
                    'title' => __("Display 'View Details' Button", 'apl-quick-view'),
                    'type' => 'switch',
                    'data_type' => 'boolean',
                    'sanitize_callback' => 'absint',
                    'default' => 1
                )
            )
        ),
        'generalstyle' => array(
            'title' => __('General Style Settings', 'apl-quick-view'),
            'fields' => array(
                'algo_wc_modal_background_color' => array(
                    'title' => __('Modal Window Background Color', 'apl-quick-view'),
                    'type' => 'color',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'default' => '#ffffff'
                ),
                'algo_wc_modal_overlay_bg_color' => array(
                    'title' => __('Modal Overlay Background', 'apl-quick-view'),
                    'type' => 'color',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'default' => '#000000'
                ),
                'algo_wc_close_btn_color' => array(
                    'title' => __('Modal Window Close Button Color', 'apl-quick-view'),
                    'type' => 'color',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'default' => '#E20404'
                ),
                'algo_wc_quick_view_button_color' => array(
                    'title' => __('Quick View Button Color', 'apl-quick-view'),
                    'type' => 'color',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'default' => '#eeeeee'
                ),
                'algo_wc_quick_view_button_text_color' => array(
                    'title' => __('Quick View Button Text Color', 'apl-quick-view'),
                    'type' => 'color',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'default' => '#333333'
                ),
                'algo_wc_quick_view_button_hover_color' => array(
                    'title' => __('Quick View Button Hover Color', 'apl-quick-view'),
                    'type' => 'color',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'default' => '#bebcbc'
                ),
                'algo_wc_quick_view_button_hover_text_color' => array(
                    'title' => __('Quick View Button Hover Text Color', 'apl-quick-view'),
                    'type' => 'color',
                    'data_type' => 'string',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'default' => '#333333'
                ),
            )
        )
    );
}
