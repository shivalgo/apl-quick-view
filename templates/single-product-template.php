<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$algo_wc_quick_view_Show_title = get_option('algo_wc_show_product_name', 1);
$algo_wc_quick_view_Show_image = get_option('algo_wc_show_product_img', 1);
$algo_wc_show_product_price = get_option('algo_wc_show_product_price', 1);
$algo_wc_show_product_excerpt = get_option('algo_wc_show_product_excerpt', 1);
$algo_wc_show_product_add_cart = get_option('algo_wc_show_product_add_cart', 1);
$algo_wc_show_full_dec = get_option('algo_wc_show_full_dec', 0);
$algo_wc_show_product_meta = get_option('algo_wc_show_product_meta', 1);
$algo_wc_enable_lightbox = get_option('algo_wc_enable_lightbox', 1);
$algo_wc_enable_flexslider = get_option('algo_wc_enable_flexslider', 0);
$algo_wc_add_view_detail_btn = get_option('algo_wc_add_view_detail_btn', 1);
$algo_wc_quick_view_navigation = get_option('algo_wc_quick_view_navigation', 1);
$algo_wc_quick_view_mode = get_option('algo_wc_quick_view_mode', 'popup');

if (have_posts()):
    while (have_posts()):
        the_post(); ?>
        <div id="product-<?php the_ID(); ?>" <?php wc_product_class(); ?>>
            <?php
            $current_product_url = get_permalink($product_id);
            // Get the previous and next products
            $awp_qv_nav_html = '';
            if ($algo_wc_quick_view_navigation) {
                $previous_product = get_previous_post();
                $next_product = get_next_post();
                $awp_qv_nav_html .= '<div class="product-navigation awp-qv-navigation">';
                // Previous Product Button
                if ($previous_product) {
                    $previous_url = get_permalink($previous_product->ID);
                    $awp_qv_nav_html .= '<a href="#" data-product-id="' . $previous_product->ID . '" class="prev-product awp-wc-nextprev-product">← Previous</a>';
                }
                // Next Product Button
                if ($next_product) {
                    $next_url = get_permalink($next_product->ID);
                    $awp_qv_nav_html .= '<a href="#" data-product-id="' . $next_product->ID . '" class="next-product awp-wc-nextprev-product">Next →</a>';
                }
                $awp_qv_nav_html .= '</div>';
            }
            $stickHtml = '';
            // Include the sticky menu here
            if ($algo_wc_quick_view_navigation || $algo_wc_add_view_detail_btn) {
                $stickHtml .= '<div class="awp-qv-stiky-menu">
                    <div class="awp-qv-stiky-menu-in">';
                if ($algo_wc_add_view_detail_btn) {
                    $stickHtml .= '<a href="' . esc_url($current_product_url) . '"
                                class="btn button awp-wc-product-detail-btn">' . esc_html(__('View Product Details →', 'apl-quick-view')) . '</a>';
                }
                $stickHtml .= '</div>';
                $stickHtml .= wp_kses_post($awp_qv_nav_html);
                $stickHtml .= '</div>';
            }
            if ($algo_wc_quick_view_mode && $algo_wc_quick_view_mode === "drawer") {
                echo '<div class="awp-wc-qv-content">';
            }
            if ($algo_wc_quick_view_Show_image):
                ?>
                <div
                    class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images">
                    <div class="woocommerce-product-gallery__wrapper awp-wc-prod-gallery">
                        <?php
                        // do_action('woocommerce_before_single_product_summary');
            
                        // Get the product object
                        $product = wc_get_product($product_id);

                        // Check if the product exists
                        if ($product) {
                            $lightbox_group = 'product-gallery';
                            // Get the product gallery images
                            $attachment_ids = $product->get_gallery_image_ids();

                            // Get the main product image (featured image)
                            $featured_image_id = $product->get_image_id();

                            // Initialize an array to store image URLs
                            $all_attachments_ids = [];

                            // Get the featured image URL
                            if ($featured_image_id) {
                                $featured_image_url = wp_get_attachment_url($featured_image_id);
                                $all_attachments_ids[] = $featured_image_id;
                                if ($algo_wc_enable_lightbox && !$algo_wc_enable_flexslider) {
                                    echo '<a href="' . esc_url($featured_image_url) . '" data-lightbox="' . esc_attr($lightbox_group) . '" data-title="' . esc_attr(get_the_title()) . '">';
                                    echo wp_get_attachment_image($featured_image_id, 'full', false, array('class' => 'awc-product-img', 'alt' => esc_attr(get_the_title())));
                                    echo '</a>';
                                } elseif (!$algo_wc_enable_lightbox && !$algo_wc_enable_flexslider) {
                                    echo wp_get_attachment_image($featured_image_id, 'full', false, array('class' => 'awc-product-img', 'alt' => esc_attr(get_the_title())));
                                }
                            }

                            // Get the gallery attachment ids
                            foreach ($attachment_ids as $attachment_id) {
                                $all_attachments_ids[] = $attachment_id;
                            }

                            // Product gallery images for lightbox
                            if ($algo_wc_enable_lightbox && !$algo_wc_enable_flexslider) {
                                foreach ($attachment_ids as $attachment_id) {
                                    $image_url = wp_get_attachment_url($attachment_id);
                                    echo '<a href="' . esc_url($image_url) . '" data-lightbox="' . esc_attr($lightbox_group) . '" data-title="' . esc_attr(get_the_title()) . '" style="display:none">';
                                    echo wp_get_attachment_image($attachment_id, 'full', false, array('class' => 'awc-product-img', 'alt' => esc_attr(get_the_title())));
                                    echo '</a>';
                                }
                            }

                            if ($algo_wc_enable_flexslider) {
                                echo '<div class="awp-qv-flexslider flexslider">
                                        <ul class="awp-qv-slides slides">';
                                foreach ($all_attachments_ids as $attachment_id) {
                                    $image_url = wp_get_attachment_url($attachment_id);
                                    echo '<li><a href="' . esc_url($image_url) . '" data-lightbox="' . esc_attr($lightbox_group) . '" data-title="' . esc_attr(get_the_title()) . '">';
                                    echo wp_get_attachment_image($attachment_id, 'full', false, array('class' => 'awc-product-img', 'alt' => esc_attr(get_the_title())));
                                    echo '</a></li>';
                                }
                                echo '</ul>
                                    </div>';
                            }

                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="summary entry-summary">
                <div class="inner-summary">
                    <?php
                    get_the_title();
                    if ($algo_wc_quick_view_Show_title) {
                        woocommerce_template_single_title();
                    }
                    if (comments_open()) {
                        woocommerce_template_single_rating();
                    }
                    if ($algo_wc_show_product_price) {
                        woocommerce_template_single_price();
                    }
                    if ($algo_wc_show_product_excerpt) {
                        woocommerce_template_single_excerpt();
                    }
                    if ($algo_wc_show_full_dec) {
                        the_content();
                    }
                    if ($algo_wc_show_product_add_cart) {
                        woocommerce_template_single_add_to_cart();
                    }
                    if ($algo_wc_show_product_meta) {
                        woocommerce_template_single_meta();
                    }
                    woocommerce_template_single_sharing();

                    ?>
                </div>
                <?php
                if ($algo_wc_quick_view_mode && $algo_wc_quick_view_mode === "popup") {
                    echo wp_kses_post($stickHtml);
                }
                ?>
            </div>
            <?php
            //  do_action('woocommerce_after_single_product_summary');
    
            if ($algo_wc_quick_view_mode && $algo_wc_quick_view_mode === "drawer") {
                echo '</div>';
                echo wp_kses_post($stickHtml);
            }
            ?>
        </div>
    <?php endwhile;
endif;
?>