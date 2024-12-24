<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
function awp_wc_render_field($args)
{
    // Extract arguments and provide defaults
    $option_name = isset($args['id']) ? $args['id'] : '';
    $value = get_option($option_name, isset($args['default']) ? $args['default'] : '');

    // Sanitize type and default if not set
    $type = isset($args['type']) ? $args['type'] : 'text';
    $options = isset($args['options']) ? $args['options'] : array();

    switch ($type) {
        case 'text':
            echo "<input type='text' id='" . esc_attr($option_name) . "' class='algo_wc_field' name='" . esc_attr($option_name) . "' value='" . esc_attr($value) . "' />";
            break;

        case 'textarea':
            echo "<textarea id='" . esc_attr($option_name) . "' class='algo_wc_field' name='" . esc_attr($option_name) . "'>" . esc_textarea($value) . "</textarea>";
            break;

        case 'checkbox':
            echo "<input type='checkbox' id='" . esc_attr($option_name) . "' class='algo_wc_field' name='" . esc_attr($option_name) . "' value='1' " . checked(1, $value, false) . " />";
            break;

        case 'select':
            echo "<select id='" . esc_attr($option_name) . "' class='algo_wc_field' name='" . esc_attr($option_name) . "'>";
            foreach ($options as $key => $label) {
                echo "<option value='" . esc_attr($key) . "' " . selected($key, $value, false) . ">" . esc_html($label) . "</option>";
            }
            echo "</select>";
            break;

        case 'radio':
            foreach ($options as $key => $label) {
                echo "<label><input type='radio' id='" . esc_attr($option_name) . "' class='algo_wc_field' name='" . esc_attr($option_name) . "' value='" . esc_attr($key) . "' " . checked($key, $value, false) . " />" . esc_html($label) . "</label><br />";
            }
            break;

        case 'color':
            echo "<input type='color' id='" . esc_attr($option_name) . "' class='algo_wc_field awp-wc-color-picker' name='" . esc_attr($option_name) . "' value='" . esc_attr($value) . "' />";
            break;

        case 'number':
            echo "<input type='number' id='" . esc_attr($option_name) . "' class='algo_wc_field' name='" . esc_attr($option_name) . "' value='" . esc_attr($value) . "' />";
            break;

        case 'date':
            echo "<input type='date' id='" . esc_attr($option_name) . "' class='algo_wc_field' name='" . esc_attr($option_name) . "' value='" . esc_attr($value) . "' />";
            break;

        case 'datetime-local':
            echo "<input type='datetime-local' id='" . esc_attr($option_name) . "' class='algo_wc_field' name='" . esc_attr($option_name) . "' value='" . esc_attr($value) . "' />";
            break;
        case 'image':
            $imgHtml = "<div id='" . esc_attr($option_name) . "' class='algo-image-upload'>";

            if ($value) {
                // If image URL is available, display the image with proper escaping
                $imgHtml .= wp_get_attachment_image($value, 'full', false, array('style' => 'max-width:50px;'));
            } else {
                // If no image URL, use a default icon
                $imgHtml .='<span class="dashicons dashicons-visibility" aria-hidden="true"></span>';               
            }

            $imgHtml .= "<input type='hidden' name='" . esc_attr($option_name) . "' value='" . esc_attr($value) . "' class='regular-text algo-image-upload-field' />
                <button type='button' class='button algo-upload-image-button'>" . esc_html__('Upload Icon', 'apl-quick-view') . "</button>
                <button class='button algo-remove-image-button' style='display:" . ($value ? 'inline-block' : 'none') . ";'>" . esc_html__('Remove Image', 'apl-quick-view') . "</button>
            <br/><span style='font-size:12px;'>" . esc_html__('It is recommended to use an icon size of 24px × 24px.', 'apl-quick-view') . "</span></div>";

            echo wp_kses_post($imgHtml);
            break;

        case 'switch':
            echo "<label class='switch'>
                    <input type='checkbox' id='" . esc_attr($option_name) . "' class='algo_wc_field' name='" . esc_attr($option_name) . "' value='1' " . checked(1, $value, false) . " />
                    <span class='slider round'></span>
                  </label>";
            break;
        case 'heading':
            echo "";
            break;

        default:
            echo "<input type='text' id='" . esc_attr($option_name) . "' class='algo_wc_field' name='" . esc_attr($option_name) . "' value='" . esc_attr($value) . "' />";
            break;
    }
}